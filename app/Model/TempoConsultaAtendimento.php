<?php

App::import("Model", "BSModel");

class TempoConsultaAtendimento extends BSModel {

    public $useTable = 'tempo_consulta_atendimento';
    public $displayField = "tempo_consulta";
    public $hasAndBelongsToMany = array(
        'Tipologia' =>
            array(
                'className' => 'Tipologia',
                'joinTable' => 'tempo_consulta_tipologia',
                'foreignKey' => 'tempo_consulta_id',
                'associationForeignKey' => 'tipologia_id'
            )
    );
    public $validate = array(
        'tempo_consulta' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'O Campo Tempo de Consulta é de preenchimento obrigatório.'
            ),
            'validarUnicidade' => array(
                'rule' => array('validarUnicidade'),
                'message' => 'Já existe um Tempo de Consulta cadastrado para uma ou mais tipologias selecionadas',
            ),
        )
    );

    public function validarUnicidade() {

        
        $filtro = new BSFilter();
        if (count($this->data['Tipologia']['Tipologia']) > 1){
            $condicoes['TempoConsultaTipologia.tipologia_id IN '] = $this->data['Tipologia']['Tipologia'];
        }else{
            $condicoes['TempoConsultaTipologia.tipologia_id '] = $this->data['Tipologia']['Tipologia'];
        }
        $joins[] = array(
            'table' => 'tempo_consulta_tipologia',
            'alias' => 'TempoConsultaTipologia',
            'type' => 'INNER',
            'conditions' => array("TempoConsultaTipologia.tempo_consulta_id = TempoConsultaAtendimento.id")
        );

        if (isset($this->data[$this->alias]['id'])) {
            $condicoes['TempoConsultaAtendimento.id !='] = $this->data[$this->alias]['id'];
        }
        $filtro->setJoins($joins);
        $filtro->setTipo('count');
        $filtro->setCondicoes($condicoes);
        $count = $this->listar($filtro);

        return $count == 0;
    }

    public function buscarTempoConsultaAtendimento($idTipologia) {
        $filtro = new BSFilter();

        $joins[] = array(
            'table' => 'tempo_consulta_tipologia',
            'alias' => 'TempoConsultaTipologia',
            'type' => 'INNER',
            'conditions' => array("TempoConsultaTipologia.tempo_consulta_id = TempoConsultaAtendimento.id")
        );

        $condicoes['TempoConsultaTipologia.tipologia_id'] = $idTipologia;
        $filtro->setCondicoes($condicoes);
        $filtro->setJoins($joins);
        $filtro->setTipo('all');
        $filtro->setCamposRetornadosString("TempoConsultaAtendimento.tempo_consulta");

        $tempoConsulta = null;

        if(isset($this->listar($filtro)[0]['TempoConsultaAtendimento']['tempo_consulta'])){
            $tempoConsulta = $this->listar($filtro)[0]['TempoConsultaAtendimento']['tempo_consulta'];
        }

        // pr( is_null( $retorno ) );die;
        
        return $tempoConsulta;
    }
}
