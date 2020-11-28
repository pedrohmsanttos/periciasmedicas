<?php

App::import("Model", "BSModel");
App::import("Model", "Tipologia");
App::import("Model", "UsuarioSenhaHist");
App::import("Model", "ParametroGeral");

class Usuario extends BSModel {

    /**
     * Ids dos peritos e os tipos de usuários.
     * @var int
     */
    public $peritoCredenciado;
    public $peritoServidor;
    public $interno;
    public $servidor;

    public $ignoreMail;

    /**
     * Last Ids
     */
    public $inserted_ids = [];

    /**
     * Configurando datas
     * @var array 
     */
    var $actsAs = array('DataPtBr');

    /**
     *  Nome da base de dados
     * @var string
     */
    public $useTable = 'usuario';
    public $belongsTo = array(
        'TipoUsuario' => array('className' => 'TipoUsuario', 'foreignKey' => 'tipo_usuario_id'),
        'UnidadeAtendimento' => array('className' => 'UnidadeAtendimento', 'foreignKey' => 'unidade_atendimento_id'),
        'Sexo' => array('className' => 'Sexo', 'foreignKey' => 'sexo_id'),
        'EstadoCivil' => array('className' => 'EstadoCivil', 'foreignKey' => 'estado_civil_id'),
        'EnderecoUsuario' => array('className' => 'EnderecoSimples', 'foreignKey' => 'endereco_id'),
        'Empresa' => array('className' => 'Empresa', 'foreignKey' => 'empresa_id'));
    public $hasMany = [
        'Dependente' => ['className' => 'Dependente',
            'conditions' => ['Dependente.ativo' => TRUE]],
        'Vinculo' => ['className' => 'Vinculo',
            'conditions' => ['Vinculo.ativo' => TRUE]],
        'AgendaAtendimento' => ['className' => 'AgendaAtendimento',
            'conditions' => ['AgendaAtendimento.ativo' => TRUE]],
        'AgendaAtendimentoDomicilio' => ['className' => 'AgendaAtendimentoDomicilio',
            'conditions' => ['AgendaAtendimentoDomicilio.ativo' => TRUE]],
        'UsuarioSenhaHist' => ['className' => 'UsuarioSenhaHist' ,
            'conditions' => ['UsuarioSenhaHist.ativo' => TRUE]]
    ];
    public $hasAndBelongsToMany = array(
        'Perfil' => array(
            'className' => 'Perfil',
            'joinTable' => 'usuario_perfil',
            'foreignKey' => 'usuario_id',
            'associationForeignKey' => 'perfil_id'
        ),
        'Tipologia' => array(
            'className' => 'Tipologia',
            'joinTable' => 'usuario_tipologia',
            'foreignKey' => 'usuario_id',
            'associationForeignKey' => 'tipologia_id'
        )
    );
    public $validate = array(
        'cpf' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'O Campo CPF é obrigatório.'
            ),
            'validarUnicidade' => array(
                'rule' => array('validarUnicidade'),
                'message' => 'Já existe um usuário cadastrado com este CPF',
            ),
            'validarCPF' => array(
                'rule' => array(
                    'validarCPF'
                ),
                'message' => 'CPF inválido.'
            )
        ),
        'nome' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'O Campo Nome do Usuário é obrigatório.'
            )
        ),
        'tipo_usuario_id' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'O Campo Tipo do usuário é obrigatório.'
            )
        ),
        'AgendaAtendimento' => array(
            'validarAgendaAtendimento' => array(
                'rule' => array('validarAgendaAtendimento'),
                'message' => 'É necessario preencher ao menos um agendamento para concluir o cadastro.'
            )
        ),
        'email' => array(
            'email' => array(
                'rule' => array('email'),
                'allowEmpty' => true,
                'message' => 'O E-mail informado está com um formato inválido.'
            )
        )
    );

    /**
     * Método para buscar o e-mail de um determinado Usuario
     */
    public function buscarUsuarioEmail($idUsuario) {
        $filtro = new BSFilter();
        $filtro->setCamposRetornados("email");
        $filtro->setTipo('all');
        $condicoes['Usuario.id'] = $idUsuario;
        $filtro->setCondicoes($condicoes);
        $usuarios = $this->listar($filtro);
        $usuario = null;
        if (!empty($usuarios)) {
            $usuario = $usuarios[0];
        }
        return $usuario;
    }

    public function __construct($id = false, $table = null, $ds = null) {
        parent::__construct($id, $table, $ds);
        $this->peritoCredenciado = USUARIO_PERITO_CREDENCIADO;
        $this->peritoServidor = USUARIO_PERITO_SERVIDOR;
        $this->interno = USUARIO_INTERNO;
        $this->servidor = USUARIO_SERVIDOR;
    }

    /**
     * Valida a numero de registro quanto o usuário for do tipo perito
     */
    private function validateNumeroRegistro() {
        if ( ( isset($this->data[$this->alias]['tipo_usuario_id']) && $this->data[$this->alias]['tipo_usuario_id'] == $this->peritoCredenciado ) ||
              ( isset($this->data[$this->alias]['tipo_usuario_id']) && $this->data[$this->alias]['tipo_usuario_id'] == $this->peritoServidor )  ) {
            if (isset($this->data[$this->alias]['numero_registro'])) {
                if ($this->data[$this->alias]['numero_registro'] == "") {
                    $this->invalidate('numero_registro', 'O Campo Número de Registro é obrigatório.');
                }
            }
        }
    }

    public function usuarioTipoServidor($cpf) {
        $filtro = new BSFilter();
        $condicoes['Usuario.cpf'] = Util::limpaDocumentos($cpf);
        $filtro->setCondicoes($condicoes);
        $filtro->setCamposRetornadosString("tipo_usuario_id");
        $filtro->setTipo('all');

        $usuarioTipoServidor = false;
        $usuarios = $this->listar($filtro);
        if (count($usuarios) > 0) {
            $usuario = $usuarios[0]['Usuario'];
            if ($usuario['tipo_usuario_id'] == USUARIO_SERVIDOR || $usuario['tipo_usuario_id'] == USUARIO_PERITO_SERVIDOR) {
                $usuarioTipoServidor = true;
            }
        }
        return $usuarioTipoServidor;
    }

    public function buscaEndereco($cpf){
        $filtro = new BSFilter();

        $joins[] = array(
            'table' => 'endereco',
            'alias' => 'Endereco',
            'type' => 'inner',
            'conditions' => array('Usuario.endereco_id = Endereco.id')
        );

        $condicoes['Usuario.cpf'] = Util::limpaDocumentos($cpf);
        $filtro->setCondicoes($condicoes);
        $filtro->setCamposRetornadosString("Endereco.id,Endereco.estado_id,Endereco.municipio_id,Endereco.logradouro,Endereco.cep,Endereco.numero,Endereco.complemento,Endereco.bairro");
         
        $camposRetornados = array(
            'Endereco.id',
            'Endereco.estado_id','
            Endereco.municipio_id',
            'Endereco.logradouro',
            'Endereco.cep',
            'Endereco.numero',
            'Endereco.complemento',
            'Endereco.bairro' 
        );    
        $filtro->setCamposRetornados($camposRetornados);
        $filtro->setTipo('all');
        $filtro->setJoins($joins);
        $usuario = $this->listar($filtro);

        $endereco = array();
        foreach ($usuario as $u) {
            $endereco['id']             = $u['Endereco']['id'];
            $endereco['estado_id']      = $u['Endereco']['estado_id'];
            $endereco['municipio_id']   = $u['Endereco']['municipio_id'];
            $endereco['logradouro']     = $u['Endereco']['logradouro'];
            $endereco['cep']            = $u['Endereco']['cep'];
            $endereco['numero']         = $u['Endereco']['numero'];
            $endereco['complemento']    = $u['Endereco']['complemento'];
            $endereco['bairro']         = $u['Endereco']['bairro'];
         
        }

        return $endereco;
    }

    public function buscarTipoUsuario($id) {
        $filtro = new BSFilter();
        $condicoes['Usuario.id'] = $id;
        $filtro->setCondicoes($condicoes);
        $filtro->setCamposRetornadosString("tipo_usuario_id");
        $filtro->setTipo('all');
        $usuario = $this->listar($filtro);
        return $usuario[0]['Usuario']['tipo_usuario_id'];
    }

    /**
     * Valida a empresa
     */
    private function _validateEmpresa() {
        if ( isset($this->data[$this->alias]['tipo_usuario_id']) && $this->data[$this->alias]['tipo_usuario_id'] == $this->peritoCredenciado) {
            if (isset($this->data[$this->alias]['empresa_id'])) {
                if ($this->data[$this->alias]['empresa_id'] == "") {
                    $this->invalidate('empresa_id', 'O Campo Empresa é obrigatório.');
                }
            }
        }
    }

    /**
     * Valida a data de Admissão
     */
    private function _validateDataAdmissao() {
        if ( ( isset($this->data[$this->alias]['tipo_usuario_id']) && $this->data[$this->alias]['tipo_usuario_id'] == $this->peritoCredenciado ) || 
             (isset($this->data[$this->alias]['tipo_usuario_id']) && $this->data[$this->alias]['tipo_usuario_id'] == $this->peritoServidor ) ) {
            if (isset($this->data[$this->alias]['data_admissao_pericia'])) {
                if ($this->data[$this->alias]['data_admissao_pericia'] == "") {
                    $this->invalidate('data_admissao_pericia', 'O campo Data de Admissão na Perícia é obrigatório.');
                }
            }
        }
    }

    /**
     * Validação se a senha digitada, é igual a senha atual do usuário.
     */
    private function _validatePassOldUser() {
        $user_id = $this->data[$this->alias]['id'];
        $user = $this->find('first', array(
            'conditions' => array(
                'Usuario.id' => $user_id
            ),
            'fields' => array('senha')
        ));
        $storedHash = $user['Usuario']['senha'];
        $flagHash = empty($storedHash)?'$2a$10$kJx8B4L.w/fAjneTGhGxz.Czgr64yKrHAw12u4aNHPx3uGHydRu9q':$storedHash;
        $passCurrent = Security::hash($this->data[$this->alias]['senha_atual'], 'blowfish', $flagHash);
        $pass = $this->data[$this->alias]['senha_atual'];
        if ($storedHash != $passCurrent && $storedHash != $pass) {
            $this->invalidate('senha_atual', 'Senha Atual não confere com a senha utilizada pelo usuário.');
        }
    }

    /**
     * Valida se as senhas digitadas são idênticas.
     */
    private function _validateIdenticNewPass() {
        if ($this->data[$this->alias]['senha'] != $this->data[$this->alias]['confirma_nova_senha']) {
            $this->invalidate('senha', 'As senhas não conferem.');
        } else {
            #Após verificar se as senhas estão iguais, verifica se as senhas são validas.
            // $this->_validateCaracters();
        }
    }

    /**
     * Valida se a tipologia está vazia, após verificar se o tipo de usuário
     */
    private function _validateSelectTipologia() {
        if ( ( isset($this->data[$this->alias]['tipo_usuario_id']) && $this->data[$this->alias]['tipo_usuario_id'] == $this->peritoCredenciado ) ||
             ( isset($this->data[$this->alias]['tipo_usuario_id']) &&  $this->data[$this->alias]['tipo_usuario_id'] == $this->peritoServidor ) ) {
            if (isset($this->data['Tipologia']['Tipologia'])) {
                if ($this->data['Tipologia']['Tipologia'] == "") {
                    $this->invalidate('Tipologia', 'É necessário selecionar ao menos uma Tipologia.');
                }
            }
        }
    }

    /**
     * Valida se selecionou ao menos um perfil.
     */
    private function _validatePerfil() {
        if (isset($this->data['Perfil']['Perfil'])) {
            if ($this->data['Perfil']['Perfil'] == "") {
                $this->invalidate('Perfil', 'É nécessario selecionar ao menos um Perfil.');
            }
        }
    }

    /**
     * Verifica se existem caracteres especiais na senha.
     */
    private function _validateCaracters() {
        if (!preg_match("/^[a-z A-Z0-9\\/\\\\.'\"]+$/", $this->data[$this->alias]['senha'])) {
            $this->invalidate('confirma_nova_senha', 'Não é permitido caracteres especiais na senha.');
        }
    }

    /**
     * Informa o tamanho minimo e maximo da senha
     * @return boolean
     */
    private function _validateLenghtPass() {
        $validate = true;
        if (strlen($this->data[$this->alias]['senha']) < 6) {
            $this->invalidate('senha', 'A Senha não pode possuir menos de 6 caracteres.');
            $validate = false;
        }

        if (strlen($this->data[$this->alias]['senha']) > 30) {
            $this->invalidate('senha', 'A Senha não pode possuir mais de 30 caracteres.');
            $validate = false;
        }
        return $validate;
    }

    private function _validateEmptyPass($currentPass, $pass, $repetePass) {
        if ($currentPass && (empty($pass) || empty($repetePass))) {
            $this->invalidate('senha', 'Preencha todos os campos referente a alteração da senha');
            return true;
        } else if ($pass && (empty($currentPass) || empty($repetePass))) {
            $this->invalidate('senha', 'Preencha todos os campos referente a alteração da senha');
            return true;
        } else if ($repetePass && (empty($currentPass) || empty($pass))) {
            $this->invalidate('senha', 'Preencha todos os campos referente a alteração da senha');
            return true;
        }
        return false;
    }

    /**
     * Validando a senha do usuário
     */
    private function _validatePass() {
        $tipoUsuario = $this->data[$this->alias]['tipo_usuario_id'];

        $this->ParametroGeral =  new ParametroGeral();

        $parametros = $this->ParametroGeral->getParametros();

        $this->UsuarioSenhaHist = new UsuarioSenhaHist();

        $historicoSenhas = $this->UsuarioSenhaHist->find('all', [
            'order' => ['UsuarioSenhaHist.id' => 'desc'],
            'condition' => ['UsuarioSenhaHist.usuario_id' =>$this->data[$this->alias]['id']],
            'limit' => intval($parametros['quantidade_historico_senha'])
        ]);


        if (true) {//$tipoUsuario == $this->peritoCredenciado || $tipoUsuario == $this->interno
            $senhaAtual = (isset($this->data[$this->alias]['senha_atual'])) ? $this->data[$this->alias]['senha_atual'] : "";
            $senha = (isset($this->data[$this->alias]['senha'])) ? $this->data[$this->alias]['senha'] : "";
            $repeteSenha = (isset($this->data[$this->alias]['confirma_nova_senha'])) ? $this->data[$this->alias]['confirma_nova_senha'] : "";

            $modifyingPass= false;
            if (!empty($senha) && isset($repeteSenha) && !empty($repeteSenha)) {

                $modifyingPass = true;
                if ($this->_validateLenghtPass()) {
                    #Valida se a senha digitada está parecida com a senha atual.
                    $this->_validatePassOldUser();

                    #Valida se a senha antiga é igual a senha nova
                    $this->_validatePassOldNewPass($senhaAtual, $senha);

                    $msg = 'A senha nova tem que ser diferente da atual.';

                    $flagHash = empty($senhaAtual)?'$2a$10$kJx8B4L.w/fAjneTGhGxz.Czgr64yKrHAw12u4aNHPx3uGHydRu9q':$senhaAtual;

                    $this->_validatePassOldNewPass($senhaAtual, Security::hash($senha, 'blowfish', $flagHash), $msg, 'senha');
                    $msgErroHist = '';
                    if($parametros['quantidade_historico_senha'] == 1){
                        $msgErroHist = 'A senha nova não pode ser igual a senha anterior';
                    }else if($parametros['quantidade_historico_senha'] > 1){
                        $msgErroHist = 'A senha nova não pode ser igual às últimas '. intval($parametros['quantidade_historico_senha']) . ' senhas anteriores.';
                    }
                    foreach($historicoSenhas as $historicoSenha){
                        $senhaHist = $historicoSenha['UsuarioSenhaHist']['senha'];
                        if(!$this->_validatePassOldNewPass(Security::hash($senha, 'blowfish', $senhaHist), $senhaHist, $msgErroHist, 'senha'))break;
                    }
                    #Validar Senhas novas são idênticas
                    $this->_validateIdenticNewPass();
                }
            } else {
                $modifyingPass = $this->_validateEmptyPass($senhaAtual, $senha, $repeteSenha);
            }
            if($modifyingPass){
                if(count($this->validationErrors) == 0){
                    $usuData = $this->data[$this->alias];
                    $this->UsuarioSenhaHist->save([
                        'usuario_id' => $usuData['id'],
                        'senha' => $usuData['senha_atual']
                    ]);
                }
            }
        }
    }

    private function _validatePassOldNewPass($currentPass, $pass, $msg =null, $field= 'senha_atual'){
        if ($currentPass === $pass) {
            if($msg == null){
                $msg = 'A senha anterior não pode ser igual a senha atual.';
            }
            $this->invalidate($field, $msg);
            return false;
        }
        return true;
    }

    /**
     * Validando a obrigatóriedade de email
     */
    private function _validateMail() {
        if ( ( isset($this->data[$this->alias]['tipo_usuario_id']) && $this->data[$this->alias]['tipo_usuario_id'] ) == $this->peritoCredenciado || 
              (isset($this->data[$this->alias]['tipo_usuario_id']) && $this->data[$this->alias]['tipo_usuario_id'] == $this->interno ) ) {
            if ($this->data[$this->alias]['email'] == "") {
                $this->invalidate('email', 'O campo E-mail é obrigatório');
            }
        }
    }

    public function  afterFind($results, $primary = false)
    {
	    /*
        $arrDates = array(
            'data_nascimento'=>false,
            'data_obito'=>false,
            'data_admissao_pericia'=>false,
            'expirar_senha'=>true,
            'data_alteracao'=>true,
            'data_exclusao'=>true,
            'data_inclusao'=>true
        );
        foreach ($results as &$line){ // O "&" eh pra $line ser uma referencia do conteudo de $results
            if(isset($line[$this->alias]) && !empty($line[$this->alias])){
	            foreach($arrDates as $key => $val){
		            if(isset($line[$this->alias][$key])&& !empty($line[$this->alias][$key])){
			            $line[$this->alias][$key] = Util::toBrDataHora($line[$this->alias][$key], $val);
		            }
	            }
            }
        }
	    */
        return parent::afterFind($results, $primary); // TODO: Change the autogenerated stub
    }

    /**
     * Validações rodando antes das validações dos metodos.
     * @param type $options
     */
    public function beforeValidate($options = array()) {
        #Valida o campo Data Admissão de pericia.
        $this->_validateDataAdmissao();

        #Valida o campo Empresa.
        $this->_validateEmpresa();

        if (isset($this->data[$this->alias]['id']) && $this->data[$this->alias]['id']) {
            #Validar Senha
            $this->_validatePass();
        }

        #Validar tipologia
        $this->_validateSelectTipologia();

        #Validar Perfil
        $this->_validatePerfil();

        if(!isset($this->ignoreMail) || $this->ignoreMail == true){
            #Validar se o e-mail é obrigatorio
            $this->_validateMail();
        }

        
        $this->validateNumeroRegistro();

        parent::beforeValidate($options);
    }

    public function afterSave($created, $options = array()) {
        if ($created) {
            $this->inserted_ids[] = $this->getInsertID();
        }
        parent::afterSave($created, $options);
    }

    /**
     * Verifica se existe mais pessoas cadastradas com esse CPF.
     * @return boolean
     */
    public function validarUnicidade() {
        $filtro = new BSFilter();
        $condicoes = array();
        $condicoes['Usuario.cpf'] = Util::limpaDocumentos($this->data[$this->alias]['cpf']);

        if (isset($this->data[$this->alias]['id'])) {
            $condicoes['Usuario.id != '] = $this->data[$this->alias]['id'];
        }

        $filtro->setTipo('count');
        $filtro->setCondicoes($condicoes);
        $count = $this->listar($filtro);
        return $count == 0;
    }

    /**
     * Método para verificar se o CPF é válido
     */
    public function validarCPF() {
        if(isset($this->data[$this->alias]['id'])){
            if($this->data[$this->alias]['id'] != '1'){ // Administrador
                return Util::validaCPF($this->data[$this->alias]['cpf']);
            }else{
                return true;
            }
        }else{
            return Util::validaCPF($this->data[$this->alias]['cpf']);
        }
    }

    public function beforeSave($options = array()) {
        $this->ParametroGeral =  new ParametroGeral();

        $parametros = $this->ParametroGeral->getParametros();

        //Limpando CPF
        if (isset($this->data[$this->alias]['cpf'])) {
            $this->data[$this->alias]['cpf'] = Util::limpaDocumentos($this->data[$this->alias]['cpf']);
        }
        if (isset($this->data[$this->alias]['telefone'])) {
            $this->data[$this->alias]['telefone'] = Util::removerMascaraTelefone($this->data[$this->alias]['telefone']);
        }

        if (isset($this->data[$this->alias]['telefone_trabalho'])) {
            $this->data[$this->alias]['telefone_trabalho'] = Util::removerMascaraTelefone($this->data[$this->alias]['telefone_trabalho']);
        }

        if (isset($this->data[$this->alias]['telefone_celular'])) {
            $this->data[$this->alias]['telefone_celular'] = Util::removerMascaraTelefone($this->data[$this->alias]['telefone_celular']);
        }

        if (!isset($this->data[$this->alias]['id'])) {
            if(!isset($this->data[$this->alias]['expirar_senha'])){
                $this->data[$this->alias]['expirar_senha'] = date('d/m/Y', strtotime("+{$parametros['dias_expiracao_senha']} days")) . ' 00:00:00';
            }
        }
        
        if (isset($this->data[$this->alias]['senha'])) {
            //Verifica se está em uma edição
            if (isset($this->data[$this->alias]['id'])) {
                $usuarioAlter = $this->findById($this->data[$this->alias]['id']);
                $this->data[$this->alias]['expirar_senha'] = date('d/m/Y', strtotime("+{$parametros['dias_expiracao_senha']} days")) . ' 00:00:00';
            }

            if (empty($this->data[$this->alias]['senha'])) {
                unset($this->data[$this->alias]['senha']);
            } else {
                Security::setHash('blowfish');
                $hashPass = Security::hash(trim($this->data[$this->alias]['senha']));
                $this->data[$this->alias]['senha'] = $hashPass;

                $this->data[$this->alias]['expirar_senha'] = date('d/m/Y', strtotime("+{$parametros['dias_expiracao_senha']} days")) . ' 00:00:00';
            }
        }
        
        if (isset($this->data[$this->alias]['id']) && $this->data[$this->alias]['id'] && isset($this->data[$this->alias]['tipo_usuario_id'])) {
            $this->resolverDependenciaDependente();
            $this->resolverDependenciaVinculo();
            $this->resolverDependenciaAgendaAtendimento();
            $this->resolverDependenciaAgendaAtendimentoDomicilio();
        }

        return parent::beforeSave($options);
    }

    /**
     * Função que monta array com os ids
     * @param array $data
     * @param string $alias
     * @return array
     */
    private function montarArrayParaListarIds($data, $alias) {
        $arrIds = [];
        foreach ($data as $key => $row) {
            if (isset($row[$alias]['id'])) {
                $arrIds[] = $row[$alias]['id'];
            } else if (isset($row['id'])) {
                $arrIds[] = $row['id'];
            }
        }
        return $arrIds;
    }

    /**
     * Método para fazer a exclusão das agendas de atendimento do usuário
     */
    private function resolverDependenciaAgendaAtendimento() {
        if(isset($this->data['AgendaAtendimento'])){
            $agendaAtendimento = $this->montarArrayParaListarIds($this->data['AgendaAtendimento'], 'AgendaAtendimento');
            $filtroAgendaAtendimento = new BSFilter();
            $condicoes['AgendaAtendimento.usuario_id'] = $this->data[$this->alias]['id'];
            $filtroAgendaAtendimento->setCondicoes($condicoes);
            $filtroAgendaAtendimento->setTipo('list');
            $agendaBanco = $this->AgendaAtendimento->listar($filtroAgendaAtendimento);
            foreach ($agendaBanco as $id => $agenda) {
                if (!in_array($id, $agendaAtendimento)) {
                    $this->AgendaAtendimento->delete($id);
                }
            }
        }
    }

    /**
     * Método para fazer a exclusão das agendas de atendimento em domicilio do usuário
     */
    private function resolverDependenciaAgendaAtendimentoDomicilio() {
        if(isset($this->data['AgendaAtendimentoDomicilio'])){
            $agendaAtendimento = $this->montarArrayParaListarIds($this->data['AgendaAtendimentoDomicilio'], 'AgendaAtendimentoDomicilio');
            $filtroAgendaAtendimento = new BSFilter();
            $condicoes['AgendaAtendimentoDomicilio.usuario_id'] = $this->data[$this->alias]['id'];
            $filtroAgendaAtendimento->setCondicoes($condicoes);
            $filtroAgendaAtendimento->setTipo('list');
            $agendaBanco = $this->AgendaAtendimentoDomicilio->listar($filtroAgendaAtendimento);
            foreach ($agendaBanco as $id => $agenda) {
                if (!in_array($id, $agendaAtendimento)) {
                    $this->AgendaAtendimentoDomicilio->delete($id);
                }
            } 
        }
    }

    /**
     * Método para fazer a exclusão dos vinculos
     */
    private function resolverDependenciaVinculo() {
        if(isset($this->data['Vinculo'])){
            $tipoUsuario = $this->data[$this->alias]['tipo_usuario_id'];
            $arrayVinculosIds = array();
            if($tipoUsuario == USUARIO_PERITO_CREDENCIADO){
                unset($this->data['Vinculo']);
            }else{
                $arrayVinculosIds = $this->montarArrayParaListarIds($this->data['Vinculo'], 'Vinculo');
            }
            
            $filtroVinculo = new BSFilter();
            $condicoes['Vinculo.usuario_id'] = $this->data[$this->alias]['id'];
            $filtroVinculo->setCondicoes($condicoes);
            $filtroVinculo->setTipo('list');
            $vinculoBanco = $this->Vinculo->listar($filtroVinculo);
            foreach ($vinculoBanco as $id => $vinculo) {
                if (!in_array($id, $arrayVinculosIds)) {
                    $this->Vinculo->delete($id);
                }
            }
        }
    }

    /**
     * Método para fazer a exclusão dos dependentes
     */
    private function resolverDependenciaDependente() {
        if(isset($this->data['Dependente'])){
            $arrayDependenteIds = $this->montarArrayParaListarIds($this->data['Dependente'], 'Dependente');
            $filtroDependente = new BSFilter();
            $condicoes['Dependente.usuario_id'] = $this->data[$this->alias]['id'];
            $filtroDependente->setCondicoes($condicoes);
            $filtroDependente->setTipo('list');
            $dependenteBanco = $this->Dependente->listar($filtroDependente);
            foreach ($dependenteBanco as $id => $dependente) {
                if (!in_array($id, $arrayDependenteIds)) {
                    $this->Dependente->delete($id);
                }
            }
        }
    }

    /**
     * Método para carregar a lista de permissões associadas aos perfis do Usuário Logado
     */
    public function carregarPermissoes($idUsuario, $idPerfil) {

        $filtro = new BSFilter();
        $filtro->setCamposRetornadosString('Funcionalidade.nome');
        $condicoes['up.usuario_id'] = $idUsuario;
        $condicoes['perfil.ativado'] = true;
        $condicoes['perfil.id'] = $idPerfil;
        $filtro->setCondicoes($condicoes);
        $joins[] = array(
            'table' => 'perfil_funcionalidade',
            'alias' => 'pf',
            'type' => 'left',
            'conditions' => array('pf.funcionalidade_id = Funcionalidade.id')
        );
        $joins[] = array(
            'table' => 'perfil',
            'alias' => 'perfil',
            'type' => 'left',
            'conditions' => array('pf.perfil_id = perfil.id')
        );
        $joins[] = array(
            'table' => 'usuario_perfil',
            'alias' => 'up',
            'type' => 'left',
            'conditions' => array('up.perfil_id = pf.perfil_id')
        );
        $filtro->setJoins($joins);
        $permissoes = $this->Perfil->Funcionalidade->listar($filtro);
        
        return $permissoes;
    }

    public function carregarPermissoesPerfil($idUsuario, $idPerfil = ''){

        $filtro = new BSFilter();
        $filtro->setCamposRetornadosString('Perfil.nome');
        $condicoes['up.usuario_id'] = $idUsuario;
        $condicoes['Perfil.ativado'] = true;
        if(!empty($idPerfil))$condicoes['Perfil.id'] = $idPerfil;

        $filtro->setCondicoes($condicoes);


        $joins[] = array(
            'table' => 'usuario_perfil',
            'alias' => 'up',
            'type' => 'left',
            'conditions' => array('up.perfil_id = Perfil.id')
        );
        $filtro->setJoins($joins);

        return $this->Perfil->listar($filtro);
    }




    /**
     * 
     * @param type $id
     * @return type
     */
    public function findById($id) {
        $this->Behaviors->load('Containable');

        $usuario = $this->find('first', array(
            'conditions' => array(
                $this->alias . '.ativo' => true,
                $this->alias . '.id' => $id,
            ),
            'contain' => array('Perfil', 'EnderecoUsuario',
                'UnidadeAtendimento', 'Sexo', 'EstadoCivil', 'Empresa', 'TipoUsuario', 'Tipologia')
        ));

        $filtroVinculo = new BSFilter();
        $condicoesVinculo['Vinculo.usuario_id'] = $id;
        $filtroVinculo->setCondicoes($condicoesVinculo);
        $filtroVinculo->setTipo('all');
        $filtroVinculo->setContain(array('OrgaoOrigem', 'Lotacao', 'Funcao', 'Cargo'));
        $this->Vinculo->Behaviors->load('Containable');
        $usuario['Vinculo'] = $this->Vinculo->listar($filtroVinculo);

        $filtroAgendaAtendimento = new BSFilter();
        $condicoesAgendaAtendimento['AgendaAtendimento.usuario_id'] = $id;
        $filtroAgendaAtendimento->setCondicoes($condicoesAgendaAtendimento);
        $filtroAgendaAtendimento->setTipo('all');
        $filtroAgendaAtendimento->setContain(array('UnidadeAtendimento', 'Tipologia'));
        $this->AgendaAtendimento->Behaviors->load('Containable');
        $usuario['AgendaAtendimento'] = $this->AgendaAtendimento->listar($filtroAgendaAtendimento);

        $filtroAgendaAtendimentoDomicilio = new BSFilter();
        $condicoesAgendaAtendimentoDomicilio['AgendaAtendimentoDomicilio.usuario_id'] = $id;
        $filtroAgendaAtendimentoDomicilio->setCondicoes($condicoesAgendaAtendimentoDomicilio);
        $filtroAgendaAtendimentoDomicilio->setTipo('all');
        $filtroAgendaAtendimentoDomicilio->setContain(array('UnidadeAtendimento', 'Tipologia'));
        $this->AgendaAtendimentoDomicilio->Behaviors->load('Containable');
        $usuario['AgendaAtendimentoDomicilio'] = $this->AgendaAtendimentoDomicilio->listar($filtroAgendaAtendimentoDomicilio);



        /*
            Fazendo uma consulta na tabela agen_aten_domic_tip(ligação N - N de AgendamentoAtendimentoDomicilio - Tipologia)
            e colocando o resultado da consulta em $usuario['AgendaAtendimentoDomicilio']['Tipologia'], pois a recuperação dos dados
            da tipologia não estava sendo feito
        */
        foreach ($usuario['AgendaAtendimentoDomicilio'] as $keyAgendaAtendimentoDomicilio => $agendaAtendimentoDomicilio) {
            $idAgendaAtendimento = $agendaAtendimentoDomicilio['AgendaAtendimentoDomicilio']['id'];
            

             $query = array(
                'joins' => array(
                    array(
                        "table" => "agen_aten_domic_tip",
                        "alias" => "AgendaTipologia",
                        "type" => "LEFT",
                        "conditions" => array(
                            "Tipologia.id = AgendaTipologia.tipologia_id"
                        )
                    ),
                ),
                'recursive' => -1,
                'conditions' => array(
                    'AgendaTipologia.agend_atendi_domic_id' => $idAgendaAtendimento
                ),
            );

             $data = $this->Tipologia->find('all', $query);

             foreach ($data as $keyTipologia => $tipologia) {
                $usuario['AgendaAtendimentoDomicilio'][$keyAgendaAtendimentoDomicilio]['Tipologia'][$keyTipologia] = $tipologia['Tipologia'];
             }

        }

        // -------------------------------------------------------------- END -------------------------------------------- //
        

        $filtroDependete = new BSFilter();
        $condicoesDependete['Dependente.usuario_id'] = $id;
        $filtroDependete->setCondicoes($condicoesDependete);
        $filtroDependete->setTipo('all');
        $filtroDependete->setContain(array('Qualidade', 'EnderecoDependente'));
        $this->Dependente->Behaviors->load('Containable');
        $usuario['Dependente'] = $this->Dependente->listar($filtroDependete);

        return $usuario;
    }

    public function resolverDependenciasExclusao($id = null) {
        $filtro = new BSFilter();
        $filtro->setLimitarItensAtivos(false);
        $filtro->setTipo('all');
        $condicoes['Usuario.id'] = $id;
        $filtro->setCondicoes($condicoes);

        $usuario = $this->listar($filtro)[0];
        $endereco = new Endereco();
        $endereco->delete($usuario['Usuario']['endereco_id']);
        $this->resolverDependenciasExclusaoVinculo($id);
        $this->resolverDependenciasExclusaoDependentes($id);
        $this->resolverDependenciasExclusaoAgendaAtendimento($id);
        $this->resolverDependenciasExclusaoAgendaAtendimentoDomicilio($id);
    }

    /**
     * Método para excluir as Agendas de atendimento de um determinado usuário
     * @param type $id
     */
    public function resolverDependenciasExclusaoAgendaAtendimento($id) {
        $filtro = new BSFilter();
        $filtro->setTipo('list');
        $condicoes['AgendaAtendimento.usuario_id'] = $id;
        $filtro->setCondicoes($condicoes);
        $agendaAtendimento = new AgendaAtendimento();
        $agendas = $agendaAtendimento->listar($filtro);
        foreach ($agendas as $idAgenda => $a) {
            $agendaAtendimento->delete($idAgenda);
        }
    }

    /**
     * Método para excluir as Agendas de atendimento de um determinado usuário
     * @param type $id
     */
    public function resolverDependenciasExclusaoAgendaAtendimentoDomicilio($id) {
        $filtro = new BSFilter();
        $filtro->setTipo('list');
        $condicoes['AgendaAtendimentoDomicilio.usuario_id'] = $id;
        $filtro->setCondicoes($condicoes);
        $agendaAtendimento = new AgendaAtendimentoDomicilio();
        $agendas = $agendaAtendimento->listar($filtro);
        foreach ($agendas as $idAgenda => $a) {
            $agendaAtendimento->delete($idAgenda);
        }
    }

    /**
     * Método para excluir os dependentes associados a um determinado usuário
     * @param type $id
     */
    public function resolverDependenciasExclusaoDependentes($id) {
        $filtro = new BSFilter();
        $filtro->setTipo('list');
        $condicoes['Dependente.usuario_id'] = $id;
        $filtro->setCondicoes($condicoes);
        $dependente = new Dependente();
        $dependentes = $dependente->listar($filtro);
        foreach ($dependentes as $idDependente => $d) {
            $dependente->delete($idDependente);
        }
    }

    /**
     * Método para excluir os vinculos associados a um determinado usuário
     * @param type $id
     */
    public function resolverDependenciasExclusaoVinculo($id) {
        $filtroVinculo = new BSFilter();
        $filtroVinculo->setTipo('list');
        $condicoesVinculo['Vinculo.usuario_id'] = $id;
        $filtroVinculo->setCondicoes($condicoesVinculo);
        $filtroVinculo->setLimiteListagem(40);
        $vinculo = new Vinculo();
        $vinculos = $vinculo->listar($filtroVinculo);
        foreach ($vinculos as $idVinculo => $v) {
            $vinculo->delete($idVinculo);
        }
    }

    public function obterUsuario($condicoes, $type = 'all', $joins=null) {
        $filtro = new BSFilter();
        $filtro->setCondicoes($condicoes);
        $filtro->setCamposRetornadosString("nome", "cpf", "id", "data_obito");
        $this->Behaviors->load('Containable');
        $filtro->setContain(array('TipoUsuario'));
        $filtro->setTipo($type);
        $filtro->setJoins($joins);
        return $this->listar($filtro);
    }

    public function getEmailById($id){
        $condicoes =array('Usuario.id' => $id);
        $filtro = new BSFilter();
        $filtro->setCondicoes($condicoes);
        $filtro->setTipo('list');
        $filtro->setCamposRetornadosString('email');
        $result = $this->listar($filtro);
        return $result[$id];
    }

    public function getNomeById($id){
        $condicoes =array('Usuario.id' => $id);
        $filtro = new BSFilter();
        $filtro->setCondicoes($condicoes);
        $filtro->setTipo('list');
        $filtro->setCamposRetornadosString('nome');
        $result = $this->listar($filtro);
        return $result[$id];
    }

    public function getSexoById($id){
        $condicoes =array('Usuario.id' => $id);
        $filtro = new BSFilter();
        $filtro->setCondicoes($condicoes);
        $filtro->setTipo('list');
        $filtro->setCamposRetornadosString('sexo_id');
        $result = $this->listar($filtro);
        return empty($id)?null:$result[$id];
    }


    public function getUsuario($cpf){
        $db = $this->getDataSource();
        $result = $db->fetchAll(
            'SELECT * from usuario where cpf = ?',
            array($cpf)
        );
        return (!empty($result))?$result[0][0]:array();
    }

    public function listJuntaPeritos($idAtendimento){
        $db = $this->getDataSource();
        $result = $db->fetchAll(
            'SELECT 
            u.numero_registro as "Perito__numero_registro", 
            u.nome as "Perito__nome" from atendimento_perito ap 
            inner join usuario u on u.id = ap.usuario_id
            where ap.atendimento_id = ?',
            array($idAtendimento)
        );
        return $result;
    }

    public function removeLastPerito($idAtendimento){

        $sql = "DELETE FROM atendimento_perito WHERE atendimento_id =" .$idAtendimento;
        $result = $this->query($sql);
    }

}
