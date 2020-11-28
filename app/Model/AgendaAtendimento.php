<?php

App::import("Model", "BSModel");

class AgendaAtendimento extends BSModel {

    public $useTable = 'agenda_atendimento';
    public $displayField = "dia_semana";
    public $belongsTo = array(
        'Usuario' => array('className' => 'Usuario', 'foreignKey' => 'usuario_id'),
        'UnidadeAtendimento' => array('className' => 'UnidadeAtendimento', 'foreignKey' => 'unidade_atendimento_id')
    );
    
    public $hasAndBelongsToMany = array(
        'Tipologia' => array(
            'className' => 'Tipologia',
            'joinTable' => 'agen_aten_tip',
            'foreignKey' => 'agen_aten_id',
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
            $this->invalidate('AgendaAtendimento.Tipologia', __('validacao_tipologia_obrigatoria_agenda_atendimento'));
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
                    if ($interacao["AgendaAtendimento"]['dia_semana'] == $data['dia_semana']) {
                        $horaInicioInteracao = $interacao["AgendaAtendimento"]['hora_inicial'];
                        $horaFinalInteracao = $interacao["AgendaAtendimento"]['hora_final'];
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
                if ($interacao["AgendaAtendimento"]['dia_semana'] == $data['dia_semana']) {
                    $horaInicioInteracao = $interacao["AgendaAtendimento"]['hora_inicial'];
                    $horaFinalInteracao = $interacao["AgendaAtendimento"]['hora_final'];
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
    public function consultarAgendasAtendimento($idTipologia, $idUnidade, $diaSemana, $agendaSistema){

        $sqlTTFilter = "
        select 
            asiit.hora_inicial as \"hora\", asiit.unidade_atendimento_id as \"unidade\"
            ,asiit.dia_semana as \"dia\", asiitti.tipologia_id as \"tipologia\"
            from agenda_sistema_item asiit 
            inner join agen_sist_item_tip asiitti on asiitti.agenda_sistema_item_id = asiit.\"id\"
            where 
            asiit.agenda_sistema_id = {$agendaSistema['AgendaSistema']['id']}
            and 
            (
                
                asiitti.tipologia_id = $idTipologia
                and asiit.ativo = true";
                

                if($idUnidade){
                    $sqlTTFilter .= " and asiit.unidade_atendimento_id = $idUnidade ";
                }
                
            
            $sqlTTFilter .= " and asiit.dia_semana ". (empty($diaSemana)?"is null":"= '$diaSemana'").
            ")";

        $filtro = new BSFilter();
        $condicoes['agen_aten_tip.tipologia_id'] = $idTipologia;
        $condicoes['AgendaAtendimento.unidade_atendimento_id'] = $idUnidade;
        $condicoes['AgendaAtendimento.dia_semana'] = empty($diaSemana)?null:$diaSemana;
        $condicoes['AgendaAtendimento.permitir_agendamento'] = true;
        $joins[] = array(
                'table' => 'agen_aten_tip',
                'alias' => 'agen_aten_tip',
                 'type' => 'left',
                'conditions' => array('agen_aten_tip.agen_aten_id = AgendaAtendimento.id')
            );
        $joins[] = array(
            'table' => "($sqlTTFilter)filtert",
            'type' => 'inner',
            'conditions' => array(
                'agen_aten_tip.tipologia_id = filtert.tipologia',
                'AgendaAtendimento.unidade_atendimento_id = filtert.unidade',
                'AgendaAtendimento.dia_semana = filtert.dia',
                'AgendaAtendimento.hora_inicial = filtert.hora'
            )
        );
        $filtro->setCamposRetornadosString("*");
        $filtro->setCondicoes($condicoes);
        $filtro->setJoins($joins);
        $filtro->setTipo('all');
        return $this->listar($filtro);
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
