<?php

App::import("Model", "BSModel");

class AgendaSistemaItem extends BSModel {

    public $useTable = 'agenda_sistema_item';
    public $displayField = "dia_semana";
    public $belongsTo = array(
        'AgendaSistema' => array('className' => 'AgendaSistema', 'foreignKey' => 'agenda_sistema_id'),
        'UnidadeAtendimento' => array('className' => 'UnidadeAtendimento', 'foreignKey' => 'unidade_atendimento_id')
    );
    
    public $hasAndBelongsToMany = array(
        'Tipologia' => array(
            'className' => 'Tipologia',
            'joinTable' => 'agen_sist_item_tip',
            'foreignKey' => 'agenda_sistema_item_id',
            'associationForeignKey' => 'tipologia_id'
        )
    );
    
    public $validate = array(
        'hora_inicial' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'O Campo Horário inicial é de preenchimento obrigatório.'
            ),
            'horarioValido' => array(
                'rule' => array('horarioValido'),
                'message' => 'O campo Horário inicial está inválido.',
            )
        ),
        'hora_final' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'O Campo Horário final é de preenchimento obrigatório.'
            ),
            'horarioValido' => array(
                'rule' => array('horarioValido'),
                'message' => 'O campo Horário final está inválido',
            )
        ),
        'dia_semana' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'O Campo Dia da Semana é de preenchimento obrigatório'
            )
        ),
        'unidade_atendimento_id' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'O Campo Unidade de Atendimento é de preenchimento obrigatório'
            )
        )
    );

    public function beforeValidate($options = array()) {
        if ($this->data[$this->alias]['hora_inicial'] && $this->data[$this->alias]['hora_final']) {
            if ($this->data[$this->alias]['hora_inicial'] > $this->data[$this->alias]['hora_final']) {
                $this->invalidate('hora_inicial', __('validacao_horario_inicial_maior_final'));
            }
        }
        if(!isset($this->data["Tipologia"]) || empty($this->data["Tipologia"])){
            $this->invalidate('AgendaSistemaItem.Tipologia', __('validacao_tipologia_obrigatoria_agenda_atendimento'));
        }
        parent::beforeValidate($options);
    }

    public function horarioValido($current) {
        if (isset($current['hora_inicial'])) {
            if (!Util::hourValid($this->data[$this->alias]['hora_inicial'])) {
                return false;
            }
        }

        if (isset($current['hora_final'])) {
            if (!Util::hourValid($this->data[$this->alias]['hora_final'])) {
                return false;
            }
        }
        
        return true;
    }

    /**
     * Função que tem um retorno se existe intercessão entre os horarios.
     * @param array $list
     * @param array $data
     * @return boolean
     */
    public function verificaIntercessaoHorario($list, $data) {
        $retorno = false;
        foreach ($list as $key => $interacao) {
            if (isset($data['id'])) {
                if ((int) $data['id'] != $key) {
                    if ($interacao["AgendaSistemaItem"]['dia_semana'] == $data['dia_semana']
                        && $data['unidade_atendimento_id'] == $interacao["AgendaSistemaItem"]['unidade_atendimento_id']) {

                        $horaInicioInteracao = $interacao["AgendaSistemaItem"]['hora_inicial'];
                        $horaFinalInteracao = $interacao["AgendaSistemaItem"]['hora_final'];
                        $horaInicioData = $data['hora_inicial'];
                        $horaFinalData = $data['hora_final'];
                        if ($this->verificaIntercessao($horaInicioData, $horaFinalData, $horaInicioInteracao, $horaFinalInteracao)) {
                            return true;
                        } else {
                            $retorno = false;
                        }
                    }
                }
            } else {
                if ($interacao["AgendaSistemaItem"]['dia_semana'] == $data['dia_semana']
                    && $data['unidade_atendimento_id'] == $interacao["AgendaSistemaItem"]['unidade_atendimento_id']) {

                    $horaInicioInteracao = $interacao["AgendaSistemaItem"]['hora_inicial'];
                    $horaFinalInteracao = $interacao["AgendaSistemaItem"]['hora_final'];
                    $horaInicioData = $data['hora_inicial'];
                    $horaFinalData = $data['hora_final'];
                    if ($this->verificaIntercessao($horaInicioData, $horaFinalData, $horaInicioInteracao, $horaFinalInteracao)) {
                        return true;
                    } else {
                        $retorno = false;
                    }
                }
            }
        }
        return $retorno;
    }

    public function existeAgendamento($data) {
        $retorno = false;
        if (isset($data[$this->name])) {
            $retorno = empty($data[$this->name]);
        }
        return $retorno;
    }

    /**
     * Método para consultar todas as agendas de atendimentos dos usuários peritos do sistema
     * @param type $idTipologia
     * @param type $idUnidade
     * @param type $diaSemana
     */
    public function consultarAgendaSistemaItem($idTipologia, $idUnidade, $diaSemana, $horaInicial=null){
        $idTipologia[] = 0;
        $idTipologia[] = 0;
        $strTipologia = implode(',',$idTipologia);
        $db = $this->getDataSource();
        $sqlTTFilter = "
        select 
        DISTINCT 
            asiit.hora_inicial as \"Filter__hora\", asiit.unidade_atendimento_id as \"Filter__unidade\"
            ,asiit.dia_semana as \"Filter__dia\", asiitti.tipologia_id as \"Filter__tipologia\"
            from agenda_sistema asi
            inner join agenda_sistema_item asiit on asi.\"id\" = asiit.agenda_sistema_id
            inner join agen_sist_item_tip asiitti on asiitti.agenda_sistema_item_id = asiit.\"id\"
            where 
            ((
                (asi.prazo_final >= now()::timestamp::date or asi.prazo_final is null ) 
                and (asi.prazo_inicial <= now()::timestamp::date or asi.prazo_inicial is null)
            ) 
            and asi.ativo = true )
            and 
            (
                asiitti.tipologia_id in ($strTipologia)
                and asiit.unidade_atendimento_id = $idUnidade
                and asiit.dia_semana ". (empty($diaSemana)?"is null":"= '$diaSemana'")."
                and asiit.ativo = true
                ". ((!empty($horaInicial))?" and asiit.hora_inicial = '$horaInicial'":"") ."
            )";
        $result = $db->fetchAll($sqlTTFilter);
        return $result;
    }
    
    /**
     * Aplica regra de intercessão e retorna informando se existe ou não
     * intercessão.
     * 
     * @param string $horaInicio
     * @param string $horaFim
     * @param string $horaInicioInteracao
     * @param string $horaFinalInteracao
     * @return boolean
     */
    private function verificaIntercessao($horaInicio, $horaFim, $horaInicioInteracao, $horaFinalInteracao) {
        if (($horaInicio > $horaInicioInteracao && $horaFim < $horaFinalInteracao) ||
                ($horaInicio < $horaFinalInteracao && $horaFim > $horaInicioInteracao)) {
            return true;
        } else {
            return false;
        }
    }


}
