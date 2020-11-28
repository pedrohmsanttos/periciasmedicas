<?php

App::import("Model", "BSModel");
App::import("Model", "Agendamento");
App::import("Model", "TipoInvalidezFisica");
App::import("Model", "TipoSituacaoParecerTecnico");

class Atendimento extends BSModel {

    const MODO_INICIAL = "Inicial";
    const MODO_PRORROGACAO = "Prorrogação";

    public $useTable = 'atendimento';

    public $hasOne = array(
        'AtendimentoCAT' => array(
            'classname' => 'AtendimentoCAT',
            'foreignKey' => 'atendimento_id',
            'dependent' => true
        ),'PreAdmissional' => array(
            'classname' => 'PreAdmissional',
            'foreignKey' => 'atendimento_id',
            'dependent' => true
        ),
        'AtendimentoInspecao' => array(
            'classname' => 'AtendimentoInspecao',
            'foreignKey' => 'atendimento_id',
            'dependent' => true
        )
    );

    public $belongsTo = array(
        'Servidor' => array(
            'className' => 'Usuario',
            'foreignKey' => 'usuario_id'
        ),
        'TipoIsencao' => array(
            'className' => 'TipoIsencao',
            'foreignKey' => 'isencao_id'
        ),
        'TipoSituacaoParecerTecnico' => array(
            'className' => 'TipoSituacaoParecerTecnico',
            'foreignKey' => 'situacao_id'
        ),
        // 'Cid' => array(
        //     'className' => 'Cid',
        //     'foreignKey' => 'cid_id'
        // ),
        'Agendamento' => array(
            'className' => 'Agendamento',
            'foreignKey' => 'agendamento_id'
        ),
        'TipoInvalidezFisica' => array(
            'className' => 'TipoInvalidezFisica',
            'foreignKey' => 'invalidez_fisica_id'
        ),
        'IncapacidadeTipoInvalidezFisica' => array(
            'className' => 'TipoInvalidezFisica',
            'foreignKey' => 'incap_atos_vida_civil_id'
        )
    );
    public $hasAndBelongsToMany = array(
        'Perito' => array(
            'className' => 'Usuario',
            'joinTable' => 'atendimento_perito',
            'foreignKey' => 'atendimento_id',
            'associationForeignKey' => 'usuario_id'
        ),
        'RequisicaoDisponivel' => array(
            'className' => 'RequisicaoDisponivel',
            'joinTable' => 'atendimento_req_disp',
            'foreignKey' => 'atendimento_id',
            'associationForeignKey' => 'req_disp_id'
        ),
        'Tag' => array(
            'className' => 'Tag',
            'joinTable' => 'inspecao_tag',
            'foreignKey' => 'atendimento_id',
            'associationForeignKey' => 'tag_id'
        ),
         'Cid' => array(
             'className' => 'Cid',
             'joinTable' => 'atendimento_cid',
             'foreignKey' => 'atendimento_id',
             'associationForeignKey' => 'cid_id'
         )
    );
    public $validate = array(
        'situacao_id' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'O Campo Situação da aba "Parecer Técnico" é obrigatório.'
            )
        ),
     /*   'parecer' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'O Campo Parecer da aba "Parecer Técnico" é obrigatório.'
            )
        ),*/
        'dependente_maior_invalido' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'O Campo Dependente Maior inválido da aba "Parecer Técnico" é obrigatório.'
            )
        )
    /*,
        'invalidez_fisica_id' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'O Campo Invalidez Física da aba "Parecer Técnico" é obrigatório.'
            )
        )*/
    );

    public function findById($id) {
        $atendimento = parent::findById($id);
        if (isset($atendimento['Atendimento']['data_parecer']) && $atendimento['Atendimento']['data_parecer']) {
            $atendimento['Atendimento']['data_parecer'] = Util::toBrData($atendimento['Atendimento']['data_parecer']);
        }
        if (isset($atendimento['Atendimento']['data_insencao_temporaria']) && $atendimento['Atendimento']['data_insencao_temporaria']) {
            $atendimento['Atendimento']['data_insencao_temporaria'] = Util::toBrData($atendimento['Atendimento']['data_insencao_temporaria']);
        }
        if (isset($atendimento['Atendimento']['data_dependente_invalido']) && $atendimento['Atendimento']['data_dependente_invalido']) {
            $atendimento['Atendimento']['data_dependente_invalido'] = Util::toBrData($atendimento['Atendimento']['data_dependente_invalido']);
        }
        if (isset($atendimento['Atendimento']['data_dependente_inc_atos_vida']) && $atendimento['Atendimento']['data_dependente_inc_atos_vida']) {
            $atendimento['Atendimento']['data_dependente_inc_atos_vida'] = Util::toBrData($atendimento['Atendimento']['data_dependente_inc_atos_vida']);
        }
        if (isset($atendimento['Atendimento']['data_limite_exigencia']) && $atendimento['Atendimento']['data_limite_exigencia']) {
            $atendimento['Atendimento']['data_limite_exigencia'] = Util::toBrData($atendimento['Atendimento']['data_limite_exigencia']);
        }
        return $atendimento;
    }
    private function validateDataParecer() {
        $tipologiaId = $this->getTipologia($this->data['Atendimento']['agendamento_id']);
        //pr($tipologiaId); pr($this->data['Atendimento']); die;;

        if (isset($this->data['Atendimento']['emitir_laudo']) && $this->data['Atendimento']['emitir_laudo'] == true || isset($this->data['finalizarAtendimento'])) {

            if (in_array($tipologiaId, array(
                TIPOLOGIA_LICENCA_ACOMPANHAMENTO_FAMILIAR,
                TIPOLOGIA_LICENCA_MATERNIDADE,
                TIPOLOGIA_LICENCA_MATERNIDADE_ABORTO,
                TIPOLOGIA_LICENCA_MEDICA_TRATAMENTO_SAUDE,
                TIPOLOGIA_LICENCA_NATIMORTO
            ))) {
                //valida com situação deferido
                if (isset($this->data['Atendimento']['situacao_id']) &&
                    $this->data['Atendimento']['situacao_id'] == TipoSituacaoParecerTecnico::DEFERIDO
                ) {
                    if (isset($this->data['Atendimento']['data_parecer']) && $this->data['Atendimento']['data_parecer'] == "") {
                        $this->invalidate('data_parecer', 'O Campo "A partir de" da aba "Parecer Técnico" é obrigatório.');
                    }
                }
            } else {
                $valideData = true;
                if (isset($tipologiaId) &&
                    in_array($tipologiaId,
                        array(TIPOLOGIA_COMUNICACAO_DE_ACIDENTE_DE_TRABALHO)
                    )
                ) {
                    //não valida quando deferido
                    if (isset($this->data['Atendimento']['situacao_id']) &&
                        $this->data['Atendimento']['situacao_id'] == TipoSituacaoParecerTecnico::DEFERIDO
                    ) {
                        $valideData = false;
                    }
                }
                if ($valideData && isset($this->data['Atendimento']['data_parecer']) && $this->data['Atendimento']['data_parecer'] == "") {
                    $this->invalidate('data_parecer', 'O Campo "A partir de" da aba "Parecer Técnico" é obrigatório.');
                }
            }
        }
    }


    public function beforeSave($options = array()){
	    if(isset($this->data['Atendimento']['data_parecer'])){
		    $this->data['Atendimento']['data_parecer'] = Util::toDBDataHora($this->data['Atendimento']['data_parecer']);
	    }
        

        // pr($this->data);die;
        parent::beforeSave($options);

        $log = $this->getDataSource()->getLog(false, false);

    }

    public function beforeValidate($options = array()){
	    $ignore = false;
        $this->validarAbaJuntaPeritos();
	    $onSave = null;
	    $onEmitir = null;

        if (isset($this->data['Atendimento']['invalidez_fisica_id']) && $this->data['Atendimento']['invalidez_fisica_id'] == TipoInvalidezFisica::$TEMPORARIA) {
            if (empty($this->data['Atendimento']['data_dependente_invalido'])) {
                $this->invalidate('data_dependente_invalido', __('erro_data_invalidez_fisica_obrigatoria'));
            }
        }

        if (isset($this->data['Atendimento']['incap_atos_vida_civil_id']) && $this->data['Atendimento']['incap_atos_vida_civil_id'] == TipoInvalidezFisica::$TEMPORARIA) {
            if (empty($this->data['Atendimento']['data_dependente_inc_atos_vida'])) {
                $this->invalidate('data_dependente_inc_atos_vida', __('erro_data_atos_vida_civil_obrigatoria'));
            }
        }

        $agendamento = $this->Agendamento->getAgendamento($this->data['Atendimento']['agendamento_id']);
        if($agendamento){
            $tipologia_id = $agendamento['tipologia_id'];
            if($agendamento['recurso_tipologia_id']){
                $tipologia_id = $agendamento['recurso_tipologia_id'];
            }
        }else{
            $tipologia_id = [];
        }
        
        $ignoreDurante = false;
        if(isset($agendamento['num_exigencia'])){
            if(isset($this->data['Atendimento']['situacao_id']) && (!empty($this->data['Atendimento']['situacao_id'])) && $this->data['Atendimento']['situacao_id'] == TipoSituacaoParecerTecnico::DEFINITIVO  || $this->data['Atendimento']['situacao_id'] == TipoSituacaoParecerTecnico::EM_EXIGENCIA){
                $ignoreDurante = true;
            }
        }
        if (isset($tipologia_id) && ($tipologia_id == TIPOLOGIA_READAPTACAO_FUNCAO && !$ignoreDurante ) || ($tipologia_id == TIPOLOGIA_REMANEJAMENTO_FUNCAO && !$ignoreDurante ) || ($tipologia_id == TIPOLOGIA_REMOCAO && !$ignoreDurante)) {
            if (isset($this->data['Atendimento']['duracao']) && empty($this->data['Atendimento']['duracao'])) {
                $this->invalidate('duracao', __('erro_duracao_obrigatoria'));
            }
        }

        if (isset($tipologia_id) && $tipologia_id == TIPOLOGIA_ISENCAO_CONTRIBUICAO_PREVIDENCIARIA) {
        	
        	$onEmitir = function (){
	            if (isset($this->data['Atendimento']['isencao_id']) && !$this->data['Atendimento']['isencao_id']) {
                    //tira a obrigatoriedade do botao de isencao
	                //$this->invalidate('isencao_id', __('O Campo Isenção da aba "Parecer Técnico" é obrigatório.'));
	            }else if($this->data['Atendimento']['isencao_id'] == '1'){
                    if(!$this->data['Atendimento']['data_insencao_temporaria']){
                        $this->invalidate('isencao_id', __('O Campo Período Concedido da aba "Parecer Técnico" é obrigatório para Isenção Temporária.'));
                    }
                }
	            if (isset($this->data['Atendimento']['aposentado']) && isset($this->data['Atendimento']['pensionista'])) {
	                if ($this->data['Atendimento']['aposentado'] == "0" && $this->data['Atendimento']['pensionista'] == "0") {
	                    $this->invalidate('validacao_tipo_beneficio', __('O Campo Tipo de Benefício Previdenciário da aba "Parecer Técnico" é obrigatório.'));
	                }
	            }
            };
        }
        
        if (isset($tipologia_id) && $tipologia_id == TIPOLOGIA_DESIGNACAO_DE_ASSISTENTE_TECNICO) {
            $onEmitir = function (){
                if (!isset($this->data['Atendimento']['questionamentos']) || $this->data['Atendimento']['questionamentos'] == '') {
                    $this->invalidate('questionamentos', 'O Campo Questionamentos da aba "Questionamentos" é obrigatório.');
                }
                if (!isset($this->data['Atendimento']['parecer']) || $this->data['Atendimento']['parecer'] == '') {
                    $this->invalidate('parecer', 'O Campo Parecer da aba "Parecer Técnico" é obrigatório.');
                }
            };

        }

        if (isset($tipologia_id) && $tipologia_id == TIPOLOGIA_SINDICANCIA_INQUERITO_PAD){
            $onEmitir = function(){
                $this->validator()->remove('situacao_id');
                if (!isset($this->data['Atendimento']['parecer']) || $this->data['Atendimento']['parecer'] == '') {
                    $this->invalidate('parecer', 'O Campo Parecer da aba "Parecer Técnico" é obrigatório.');
                }
            };
        }

        if (isset($tipologia_id) && $tipologia_id == TIPOLOGIA_APOSENTADORIA_ESPECIAL){
            $onEmitir = function(){
                if(!isset($this->data['Atendimento']['necessario_inspecao']) || $this->data['Atendimento']['necessario_inspecao'] === "" ) {
                    $this->invalidate('necessario_inspecao', 'O campo "Será necessário inspeção?" é obrigatório. ');
                }else if($this->data['Atendimento']['necessario_inspecao'] == 1 &&
                        (!isset($this->data['Atendimento']['numero_inspecao']) || empty($this->data['Atendimento']['numero_inspecao']))) {
	                $this->invalidate('escolha_inspecao', 'Número de inspeção inválido');
                }
            };
        }

        if (isset($tipologia_id) && $tipologia_id == TIPOLOGIA_COMUNICACAO_DE_ACIDENTE_DE_TRABALHO){
            $onEmitir = function(){
                if(!isset($this->data['AtendimentoCAT']['salvo_medico']) || $this->data['AtendimentoCAT']['salvo_medico'] == false ) {
                    $this->invalidate('parecer', 'É preciso que o médico dê seu parecer antes de emitir o laudo. ');
                }
                if(!isset($this->data['AtendimentoCAT']['salvo_engenheiro']) || $this->data['AtendimentoCAT']['salvo_engenheiro'] == false ) {
                    $this->invalidate('parecer', 'É preciso que o engenheiro dê seu parecer antes de emitir o laudo. ');
                }
                if (!isset($this->data['Atendimento']['parecer']) || $this->data['Atendimento']['parecer'] == '') {
                    $this->invalidate('parecer', 'O Campo Parecer da aba "Parecer Técnico" é obrigatório.');
                }
            };
        }
	    if( isset($tipologia_id) && $tipologia_id == TIPOLOGIA_ADMISSAO_PENSIONISTA_MAIOR_INVALIDO){
		    $onSave = function (){
			    $this->validator()->remove('dependente_maior_invalido');
		    };
            $onEmitir = function(){
                $nfisico = false; $fisicoTemp = false;
                if(!isset($this->data['Atendimento']['invalidez_fisica_id']) || $this->data['Atendimento']['invalidez_fisica_id'] == ""){
                    $nfisico = true;
                }
                $nmental = false; $mentalTemp = false;
                if(!isset($this->data['Atendimento']['incap_atos_vida_civil_id']) || $this->data['Atendimento']['incap_atos_vida_civil_id'] == ""){
                    $nmental = true;
                }
                if(isset($this->data['Atendimento']['dependente_maior_invalido']) && $this->data['Atendimento']['dependente_maior_invalido'] == 1){
                    if($nfisico && $nmental){
                        $this->invalidate('dependente_maior_invalido', "É preciso selecionar ao menos um tipo de invalidez, física ou mental");
                    }
                }

            };
	    }
        if (isset($tipologia_id) && $tipologia_id == TIPOLOGIA_INSPECAO)
        {
            $onEmitir = function() {
                if (!isset($this->data['Atendimento']['parecer']) || $this->data['Atendimento']['parecer'] == '') {
                    $this->invalidate('parecer', 'O Campo Parecer da aba "Parecer Técnico" é obrigatório.');
                }
            };
        }
        if(isset($tipologia_id) && $tipologia_id == TIPOLOGIA_LICENCA_MEDICA_TRATAMENTO_SAUDE){
            $onEmitir = function() {
                if ( (!isset($this->data['Atendimento']['modo']) || $this->data['Atendimento']['modo'] == '' ) &&  $this->data['Atendimento']['situacao_id'] != SITUACAO_INDEFERIDO ) {
                    $this->invalidate('parecer', 'O Campo Modo da aba "Parecer Técnico" é obrigatório. (1)');
                }
            };
        }
        if(isset($tipologia_id) && $tipologia_id == TIPOLOGIA_RISCO_VIDA_INSALUBRIDADE){
            $onEmitir = function() {
                if (isset($this->data['Agendamento']['vinculo']) && $this->data['Agendamento']['vinculo'] != ESTATUTARIO &&
                    isset($this->data['Atendimento']['situacao_id']) && $this->data['Atendimento']['situacao_id'] == SITUACAO_SE_ENQUADRA){
                    if (!isset($this->data['Atendimento']['numero_nr']) || $this->data['Atendimento']['numero_nr'] == '') {
                        $this->invalidate('parecer', 'O Campo Número da NR da aba "Parecer Técnico" é obrigatório.');
                    }
                    if (!isset($this->data['Atendimento']['numero_anexo']) || $this->data['Atendimento']['numero_anexo'] == '') {
                        $this->invalidate('parecer', 'O Campo Número do Anexo da aba "Parecer Técnico" é obrigatório.');
                    }
                    if (!isset($this->data['Atendimento']['letra']) || $this->data['Atendimento']['letra'] == '') {
                        $this->invalidate('parecer', 'O Campo Letra da aba "Parecer Técnico" é obrigatório.');
                    }
                    if (!isset($this->data['Atendimento']['natureza_agente']) || $this->data['Atendimento']['natureza_agente'] == '') {
                        $this->invalidate('parecer', 'O Campo Natureza do Agente da aba "Parecer Técnico" é obrigatório.');
                    }
                }
            };
        }

        //PADRÃO PARA TODAS AS TIPOLOGIAS;
        $this->validarOnEmitirLaudo($onEmitir, $onSave);
        if(isset($this->data['Atendimento']['status_atendimento']) && !empty($this->data['Atendimento']['status_atendimento'])){
            if($this->data['Atendimento']['status_atendimento'] != "Salvo"){
                $this->validarModoAtendimento($tipologia_id);
                $this->validateDataParecer();
                $this->validarExigencias();
            }
        }


        parent::beforeValidate($options);
    }

    private function validarOnEmitirLaudo($onEmitir = null, $onSave = null){
        if (isset($this->data['Atendimento']['emitir_laudo']) && $this->data['Atendimento']['emitir_laudo'] == 'true' || isset($this->data['finalizarAtendimento'])) {
            $this->validator()->getField('situacao_id')->setRules(array(
                'required' => array(
                    'rule' => 'notBlank',
                    'message' => 'O Campo Situação da aba "Parecer Técnico" é obrigatório.'
                )
            ));

            if(is_callable($onEmitir)){
                $onEmitir();
            }
        }else{
	        $this->removeAllValidation();

            if(is_callable($onSave))$onSave();
        }
    }

    /**
     * Método para validar se a data limite das exigências foi preenchida e se a mesma não é menor que a data atual quando a situação do parecer for 
     * Em exigência
     */
    private function validarExigencias() {
        if (isset($this->data[$this->alias]['situacao_id']) && $this->data[$this->alias]['situacao_id'] == TipoSituacaoParecerTecnico::EM_EXIGENCIA) {
            if (isset($this->data[$this->alias]['data_limite_exigencia']) && !$this->data[$this->alias]['data_limite_exigencia']) {
                $this->invalidate('validacao_data_limite_exigencia', __('O Campo Data Limite do popup de exigências é de preenchimento obrigatório.'));
            } else {
                $dataLimite = Util::toDBData($this->data[$this->alias]['data_limite_exigencia']);
                $dataAtual = date('Y-m-d');
                if ($dataLimite < $dataAtual) {
                    $this->invalidate('validacao_data_limite_exigencia', 'A Data Limite do popup de exigências não pode ser menor que a data atual.');
                }
            }
            if( !isset($this->data['RequisicaoDisponivel']) ||
                !isset($this->data['RequisicaoDisponivel']['RequisicaoDisponivel']) ||
                empty($this->data['RequisicaoDisponivel']['RequisicaoDisponivel'])
            ){
                $this->invalidate('validacao_data_limite_exigencia', 'É preciso selecionar ao menos uma Requisição .');
            }
        }
    }

    private function validarModoAtendimento($tipologia_id) {
        if (TIPOLOGIA_LICENCA_ACOMPANHAMENTO_FAMILIAR == $tipologia_id) {
             if (( !isset($this->data['Atendimento']['modo']) || !$this->data['Atendimento']['modo'] ) && $this->data['Atendimento']['situacao_id'] != SITUACAO_INDEFERIDO ) {
                 $this->invalidate('modo', __('O Campo Modo da aba "Parecer Técnico" é obrigatório. (2)'));
             }
         }

        if (
            $tipologia_id != TIPOLOGIA_LICENCA_MEDICA_TRATAMENTO_SAUDE &&
            $tipologia_id != TIPOLOGIA_ADMISSAO_PENSIONISTA_MAIOR_INVALIDO &&
            $tipologia_id != TIPOLOGIA_READAPTACAO_FUNCAO &&
            $tipologia_id != TIPOLOGIA_REMANEJAMENTO_FUNCAO) {
            if (isset($this->data['Atendimento']['modo']) && !trim($this->data['Atendimento']['modo']) && $this->data['Atendimento']['situacao_id'] != SITUACAO_INDEFERIDO) {
                $this->invalidate('modo', __('O Campo Modo da aba "Parecer Técnico" é obrigatório. (3)'));
            }
        }

    }

    private function getTipologia($agendamento_id) {
        $filtro = new BSFilter();
        $condicoes = array('Agendamento.id' => $agendamento_id);
        $filtro->setCondicoes($condicoes);
        $filtro->setCamposRetornados(['Agendamento.tipologia_id']);
        $filtro->setTipo('list');

        $arrTipologia = $this->Agendamento->listar($filtro);

        return $tipologia_id = (!empty($arrTipologia)) ? $arrTipologia[$agendamento_id] : [];
    }

    private function validarAbaJuntaPeritos() {
        
        $tipologia_id = "";
        if(isset($this->data['Agendamento']) && !empty($this->data['Agendamento'])){
            $tipologia_id = $this->data['Agendamento']['tipologia_id'];
        }
        /*  História #14935  - Retirar regra de 90 dias
        if ($tipologia_id != TIPOLOGIA_LICENCA_ACOMPANHAMENTO_FAMILIAR &&
            $tipologia_id != TIPOLOGIA_LICENCA_MEDICA_TRATAMENTO_SAUDE &&
            $tipologia_id != TIPOLOGIA_ADMISSAO_PENSIONISTA_MAIOR_INVALIDO &&
            $tipologia_id != TIPOLOGIA_READAPTACAO_FUNCAO &&
            $tipologia_id != TIPOLOGIA_REMANEJAMENTO_FUNCAO){
            if (isset($this->data['Atendimento']['duracao'])){
                $duracao = $this->data['Atendimento']['duracao'];
                if ((!empty($duracao) && $duracao > 90) && $this->data['Agendamento']["tipologia_id"] != 1) {
                    if (!isset($this->data['Atendimento']['Perito']) || empty($this->data['Atendimento']['Perito'])) {
                        $this->invalidate('nome_perito', __('erro_duracao_maior_90_sem_perito'));
                    }
                }
            }
            unset($this->data['Agendamento']);
        } //*/

    }

    /**
     * Método para buscar um atendimento com a data inicial e a duração do parecer técnico
     */
    public function buscarAtendimentoDataParecer($idAtendimento) {
        $filtro = new BSFilter();
        $filtro->setTipo('all');
        $filtro->setCamposRetornadosString("id", "data_parecer", "duracao");
        $condicoes['Atendimento.id'] = $idAtendimento;
        $filtro->setCondicoes($condicoes);
        $licencas = $this->listar($filtro);
        $licenca = null;
        if (count($licencas) > 0) {
            $licenca = $licencas[0];
        }
        return $licenca;
    }

    public function listarAtendimentosVigentes($servidor_id, $tipologiaSelecionada) {
        $filtro = new BSFilter();
        $filtro->setTipo('all');
        $filtro->setCamposRetornadosString("id", "data_parecer", "duracao", "Tipologia.nome");
        $condicoes['Atendimento.usuario_id'] = $servidor_id;
        $condicoes['Atendimento.situacao_id IN '] = array(TipoSituacaoParecerTecnico::DEFERIDO, TipoSituacaoParecerTecnico::DEFINITIVO,
            TipoSituacaoParecerTecnico::INTEGRAL, TipoSituacaoParecerTecnico::PROPORCIONAL, TipoSituacaoParecerTecnico::PROVISORIO, TipoSituacaoParecerTecnico::TEMPORARIO);
        $filtro->setCondicoes($condicoes);
        $joins = array();
        $joins[] = array(
            'table' => 'agendamento',
            'alias' => 'AgendamentoServidor',
            'type' => 'inner',
            'conditions' => array('AgendamentoServidor.id = Atendimento.agendamento_id')
        );
        $joins[] = array(
            'table' => 'tipologia',
            'alias' => 'Tipologia',
            'type' => 'inner',
            'conditions' => array('Tipologia.id = AgendamentoServidor.tipologia_id')
        );
        $filtro->setJoins($joins);
        $licencas = $this->listar($filtro);
        $arrayRetorno = array();
        foreach ($licencas as $licenca) {
            $dataParecer = $licenca['Atendimento']['data_parecer'];
            $id = $licenca['Atendimento']['id'];
            $duracao = $licenca['Atendimento']['duracao'];
            $dataFinal = null;
            $dataAtual = date('Y-m-d');

            if ($duracao) {
                $dataFinal = date('Y-m-d', strtotime($dataParecer . ' + ' . $duracao . ' days'));
            }

            if ($dataFinal == null || ($dataAtual < $dataFinal) && ($dataAtual > $dataParecer)) {
                $arrayRetorno[$id] = $licenca['Tipologia']['nome'] . " - Período " . Util::inverteData($dataParecer) . ($dataFinal ? " a " . Util::inverteData($dataFinal) : "");
            }
        }
        return $arrayRetorno;
    }

    public function buscarSituacao($id){
        $filtro = new BSFilter();
        $filtro->setCamposRetornadosString('id', 'situacao_id');
        $condicoes['Atendimento.id'] = $id;
        $filtro->setCondicoes($condicoes);
        $filtro->setTipo('all');
        return $this->listar($filtro)[0];
    }

    public function buscarStatus($id){
        $filtro = new BSFilter();
        $filtro->setCamposRetornadosString('id', 'status_atendimento');
        $condicoes['Atendimento.id'] = $id;
        $filtro->setCondicoes($condicoes);
        $filtro->setTipo('all');
        return $this->listar($filtro)[0];
    }

    public function getTipologiaIdAtendimento($id){
        $filtro = new BSFilter();
        $condicoes = array('Atendimento.id' => $id);
        $filtro->setCondicoes($condicoes);
        $filtro->setCamposRetornados(['Agendamento.tipologia_id', 'Agendamento.numero_processo']);
        $filtro->setTipo('list');

        $filtro->setJoins(array(
           array(
               'table' => 'agendamento',
               'alias' => 'Agendamento',
               'type' => 'inner',
               'conditions' => array('Agendamento.id = Atendimento.agendamento_id')
           )
        ));

        $arrResultado = $this->listar($filtro);

        $tipologia_id = (!empty($arrResultado)) ? array_keys($arrResultado)[0] : [];

        if($tipologia_id == TIPOLOGIA_RECURSO_ADMINISTRATIVO){
            $tipologia_id = $this->getTipologiaIdAtendimento($arrResultado[$tipologia_id]);
        }

        return $tipologia_id;
    }

    public function getAtendimentoByAgendamento($id, $full = false){
		
		
        $filtro = new BSFilter();
        $filtro->setTipo('first');
        $filtro->setCondicoes(array(
            'Agendamento.id' => $id
        ));
        $resultado= $this->listar($filtro);
        if(!$full){
            $resultado = $resultado['Atendimento'];
        }
        return $resultado;
    }

    public function setDownloadLaudo($id,$dataHoje,$url_atual,$horaEmissao){
        
        $str = 'chegou';
       
        $sql = "INSERT INTO \"desen\".\"emissao_laudo\"(usuario_id,atendimento_id,data_emissao,url_emissao,hora_emissao)VALUES(1, $id, '$dataHoje', '$url_atual', '$horaEmissao')";
        
        // echo $sql;die;
        $result = $this->query($sql);
         // return $sql;
    }
}
