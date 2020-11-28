<?php

App::import("Model", "BSModel");

class AgendaAtendimentoDomicilio extends BSModel {

    public $useTable = 'agenda_atendimento_domicilio';
    public $displayField = "dia_semana";
    public $belongsTo = array(
        'Usuario'               => array('className' => 'Usuario', 'foreignKey' => 'usuario_id'),
        'UnidadeAtendimento'    => array('className' => 'UnidadeAtendimento', 'foreignKey' => 'unidade_atendimento_id'),
        'Municipio'             => array('className' => 'Municipio', 'foreignKey' => 'municipio_id')
    );
    
    public $hasAndBelongsToMany = array(
        // 'Tipologia' => array(
        //     'className' => 'Tipologia',
        //     'joinTable' => 'agen_aten_tip',
        //     'foreignKey' => 'agen_aten_id',
        //     'associationForeignKey' => 'tipologia_id'
        // )

        'Tipologia' => array(
            'className' => 'Tipologia',
            'joinTable' => 'agen_aten_domic_tip',
            'foreignKey' => 'agend_atendi_domic_id',
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
        ),
        'municipio_id' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'O Campo Município é de preenchimento obrigatório'
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
            $this->invalidate('AgendaAtendimentoDomicilio.Tipologia', __('validacao_tipologia_obrigatoria_agenda_atendimento'));
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
                    if ($interacao["AgendaAtendimentoDomicilio"]['dia_semana'] == $data['dia_semana']) {
                        $horaInicioInteracao = $interacao["AgendaAtendimentoDomicilio"]['hora_inicial'];
                        $horaFinalInteracao = $interacao["AgendaAtendimentoDomicilio"]['hora_final'];
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
                if ($interacao["AgendaAtendimentoDomicilio"]['dia_semana'] == $data['dia_semana']) {
                    $horaInicioInteracao = $interacao["AgendaAtendimentoDomicilio"]['hora_inicial'];
                    $horaFinalInteracao = $interacao["AgendaAtendimentoDomicilio"]['hora_final'];
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
    public function consultarAgendasAtendimento($idTipologia, $idUnidade, $diaSemana){
        
        $filtro = new BSFilter();
        $condicoes['agen_aten_domic_tip.tipologia_id'] = $idTipologia;
        if(!empty($idUnidade)){
            $condicoes['AgendaAtendimentoDomicilio.unidade_atendimento_id'] = $idUnidade;
        }
        $condicoes['AgendaAtendimentoDomicilio.dia_semana'] = $diaSemana;
        $joins[] = array(
                'table' => 'agen_aten_domic_tip',
                'alias' => 'agen_aten_domic_tip',
                'type' => 'left',
                'conditions' => array('agen_aten_domic_tip.agend_atendi_domic_id = AgendaAtendimentoDomicilio.id')
            );
			//pr($condicoes);die;
        $filtro->setCondicoes($condicoes);
        $filtro->setJoins($joins);
        $filtro->setTipo('all');

         //pr($this->listar($filtro));die;
        return $this->listar($filtro);
    }

    /**
     * Método para consultar todas as agendas de atendimentos dos usuários peritos do sistema
     * @param type $idTipologia
     * @param type $idUnidade
     * @param type $diaSemana
     */
    public function consultarTipologiaAtendiDomicilio(){
        // $filtro = new BSFilter();
        // $condicoes['agen_aten_domic_tip.agend_atendi_domic_id'] = $idAgendamento;
        // // $condicoes['AgendaAtendimento.unidade_atendimento_id'] = $idUnidade;
        // // $condicoes['AgendaAtendimento.dia_semana'] = $diaSemana;
        // $joins[] = array(
        //         'table' => 'agen_aten_domic_tip',
        //         'alias' => 'agen_aten_domic_tip',
        //         'type' => 'left',
        //         'conditions' => array('agen_aten_domic_tip.agend_atendi_domic_id = AgendaAtendimentoDomicilio.id')
        //     );
        // $filtro->setCondicoes($condicoes);
        // $filtro->setJoins($joins);
        // $filtro->setTipo('all');
        // return $this->listar($filtro);

        pr($this->Tipologia);
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
