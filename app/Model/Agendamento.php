<?php

App::import("Model", "BSModel");
App::import("Model", "Vinculo");
App::import("Model", "Usuario");
App::import("Model", "GerenciamentoSala");
App::import("Model", "UnidadeAtendimento");
App::import("Model", "Atendimento");

class Agendamento extends BSModel {

    private static $ignoreValidation = false;

    public static function setIgnoreValidation($ignore = true){
        self::$ignoreValidation = $ignore && true;
    }
    public static function getIgnoreValidation(){
        return self::$ignoreValidation;
    }

    public static $ARRAY_TIPOLOGIAS_SEM_SALA = array(TIPOLOGIA_RISCO_VIDA_INSALUBRIDADE, TIPOLOGIA_COMUNICACAO_DE_ACIDENTE_DE_TRABALHO,
        TIPOLOGIA_APOSENTADORIA_ESPECIAL, TIPOLOGIA_SINDICANCIA_INQUERITO_PAD, TIPOLOGIA_INSPECAO);

    public static $ARRAY_TIPOLOGIAS_SEM_DATA = array(TIPOLOGIA_RISCO_VIDA_INSALUBRIDADE,TIPOLOGIA_COMUNICACAO_DE_ACIDENTE_DE_TRABALHO,
        TIPOLOGIA_APOSENTADORIA_ESPECIAL);

    /* TIPOLOGIA_ADMISSAO_PENSIONISTA_MAIOR_INVALIDO, TIPOLOGIA_APOSENTADORIA_INVALIDEZ,
        TIPOLOGIA_INFORMACAO_SEGURO_COMPREENSIVO_HABITACIONAL, TIPOLOGIA_LICENCA_MEDICA_TRATAMENTO_SAUDE  */

    public static $ARRAY_TIPOLOGIAS_SEM_CID = array(TIPOLOGIA_DESIGNACAO_DE_ASSISTENTE_TECNICO, TIPOLOGIA_RISCO_VIDA_INSALUBRIDADE,
        TIPOLOGIA_APOSENTADORIA_ESPECIAL, TIPOLOGIA_EXAME_PRE_ADMISSIONAL, TIPOLOGIA_SINDICANCIA_INQUERITO_PAD,
        TIPOLOGIA_COMUNICACAO_DE_ACIDENTE_DE_TRABALHO, TIPOLOGIA_RECURSO_ADMINISTRATIVO, TIPOLOGIA_INSPECAO,
        TIPOLOGIA_LICENCA_ACOMPANHAMENTO_FAMILIAR, TIPOLOGIA_LICENCA_MATERNIDADE, TIPOLOGIA_ISENCAO_CONTRIBUICAO_PREVIDENCIARIA,
        TIPOLOGIA_AVALIACAO_HABILITACAO_DEPENDENTES, TIPOLOGIA_LICENCA_NATIMORTO,
        TIPOLOGIA_LICENCA_MATERNIDADE_ABORTO );

    private function diaDaSemana($strData){
        $arrData = explode('/',$strData);
        $diasDaSemana = array('Domingo', 'Segunda-feira', 'Terça-feira', 'Quarta-feira','Quinta-feira', 'Sexta-feira' ,'Sábado');
        if(count($arrData) > 1){
            $numDiaSemana = date( "w", strtotime("{$arrData[2]}-{$arrData[1]}-{$arrData[0]}"));
            return $diasDaSemana[$numDiaSemana];
        }else{
            return '';
        }
    }

    /**
     * Nome da tabela
     * @var string
     */
    public $useTable = 'agendamento';

    /*
     * 'Cid' =>
        array(
            'className' => 'Cid',
            'joinTable' => 'unidade_atendimento_cid',
            'foreignKey' => 'unidade_atendimento_id',
            'associationForeignKey' => 'cid_id'

        ),
     */

    public $hasAndBelongsToMany = array(
        'Cids' => array(
            'className' => 'Cid',
            'joinTable' => 'agendamento_cid',
            'foreignKey' => 'agendamento_id',
            'associationForeignKey' => 'cid_id'
        )
    );

    public $hasOne = array(
        'AgendamentoCAT' => array(
            'classname' => 'AgendamentoCAT',
            'foreignKey' => 'agendamento_id',
            'dependent' => true
        ));

    /**
     * Variaveis que vem como padrão dentro de um find list.
     * @var string
     */
        public $belongsTo = array(
        'UsuarioVersao'                 => array('className' => 'Usuario', 'foreignKey' => 'usuario_versao_id'),
        'UsuarioServidor'               => array('className' => 'Usuario', 'foreignKey' => 'usuario_servidor_id'),
        'Tipologia'                     => array('className' => 'Tipologia', 'foreignKey' => 'tipologia_id'),
        'Atendimento'                   => array('className' => 'Atendimento', 'foreignKey' => 'atendimento_vigente_id'),
        'Cid'                           => array('className' => 'Cid', 'foreignKey' => 'cid_id'),
        'CidAcompanhante'               => array('className' => 'Cid', 'foreignKey' => 'acompanhado_cid_id'),
        'UnidadeAtendimento'            => array('className' => 'UnidadeAtendimento', 'foreignKey' => 'unidade_atendimento_id'),
        'ChefiaImediataUm'              => array('className' => 'Usuario', 'foreignKey' => 'chefe_imediato_um_id'),
        'ChefiaImediataUmOrgaoOrigem'   => array('className' => 'OrgaoOrigem', 'foreignKey' => 'chefe_imediato_um_orgao_origem_id'),
        'ChefiaImediataUmLotacao'       => array('className' => 'OrgaoOrigem', 'foreignKey' => 'chefe_imediato_um_lotacao_id'),
        'ChefiaImediataDois'            => array('className' => 'Usuario', 'foreignKey' => 'chefe_imediato_dois_id'),
        'ChefiaImediataDoisOrgaoOrigem' => array('className' => 'OrgaoOrigem', 'foreignKey' => 'chefe_imediato_dois_orgao_origem_id'),
        'ChefiaImediataDoisLotacao'     => array('className' => 'OrgaoOrigem', 'foreignKey' => 'chefe_imediato_dois_lotacao_id'),
        'ChefiaImediataTres'            => array('className' => 'Usuario', 'foreignKey' => 'chefe_imediato_tres_id'),
        'ChefiaImediataTresOrgaoOrigem' => array('className' => 'OrgaoOrigem', 'foreignKey' => 'chefe_imediato_tres_orgao_origem_id'),
        'ChefiaImediataTresLotacao'     => array('className' => 'OrgaoOrigem', 'foreignKey' => 'chefe_imediato_tres_lotacao_id'),
        'MunicipioAtendimentoDomicilio' => array('className' => 'Municipio', 'foreignKey' => 'municipio_id_atend_domicilio'),
        'UnidadeAtendimentoDomicilio'   => array('className' => 'UnidadeAtendimento', 'foreignKey' => 'unidade_atendimento_id_atend_domici'),
        'Qualidade'                     => array('className' => 'Qualidade', 'foreignKey' => 'qualidade_id'),
        'EnderecoAtendimentoDomicilio'  => array('className' => 'EnderecoSimples', 'foreignKey' => 'endereco_id_atend_domici'),
        'DesignacaoUsuarioPerito'       => array('className' => 'Usuario', 'foreignKey' => 'designacao_usuario_perito_id'),
        'RecursoAdm'                    => array('className' => 'Atendimento', 'foreignKey' => 'numero_processo'),
        'TipologiaRecurso'              => array('className' => 'Tipologia', 'foreignKey' => 'recurso_tipologia_id'),
    );

    public $validate = array(
        'protocolo' => array(
            'validarUnicidade' => array(
                'rule' => array('validarUnicidade'),
                'message' => 'Já existe um Protocolo cadastrado com este número.',
            ),
        ),
        'processo_administrativo' => array(
            'required' => array(
                'rule' => array('boolean'),
                'message' => 'O Campo Respondendo a algum Processo Administrativo Disciplinar - PAD é obrigatório.'
            )
        ),
        'tipologia_id' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'O Campo Tipologia é obrigatório.'
            )
        ),
        'confirmar_divulgacao' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'O Campo Confirmar Divulgação é obrigatório.'
            )
        ),
        'usuario_servidor_id' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'É necessário informar pelo menos um Servidor para o agendamento.'
            )
        ),
        'cpf_acompanhado' => array(
            'validarCPF' => array(
                'rule' => array(
                    'validarCPF'
                ),
                'allowEmpty' => true,
                'message' => 'CPF inválido.'
            )
        )
    );

    /**
     * Método para verificar se o CPF é válido
     */
    public function validarCPF() {
        return Util::validaCPF($this->data[$this->alias]['cpf_acompanhado']);
    }

    private function validateDuracao() {
        if (isset($this->data[$this->alias]['tipologia_id'])) {
            $tipologia = $this->data[$this->alias]['tipologia_id'];

            $valideDurente =  true;
            if(in_array($tipologia, array(TIPOLOGIA_REMANEJAMENTO_FUNCAO, TIPOLOGIA_REMOCAO, TIPOLOGIA_READAPTACAO_FUNCAO) )&&
                (isset($this->data[$this->alias]['readaptacao_definitiva'])&& !empty($this->data[$this->alias]['readaptacao_definitiva']))){
                $valideDurente = false;
            }
            //QUALQUER TIPOLOGIA QUE NAO ESTEJA NO ARRAY IRA VALIDAR DURACAO
            if (!in_array($tipologia, array(TIPOLOGIA_APOSENTADORIA_INVALIDEZ, TIPOLOGIA_ISENCAO_CONTRIBUICAO_PREVIDENCIARIA, TIPOLOGIA_REVERSAO_APOSENTADORIA_INVALIDEZ,
                    TIPOLOGIA_AVALIACAO_HABILITACAO_DEPENDENTES, TIPOLOGIA_PCD, TIPOLOGIA_ADMISSAO_PENSIONISTA_MAIOR_INVALIDO, TIPOLOGIA_INFORMACAO_SEGURO_COMPREENSIVO_HABITACIONAL,
                    TIPOLOGIA_RISCO_VIDA_INSALUBRIDADE, TIPOLOGIA_RECURSO_ADMINISTRATIVO, TIPOLOGIA_EXAME_PRE_ADMISSIONAL, TIPOLOGIA_DESIGNACAO_DE_ASSISTENTE_TECNICO,
                    TIPOLOGIA_SINDICANCIA_INQUERITO_PAD, TIPOLOGIA_APOSENTADORIA_ESPECIAL, TIPOLOGIA_INSPECAO,
                    TIPOLOGIA_COMUNICACAO_DE_ACIDENTE_DE_TRABALHO)) && $valideDurente ) {
                if (!isset($this->data[$this->alias]['duracao']) || empty($this->data[$this->alias]['duracao'])) {
                    $this->invalidate('duracao', 'O Campo Durante é obrigatório.');
                }
            }
        }
    }

    private function validateCid() {
        if (isset($this->data[$this->alias]['tipologia_id'])) {
            $tipologia = $this->data[$this->alias]['tipologia_id'];
            if (!in_array($tipologia, Agendamento::$ARRAY_TIPOLOGIAS_SEM_CID)) {
                if (!isset($this->data['Cids']) || count($this->data['Cids']) == 0 || empty($this->data['Cids'][0])  ) {
                    // pr($this->data);
                    // die;
                    $this->invalidate('tipologia_id', 'É preciso escolher ao menos um CID.');
                }
            }
        }
    }

    private function validateExigencia() {
        if (isset($this->data[$this->alias]['num_exigencia'])){

            if(($this->data[$this->alias]['chkbx_exigencia'] == '1') && (empty($this->data[$this->alias]['num_exigencia']))) {
                $this->invalidate('num_exigencia', 'É preciso escolher o número do atendimento em exigência');
            }
        }
    }



    private function validateUnidadeAtendimento() {
        if (isset($this->data[$this->alias]['tipologia_id'])) {
            $tipologia = $this->data[$this->alias]['tipologia_id'];
            $valide = true;
            if(isset($this->data[$this->alias]['atend_domicilio_unidade_proxima']) && $this->data[$this->alias]['atend_domicilio_unidade_proxima'] === '0') {
                $valide = false;
            }
          
            if (in_array($tipologia, array(TIPOLOGIA_RISCO_VIDA_INSALUBRIDADE, TIPOLOGIA_COMUNICACAO_DE_ACIDENTE_DE_TRABALHO,
                    TIPOLOGIA_APOSENTADORIA_ESPECIAL, TIPOLOGIA_INSPECAO))) {
                $valide = false;
            }
            if ($valide) {
                if (!isset($this->data[$this->alias]['unidade_atendimento_id']) || empty($this->data[$this->alias]['unidade_atendimento_id'])) {
                    $this->invalidate('unidade_atendimento_id', 'O Campo Unidade de Atendimento é obrigatório.');
                }
            }
        }
    }


    private function validateDiaSemana() {
        if (isset($this->data[$this->alias]['tipologia_id'])) {
            $tipologia = $this->data[$this->alias]['tipologia_id'];
            
            if (!in_array($tipologia,
                    array(TIPOLOGIA_RISCO_VIDA_INSALUBRIDADE, TIPOLOGIA_DESIGNACAO_DE_ASSISTENTE_TECNICO,TIPOLOGIA_COMUNICACAO_DE_ACIDENTE_DE_TRABALHO,
                        TIPOLOGIA_APOSENTADORIA_ESPECIAL, TIPOLOGIA_INSPECAO))) {
                if (!isset($this->data[$this->alias]['dia_semana']) || empty($this->data[$this->alias]['dia_semana'])) {
                    $this->invalidate('dia_semana', 'O Campo Dia da Semana é obrigatório.');
                }
            }
        }
    }

    private function validateDataHora() {
        if (isset($this->data[$this->alias]['tipologia_id'])) {
            $tipologia = $this->data[$this->alias]['tipologia_id'];
 
            if (!in_array($tipologia,
                    array(TIPOLOGIA_RISCO_VIDA_INSALUBRIDADE, TIPOLOGIA_DESIGNACAO_DE_ASSISTENTE_TECNICO, TIPOLOGIA_COMUNICACAO_DE_ACIDENTE_DE_TRABALHO,
                        TIPOLOGIA_APOSENTADORIA_ESPECIAL, TIPOLOGIA_INSPECAO))) {
                if (!isset($this->data[$this->alias]['data_hora']) || empty($this->data[$this->alias]['data_hora'])) {
                    $this->invalidate('data_hora', 'O Campo Data e Hora é obrigatório.');
                }
            }
        }
    }

    /**
     * Validação da chefia imediata
     */
    private function validateChefiaImediata() {
        
        // pr($this->data);die;

        $tipologia = '';
        if (isset($this->data[$this->alias]['tipologia_id'])) {
            $tipologia = $this->data[$this->alias]['tipologia_id'];
        }


        
        // if(($tipologia == TIPOLOGIA_RECURSO_ADMINISTRATIVO) && (isset($this->data[$this->alias]['recurso_tipologia_id'])) && $this->data[$this->alias]['recurso_tipologia_id'] == TIPOLOGIA_ISENCAO_CONTRIBUICAO_PREVIDENCIARIA ){
            
        // }


        if(!in_array($tipologia, array(TIPOLOGIA_DESIGNACAO_DE_ASSISTENTE_TECNICO, TIPOLOGIA_ADMISSAO_PENSIONISTA_MAIOR_INVALIDO, TIPOLOGIA_ISENCAO_CONTRIBUICAO_PREVIDENCIARIA)) &&  !(($tipologia == TIPOLOGIA_RECURSO_ADMINISTRATIVO) && (isset($this->data[$this->alias]['recurso_tipologia_id'])) && $this->data[$this->alias]['recurso_tipologia_id'] == TIPOLOGIA_ISENCAO_CONTRIBUICAO_PREVIDENCIARIA)){
            if (isset($this->data[$this->alias]['chefe_imediato_um_id'])) {
                if ((empty($this->data[$this->alias]['chefe_imediato_um_id'])) && (empty($this->data[$this->alias]['chefe_imediato_dois_id'])) && (empty($this->data[$this->alias]['chefe_imediato_tres_id']))) {
                    $this->invalidate('getmsgvalidate', 'É obrigatório ao menos a escolha de uma chefia imediata.');
                }
            }
        }
    }

    private function validateApartirDe(){
        if (isset($this->data[$this->alias]['tipologia_id'])) {
            $tipologia = $this->data[$this->alias]['tipologia_id'];
            if (!in_array($tipologia, array(TIPOLOGIA_RECURSO_ADMINISTRATIVO, TIPOLOGIA_EXAME_PRE_ADMISSIONAL,
                    TIPOLOGIA_DESIGNACAO_DE_ASSISTENTE_TECNICO, TIPOLOGIA_RISCO_VIDA_INSALUBRIDADE,
                    TIPOLOGIA_SINDICANCIA_INQUERITO_PAD, TIPOLOGIA_INSPECAO, TIPOLOGIA_COMUNICACAO_DE_ACIDENTE_DE_TRABALHO,
                    TIPOLOGIA_ADMISSAO_PENSIONISTA_MAIOR_INVALIDO, TIPOLOGIA_ISENCAO_CONTRIBUICAO_PREVIDENCIARIA, TIPOLOGIA_APOSENTADORIA_INVALIDEZ
                ))) {
                if (!isset($this->data[$this->alias]['data_a_partir']) || empty($this->data[$this->alias]['data_a_partir'])) {
                    $this->invalidate('data_a_partir', 'O Campo A partir de é obrigatório.');
                }
                if( in_array($tipologia, array(TIPOLOGIA_LICENCA_MEDICA_TRATAMENTO_SAUDE, TIPOLOGIA_LICENCA_ACOMPANHAMENTO_FAMILIAR))){
                    $dateLimit = (new DateTime('-10 day'))->format('Y-m-d');
                    //if(CakeSession::read('Auth.User.tipo_usuario_id') != USUARIO_INTERNO){
                        // if($dateLimit > Util::inverteData($this->data[$this->alias]['data_a_partir'])){
                        //     $this->invalidate('data_a_partir', 'A data "A partir de" para a tipologia escolhida não pode ser mais antiga que 10 dias atrás.');
                        // }
                        if(Util::toBrDataHora($dateLimit)> Util::toBrDataHora($this->data[$this->alias]['data_a_partir'])){
                            //História #13589 - ignorar validação
                            if(false)$this->invalidate('data_a_partir', 'A data "A partir de" para a tipologia escolhida não pode ser mais antiga que 10 dias atrás.');
                        }
                    //}
                }
            }
        }
    }

    public function validarObitoServidor() {
        if (isset($this->data[$this->alias]['tipologia_id'])) {
            $tipologia = $this->data[$this->alias]['tipologia_id'];
            if ($tipologia == TIPOLOGIA_ADMISSAO_PENSIONISTA_MAIOR_INVALIDO) {
                if (!isset($this->data[$this->alias]['data_obito']) || empty($this->data[$this->alias]['data_obito'])) {
                    $this->invalidate('data_obito', 'O Campo Data Óbito é obrigatório.');
                }else{
                    $data = Util::toDBData($this->data[$this->alias]['data_obito']);
                    if(strtotime($data) > strtotime(date("Y-m-d"))){
                        $this->invalidate('data_obito', 'O Campo Data Óbito não pode ser uma data futura.');
                    }

                }
            }
        }
    }

    public function validateCamposCat(){

        if(isset($this->data[$this->alias]['tipologia_id'])) {
            $tipologia = $this->data[$this->alias]['tipologia_id'];
            if ($tipologia == TIPOLOGIA_COMUNICACAO_DE_ACIDENTE_DE_TRABALHO){
                if(isset($this->data['AgendamentoCAT']['chkbx_plantonista']) && ($this->data['AgendamentoCAT']['chkbx_plantonista']) == 1){
                    if(empty($this->data['AgendamentoCAT']['expediente_plantonista'])){
                        $this->invalidate('expedienteMsg', 'É necessário preencher o expediente do plantonista');
                        }
                    }

                if(isset($this->data['AgendamentoCAT']['expediente_plantonista']) && ($this->data['AgendamentoCAT']['expediente_plantonista'] == 4) && (empty($this->data['AgendamentoCAT']['plantonista_outro']))){
                    $this->invalidate('plantonistaMsg', 'Preencha o tipo de expediente');

                }
                if(empty($this->data['AgendamentoCAT']['local_acidente_doenca'])){
                    $this->invalidate('localMsg', 'É necessário preencher o local em que ocorreu o acidente');

                }

                if(empty($this->data['AgendamentoCAT']['data_acidente_doenca'])){
                    $this->invalidate('dataMsg', 'É necessário preencher a data em que ocorreu o acidente');

                }
                if(empty($this->data['AgendamentoCAT']['hora_acidente_doenca'])){
                    $this->invalidate('horaacidenteMsg', 'É necessário preencher a hora em que ocorreu o acidente');

                }
                if(empty($this->data['AgendamentoCAT']['horario_trabalho_inicio']) || empty($this->data['AgendamentoCAT']['horario_trabalho_fim'])){
                    $this->invalidate('horainiciofimMsg', 'É necessário preencher o horário de trabalho do acidentado');

                }
                if(empty($this->data['AgendamentoCAT']['horario_cumprido_entrada']) || empty($this->data['AgendamentoCAT']['horario_cumprido_saida'])){
                    $this->invalidate('horaentradasaidaMsg', 'É necessário preencher o horário cumprido do acidentado');

                }
                if(empty($this->data['AgendamentoCAT']['apos_quantas_horas_trabalho_acidente_doenca'])){
                    $this->invalidate('qtdhrsMsg', 'É necessário preencher a após quantas horas ocorreu o acidente');

                }
                if(empty($this->data['AgendamentoCAT']['lotacao'])){
                    $this->invalidate('lotacaoMsg', 'É necessário preencher a lotação');

                }
                if(empty($this->data['AgendamentoCAT']['setor'])){
                    $this->invalidate('setorMsg', 'É necessário preencher o setor');

                }
                if( trim($this->data['AgendamentoCAT']['tipo_acidente_doenca']) == "" ){
                    $this->invalidate('tipoacidenteMsg', 'É necessário preencher o tipo');

                }
                if(trim($this->data['AgendamentoCAT']['registro_policial_acidente_doenca']) == ""){
                    $this->invalidate('registroMsg', 'É necessário preencher se houve registro policial');

                }
                if(isset($this->data['AgendamentoCAT']['registro_policial_acidente_doenca']) && ($this->data['AgendamentoCAT']['registro_policial_acidente_doenca']) == 1 && empty($this->data['AgendamentoCAT']['descricao_registro_policial_acidente_doenca'])){
                    $this->invalidate('descricaoMsg', 'É necessário adicionar o arquivo do registro policial');

                }
                if(empty($this->data['AgendamentoCAT']['chkbx_diarista']) && empty($this->data['AgendamentoCAT']['chkbx_plantonista'])){
                    $this->invalidate('chkbxMsg', 'É necessário selecionar o expediente do acidentado');
                }
                if(trim($this->data['AgendamentoCAT']['assistencia_medica_hospitalar_acidente_doenca']) == ""){
                    $this->invalidate('assistenciaMsg', 'É necessário preencher se o acidentado recebeu assistência médica');
                }
                if(isset($this->data['AgendamentoCAT']['assistencia_medica_hospitalar_acidente_doenca']) && ($this->data['AgendamentoCAT']['assistencia_medica_hospitalar_acidente_doenca']) == 1 && empty($this->data['AgendamentoCAT']['local_assistencia_medica_hospitalar_acidente_doenca'])){
                    $this->invalidate('localMsg', 'É necessário preencher se o acidentado recebeu assistência médica');

                }
                if(empty($this->data['AgendamentoCAT']['descricao_acidente_doenca'])){
                    $this->invalidate('descricaoMsg', 'É necessário descrever como ocorreu o acidente');

                }
                if(trim($this->data['AgendamentoCAT']['testemunha_acidente_doenca']) == ""){
                    $this->invalidate('testemunhaMsg', 'É necessário selecionar se houve testemunha');

                }
                if(isset($this->data['AgendamentoCAT']['testemunha_acidente_doenca']) && ($this->data['AgendamentoCAT']['testemunha_acidente_doenca']) == 1 && empty($this->data['AgendamentoCAT']['nome_testemunha_acidente_doenca'])){
                    $this->invalidate('descricaoMsg', 'É preencher o nome da testemunha');

                }else if(isset($this->data['AgendamentoCAT']['testemunha_acidente_doenca']) && ($this->data['AgendamentoCAT']['testemunha_acidente_doenca']) == 1 && empty($this->data['AgendamentoCAT']['matricula_testemunha_acidente_doenca'])){
                     $this->invalidate('descricaoMsg', 'É preencher o cpf da testemunha');
                }
                

            }
        }
    }


    public function beforeValidate($options = array())
    {   

        if($this::$ignoreValidation){
            $this->removeAllValidation();
            return true;
        }
        $tipologia = '';
        if (isset($this->data[$this->alias]['tipologia_id'])) {
            $tipologia = $this->data[$this->alias]['tipologia_id'];
        }

        $ignoreConfirmarDivulgacao = false;
        
       
        if (in_array($tipologia, array(TIPOLOGIA_DESIGNACAO_DE_ASSISTENTE_TECNICO, TIPOLOGIA_EXAME_PRE_ADMISSIONAL,
            TIPOLOGIA_RISCO_VIDA_INSALUBRIDADE, TIPOLOGIA_SINDICANCIA_INQUERITO_PAD))) {
            $ignoreConfirmarDivulgacao = true;
            $this->validator()->remove('confirmar_divulgacao');
        }

        if($tipologia == TIPOLOGIA_ADMISSAO_PENSIONISTA_MAIOR_INVALIDO OR $tipologia == TIPOLOGIA_ISENCAO_CONTRIBUICAO_PREVIDENCIARIA){
            $this->validator()->remove('processo_administrativo');
        }

        if($tipologia == TIPOLOGIA_RECURSO_ADMINISTRATIVO){
            if(!isset($this->data[$this->alias]['numero_processo']) || empty($this->data[$this->alias]['numero_processo'])){
                $this->invalidate('escolha_processo', "O campo Número do Processo / Laudo é obrigatório ");
            }
        }
        
        // }else if($tipologia == TIPOLOGIA_RECURSO_ADMINISTRATIVO){
        //     if(isset($this->data[$this->alias]['numero_processo'])){
        //         $Ag = $this->data[$this->alias]['numero_processo']
        //         $Pg = $this->Agendamento->findById($Ag);
        //         if($Pg['Agendamento']['tipologia_id'] == 5){
        //             $this->validator()->remove('getmsgvalidate');
        //         }
        //     }
        // }

//        $this->validateExistsNomeDependete();
        
        $this->validarUnicidadeAgendamento();
        $this->validateChefiaImediata();
        $this->validarCamposObrigatoriosAcompanhante();
        $this->validarObitoServidor();
        $this->validarDataNascimento();
        $this->validateDuracao();
        $this->validateApartirDe();
        $this->validateCid();
        $this->validateUnidadeAtendimento();
        $this->validateDiaSemana();
        $this->validateDataHora();
        $this->validateSexo();
        $this->validarTipoIsencao();

        $this->validateExigencia();
        $this->validateCamposCat();

        if($tipologia == TIPOLOGIA_APOSENTADORIA_ESPECIAL){
            if($this->data[$this->alias]['ppp'] == null){
                $this->invalidate('ppp', 'É obrigatório o envio do PPP.');
            }
            if($this->data[$this->alias]['ltcat'] == null){
                $this->invalidate('ltcat', 'É obrigatório o envio do LTCAT.');
            }
        }
        $arrayTipoDeclaracao = array(TIPOLOGIA_READAPTACAO_FUNCAO, TIPOLOGIA_REMANEJAMENTO_FUNCAO, TIPOLOGIA_REMOCAO);
        if(in_array($tipologia, $arrayTipoDeclaracao)){
	        if($this->data[$this->alias]['declaracao_atribuicoes'] == null){
		        $this->invalidate('declaracao_atribuicoes', 'É obrigatório o envio da Declaração de Atribuições.');
	        }
        }

        if (isset($this->data[$this->alias]['processo_administrativo']) && $this->data[$this->alias]['processo_administrativo'] == 1) {
            if(!isset($this->data[$this->alias]['numero_pad']) || empty($this->data[$this->alias]['numero_pad'])){
                $this->invalidate('processo_administrativo', 'Servidores respondendo a PAD devem informar o número do mesmo.');
            }
        }

        if (!$ignoreConfirmarDivulgacao) {
            if (isset($this->data[$this->alias]['confirmar_divulgacao']) && $this->data[$this->alias]['confirmar_divulgacao'] == 0) {
                $msg = 'É necessário autorizar a divulgação das informações médicas e seu diagnóstico codificado (CID)
                    para fins de perícias medica, conforme a resolução do Conselho Federal de Medicina- CFM nº 1658/2002
                    para realizar um agendamento.';

                if ($tipologia == TIPOLOGIA_INSPECAO){
                    $msg = 'Autorizo a divulgação das informações aqui declaradas';
                }
                $this->invalidate('validacao_autorizacao_cmf', $msg);
            }
        }

        $requiredTipo = false;  //
        if (in_array($tipologia, array(TIPOLOGIA_SINDICANCIA_INQUERITO_PAD))) {
            $requiredTipo = true;
        }
        if ($requiredTipo) {
            if (isset($this->data[$this->alias]['tipo']) && empty($this->data[$this->alias]['tipo'])) {
                $this->invalidate('tipo', 'É necessário informar o tipo da tipologia escolhida');
            }
        }

        if ($tipologia == TIPOLOGIA_LICENCA_MEDICA_TRATAMENTO_SAUDE) {
            if (!isset($this->data[$this->alias]['tratamento_acidente']) || $this->data[$this->alias]['tratamento_acidente'] === "") {
                $this->invalidate('tratamento_acidente', 'É necessário informar se a licença é decorrente de acidente de trabalho');
            } else if ($this->data[$this->alias]['tratamento_acidente'] == 1) {
                if (!isset($this->data[$this->alias]['tratamento_acidente_processo']) || empty($this->data[$this->alias]['tratamento_acidente_processo'])) {
                    $this->invalidate('tratamento_acidente_processo', 'É necessário informar o processo CAT');
                }
            }else{
                $this->data[$this->alias]['tratamento_acidente_processo'] = null;
            }
        }

       


        if($tipologia == TIPOLOGIA_SINDICANCIA_INQUERITO_PAD){
            $isOk = $this->validarDataHoraLivre($this->data[$this->alias]);
            $now = date('Y-m-d H:i:s');
            if($isOk){
                if($now > Util::toDBDataHora($this->data[$this->alias]['data_hora'])){
                    $this->invalidate('data_livre', 'Não é possível agendar com uma data e hora menor que a atual');
                }
            }
        }
        $this->validarDesignacao();
        $this->validarInspecao();

        $this->validarDadosPretenso();

        parent::beforeValidate($options);
    }

    public function afterFind($results, $primary = false)
    {

        return parent::afterFind($results, $primary); // TODO: Change the autogenerated stub
    }

    public function beforeSave($options = array()) {

        parent::beforeSave($options);

        $arrDateFields = array(
            'data_hora' => true, 'data_inclusao'=> true, 'data_exclusao'=> true,
            'data_alteracao' => true);

        foreach($arrDateFields as $key => $val){
            if(isset($this->data[$this->alias][$key]) && !empty($this->data[$this->alias][$key])){
                $this->data[$this->alias][$key] = Util::toBrDataHora($this->data[$this->alias][$key], $val);
            }
        }

        if (isset($this->data[$this->alias]['cpf_acompanhado'])) {
            $this->data[$this->alias]['cpf_acompanhado'] = Util::limpaDocumentos($this->data[$this->alias]['cpf_acompanhado']);
        }

        if (isset($this->data[$this->alias]['cpf_pretenso'])) {
            $this->data[$this->alias]['cpf_pretenso'] = Util::limpaDocumentos($this->data[$this->alias]['cpf_pretenso']);
        }

    }

    public function possuiAgendamentoVigente($unidade, $data, $usuario, $tipologia, $id = null, $numero_processo = null) {
        $tipologia_id = $tipologia;

        $filtro = new BSFilter();
        $filtro->setTipo('all');
        $filtro->setCamposRetornadosString("id");
        $condicoes[] = array('or' => array(
            'Agendamento.unidade_atendimento_id'=> $unidade,
            'Agendamento.unidade_atendimento_id is null'));
        $condicoes[] = array('or'=>array(
            'Agendamento.data_hora >= ' => date("d/m/Y H:i"),
            'Agendamento.data_hora is null'));
        $condicoes['Agendamento.usuario_servidor_id'] = $usuario;
        $condicoes['Agendamento.tipologia_id'] = $tipologia_id;
        if($numero_processo){
            $condicoes['Agendamento.numero_processo'] = $numero_processo;
        }

        $condicoes['Agendamento.status_agendamento in'] = array('Agendado', 'Aguardando Atendimento');
        if ($id) {
            $condicoes['Agendamento.id != '] = $id;
        }
        $filtro->setCondicoes($condicoes);
        $agendamentos = $this->listar($filtro);
        return $agendamentos;
    }

    private function validarDesignacao(){
        if(isset($this->data[$this->alias]['tipologia_id'])){
            $dados = $this->data[$this->alias];
            $tipologiaId = $dados['tipologia_id'];
            if($tipologiaId == TIPOLOGIA_DESIGNACAO_DE_ASSISTENTE_TECNICO){
                $isOk = $this->validarDataHoraLivre($dados);
                $now = date('Y-m-d H:i:s');
                if($isOk){
                    if($now > Util::toDBDataHora($dados['data_hora'])){
                        $this->invalidate('data_livre', 'Não é possível agendar com uma data e hora menor que a atual');
                    }
                }
                if(!isset($dados['designacao_usuario_perito_id']) || $dados['designacao_usuario_perito_id'] == ''){
                    $this->invalidate('designacao_usuario_perito_id', 'É necessário informar um perito.');
                }
                $servidorAg = '';
                if(isset($dados['usuario_servidor_id'])){
                    $servidorAg = $dados['usuario_servidor_id'];
                }
                $peritoDesignado = '';
                if(isset($dados['designacao_usuario_perito_id'])){
                    $peritoDesignado  = $dados['designacao_usuario_perito_id'];
                }
                $chefeUm = '';
                if(isset($dados['chefe_imediato_um_id'])){
                    $chefeUm = $dados['chefe_imediato_um_id'];
                }

                $chefeDois = '';
                if(isset($dados['chefe_imediato_dois_id'])){
                    $chefeDois = $dados['chefe_imediato_dois_id'];
                }

                $chefeTres = '';
                if(isset($dados['chefe_imediato_tres_id'])){
                    $chefeTres = $dados['chefe_imediato_tres_id'];
                }
                if($servidorAg == $peritoDesignado){
                    $this->invalidate('nome', 'O servidor do agendamento não pode ser o perito designado');
                }
                if(in_array($servidorAg , array($chefeUm, $chefeDois, $chefeTres))){
                    $this->invalidate('nome', 'O servidor do agendamento não pode ser chefe imediato de si mesmo');
                }
                if(in_array($peritoDesignado , array($chefeUm, $chefeDois, $chefeTres))){
                    $this->invalidate('nome_perito', 'O perito designado não pode ser chefe imediato de si mesmo');
                }
            }
        }
    }

    private function validarInspecao(){
        if(isset($this->data[$this->alias]['tipologia_id'])){
            $dados = $this->data[$this->alias];
            $tipologiaId = $dados['tipologia_id'];
            if($tipologiaId == TIPOLOGIA_INSPECAO){
                $this->validarDataHoraLivre($dados);

                if(!isset($dados['orgao']) || $dados['orgao'] == ''){
                    $this->invalidate('orgao', 'É necessário informar o Local – Orgão.');
                }
            }

        }
    }

    private function validarDadosPretenso(){
        if(isset($this->data[$this->alias]['tipologia_id'])){
            $dados = $this->data[$this->alias];
            $dataAtual = date('Y-m-d');
            // $dataAtual = Util::toBrData($dataAtual);
            $tipologiaId = $dados['tipologia_id'];


            // pr($dataAtual);
            // pr($dtPretenso);
            // pr($dataAtual < $dtPretenso);
            // die;
            if($tipologiaId == TIPOLOGIA_ADMISSAO_PENSIONISTA_MAIOR_INVALIDO){
                $dtPretenso = date('Y-m-d', strtotime( str_replace("/", "-", $dados['data_nascimento_pretenso'])));
                
                
                if(!isset($dados['cpf_pretenso']) || $dados['cpf_pretenso'] == ''){
                    $this->invalidate('cpf_pretenso', 'É necessário informar o CPF do Pretenso Pensionista.');
                }if(!Util::validaCPF($dados['cpf_pretenso'])){
                    $this->invalidate('cpf_pretenso', 'CPF do Pretenso inválido.');
                }
                if(!isset($dados['nome_pretenso']) || $dados['nome_pretenso'] == ''){
                    $this->invalidate('nome_pretenso', 'É necessário informar o Nome do Pretenso Pensionista.');
                }if(!isset($dados['data_nascimento_pretenso']) || $dados['data_nascimento_pretenso'] == ''){
                    $this->invalidate('data_nascimento_pretenso', 'É necessário informar a Data de Nascimento do Pretenso Pensionista.');
                }if(!isset($dados['sexo_id_pretenso']) || $dados['sexo_id_pretenso'] == ''){
                    $this->invalidate('data_nascimento_pretenso', 'É necessário informar o Sexo do Pretenso Pensionista.');
                }if($dataAtual < $dtPretenso){
                    $this->invalidate('data_nascimento_pretenso', 'A data de nascimento do Pretenso Pensionista não pode ser uma data futura.');
                }
            }

        }
    }

    private function validarDataHoraLivre($dados){
        if(!isset($dados['data_livre']) || $dados['data_livre'] == ''){
            $this->invalidate('data_livre', 'É necessário informar uma data.');
            return false;
        }
        if(!isset($dados['hora_livre']) || $dados['hora_livre'] == ''){
            $this->invalidate('hora_livre', 'É necessário informar uma hora.');
            return false;
        }
        return true;
    }


    private function validarUnicidadeAgendamento() {
        if (isset($this->data[$this->alias]['unidade_atendimento_id']) && isset($this->data[$this->alias]['data_hora'])
                && isset($this->data[$this->alias]['usuario_servidor_id']) && isset($this->data[$this->alias]['tipologia_id'])) {
            $id = null;
            if (isset($this->data[$this->alias]['id']) && !empty($this->data[$this->alias]['id'])) {
                $id = $this->data[$this->alias]['id'];
            }
            $unidade = $this->data[$this->alias]['unidade_atendimento_id'];
            $data = $this->data[$this->alias]['data_hora'];
            $servidor = $this->data[$this->alias]['usuario_servidor_id'];
            $tipologia = $this->data[$this->alias]['tipologia_id'];
            $numero_processo = $this->data[$this->alias]['numero_processo'];
            $count = count($this->possuiAgendamentoVigente($unidade, $data, $servidor, $tipologia, $id, $numero_processo));

            $encaixe = isset($this->data[$this->alias]['encaixe'])?$this->data[$this->alias]['encaixe']:null;
            // pr($this->data[$this->alias]);die;
            if ($count > 0 && (is_null($encaixe) or $encaixe == '0' )) {
                $this->invalidate('data_hora', __('validacao_unicidade_agendamento'));
            }
        }
    }

    private function validarDataNascimento() {
        if (isset($this->data[$this->alias]['data_nascimento_acompanhado']) && $this->data[$this->alias]['data_nascimento_acompanhado']) {
            // $dataNascimento = Util::inverteData($this->data[$this->alias]['data_nascimento_acompanhado']);
            $dataNascimento = $this->data[$this->alias]['data_nascimento_acompanhado'];
            $dataAtual = date('Y-m-d');


            $dataNascimento = Util::toDBData($dataNascimento);
            $dataAtual = Util::toDBData($dataAtual);
            $dataNascimento = new DateTime($dataNascimento);
            $dataAtual = new DateTime($dataAtual);

            if ($dataNascimento > $dataAtual) {
                $this->invalidate('data_nascimento_acompanhado', 'A Data de Nascimento não pode ser uma data futura.');
            }

        }
    }

    private function validarCamposObrigatoriosAcompanhante() {
        if (isset($this->data[$this->alias]['tipologia_id'])) {
            $tipologia = $this->data[$this->alias]['tipologia_id'];
            if ($tipologia == TIPOLOGIA_LICENCA_ACOMPANHAMENTO_FAMILIAR) {
                if (!isset($this->data[$this->alias]['acompanhado_cid_id']) || !$this->data[$this->alias]['acompanhado_cid_id']) {
                    $this->invalidate('searchAcompnhadoCid', 'O Campo CID é obrigatório.');
                }
                if (!isset($this->data[$this->alias]['nome_acompanhado_sem_abreviacao']) || !$this->data[$this->alias]['nome_acompanhado_sem_abreviacao']) {
                    $this->invalidate('nome_acompanhado_sem_abreviacao', 'O Campo Nome sem Abreviação é obrigatório.');
                }
                if (!isset($this->data[$this->alias]['data_nascimento_acompanhado']) || !$this->data[$this->alias]['data_nascimento_acompanhado']) {
                    $this->invalidate('data_nascimento_acompanhado', 'O Campo Data de Nascimento é obrigatório.');
                }
                if (!isset($this->data[$this->alias]['qualidade_id']) || !$this->data[$this->alias]['qualidade_id']) {
                    $this->invalidate('qualidade_id', 'O Campo Qualidade é obrigatório.');
                }
            }
        }
    }

    private function validateExistsNomeDependete() {
        $nomeDependete = $this->data[$this->alias]['nome_acompanhado_sem_abreviacao'];

        $filtro = new BSFilter();
        $condicoes = array();
        $condicoes['Agendamento.nome_acompanhado_sem_abreviacao'] = Util::limpaDocumentos($nomeDependete);

        if (isset($this->data[$this->alias]['id'])) {
            $condicoes['Agendamento.id != '] = $this->data[$this->alias]['id'];
        }

        $filtro->setTipo('count');
        $filtro->setCondicoes($condicoes);
        $count = $this->listar($filtro);
        if ($count != 0) {
            $this->invalidate('nome_acompanhado_sem_abreviacao', 'Já existe um servidor para esse acompanhamento.');
        }
    }

    public function validarUnicidade() {
        $filtro = new BSFilter();
        $condicoes['Agendamento.protocolo'] = $this->data[$this->alias]['protocolo'];

        if (isset($this->data[$this->alias]['id'])) {
            $condicoes['Agendamento.id !='] = $this->data[$this->alias]['id'];
        }
        $filtro->setTipo('count');
        $filtro->setCondicoes($condicoes);
        $count = $this->listar($filtro);
        return $count == 0;
    }

    public function validarExclusao($id) {
        $retorno = true;
        if (is_numeric($id)) {
            $vinculo = new Vinculo();
            $filtro = new BSFilter();
            $condicoes['Vinculo.cargo_id'] = $id;
            $filtro->setCondicoes($condicoes);
            $filtro->setTipo('count');
            $count = $vinculo->listar($filtro);
            if ($count == 0) {
                $retorno = false;
            }
        }
        return $retorno;
    }

    /**
     * Método para consultar os agendamentos realizados de acordo com a tipologia, unidade e dia da semana
     * @param type $idUnidade
     * @param type $diaSemana
     * @return type
     */
    public function consultarAgendamentos($idUnidade, $diaSemana) {
        $dataAtual = date('Y-m-d H:i');
        $filtro = new BSFilter();
        $condicoes['Agendamento.unidade_atendimento_id'] = $idUnidade;
        $condicoes['Agendamento.dia_semana'] = $diaSemana;
        $condicoes['Agendamento.data_hora >= '] = $dataAtual;
        $filtro->setCondicoes($condicoes);
        $filtro->setTipo('all');
        return $this->listar($filtro);
    }

    /**
     * Método para consultar os agendamentos domiciliar realizados de acordo com a tipologia, unidade e dia da semana
     * @param type $idUnidade
     * @param type $diaSemana
     * @return type
     */
    public function consultarAgendamentosDomiciliar($idUnidade, $diaSemana) {
        $dataAtual = date('Y-m-d H:i');
        $filtro = new BSFilter();
        
        if(!empty($idUnidade)){
            $condicoes['Agendamento.unidade_atendimento_id']    = $idUnidade;
        }
        
        $condicoes['Agendamento.dia_semana']                = $diaSemana;
        $condicoes['Agendamento.data_hora >= ']             = $dataAtual;
        $condicoes['Agendamento.atendimento_domiciliar']    = true;

        $filtro->setCondicoes($condicoes);
        $filtro->setTipo('all');

        return $this->listar($filtro);
    }

    public function buscarDataAgendamento($id) {
        $filtro = new BSFilter();
        $condicoes['Agendamento.id'] = $id;
        $filtro->setCondicoes($condicoes);
        $filtro->setCamposRetornadosString("data_hora");
        $filtro->setTipo('all');
        $agendamentos = $this->listar($filtro);
        return count($agendamentos) > 0 ? $agendamentos[0]['Agendamento']['data_hora'] : null;
    }

    public function listarAgendamentosConfimados($salaPerito, $usuario = null) {
        $peritoId = '';
        if(!empty($usuario)){
            $peritoId = $usuario['Usuario']['id'];
        }
        $filtro = new BSFilter();

        $arrOr = array();
        $arrOr[] = array('(CAST(Agendamento.data_hora as date)) =' => date('Y-m-d'));

        // TIPOLOGIA SEM DATA
        $arrOr[] = array('Agendamento.tipologia_id IN' => Agendamento::$ARRAY_TIPOLOGIAS_SEM_DATA);
        $condicoes[] = array('or' =>$arrOr);

        $arrOr = array();
        $arrOr[] = array('Agendamento.status_agendamento' => "Aguardando Atendimento");
        $arrOr[] = array('Agendamento.status_agendamento' => "Agendado");
        $condicoes[] = array('or' => $arrOr);

        $arrOrTipologiaComSalaSemSala = array();
        if (!empty($salaPerito)) {
            $idsTipologias = Util::criarListaIds($salaPerito['Tipologia']);
            //DESIGNACAO APENAS O PERITO DESIGNADO PODE ATENDER
            $idsTipologias = array_diff($idsTipologias, array(TIPOLOGIA_DESIGNACAO_DE_ASSISTENTE_TECNICO));
        }
        $idsTipologias[] = 0;
        if (count($idsTipologias) == 1){
            $arrOrTipologiaComSalaSemSala[] = array('Agendamento.tipologia_id'=> $idsTipologias);
        } else {
            $arrOrTipologiaComSalaSemSala[] = array('Agendamento.tipologia_id IN'=> $idsTipologias);
        }

        $arrTipologiaSemSalaQueAtende = array(0);

        $arrAgendaAtendimento = $usuario['AgendaAtendimento'];
        //pr($arrAgendaAtendimento);die;
        $arrTipologiaAgendaHoje = array();
        $diaSemanaHoje = $this->diaDaSemana(date("d/m/Y"));
        foreach($arrAgendaAtendimento as  $agendaAtendimento){
            if($diaSemanaHoje == $agendaAtendimento['AgendaAtendimento']['dia_semana']){
                foreach($agendaAtendimento['Tipologia'] as $tipologia){
                    $arrTipologiaAgendaHoje[] = $tipologia['id'];
                }
            }
        }
        //VERIFICAR QUAIS DAS TIPOLOGIAS DA AGENDA NAO PRECISA DE SALA
        $arrTipologiaAgendaHoje = array_unique($arrTipologiaAgendaHoje);
        foreach($arrTipologiaAgendaHoje as $idTipologia){
            if(in_array($idTipologia, Agendamento::$ARRAY_TIPOLOGIAS_SEM_SALA)){
                $arrTipologiaSemSalaQueAtende[] = $idTipologia;
            }
        }
        //GARANTIR QUE SEMPRE VAI UTILIZAR O IN DO SQL -> ARRAY COM + DE 1 ELEMENTO
        $arrTipologiaSemSalaQueAtende[] = 0;
        $arrTipologiaSemSalaQueAtende[] = 0;
        $arrOrTipologiaComSalaSemSala[] = array('Agendamento.tipologia_id IN' => $arrTipologiaSemSalaQueAtende);

        //CASO SEJA UM RECURSO DE UMA TIPOLOGIA QUE ATENDE SEM SALA
        $condicaoRecursoSemSala = array(
            'Agendamento.tipologia_id' => TIPOLOGIA_RECURSO_ADMINISTRATIVO,
            'Agendamento.recurso_tipologia_id IN' => $arrTipologiaSemSalaQueAtende
        );
        $arrOrTipologiaComSalaSemSala[] = $condicaoRecursoSemSala;


        //IGNORAR VERIFICACAO DE SALA/AGENDA SE FOR DESIGNACAO//RECURSO DE DESIGNACAO PARA O PERITO LOGADO
        $arrOrTipologiaComSalaSemSala[] = array(
            'or' => array(
                //RECURSO DE DESIGNACAO
                array(
                    'Agendamento.tipologia_id' => TIPOLOGIA_RECURSO_ADMINISTRATIVO,
                    'Agendamento.recurso_tipologia_id' => TIPOLOGIA_DESIGNACAO_DE_ASSISTENTE_TECNICO
                ),
                //DESIGNACAO
                'Agendamento.tipologia_id' => TIPOLOGIA_DESIGNACAO_DE_ASSISTENTE_TECNICO,
            ),
            'Agendamento.designacao_usuario_perito_id'=>$peritoId);

        $condicoes[] = array('or' =>$arrOrTipologiaComSalaSemSala);


        if (!empty($salaPerito)){
            $arrOr  = array(
                'Agendamento.unidade_atendimento_id'=>$salaPerito['UnidadeAtendimento']['id'],

                //DESIGNACAO
                'Agendamento.designacao_usuario_perito_id'=>$peritoId,

                //TIPOLOGIAS SEM SALA
                'Agendamento.tipologia_id in '=>Agendamento::$ARRAY_TIPOLOGIAS_SEM_SALA,

                //RECURSO DE UMA TIPOLOGIA SEM SALA
                array(
                    'Agendamento.tipologia_id' => TIPOLOGIA_RECURSO_ADMINISTRATIVO,
                    'Agendamento.recurso_tipologia_id in '=>Agendamento::$ARRAY_TIPOLOGIAS_SEM_SALA
                )
            );
            $condicoes[] = array('or'=>$arrOr);
        }else{
            $arrOr  = array(
                //DESIGNACAO
                'Agendamento.designacao_usuario_perito_id'=>$peritoId,

                //TIPOLOGIAS SEM SALA
                'Agendamento.tipologia_id in '=>Agendamento::$ARRAY_TIPOLOGIAS_SEM_SALA,

                //RECURSO DE UMA TIPOLOGIA SEM SALA
                array(
                    'Agendamento.tipologia_id' => TIPOLOGIA_RECURSO_ADMINISTRATIVO,
                    'Agendamento.recurso_tipologia_id in '=>Agendamento::$ARRAY_TIPOLOGIAS_SEM_SALA
                )
            );
            $condicoes[] = array('or'=>$arrOr);
        }
        //LISTAGEM ATENDIMENTO NAO DOMICILIAR
        $arrOr = array();
        $arrOr[] = array('Agendamento.atendimento_domiciliar <>'=> true);
        $arrOr[] = array('Agendamento.atend_domicilio_unidade_proxima <>'=> false);
        $condicoes[] = array('or' =>$arrOr);

        $filtro->setCondicoes($condicoes);
        $filtro->setTipo('all');
        $filtro->setCamposOrdenados(['Agendamento.tempo_consulta' => 'asc','Agendamento.status_agendamento' => 'desc', 'Agendamento.data_hora' => 'asc', 'Agendamento.encaixe' => 'asc']);
        $arrRetorno = $this->listar($filtro);

        
        $arrAgendamentoDomicilio = $this->listarAgendamentosDomiciliarConfimados($usuario);
        //pr($arrAgendamentoDomicilio);die;
        return array_merge($arrRetorno, $arrAgendamentoDomicilio);
    }

    public function buscarProximoAgendamentoConfirmado($usuario, $idUnidade = 0, $tipologias = array()) {

        $peritoId = '';
        if(!empty($usuario)){
            $peritoId = $usuario['Usuario']['id'];
        }


        $filtro = new BSFilter();

        $arrOr = array();
        $arrOr[] = array('(CAST(Agendamento.data_hora as date)) =' => date('Y-m-d'));

        //TIPOLOGIAS SEM DATA
        $arrOr[] = array('Agendamento.tipologia_id in '=>Agendamento::$ARRAY_TIPOLOGIAS_SEM_DATA);
        $condicoes[] = array('or' =>$arrOr);

        $condicoes['Agendamento.agendamento_confirmado'] = true;
        $condicoes['Agendamento.status_agendamento'] = "Aguardando Atendimento";

        $arrOrTipologiaComSalaSemSala = array();
        $idsTipologias = Util::criarListaIds($tipologias);
        //DESIGNACAO APENAS O PERITO DESIGNADO PODE ATENDER
        $idsTipologias = array_diff($idsTipologias, array(TIPOLOGIA_DESIGNACAO_DE_ASSISTENTE_TECNICO));
        $idsTipologias[] = 0;
        $idsTipologias[] = 0; //GARANTIR ARRAY COM + DE 1 ELEMENTO

        $arrOrTipologiaComSalaSemSala[] = array('Agendamento.tipologia_id IN'=> $idsTipologias);


        $arrAgendaAtendimento = $usuario['AgendaAtendimento'];
        $arrTipologiaAgendaHoje = array();
        $diaSemanaHoje = $this->diaDaSemana(date("d/m/Y"));
        //TIPOLOGIAS QUE O PERITO ATENDE HOJE
        foreach($arrAgendaAtendimento as  $agendaAtendimento){
            if($diaSemanaHoje == $agendaAtendimento['AgendaAtendimento']['dia_semana']){
                foreach($agendaAtendimento['Tipologia'] as $tipologia){
                    $arrTipologiaAgendaHoje[] = $tipologia['id'];
                }
            }
        }
        //TIPOLOGIAS SEM SALA
        $arrTipologiaAgendaHoje = array_unique($arrTipologiaAgendaHoje);
        foreach($arrTipologiaAgendaHoje as $idTipologia){
            if(in_array($idTipologia, Agendamento::$ARRAY_TIPOLOGIAS_SEM_SALA)){
                $arrTipologiaSemSalaQueAtende[] = $idTipologia;
            }
        }
        $arrTipologiaSemSalaQueAtende[] = 0;
        $arrTipologiaSemSalaQueAtende[] = 0; //GARANTIR ARRAY COM + DE 1 ELEMENTO

        $arrOrTipologiaComSalaSemSala[] = array('Agendamento.tipologia_id IN' => $arrTipologiaSemSalaQueAtende);

        //CASO SEJA UM RECURSO DE UMA TIPOLOGIA QUE ATENDE SEM SALA
        $condicaoRecursoSemSala = array(
            'Agendamento.tipologia_id' => TIPOLOGIA_RECURSO_ADMINISTRATIVO,
            'Agendamento.recurso_tipologia_id IN' => $arrTipologiaSemSalaQueAtende
        );
        $arrOrTipologiaComSalaSemSala[] = $condicaoRecursoSemSala;

        //IGNORAR VERIFICAÇÃO DE SALA/AGENDA SE FOR DESIGNAÇÃO/RECURSO DE DESIGNACAO PARA O PERITO LOGADO
        $arrOrTipologiaComSalaSemSala[] = array(
            'or' => array(
                //RECURSO DE DESIGNACAO
                array(
                    'Agendamento.tipologia_id' => TIPOLOGIA_RECURSO_ADMINISTRATIVO,
                    'Agendamento.recurso_tipologia_id' => TIPOLOGIA_DESIGNACAO_DE_ASSISTENTE_TECNICO
                ),
                //DESIGNACAO
                'Agendamento.tipologia_id' => TIPOLOGIA_DESIGNACAO_DE_ASSISTENTE_TECNICO,
            ),
            'Agendamento.designacao_usuario_perito_id'=>$peritoId);

        $condicoes[] = array('or' =>$arrOrTipologiaComSalaSemSala);


        $arrOr = array();
        //DESIGNACAO
        $arrOr['Agendamento.designacao_usuario_perito_id'] = $peritoId;

        //TIPOLOGIA COM SALA
        $arrOr['Agendamento.unidade_atendimento_id'] = $idUnidade;

        //TIPOLOGIA SEM SALA
        $arrOr['Agendamento.tipologia_id'] = Agendamento::$ARRAY_TIPOLOGIAS_SEM_SALA;

        //RECURSO DE TIPOLOGIA SEM SALA
        $arrOr[] = array(
            'Agendamento.tipologia_id' => TIPOLOGIA_RECURSO_ADMINISTRATIVO,
            'Agendamento.recurso_tipologia_id in '=>Agendamento::$ARRAY_TIPOLOGIAS_SEM_SALA
        );
        $condicoes[] = array('or'=> $arrOr);

        //TIPOLOGIA QUE PRECISAM DE HOMOLOGACAO
        $condicoes['(CASE WHEN "Agendamento"."tipologia_id" IN ('.TIPOLOGIA_RISCO_VIDA_INSALUBRIDADE.', '.TIPOLOGIA_COMUNICACAO_DE_ACIDENTE_DE_TRABALHO.') THEN "Agendamento"."homologa" ELSE  1 END) = '] = 1;


        $filtro->setCondicoes($condicoes);
        $filtro->setCamposOrdenados(['Agendamento.tempo_consulta' => 'asc', 'Agendamento.data_hora' => 'asc']);
        $filtro->setLimiteListagem(1);
        $filtro->setCamposRetornadosString("id", "usuario_servidor_id", "data_hora" , "tempo_consulta");
        $filtro->setTipo('all');
        $agendamentos = $this->listar($filtro);
        $agendamento = null;
        if (!empty($agendamentos)) {
            $agendamento = $agendamentos[0];
        }
        //TRECHO DE COMPARACAO ATENDIMENTO DOMICILIAR VS NA UNIDADE
        $agendamentoDomiciliar = null;
        $agendamentosDomiciliar = $this->listarAgendamentosDomiciliarConfimados($usuario, true);
        if(!empty($agendamentosDomiciliar)){
            $agendamentoDomiciliar = $agendamentosDomiciliar[0];
        }
        $resultado = $agendamento;
        if(!empty($agendamentoDomiciliar) && !empty($agendamento)){
            $dataAgendamento = Util::toDBDataHora($agendamento['Agendamento']['data_hora']);
            $dataDomiciliar = Util::toDBDataHora($agendamentoDomiciliar['Agendamento']['data_hora']);
            if ($dataDomiciliar < $dataAgendamento){
                $resultado = $agendamentoDomiciliar;
            }
        }else if(!empty($agendamentoDomiciliar)){
            $resultado = $agendamentoDomiciliar;
        }

        return $resultado;
    }

    public function buscarDuracaoAgendamento($idAgendamento) {
        $filtro = new BSFilter();
        $condicoes['Agendamento.id'] = $idAgendamento;
        $filtro->setCondicoes($condicoes);
        $filtro->setTipo('all');
        $filtro->setCamposRetornadosString('id', 'duracao');
        $agendamento = null;
        $agendamentos = $this->listar($filtro);
        if (!empty($agendamentos)) {
            $agendamento = $agendamentos[0]['Agendamento'];
        }
        return $agendamento;
    }

    public function listarAgendamentosDomiciliarConfimados($usuario, $proximo=false){
        $peritoId = '';
        if(!empty($usuario)){
            $peritoId = $usuario['Usuario']['id'];
        }

        $filtroDomicilio = new BSFilter();

        $joins= array();
        $joins[] = array(
            'table' => 'endereco',
            'alias' => 'e',
            'type' => 'left',
            'conditions' => array('e.id= Agendamento.endereco_id_atend_domici')
        );
        $filtroDomicilio->setJoins($joins);

        $condicoes = array();


        $condicoes['CAST(Agendamento.data_hora as date) = '] = date('Y-m-d');
        if(!$proximo){
            $condicoes['OR'][]['Agendamento.status_agendamento'] = "Aguardando Atendimento";
            $condicoes['OR'][]['Agendamento.status_agendamento'] = "Agendado";
        }else{
            $condicoes['OR'][]['Agendamento.status_agendamento'] = "Aguardando Atendimento";
        }

        //ATENDIMENTO DOMICILIAR EM UM ENDERECO ESPECIFICO
        $condicoes['Agendamento.atendimento_domiciliar'] = true;
        $condicoes['Agendamento.atend_domicilio_unidade_proxima'] = false;

        $agendaAtendDomicilio = $usuario['AgendaAtendimentoDomicilio'];
        $arrMunicipioDiaTipologia = array();
        foreach($agendaAtendDomicilio as $agendaDomicilio){
            $municipioDiaTipologia = array();
            $unidadeAtendimentoId = $agendaDomicilio['AgendaAtendimentoDomicilio']['unidade_atendimento_id'];
            $arrMunicipios = $this->UnidadeAtendimento->listarMunicipiosProximos($unidadeAtendimentoId);

            $municipioDiaTipologia['municipios_id'][] = $agendaDomicilio['AgendaAtendimentoDomicilio']['municipio_id'];
            foreach($arrMunicipios as $id=>$municipio){
                $municipioDiaTipologia['municipios_id'][] = $id;
            }
            $municipioDiaTipologia['dia_semana'] = $agendaDomicilio['AgendaAtendimentoDomicilio']['dia_semana'];
            $arrTipologia = $agendaDomicilio['Tipologia'];
            foreach($arrTipologia as  $tipologia){
                $municipioDiaTipologia['tipologias_id'][] = $tipologia['id'];
            }
            $arrMunicipioDiaTipologia[] = $municipioDiaTipologia;
            $arrAnd = array();
            if(count($municipioDiaTipologia['municipios_id']) > 1){
                $arrAnd['e.municipio_id in'] = $municipioDiaTipologia['municipios_id'];
            }else{
                $arrAnd['e.municipio_id'] = $municipioDiaTipologia['municipios_id'][0];
            }
            $arrAnd['Agendamento.dia_semana'] = $municipioDiaTipologia['dia_semana'];
            if(count($municipioDiaTipologia['tipologias_id']) > 1){
                $arrAnd['Agendamento.tipologia_id in'] = $municipioDiaTipologia['tipologias_id'];
            }else{
                $arrAnd['Agendamento.tipologia_id'] = $municipioDiaTipologia['tipologias_id'][0];
            }
            $condicoes['or'][] = $arrAnd;
        }
        //IGNORAR AGENDA DOMICILIO CASO FOR DESIGNACAO PARA O PERITO LOGADO
        $condicoes['or'][] = array(
            'Agendamento.tipologia_id' => TIPOLOGIA_DESIGNACAO_DE_ASSISTENTE_TECNICO,
            'Agendamento.designacao_usuario_perito_id'=>$peritoId);

        $filtroDomicilio->setCondicoes($condicoes);
        $filtroDomicilio->setTipo('all');
        if(!$proximo){
            $filtroDomicilio->setCamposOrdenados(['Agendamento.tempo_consulta' => 'asc', 'Agendamento.status_agendamento' => 'desc', 'Agendamento.data_hora' => 'asc']);
        }else{
            $filtroDomicilio->setCamposOrdenados(['Agendamento.tempo_consulta' => 'asc', 'Agendamento.data_hora' => 'asc']);
            $filtroDomicilio->setLimiteListagem(1);
            $filtroDomicilio->setCamposRetornadosString("Agendamento.id", "Agendamento.usuario_servidor_id", "Agendamento.data_hora", 'Agendamento.tempo_consulta');
        }

        $arrDomiciliar = $this->listar($filtroDomicilio);

        //pr($arrDomiciliar);die;

        return $arrDomiciliar;
    }

    public function getAgendamento($id, $full=false){
        $filtro = new BSFilter();
        $filtro->setTipo('first');
        $filtro->setCondicoes(array(
            'Agendamento.id' => $id
        ));
        $resultado = $this->listar($filtro);
        if (!$full){
            $resultado = $resultado['Agendamento'];
        }
        return $resultado;
    }

    public function  getAgendamentoIdOriginal($numero_processo){
        $atendimento = new Atendimento();
        $filtro = new BSFilter();

        $condicoes = array('Atendimento.id' => $numero_processo);
        $filtro->setCondicoes($condicoes);
        $filtro->setCamposRetornados(['Atendimento.id', 'Agendamento.id', 'Agendamento.tipologia_id', 'Agendamento.numero_processo']);
        $filtro->setTipo('all');

        $arrResultado = $atendimento->listar($filtro);


        if(!empty($arrResultado) && count($arrResultado) >0) {
            $resultado = $arrResultado[0];

            $tipologia_id = $resultado['Agendamento']['tipologia_id'];
            $agendamento_id = $resultado['Agendamento']['id'];

            if ( $tipologia_id == TIPOLOGIA_RECURSO_ADMINISTRATIVO ) {
                $agendamento_id = $this->getAgendamentoIdOriginal($resultado['Agendamento']['numero_processo']);
            }
            return $agendamento_id;
        }else{
            return null;
        }
    }

    public function  getAgendamentoOriginal($numero_processo, $full=false){
        $agendamento_id = $this->getAgendamentoIdOriginal($numero_processo);
        return $this->getAgendamento($agendamento_id, $full);
    }


    public function  getDesignadoIdOriginal($numero_processo){
        $atendimento = new Atendimento();
        $filtro = new BSFilter();

        $condicoes = array('Atendimento.id' => $numero_processo);
        $filtro->setCondicoes($condicoes);
        $filtro->setCamposRetornados(['Atendimento.id', 'Agendamento.id', 'Agendamento.tipologia_id',
            'Agendamento.numero_processo', 'Agendamento.designacao_usuario_perito_id']);
        $filtro->setTipo('all');

        $arrResultado = $atendimento->listar($filtro);


        if(!empty($arrResultado) && count($arrResultado) >0) {
            $resultado = $arrResultado[0];

            $tipologia_id = $resultado['Agendamento']['tipologia_id'];
            $perito_designado_id = $resultado['Agendamento']['designacao_usuario_perito_id'];

            if ( $tipologia_id == TIPOLOGIA_RECURSO_ADMINISTRATIVO ) {
                $perito_designado_id = $this->getAgendamentoIdOriginal($resultado['Agendamento']['numero_processo']);
            }
            return $perito_designado_id;
        }else{
            return null;
        }
    }

    public function ignorarCamposAgendamentoRecurso(){
        return array(
            'id',
            'usuario_versao_id',
            'tempo_consulta',
            'usuario_servidor_id',
            'tipologia_id',
            'atendimento_vigente_id',
            'cid_id',
            'qualidade_id',
            'unidade_atendimento_id',
            'acompanhado_cid_id',
            'data_hora',
            'chefe_imediato_um_id',
            'chefe_imediato_um_orgao_origem_id',
            'chefe_imediato_um_lotacao_id',
            'chefe_imediato_dois_id',
            'chefe_imediato_dois_orgao_origem_id',
            'chefe_imediato_dois_lotacao_id',
            'chefe_imediato_tres_id',
            'chefe_imediato_tres_orgao_origem_id',
            'chefe_imediato_tres_lotacao_id',
            'protocolo',
            'dia_semana',
            'status_agendamento',
            'sala',
            'nome_acompanhado_sem_abreviacao',
            'cpf_acompanhado',
            'outros',
            'rg_acompanhado',
            'orgao_expedidor_acompanhado',
            'nome_mae_acompanhado',
            'certidao_nascimento_acompanhado',
            'porque_assistencia_incompativel_cargo',
            'porque_voce_unica_pessoa_cuidar',
            'data_nascimento_acompanhado',
            'data_a_partir',
            'duracao',
            'processo_administrativo',
            'agendamento_encaminhado_sala',
            'agendamento_confirmado',
            'readaptacao_definitiva',
            'tratamento_fora_municipio',
            'assistencia_incompativel_cargo',
            'encaixe',
            'confirmar_divulgacao',
            'ativo',
            'data_alteracao',
            'data_exclusao',
            'data_inclusao',
            'atendimento_domiciliar',
            'atend_domicilio_unidade_proxima',
            'municipio_id_atend_domicilio',
            'unidade_atendimento_id_atend_domici',
            'endereco_id_atend_domici',
            'atend_domic_endereco',
            'numero_processo', //SALVO NO AGENDAMENTO DE RECURSO ADMINISTRATIVO
            'recurso_tipologia', //SALVO NO AGENDAMENTO DE RECURSO ADMINISTRATIVO,
            'agendamento_id' // 'NECESSÁRIO IGNORAR ISSO PARA SALVAR AGENDAMENTO_CAT DE RECURSO'
        );
    }

    private function validateSexo(){
        if(isset($this->data[$this->alias]['tipologia_id'])){
            $tipologiaId = $this->data[$this->alias]['tipologia_id'];

        }else{
            return;
        }
        if(isset($this->data[$this->alias]['usuario_servidor_id'])){
            $usuarioId = $this->data[$this->alias]['usuario_servidor_id'];
        }else{
            return;
        }

        $usuario = new Usuario();
        $sexoId = $usuario->getSexoById($usuarioId);
        if (in_array($tipologiaId,
            array(TIPOLOGIA_LICENCA_MATERNIDADE, TIPOLOGIA_LICENCA_MATERNIDADE_ABORTO, TIPOLOGIA_LICENCA_NATIMORTO))){
            if($sexoId == SEXO_MASCULINO ){
                $this->invalidate('tipologia_id', 'A tipologia escolhida só é permitida para o sexo feminino.');
            }
        }
    }

    private function validarTipoIsencao(){
        if(isset($this->data[$this->alias]['tipologia_id'])){
            $tipologiaId = $this->data[$this->alias]['tipologia_id'];
        }else{
            $agendamento = $this->findById($this->data[$this->alias]['id']);
            $tipologiaId = $agendamento['Tipologia']['id'];
        }


        if($tipologiaId == TIPOLOGIA_ISENCAO_CONTRIBUICAO_PREVIDENCIARIA){
            if(!isset($this->data[$this->alias]['tipo_isencao']) ||
                empty($this->data[$this->alias]['tipo_isencao'])){
                $this->invalidate('tipo_isencao', 'É preciso informar o tipo de isenção');
            }else{
                if($this->data[$this->alias]['tipo_isencao'] == TIPO_ISENCAO_SERVIDOR){
                    if(!isset($this->data[$this->alias]['data_aposentadoria']) ||
                        trim($this->data[$this->alias]['data_aposentadoria']) == null){
                        $this->invalidate('data_aposentadoria', 'É preciso informar a data da aposentadoria');
                    }
                }
            }
        }
    }
}
