<?php

App::import("Model", "BSModel");
App::import("Model", "Usuario");
App::import("Model", "GerenciamentoSala");
App::import("Model", "UnidadeAtendimento");
App::import("Model", "Atendimento");

class AgendaSistema extends BSModel {

    /**
     * Nome da tabela
     * @var string
     */
    public $useTable = 'agenda_sistema';
    public $hasMany = array('AgendaSistemaItem');

    public $hasAndBelongsToMany = array(
        'Tipologia' => array(
            'className' => 'Tipologia',
            'joinTable' => 'agenda_sistema_tipologia',
            'foreignKey' => 'agenda_sistema_id',
            'associationForeignKey' => 'tipologia_id'
        )
    );

    public $validate = array(
        'descricao' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'O Campo Descrição é obrigatório.'
            ),
        ),
        'prazo_final' => array(
            'rule' => array('dataFinal', 'prazo_inicial'),
            'message' => 'Validade Final preciasa ser maior ou igual a Validade Inicial.'
        )
    );

    public function dataFinal($check, $campoDataInicial) {
        if(isset($this->data['AgendaSistema'][$campoDataInicial])){
            $dataInicial = Util::toDBData($this->data['AgendaSistema'][$campoDataInicial]);
            $dataFinal = Util::toDBData(reset($check));
            return($dataFinal >= $dataInicial);
        }
        return true;
    }


    public function findById($id) {
        $this->Behaviors->load('Containable');

        $agendaSistema = $this->find('first', array(
            'conditions' => array(
                $this->alias . '.ativo' => true,
                $this->alias . '.id' => $id,
            ),
            'contain' => array('Tipologia')
        ));



        $filtroAgendaSistemaItem = new BSFilter();
        $condicoesAgendaSistemaItem['AgendaSistemaItem.agenda_sistema_id'] = $id;
        $filtroAgendaSistemaItem->setCondicoes($condicoesAgendaSistemaItem);
        $filtroAgendaSistemaItem->setTipo('all');
        $filtroAgendaSistemaItem->setContain(array('UnidadeAtendimento', 'Tipologia'));
        $this->AgendaSistemaItem->Behaviors->load('Containable');
        $agendaSistema['AgendaSistemaItem'] = $this->AgendaSistemaItem->listar($filtroAgendaSistemaItem);

        return $agendaSistema;
    }

    //com base na agenda do sistema
    public function findTipologiasDisponiveis($andWhere = "1 = 1"){
        $db = $this->getDataSource();
        $result = $db->fetchAll(
            "select t.id as \"Tipologia__id\", t.nome as \"Tipologia__nome\" 
            from agenda_sistema asis
            INNER join agenda_sistema_tipologia asist on asis.\"id\" = asist.agenda_sistema_id
            inner JOIN  tipologia t on t.id = asist.tipologia_id 
            where 
            (
              (cast(asis.prazo_final as VARCHAR) || ' 23:59:59') >= cast (now()::timestamp::date as VARCHAR)  
              or asis.prazo_final is null 
            ) 
            and asis.ativo = true and $andWhere order by t.nome");
        return $result;///$result->fetchAll(PDO::FETCH_COLUMN);
    }


    public function findAgendaSistema ($idTipologia, $idUnidade, $diaSemana){
        $sqlTTFilter = "
        select 
            asi.id as \"AgendaSistema__id\",
            asiit.hora_inicial as \"AgendaSistema__hora\", asiit.unidade_atendimento_id as \"AgendaSistema__unidade\"
            ,asiit.dia_semana as \"AgendaSistema__dia\", asiitti.tipologia_id as \"AgendaSistema__tipologia\", 
            asi.prazo_final as \"AgendaSistema__prazo_final\", asi.prazo_inicial as \"AgendaSistema__prazo_inicial\"
            from agenda_sistema asi
            inner join agenda_sistema_item asiit on asi.\"id\" = asiit.agenda_sistema_id
            inner join agen_sist_item_tip asiitti on asiitti.agenda_sistema_item_id = asiit.\"id\"
            where 
            (
                (cast(asi.prazo_final as VARCHAR) || ' 23:59:59') >= cast (now()::timestamp::date as VARCHAR) 
                or asi.prazo_final is null  
            ) 
            and asi.ativo = true and asi.habilitada =  true 
            and 
            (
                
                asiitti.tipologia_id = $idTipologia
                and asiit.ativo = true";
                if(!empty($idUnidade)){
                    $sqlTTFilter .= " and asiit.unidade_atendimento_id = $idUnidade ";
                }
            $sqlTTFilter .= " and asiit.dia_semana ". (empty($diaSemana)?"is null":"= '$diaSemana'").")";
            // pr($sqlTTFilter);die;
        $db = $this->getDataSource();
        return $db->fetchAll($sqlTTFilter);
    }
}

