<?php

// App::uses('BSController', 'Admin.Controller');
App::uses('BSController', 'Web.Controller');
App::uses('CakeEvent', 'Event');

class UsuarioController extends BSController {
//    var $components= array('Session');
// 	 fazendo um teste

    public $sessionUsuario = 'usuario';
    public $sessionFuncaoVinculo = 'funcao';
    public $sessionLotacaoVinculo = 'lotacao';
    public $sessionVinculo = 'vinculo';
    public $sessionAgendaAtendimento = 'agendaAtendimento';
    public $sessionAgendaAtendimentoDomicilio = 'agendaAtendimentoDomicilio';
    public $sessionDependentes = 'dependente';

    /*
     * Tipos de usuários;
     */
    public $peritoCredenciado;
    public $peritoServidor;
    public $interno;
    public $servidor;

    public function __construct($request = null, $response = null) {
        parent::__construct($request, $response);
        $this->helpers[] = 'PForm';

        $this->peritoCredenciado = USUARIO_PERITO_CREDENCIADO;
        $this->peritoServidor = USUARIO_PERITO_SERVIDOR;
        $this->interno = USUARIO_INTERNO;
        $this->servidor = USUARIO_SERVIDOR;
    }

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('recuperarSenha');

        if(!empty($this->Session->read('usuario_session'))){
            $this->Auth->allow('alterarSenhaLogin');
        }
        $this->Auth->allow('loginSegundo');
    }

    public function info(){
        phpinfo();
    }

    public function login() {
        $this->layout = 'public';
        $this->Session->delete('usuario_session');

        $isColaborador = false;
        if(isset($_GET['c'])){
            $isColaborador = $_GET['c'] && true;
        }else if(isset($this->request->data['Usuario']) && isset($this->request->data['Usuario']['colaborador'])){
            $isColaborador = $this->request->data['Usuario']['colaborador'] && true;
        }

        if($isColaborador){
            $this->set('isColaborador', true);
        }else{
            $this->set('isColaborador', false);
        }

        //pr($isColaborador);die;

        if ($this->Auth->user()){
            $id = $this->Auth->user('id');
            $currentFunction = $this->request->params['action']; //function corrente
            $currentController = $this->name; //Controller corrente
            $this->saveAuditLog($id,$currentController,'I',$currentFunction);
            $this->redirect(array(
                // 'plugin' => 'admin',
                'plugin' => 'web',
                'controller' => 'dashboard'
            ));
        }

        App::import('Vendor', 'recaptcha', array('file'=>'recaptcha' . DS . 'autoload.php') );

        if ($this->request->is('post')) {
            if (WITH_CAPTCHA) { // && $this->Session->read('validaCaptchaCC')
                $privateKey = "6LeXziYTAAAAAAdbqc0hGOj4Bmpinq3tvUoZy0o9";
                $recaptcha = new \ReCaptcha\ReCaptcha($privateKey);

                if (isset($this->request->data["g-recaptcha-response"])) {
                    $resp = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
                    if (!$resp->isSuccess()) {
                        $this->Session->setFlash(__('spm_erro_captcha'), 'flash_alert');
                    } else {
                        $this->validarLogin();
                    }
                }
            } else {
                $this->validarLogin();
            }
        }
    }

    public function returnNumeroTentativas(){
        $this->autoRender = false;

        $this->loadModel('ParametroGeral');
        $parametro = $this->ParametroGeral->findById('1');

        return $parametro['ParametroGeral']['numero_tentativas_login'];
    }


    public function alterarSenhaLogin(){
        $this->layout = 'public';
        $usuario = $this->Session->read('usuario_session');
        
        if($this->request->is('post')){

            $usuario = $this->Session->read('usuario_session');
            $usuario = $this->Usuario->findById($usuario['id']);

            $usuario['Usuario']['senha_atual']                 = $usuario['Usuario']['senha'];
            $usuario['Usuario']['senha']                       = $this->request->data['Usuario']['senha'];
            $usuario['Usuario']['confirma_nova_senha']         = $this->request->data['Usuario']['confirma_nova_senha'];
            $usuario['Usuario']['habilitar_alteracao_senha']   = "0";
            $this->loadModel("ParametroGeral");
            $parametros = $this->ParametroGeral->getParametros();
            $usuario['Usuario']['expirar_senha'] = date('d/m/Y', strtotime("+{$parametros['dias_expiracao_senha']} days")) . ' 00:00:00';

            $isOk = true;
            //pr($usuario['Usuario']['data_nascimento']);die;
            if($usuario['Usuario']['data_nascimento'] != $this->request->data['Usuario']['data_nascimento']){
                $isOk = false;
                $this->Usuario->invalidate('data_nascimento', 'Data de nascimento informada não coincide com a do usuário');
            }

            if($usuario['Usuario']['rg'] != $this->request->data['Usuario']['rg']){
                $isOk = false;
                $this->Usuario->invalidate('rg', 'RG informado não coincide com a do usuário');
            }

            $this->Usuario->ignoreMail = true;
            if ($isOk && $this->Usuario->saveAll($usuario['Usuario'])){
                $id = $this->Session->read('usuario_session')['id'];
                $currentFunction = $this->request->params['action']; //function corrente
                $currentController = $this->name; //Controller corrente
                $this->saveAuditLog($id,$currentController,'A',$currentFunction);

                $this->Session->setFlash(__('mensagem_senha_alterada'), 'flash_success');
                return $this->redirect(array('controller' => 'usuario', 'action' => 'logout'));
            } else {
                $mensagem = "Erro ao alterar a senha: ";
                foreach ($this->Usuario->validationErrors as $key => $value){
                    foreach ($value as $v){
                        $mensagem .= "\n";
                        $mensagem .= $v;
                        $mensagem .= "\n";
                    }
                }
                $this->Session->setFlash($mensagem, 'flash_alert');
            }
         }
    }

    /**
     * Adicionar vinculo
     */
    public function adicionarVinculo() {
        $this->validateVinculo();

        $funcoes = $this->Session->read($this->sessionFuncaoVinculo);
        $lotacoes = $this->Session->read($this->sessionLotacaoVinculo);
        $vinculo = $this->Session->read($this->sessionVinculo);

        if (is_null($vinculo)) {
            $vinculo = array();
        }

        //carrega string funções
        $funcaoString = "";
        if (!is_null($funcoes) || !empty($funcoes)) {
            foreach ($funcoes as $funcao) {
                $funcaoString = (empty($funcaoString)) ? $funcao['nome'] : $funcaoString . ', ' . $funcao['nome'];
            }
        }

        //carrega string locatacao
        $lotacaoString = "";
        if (!is_null($lotacoes) || !empty($lotacoes)) {
            foreach ($lotacoes as $lotacao) {
                $lotacaoString = (empty($lotacaoString)) ? $lotacao['nome'] : $lotacaoString . ', ' . $lotacao['nome'];
            }
        }

        $idOrgaoOrigem = $this->request->data['orgao_origem_id'];
        $nomeOrgaoOrigem = $this->request->data['nomeOrgaoOrigem'];
        $matricula = $this->request->data['matricula'];
        $idCargo = $this->request->data['cargo_id'];
        $nomeCargo = $this->request->data['nomeCargo'];
        $dataAdmissao = $this->request->data['data_admissao_servidor'];

        $key = (count($vinculo) > 0 ) ? count($vinculo) + 1 : 0;
        $novoVinculo = array();
        if (!key_exists($key, $novoVinculo)) {
            $vinculo[$key]['Vinculo'] = ['matricula' => $matricula,
                'data_admissao_servidor' => $dataAdmissao,
                'cargo_id' => $idCargo,
                'orgao_origem_id' => $idOrgaoOrigem];

            $vinculo[$key]['OrgaoOrigem'] = ['orgao_origem' => $nomeOrgaoOrigem];
            $vinculo[$key]['Cargo'] = ['nome' => $nomeCargo];
            $vinculo[$key]['Funcao'] = ['string' => $funcaoString];
            $vinculo[$key]['Funcao']['funcaoArray'] = (!is_null($funcoes)) ? $funcoes : [];
            $vinculo[$key]['Lotacao'] = ['string' => $lotacaoString];
            $vinculo[$key]['Lotacao']['lotacaoArray'] = (!is_null($lotacoes)) ? $lotacoes : [];

            $novoVinculo[0] = $vinculo[$key];
        }
        $this->Session->write($this->sessionVinculo, $vinculo);

//limpando a session de função e lotação
        $this->Session->delete($this->sessionFuncaoVinculo);
        $this->Session->delete($this->sessionLotacaoVinculo);

        echo json_encode($novoVinculo);
        die;
    }

    /**
     * Valida os vinculos.
     */
    public function validateVinculo() {
        $this->loadModel('Vinculo');
        if ($this->request->is('post')) {
            $this->Vinculo->set($this->request->data);
            $validacoes = array();
            $listVinculo = $this->Session->read($this->sessionVinculo);
            if (!$this->Vinculo->validates()) {
                $validacoesEntidadeOrgao = $this->Vinculo->validationErrors;
                array_push($validacoes, $validacoesEntidadeOrgao);
            }
            if (!is_null($listVinculo) && !empty($listVinculo)) {
                if (!$this->Vinculo->verificaUnicidadeOrgao($listVinculo, $this->request->data)) {
                    array_push($validacoes, ['orgao_origem_id' => __('validacao_orgao_origem_unico')]);
                }
            }
            if (!empty($validacoes)) {
                $this->tratarValidacoes($validacoes);
            }
        }
    }

    /*
        Método retorna as unidades de atendimento que atendam em domicílio em um determinado município
    */
    public function retornaUnidAtendimentoMunicipio($municipio = null){
        error_reporting(0);
        $this->loadModel('UnidadeAtendimento');
        $data = null;
        
        if($this->request->is('post')){
            $municipio = $this->request->data['municipio'];
            if(isset($municipio)){
                
                /* Fazendo uma consulta pelo endereco das unidades */
                $filtro = new BSFilter();
                $condicoes['UnidadeAtendimento.atendimento_domicilio'] = true;
                $condicoes['Endereco.municipio_id'] = $municipio;
                $joins[] = array(
                    'table' => 'endereco',
                    'alias' => 'Endereco',
                    'type' => 'INNER',
                    'conditions' => array("UnidadeAtendimento.endereco_id = Endereco.id")
                );
                $filtro->setCondicoes($condicoes);
                $filtro->setJoins($joins);
                $data =  $this->UnidadeAtendimento->listar($filtro);


                /* Fazendo ums consulta pelos municipios proximos que a unidade atende */
                // $filtro2 = new BSFilter();
                // $condicoes2['UnidadeAtendimento.atendimento_domicilio'] = true;
                // $condicoes2['UnidadeAtendimentoMunicipio.municipio_id'] = $municipio;
                // $joins2[] = array(
                //     'table' => 'unidade_atendimento_municipio',
                //     'alias' => 'UnidadeAtendimentoMunicipio',
                //     'type' => 'INNER',
                //     'conditions' => array("UnidadeAtendimento.id = UnidadeAtendimentoMunicipio.unidade_atendimento_id")
                // );
                // $filtro2->setCondicoes($condicoes2);
                // $filtro2->setJoins($joins2);
                // $data2 = $this->UnidadeAtendimento->listar($filtro2);

                // if(isset($data) && !empty($data)){
                //     array_merge($data, $data2);
                // }else{
                //     $data = $data2;
                // }
            }

        } 
    
        // pr($data);die;
        echo json_encode($data);
        die;
    }
    
    /**
     * Adicionar Agenda de atendimento
     */
    public function adicionarAgendaAtendimento() {


        $this->validateAgendaAtendimento();

        $agendaAtendimento = $this->Session->read($this->sessionAgendaAtendimento);
        // pr($agendaAtendimento);die;

        if (is_null($agendaAtendimento)) {
            $agendaAtendimento = array();
        }

        $diaSemana = $this->request->data['dia_semana'];
        $horaInicial = $this->request->data['hora_inicial'];
        $horaFinal = $this->request->data['hora_final'];
        $unidadeAtendimentoId = $this->request->data['unidade_atendimento_id'];
        $nomeUnidadeAtendimento = $this->request->data['nome_unidade_atendimento'];
        $tipologia = $this->request->data['Tipologia'];
        $nomeTipologia = $this->request->data['nome_tipologia'];
        $permitirAgendamento = $this->request->data['permitir_agendamento'];
        $agendaAtendimentoTemp['AgendaAtendimento'] = [
            'dia_semana' => $diaSemana,
            'hora_inicial' => $horaInicial,
            'hora_final' => $horaFinal,
            'unidade_atendimento_id' => $unidadeAtendimentoId,
            'nome_unidade_atendimento' => $nomeUnidadeAtendimento,
            'nome_tipologia' => $nomeTipologia,
            'permitir_agendamento' => $permitirAgendamento
        ];
        $agendaAtendimentoTemp['Tipologia'] = $tipologia;
        $agendaAtendimento[] = $agendaAtendimentoTemp;
        $this->Session->write($this->sessionAgendaAtendimento, $agendaAtendimento);
        echo json_encode($agendaAtendimento);
        die;
    }

    /**
     * Adicionar Agenda de atendimento domicilio
     */
    public function adicionarAgendaAtendDomicilio() {

        $this->validateAgendaAtendimentoDomicilio();

        $agendaAtendimentoDomilio = $this->Session->read($this->sessionAgendaAtendimentoDomicilio);

        // $agendaAtendimentodimentoDomilio = null;
        if (is_null($agendaAtendimentoDomilio)) {
            $agendaAtendimentoDomilio = array();
        }

        $diaSemana              = $this->request->data['dia_semana'];
        $horaInicial            = $this->request->data['hora_inicial'];
        $horaFinal              = $this->request->data['hora_final'];
        $unidadeAtendimentoId   = $this->request->data['unidade_atendimento_id'];
        $nomeUnidadeAtendimento = $this->request->data['nome_unidade_atendimento'];
        $tipologia              = $this->request->data['Tipologia'];
        $nomeTipologia          = $this->request->data['nome_tipologia'];
        $nomeMunicipio          = $this->request->data['nome_municipio'];
        $municipioId            = $this->request->data['municipio_id'];
        
        $agendaAtendimentoTemp['AgendaAtendimentoDomicilio'] = [
            'dia_semana'                => $diaSemana,
            'hora_inicial'              => $horaInicial,
            'hora_final'                => $horaFinal,
            'unidade_atendimento_id'    => $unidadeAtendimentoId,
            'nome_unidade_atendimento'  => $nomeUnidadeAtendimento,
            'nome_tipologia'            => $nomeTipologia,
            'nome_municipio'            => $nomeMunicipio,
            'municipio_id'              => $municipioId
        ];
        $agendaAtendimentoTemp['Tipologia'] = $tipologia;
        $agendaAtendimentoDomilio[] = $agendaAtendimentoTemp;
        // pr($agendaAtendimentoDomilio);die;
        $this->Session->write($this->sessionAgendaAtendimentoDomicilio, $agendaAtendimentoDomilio);
        

        echo json_encode($agendaAtendimentoDomilio);
        die;
    }

    /**
     * Adicionar Agenda de atendimento
     */
    public function atualizarAgendaAtendimento() {
        $this->validateAgendaAtendimento();
        $agendaAtendimento = (is_null($this->Session->read($this->sessionAgendaAtendimento))) ? [] :
                $this->Session->read($this->sessionAgendaAtendimento);

        // pr($this->request->data);die;    

        $id = $this->request->data['id'];
        $diaSemana = $this->request->data['dia_semana'];
        $horaInicial = $this->request->data['hora_inicial'];
        $horaFinal = $this->request->data['hora_final'];
        $unidadeAtendimentoId = $this->request->data['unidade_atendimento_id'];
        $nomeUnidadeAtendimento = $this->request->data['nome_unidade_atendimento'];
        $tipologia = $this->request->data['Tipologia'];
        $nomeTipologia = $this->request->data['nome_tipologia'];
        $permitirAgendamento = $this->request->data['permitir_agendamento'];
        $agendaAtendimentoTemp['AgendaAtendimento'] = ['dia_semana' => $diaSemana,
            'hora_inicial' => $horaInicial,
            'hora_final' => $horaFinal,
            'unidade_atendimento_id' => $unidadeAtendimentoId,
            'nome_unidade_atendimento' => $nomeUnidadeAtendimento,
            'permitir_agendamento' => $permitirAgendamento,
            'nome_tipologia' => $nomeTipologia];
        $agendaAtendimentoTemp['Tipologia'] = $tipologia;

        $agendaAtendimento[$id] = $agendaAtendimentoTemp;

        $this->Session->write($this->sessionAgendaAtendimento, $agendaAtendimento);
        echo json_encode($agendaAtendimento);
        die;
    }


    /**
     * Atualizar Agenda de atendimento em domicilio
     */
    public function atualizarAgendaAtendimentoDomicilio() {
        $this->validateAgendaAtendimentoDomicilio();

        $agendaAtendimentoDomicilio = (is_null($this->Session->read($this->sessionAgendaAtendimentoDomicilio))) ? [] :
        $this->Session->read($this->sessionAgendaAtendimentoDomicilio);

        $id                     = $this->request->data['id'];
        $diaSemana              = $this->request->data['dia_semana'];
        $horaInicial            = $this->request->data['hora_inicial'];
        $horaFinal              = $this->request->data['hora_final'];
        $unidadeAtendimentoId   = $this->request->data['unidade_atendimento_id'];
        $nomeUnidadeAtendimento = $this->request->data['nome_unidade_atendimento'];
        $municipioId            = $this->request->data['municipio_id'];
        $nomeMunicipio          = $this->request->data['nome_municipio'];
        $tipologia              = $this->request->data['Tipologia'];
        $nomeTipologia          = $this->request->data['nome_tipologia'];

        $agendaAtendimentoDomicilioTemp['AgendaAtendimentoDomicilio'] = [
            'dia_semana'                => $diaSemana,
            'hora_inicial'              => $horaInicial,
            'hora_final'                => $horaFinal,
            'unidade_atendimento_id'    => $unidadeAtendimentoId,
            'nome_unidade_atendimento'  => $nomeUnidadeAtendimento,
            'nome_tipologia'            => $nomeTipologia,
            'nome_municipio'            => $nomeMunicipio,
            'municipio_id'              => $municipioId
        ];
        $agendaAtendimentoDomicilioTemp['Tipologia'] = $tipologia;

        $agendaAtendimentoDomicilio[$id] = $agendaAtendimentoDomicilioTemp;

        $this->Session->write($this->sessionAgendaAtendimentoDomicilio, $agendaAtendimentoDomicilio);
        echo json_encode($agendaAtendimentoDomicilio);
        die;
    }

    public function deletarAgentaAtendimento(){
        $this->loadModel('AgendaSistemaItem');
        $this->loadModel('AgendaAtendimento');
        $retorno = false;
        $falha = false;
        $msg = '';
        $falhaDados = '';
        $id = $this->request->data['key'];
        $agendaAtencimento = $this->Session->read($this->sessionAgendaAtendimento);


        if (!is_null($agendaAtencimento)) {
            $item = $agendaAtencimento[$id];
        
            $agendaAtencimentoId = ( isset($item['AgendaAtendimento']['id']) ) ? $item['AgendaAtendimento']['id'] : "" ;
            //$idTipologia, $idUnidade, $diaSemana, $horaInicial
            $filtroSistema = $this->AgendaSistemaItem->consultarAgendaSistemaItem(
                $item['Tipologia'],
                $item['AgendaAtendimento']['unidade_atendimento_id'],
                $item['AgendaAtendimento']['dia_semana'],
                $item['AgendaAtendimento']['hora_inicial']);


            if(count($filtroSistema) > 0){
                foreach ($filtroSistema as $itemFiltro){
                    $filtro = $itemFiltro['Filter'];
                    $query = array(
                        'joins' => array(
                            array(
                                "table" => "agen_aten_tip",
                                "alias" => "AgendaTipologia",
                                "type" => "LEFT",
                                "conditions" => array(
                                    "AgendaAtendimento.id = AgendaTipologia.agen_aten_id"
                                )
                            ),
                        ),
                        'recursive' => -1,
                        'conditions' => array(
                            'AgendaTipologia.tipologia_id' =>  $filtro['tipologia'],
                            'AgendaAtendimento.unidade_atendimento_id' => $filtro['unidade'],
                            'AgendaAtendimento.dia_semana' => $filtro['dia'],
                            'AgendaAtendimento.hora_inicial' => $filtro['hora'],
                            'AgendaAtendimento.id != ' => $agendaAtencimentoId,
                            'AgendaAtendimento.ativo' => true
                        ),
                    );
                    $data = $this->AgendaAtendimento->find('first', $query);

                    if(count($data) == 0){
                        $falha = true;
                        $msg = "Atualmente, apenas este perito possui horário na agenda do sistema. Realoque o horário para outro perito e tente excluir novamente.";
                        $falhaDados = $filtro;
                    }
                }
            }
        }
        if(!$falha){
            unset($agendaAtencimento[$id]);
            $this->Session->write($this->sessionAgendaAtendimento, $agendaAtencimento);
            $retorno = true;
        }
        echo json_encode(['retorno'=>$retorno, 'falha'=>$falha, 'msg'=>$msg, 'data'=> $falhaDados]);
        die;
    }

    /*
        Exclui a agenda de atendimento em domicilio da sessao
    */
    public function deletarAgendaAtendimentoDomicilio() {
        $retorno = false;
        $id = $this->request->data['key'];
        $agendaAtendimentoDomicilio = $this->Session->read($this->sessionAgendaAtendimentoDomicilio);
        if (!is_null($agendaAtendimentoDomicilio)) {
            unset($agendaAtendimentoDomicilio[$id]);
            $this->Session->write($this->sessionAgendaAtendimentoDomicilio, $agendaAtendimentoDomicilio);
            $retorno = true;
        }
        echo json_encode($retorno);
        die;
    }

    /**
     * Valida os Agenda de atendimentos.
     */
    public function validateAgendaAtendimento() {
        $this->loadModel('AgendaAtendimento');
        if ($this->request->is('post')) {
            $this->AgendaAtendimento->set($this->request->data);
            $validacoes = array();

            $listAgendaAtendimento = $this->Session->read($this->sessionAgendaAtendimento);

            if (!$this->AgendaAtendimento->validates()) {
                $validacoesAgendaAtendimento = $this->AgendaAtendimento->validationErrors;
                array_push($validacoes, $validacoesAgendaAtendimento);
                $this->tratarValidacoes($validacoes);
            }

            if (!is_null($listAgendaAtendimento) && !empty($listAgendaAtendimento)) {
                if ($this->AgendaAtendimento->verificaIntercessaoHorario($listAgendaAtendimento, $this->request->data)) {
                    array_push($validacoes, ['hora_inicial' => __('validacao_horario_intercessao')]);
                    $this->tratarValidacoes($validacoes);
                }
            }
        }
    }

    /**
     * Valida os Agenda de atendimentos em domicilio.
     */
    public function validateAgendaAtendimentoDomicilio() {
        $this->loadModel('AgendaAtendimentoDomicilio');
        // pr($this->Agenda)
        if ($this->request->is('post')) {
            $this->AgendaAtendimentoDomicilio->set($this->request->data);
            $validacoes = array();

            $listAgendaAtendimentoDomicilio = $this->Session->read($this->sessionAgendaAtendimentoDomicilio);

            if (!$this->AgendaAtendimentoDomicilio->validates()) {
                $validacoesAgendaAtendimento = $this->AgendaAtendimentoDomicilio->validationErrors;
                array_push($validacoes, $validacoesAgendaAtendimento);
                $this->tratarValidacoes($validacoes);
            }

            if (!is_null($listAgendaAtendimentoDomicilio) && !empty($listAgendaAtendimentoDomicilio)) {
                if ($this->AgendaAtendimentoDomicilio->verificaIntercessaoHorario($listAgendaAtendimentoDomicilio, $this->request->data)) {
                    array_push($validacoes, ['hora_inicial' => __('validacao_horario_intercessao')]);
                    $this->tratarValidacoes($validacoes);
                }
            }
        }
    }

    /**
     * Método para tratar as mensagens de validação do cadastro de usuário
     * @param unknown $validacoces
     */
    public function tratarValidacoes($validacoces) {
        if (!empty($validacoces)) {
            $this->layout = null;
            $data = array();
            foreach ($validacoces as $key => $field) {
                foreach ($field as $key => $erro) {
                    $erros[] = $erro;
                }
            }

            if (!empty($validacoces)) {
                $data = Array(
                    "status" => "danger",
                    "message" => compact('message', 'erros')
                );
            }

            $this->set('data', $data);
            $this->render('/General/SerializeJson/');
            $this->renderView();
        }
    }

    /**
     * Metodo que retira da session uma vinculo
     */
    public function deletarVinculoSession() {
        $retorno = false;
        $id = $this->request->data['key'];
        $vinculo = $this->Session->read($this->sessionVinculo);
        if (!is_null($vinculo)) {
            unset($vinculo[$id]);
            $this->Session->write($this->sessionVinculo, $vinculo);
            $retorno = true;
        }
        echo json_encode($retorno);
        die;
    }

    /**
     * Adicionar lotação
     */
    public function adicionarLotacaoVinculo() {
        $lotacao = $this->Session->read($this->sessionLotacaoVinculo);

        if (is_null($lotacao)) {
            $lotacao = array();
        }
        $id = $this->request->data['id'];
        $nome = $this->request->data['nome'];

        $novaLotacao = array();
        if (!key_exists($id, $lotacao)) {
            $lotacao[$id] = ['id' => $id, 'nome' => $nome];
            $novaLotacao[0] = ['id' => $id, 'nome' => $nome];
        }
        $this->Session->write($this->sessionLotacaoVinculo, $lotacao);

        echo json_encode($novaLotacao);
        die;
    }

    /**
     * Metodo que retira da session uma lotação
     */
    public function deletarLotacaoSession() {
        $retorno = false;
        $id = $this->request->data['id'];
        $lotacao = $this->Session->read($this->sessionLotacaoVinculo);
        if (!is_null($lotacao)) {
            unset($lotacao[$id]);
            $this->Session->write($this->sessionLotacaoVinculo, $lotacao);
            $retorno = true;
        }
        echo json_encode($retorno);
        die;
    }

    /**
     * Adicionar função
     */
    public function adicionarFuncaoVinculo() {
        $funcao = $this->Session->read($this->sessionFuncaoVinculo);
        if (is_null($funcao)) {
            $funcao = array();
        }
        $id = $this->request->data['id'];
        $nome = $this->request->data['nome'];

        $novaFuncao = array();
        if (!key_exists($id, $funcao)) {
            $funcao[$id] = ['id' => $id, 'nome' => $nome];
            $novaFuncao[0] = ['id' => $id, 'nome' => $nome];
        }
        $this->Session->write($this->sessionFuncaoVinculo, $funcao);

        echo json_encode($novaFuncao);
        die;
    }

    /**
     * Metodo que retira da session uma função
     */
    public function deletarFuncaoSession() {
        $retorno = false;
        $id = $this->request->data['id'];
        $funcao = $this->Session->read($this->sessionFuncaoVinculo);
        if (!is_null($funcao)) {
            unset($funcao[$id]);
            $this->Session->write($this->sessionFuncaoVinculo, $funcao);
            $retorno = true;
        }
        echo json_encode($retorno);
        die;
    }

    /**
     * Método para validar o login do usuário
     * @return type
     */
    private function validarLogin() {

        $this->loadModel("SenhaServidor");
        $this->loadModel("TentativaLogin");

        $this->request->data['Usuario']['cpf'] = Util::limpaDocumentos($this->request->data['Usuario']['cpf']);
        $dataCpf = $this->request->data['Usuario']['cpf'];
        $this->set('cpf', $dataCpf);

        $existeUsuario = $this->verificarExistenciaUsuario($dataCpf);
        $possuiPerfilAtivo = $this->verificarUsuarioPossuiPerfilAtivo($dataCpf);
        $existeUsuarioInativo = $this->verificarUsuarioInativo($dataCpf);

        $this->Usuario->virtualFields['possuiPerfilAtivo'] = $possuiPerfilAtivo;

        if ($existeUsuario === "false") {
            $this->Session->setFlash(__('usuario_login_usuario_nao_existe'), 'flash_alert');
            if (WITH_CAPTCHA && !$this->Session->read('validaCaptchaCC')) {
                $this->Session->write('validaCaptchaCC', true);
            }
            $this->Session->write('showEsqueciSenha', true);
        } else {
            if ($this->verificarUsuarioObito($this->request->data['Usuario']['cpf'])) {
                $this->Session->setFlash(__('validacao_usuario_obito'), 'flash_alert');
                return $this->redirect($this->Auth->logout());
            }

            //Caso possua login e senha no contrachque
            $usuarioContracheque = $this->SenhaServidor->buscarSenhaServidor($this->request->data['Usuario']['cpf'], $this->request->data['Usuario']['senha']);
            //pr($usuarioContracheque);die;
            //pr($usuarioContracheque);die;
            if (count($usuarioContracheque) > 0){
                $this->Session->delete('colaborador');
                $this->Session->delete('showEsqueciSenha');
                $this->Session->delete('validaCaptcha');
                $this->Session->delete('validaCaptchaCC');
                //Valida se o servidor está na base no spm com perfil ativo e loga
                if ($possuiPerfilAtivo == 'true' && $existeUsuarioInativo == 'false') {
                    //Cria seção do usuario $filtro = new BSFilter();
                    $condicoes['Usuario.cpf'] = $this->request->data['Usuario']['cpf'];
                    $filtroUsuario = new BSFilter();
                    $filtroUsuario->setCondicoes($condicoes);
                    $filtroUsuario->setTipo("first");
                    $filtroUsuario->setRecursive(-1);

                    $this->Auth->login($this->Usuario->listar($filtroUsuario)['Usuario']);

                    if(!$this->controleDePermissoes()){
                        return false;
                    }

                    $id = $this->Auth->user('id');
                    $currentFunction = $this->request->params['action']; //function corrente
                    $currentController = $this->name; //Controller corrente
                    $this->saveAuditLog($id,$currentController,'I',$currentFunction);
                    $this->redirect(array('controller' => 'dashboard'));
                    return true;
                } else {
                    $this->Session->setFlash(__('usuario_login_perfil_inativo'), 'flash_alert');
                }
            } else {
                $exist = $this->SenhaServidor->estaNaBaseContraCheque($this->request->data['Usuario']['cpf']);
                //pr($exist);die;
                if(!$exist && $this->Auth->login()){
                    if($this->controleDePermissoes(true)){
                        if(!$this->verificarSenhaExpirada()){
                            return false;
                        }
                        $id = $this->Auth->user('id');
                        $currentFunction = $this->request->params['action']; //function corrente
                        $currentController = $this->name; //Controller corrente
                        $this->saveAuditLog($id,$currentController,'I',$currentFunction);
                        $this->Session->delete('validaCaptcha');
                        $this->Session->delete('validaCaptchaCC');
                        $this->redirect(array('controller' => 'dashboard'));
                        return true;
                    }
                }
                if (WITH_CAPTCHA && !$this->Session->read('validaCaptchaCC')){
                    $this->Session->write('validaCaptchaCC', true);
                }
                $this->Session->write('showEsqueciSenha', true);
                $this->Session->setFlash(__('usuario_login_invalido'), 'flash_alert');
            }
        }
    }

    public function loginSegundo(){
        $this->layout = 'public';

        if ( false  && $this->Auth->user()){ // verifica se já está logado
            $id = $this->Auth->user('id');
            $currentFunction = $this->request->params['action']; //function corrente
            $currentController = $this->name; //Controller corrente
            $this->saveAuditLog($id,$currentController,'I',$currentFunction);

            $this->redirect(array(
                'plugin' => 'web',
                'controller' => 'dashboard'
            ));
        }

        $usuarioSession =  $this->Session->read('usuario_session');
        if(!empty($usuarioSession)){
            $this->set('cpf', $usuarioSession['cpf']);
            $perfis = $this->Usuario->carregarPermissoesPerfil($usuarioSession['id']);
            $this->set('perfis', $perfis);
        }else{
            $this->Session->setFlash("É preciso executar o login um antes.",  'flash_alert');
            $this->redirect(array( 'action' => 'login' ));
        }

        App::import('Vendor', 'recaptcha', array('file'=>'recaptcha' . DS . 'autoload.php'));

        if ($this->request->is('post')) {
            if (WITH_CAPTCHA && $this->Session->read('validaCaptcha')){
                $privateKey = "6LeXziYTAAAAAAdbqc0hGOj4Bmpinq3tvUoZy0o9";
                $recaptcha = new \ReCaptcha\ReCaptcha($privateKey);

                if (isset($this->request->data["g-recaptcha-response"])){
                    $resp = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
                    if (!$resp->isSuccess()){
                        $this->Session->setFlash(__('spm_erro_captcha'), 'flash_alert');
                    } else {
                        $this->validarLoginSegundo();
                    }
                }
            } else {
                $this->validarLoginSegundo();
            }
        }
    }

    private function validarLoginSegundo(){

        $this->loadModel("TentativaLogin");

        $this->request->data['Usuario']['cpf'] = Util::limpaDocumentos($this->request->data['Usuario']['cpf']);
        $dataCpf = $this->request->data['Usuario']['cpf'];

        $possuiPerfilAtivo = $this->verificarUsuarioPossuiPerfilAtivo($dataCpf);
        $existeUsuarioInativo = $this->verificarUsuarioInativo($dataCpf);

        $this->Usuario->virtualFields['possuiPerfilAtivo'] = $possuiPerfilAtivo;

        if(!isset($this->request->data['Perfil']['id']) || empty($this->request->data['Perfil']['id'])){
            $this->Session->setFlash('É preciso escolher um perfil antes', 'flash_alert');
            return false;
        }

        if ($this->Auth->login()){
            if ($possuiPerfilAtivo == 'true' && $existeUsuarioInativo == 'false') {
                if(!$this->verificarSenhaExpirada()){
                    return false;
                }
                $this->carregarPermissoes($this->request->data['Perfil']['id']);

                $id = $this->Auth->user('id');
                $currentFunction = $this->request->params['action']; //function corrente
                $currentController = $this->name; //Controller corrente
                $this->saveAuditLog($id, $currentController, 'I', $currentFunction);


                // $perfis = $this->Usuario->findById($this->Auth->user()['id'])['Perfil'];
                $tipoUsuario = $this->Usuario->findById($this->Auth->user()['id'])['TipoUsuario'];
               
                $administrador = false;
                if($this->request->data['Perfil']['id'] == PERFIL_ADMINISTADOR && $tipoUsuario['id'] == USUARIO_INTERNO){
                    $administrador = true;
                }
                
                if($administrador){
                    $this->Session->write('Auth.User.admin', 'true'); 
                }else{
                    $this->Session->write('Auth.User.admin', 'false'); 
                }
               
                $this->redirect(array('controller' => 'dashboard'));
                return true;
            }else{
                $this->Session->setFlash(__('usuario_login_perfil_inativo'), 'flash_alert');
                $this->redirect(array('controller' => 'Usuario', 'action' => 'loginSegundo'));
                return false;
            }
        }else{

            if (true || !$this->Session->read('validaCaptcha')) {
                $this->Session->write('validaCaptcha', true);
            }
            //------------------------------------------------------------------------//
            //-------------- Verificando o número de tentativas de logar  ------------//
            //------------------------------------------------------------------------//

            //consulta tabela de usuarios com o CPF e pega o id
            $condicoes['Usuario.cpf'] = $this->request->data['Usuario']['cpf'];
            $filtroUsuario = new BSFilter();
            $filtroUsuario->setCondicoes($condicoes);
            $filtroUsuario->setTipo("first");
            $filtroUsuario->setRecursive(-1);

            $usuario = $this->Usuario->listar($filtroUsuario);
            if(!empty($usuario)){
                $idUsuario = $usuario['Usuario']['id'];
            }

            // se encontrou um usuário com o CPF
            if(isset($idUsuario)){
                // pega o numero_tentativas_login dos Parametros Gerais
                $numeroTentativasParametros = $this->returnNumeroTentativas();
                $numeroTentativasRestantes = intval($numeroTentativasParametros);

                if(!empty($numeroTentativasParametros) OR !trim($numeroTentativasParametros) == "0"){
                    // consulta tabela de tentativas com o id_usuario e ativo = 1

                    $condicoesTentativaLogin['TentativaLogin.usuario_id'] = $idUsuario;
                    $condicoesTentativaLogin['TentativaLogin.ativo'] = true;
                    $filtroTentativaLogin = new BSFilter();
                    $filtroTentativaLogin->setCondicoes($condicoesTentativaLogin);
                    $filtroTentativaLogin->setTipo("first");
                    $filtroTentativaLogin->setRecursive(-1);
                    $tentativaLogin = $this->TentativaLogin->listar($filtroTentativaLogin);

                    $numeroTentativas = "";

                    if(empty($tentativaLogin)){
                        $numeroTentativas = '1';

                    }else{
                        // atualiza registro na tabela de tentativas
                        $idTentativaLogin = $tentativaLogin['TentativaLogin']['id'];
                        $numeroTentativas = $tentativaLogin['TentativaLogin']['numero_tentativas'];
                        $numeroTentativas = intval($numeroTentativas)+1;

                        $this->TentativaLogin->id = $idTentativaLogin;
                    }
                    $data = array(
                        'usuario_id'                    => $idUsuario,
                        'data_hora_ultima_tentativa'    => date("Y-m-d H:i:s"),
                        'numero_tentativas'             => intval($numeroTentativas)
                    );

                    if($this->TentativaLogin->save($data)){

                        $id = $idUsuario;
                        $currentFunction = $this->request->params['action']; //function corrente
                        $currentController = $this->name; //Controller corrente
                        $this->saveAuditLog($id,$currentController,'I',$currentFunction);

                        $mensagem = "";
                        if( intval($this->returnNumeroTentativas()) <=  $numeroTentativas ){

                            $this->Usuario->id = $idUsuario;
                            $this->Usuario->saveField('ativado', false);
                            $mensagem = "Seu usuário foi BLOQUEADO! Entre em contato com o IRH para resolver esse problema.";

                            $data['numero_tentativas'] = 0;
                            $this->TentativaLogin->save($data);
                            $redir = 'login';
                        }else{
                            $numeroTentativasRestantes =  intval($this->returnNumeroTentativas()) - intval($numeroTentativas);
                            $mensagem = "Você ainda tem " . $numeroTentativasRestantes . " tentativa(s) antes de ter o seu login bloqueado";
                            $redir = 'loginSegundo';
                        }
                        $this->Session->setFlash($mensagem, 'flash_alert');
                        return $this->redirect(array('controller' => 'Usuario', 'action' => $redir));
                    }
                }
            }
        }
        //------------------------------------------------------------------------//
        //------------ END - Verificando o número de tentativas de logar  --------//
        //------------------------------------------------------------------------//
        // atualiza numero de tentativas += 1;
        $this->Session->setFlash(__('usuario_login_invalido'), 'flash_alert');
        return $this->redirect(array('controller' => 'Usuario', 'action' => 'loginSegundo'));
    }

    public function atualizaTentativasLogin($idUsuario){
        $this->autoRender = false;
        $this->loadModel("TentativaLogin");

        $condicoesTentativaLogin['TentativaLogin.usuario_id'] = $idUsuario;
        $condicoesTentativaLogin['TentativaLogin.ativo'] = true;
        $filtroTentativaLogin = new BSFilter();
        $filtroTentativaLogin->setCondicoes($condicoesTentativaLogin);
        $filtroTentativaLogin->setTipo("first");
        $filtroTentativaLogin->setRecursive(-1);
        $tentativaLogin = $this->TentativaLogin->listar($filtroTentativaLogin);



        if(!empty($tentativaLogin)){
            // atualiza tentativa login com ativo = false
            $this->TentativaLogin->set(array(
                    'id' => $tentativaLogin['TentativaLogin']['id'], 
                    'ativo' => false
                )
            );

            $this->TentativaLogin->save();           
        }
    }

    /**
     * Função que verifica se a senha está expirada
     * @return redirect
     */
    private function verificarSenhaExpirada() {
        $dataExpiracao = date('Y-m-d', strtotime(Util::toDBDataHora($this->Auth->user('expirar_senha'))));
        if (date('Y-m-d') >= $dataExpiracao) {
            //$this->Session->destroy();
            $user = $this->Auth->user();
            $user['habilitar_alteracao_senha'] = true;
            $this->loadModel("Usuario");
            $this->Usuario->save($user);

            $this->Session->setFlash(__('usuario_senha_expirou'), 'flash_alert');

            $this->Session->write('usuario_session', $user);
            $this->Auth->logout();
            //return $this->redirect($this->Auth->logout());
            $this->redirect(array('action' => 'alterarSenhaLogin'));
            return false;
        }
        return true;
    }

    private function verificarUsuarioObito($cpf) {
        $filtro = new BSFilter();
        $condicoes['Usuario.cpf'] = $cpf;
        $condicoes['Usuario.ativado'] = true;
        $filtro->setCondicoes($condicoes);
        $filtro->setCamposRetornadosString('data_obito');
        $filtro->setTipo('all');
        $retorno = false;
        if (!empty($this->Usuario->listar($filtro))) {
            $usuario = $this->Usuario->listar($filtro);
            if (!empty($usuario[0]['Usuario']['data_obito'])) {
                $retorno = true;
            }
        }
        return $retorno;
    }

    private function verificarUsuarioInativo($cpf) {
        $filtro = new BSFilter();
        $condicoes['Usuario.cpf'] = $cpf;
        $condicoes['Usuario.ativado'] = false;
        $filtro->setCondicoes($condicoes);
        $filtro->setTipo('count');
        return $this->Usuario->listar($filtro) > 0 ? 'true' : 'false';
    }

    private function verificarHabilitadoAlterarSenha($cpf) {
        $filtro = new BSFilter();
        $condicoes['Usuario.cpf'] = $cpf;
        $condicoes['Usuario.ativado'] = true;
        $condicoes['Usuario.habilitar_alteracao_senha'] = true;
        $filtro->setCondicoes($condicoes);
        $filtro->setTipo('count');

        // pr($this->Usuario->listar($filtro));die;
        if($this->Usuario->listar($filtro) == '0'){
            return false;
        }else{
            return true;   
        }
        
    }

    private function verificarExistenciaUsuario($cpf) {
        $filtro = new BSFilter();
        $condicoes['Usuario.cpf'] = $cpf;
        $filtro->setCondicoes($condicoes);
        $filtro->setTipo('count');
        return $this->Usuario->listar($filtro) > 0 ? 'true' : 'false';
    }

    private function verificarUsuarioPossuiPerfilAtivo($cpf) {
        $filtro = new BSFilter();
        $condicoes['Usuario.cpf'] = $cpf;
        $condicoes['perfil.ativado'] = true;
        $joins[] = array(
            'table' => 'usuario_perfil',
            'alias' => 'up',
            'type' => 'left',
            'conditions' => array('up.usuario_id = Usuario.id')
        );
        $joins[] = array(
            'table' => 'perfil',
            'alias' => 'perfil',
            'type' => 'left',
            'conditions' => array('perfil.id = up.perfil_id')
        );
        $filtro->setCondicoes($condicoes);
        $filtro->setJoins($joins);
        $filtro->setTipo('count');
        return $this->Usuario->listar($filtro) > 0 ? 'true' : 'false';
    }


    private function temPerfilAdministrador(){

        $idUsuario = $this->Auth->user()['id'];
        $isAdm = false;

        $filtro = new BSFilter();
        $condicoes['Usuario.id'] = $idUsuario;
        $condicoes['perfil.id'] = 1; // Administrador
        $condicoes['perfil.ativado'] = true;
        $joins[] = array(
            'table' => 'usuario_perfil',
            'alias' => 'up',
            'type' => 'left',
            'conditions' => array('up.usuario_id = Usuario.id')
        );
        $joins[] = array(
            'table' => 'perfil',
            'alias' => 'perfil',
            'type' => 'left',
            'conditions' => array('perfil.id = up.perfil_id')
        );
        $filtro->setCondicoes($condicoes);
        $filtro->setJoins($joins);
        $filtro->setTipo('count');

        return $this->Usuario->listar($filtro) == 1 ? 'true' : 'false';
    }

    /**
     * Método para carregar a lista de permissões associadas aos perfis do Usuário Logado
     */
    public function carregarPermissoes($perfil) {
        $this->Session->write('perfil', $perfil);
        $this->Session->write('permissoes', $this->Usuario->carregarPermissoes($this->Auth->user()['id'], $perfil));
        $this->Session->write('permissoesPerfil', $this->Usuario->carregarPermissoesPerfil($this->Auth->user()['id'], $perfil));
    }

    public function logout() {
        // $this->Session->destroy();
        if($this->Auth->user){
            $id = $this->Auth->user('id');
        }else {
            $id = $this->Session->read('usuario_session')['id'];
        }
        $this->Session->delete('usuario_session');


        $currentFunction = $this->request->params['action']; //function corrente
        $currentController = $this->name; //Controller corrente
        $this->saveAuditLog($id,$currentController,'I',$currentFunction);

        return $this->redirect($this->Auth->logout());
    }

    /**
     * Método utilizado para exibir a listagem inicial de Usuários cadastrados
     */
    public function index() {
        //Limpa sessões existentes
        $this->limparSessoes();

        //Carrega data para as buscas
        $this->carregarListasTipoUsuario();
        $this->carregarListaPerfis();
        $this->carregarListasOrgaoOrigem();
        $this->carregarListasCargo();
        $this->carregarListasEstados();
    }



    /**
     * Método responsável por realizar a filtragem de uma cargo no sistema
     */
    public function consultar() {
        $this->layout = 'ajax';

        if ($this->request->is('GET')) {
            $objFiltro = new BSFilter();
            $dataConsulta = $this->request->query['data'];
            $filtro = $this->getFilter($objFiltro);
            $limitConsulta = $dataConsulta['Usuario']['limitConsulta'];
            $this->Usuario->Behaviors->load('Containable');
            $filtro->setContain(array('TipoUsuario'));
            $this->set('usuarios', $this->paginar($filtro));
            $this->set('limiteConsultaSelecionado', $limitConsulta);
			$currentFunction = $this->request->params['action']; //function corrente
            $currentController = $this->name; //Controller corrente
            $this->saveAuditLog(null,$currentController,'C',$currentFunction);
        }
    }

    private function getFilter(BSFilter $filtro) {
        $dataConsulta = $this->request->query['data'];
        $limitConsulta = $dataConsulta['Usuario']['limitConsulta'];
        $nome = $dataConsulta['Usuario']['nome'];
        $tipoUsuario = $dataConsulta['Usuario']['TipoUsuario'];
        $ativado = $dataConsulta['Usuario']['ativado'];
        $cpf = Util::limpaDocumentos($dataConsulta['Usuario']['cpf']);
        $rg = $dataConsulta['Usuario']['rg'];
        $orgaoExpedido = $dataConsulta['Usuario']['orgao_expedidor'];
        $numeroRegistro = $dataConsulta['Usuario']['numero_registro'];
        $orgaoOrigem = $dataConsulta['Usuario']['orgaoOrigem'];
        $matricula = $dataConsulta['Usuario']['matricula'];
        $cargo = $dataConsulta['Usuario']['cargo'];
        $lotacaoNome = $dataConsulta['Usuario']['lotacaoNome'];
        $municipio = $dataConsulta['Endereco']['municipio_id'];
        $perfil = $dataConsulta['Usuario']['perfil_id'];
        $condicoes = null;
        if (!empty($nome)) {
            $condicoes['Usuario.nome ILIKE '] = "%$nome%";
        }
        if (!empty($tipoUsuario)) {
            $condicoes['Usuario.tipo_usuario_id'] = $tipoUsuario;
        }

        if ($ativado != "") {
            $condicoes['Usuario.ativado'] = $ativado;
        }
        if (!empty($cpf)) {
            $condicoes['Usuario.cpf'] = $cpf;
        }
        if (!empty($rg)) {
            $condicoes['Usuario.rg'] = $rg;
        }
        if (!empty($orgaoExpedido)) {
            $condicoes['upper(Usuario.orgao_expedidor)'] = strtoupper($orgaoExpedido);
        }
        if (!empty($numeroRegistro)) {
            $condicoes['Usuario.numero_registro'] = $numeroRegistro;
        }
        if (!empty($orgaoOrigem)) {
            $condicoes['Vinculo.orgao_origem_id'] = $orgaoOrigem;
        }
        if (!empty($matricula)) {
            $condicoes['Vinculo.matricula'] = $matricula;
        }
        if (!empty($cargo)) {
            $condicoes['Vinculo.cargo_id'] = $cargo;
        }
        if (!empty($lotacaoNome)) {

            $condicoes['Lotacao.nome ILIKE '] = "%$lotacaoNome%";
        }

        if (!empty($municipio)) {
            $condicoes['EnderecoLotacao.municipio_id'] = $municipio;
        }

        $joins[] = array(
            'table' => 'vinculo',
            'alias' => 'Vinculo',
            'type' => 'left',
            'conditions' => array('Vinculo.usuario_id = Usuario.id')
        );

        if (!empty($municipio) || (!empty($lotacaoNome))) {
            $joins[] = array(
                'table' => 'vinculo_lotacao',
                'alias' => 'VinculoLotacao',
                'type' => 'left',
                'conditions' => array('VinculoLotacao.vinculo_id = Vinculo.id')
            );

            $joins[] = array(
                'table' => 'lotacao',
                'alias' => 'Lotacao',
                'type' => 'left',
                'conditions' => array('Lotacao.id = VinculoLotacao.lotacao_id')
            );

            $joins[] = array(
                'table' => 'endereco',
                'alias' => 'EnderecoLotacao',
                'type' => 'left',
                'conditions' => array('EnderecoLotacao.id = Lotacao.endereco_id')
            );
        }
        if ($perfil != "") {
            $joins[] = array(
                'table' => 'usuario_perfil',
                'alias' => 'up',
                'type' => 'left',
                'conditions' => array('up.usuario_id = Usuario.id')
            );
            $condicoes['up.perfil_id'] = $perfil;
        }

        $filtro->setCondicoes($condicoes);
        $filtro->setJoins($joins);
        $filtro->setLimiteConsulta($limitConsulta);
        $filtro->setCamposRetornadosString("id", "nome", "cpf", "numero_registro", "ativado");
        $filtro->setCamposAgrupadosString('"Usuario"."id","TipoUsuario.id", "Usuario"."nome", "Usuario"."cpf", "Usuario"."numero_registro", "Usuario"."ativado"');
        $filtro->setCamposOrdenados(['Usuario.nome' => 'asc']);

        return $filtro;
    }

    /**
     * Método utilizado para visualizar um Usuário
     * @param string $id identificador do Usuário
     */
    public function visualizar($id = null) {

        $this->loadModel('Municipio');
        $municipiosAtendimento = $this->Municipio->listarMunicipiosUF(17);
        $this->set('munipiosAtendimentoDomiciliar', $municipiosAtendimento);

        if (!$id) {
            throw new NotFoundException(__('Usuário inválido'));
        }

        $usuario = $this->Usuario->findById($id);
        #Limpando senha do Usuário : 
        unset($usuario['Usuario']['senha']);

        $checkedHabilitarSenha = "";
        if(isset($usuario['Usuario']['habilitar_alteracao_senha']) && $usuario['Usuario']['habilitar_alteracao_senha'] == '1'){
            $checkedHabilitarSenha = "checked";
            
        }

        $this->set('checkedHabilitarSenha', $checkedHabilitarSenha);
        // pr($usuario);die;

        if (!$usuario) {
            throw new NotFoundException(__('Usuário inválido'));
        }
        $usuario['Usuario']['EmpresaComplete'] = $usuario['Empresa']['nome'];
        $usuario['Vinculo'] = $this->montarListVinculo($usuario['Vinculo']);

        $usuario = $this->preEditarUsuario($usuario);
        $this->carregarSessoes($usuario);
        $this->request->data = $usuario;
        $this->carregarDadosEdit();
        //render view edit
		
		
		 
		$currentFunction = $this->request->params['action']; //function corrente
		$currentController = $this->name; //Controller corrente
		$this->saveAuditLog($id,$currentController,'V',$currentFunction);
		
		
		
        $this->render('edit');
    }

    /**
     * Método para buscar um usuário por CPF
     */
    private function buscarUsuarioPorCPF($cpf) {
        $condicoes['Usuario.cpf'] = Util::limpaDocumentos($cpf);
        $filtro = new BSFilter();
        $filtro->setCamposRetornadosString('id', 'nome', 'email');
        $filtro->setCondicoes($condicoes);
        $filtro->setTipo('first');
        return $this->Usuario->listar($filtro);
    }

    public function recuperarSenha($interno = 0) {
        $this->layout = 'ajax';
        if ($this->request->is('get')) {
            $cpf = $this->request->query['cpf'];

            $retorno = array();

            if (!empty($cpf) && Util::limpaDocumentos($cpf) != "00000000000") {
               
                $this->loadModel("SenhaServidor");
                $this->loadModel("Usuario");
                $this->loadModel("ParametroGeral");
                $this->loadModel("UsuarioSenhaHist");


                if (!$interno && ($this->SenhaServidor->estaNaBaseContraCheque($cpf) || $this->Usuario->usuarioTipoServidor($cpf))) {
                    $retorno['message'] = __('url_alterar_senha_contra_cheque');
                    $retorno['priority'] = 'redirect';
                } else {
                    $usuario = $this->buscarUsuarioPorCPF($cpf);
                    if ($usuario){
                        $novaSenha = Util::gerar_senha(8);
                        $usuarioAntes = $this->Usuario->findById($usuario['Usuario']['id'])['Usuario'];
                        $parametros = $this->ParametroGeral->getParametros();
                        //pr($usuarioAntes);die;
                        $data = array(
                            'senha' => $novaSenha,
                            'id' => $usuario['Usuario']['id'],
                            'expirar_senha' => date('d/m/Y', strtotime("+{$parametros['dias_expiracao_senha']} days")) . ' 00:00:00',
                            'habilitar_alteracao_senha' => true
                        );
                        $this->Usuario->save($data, false);
                        //pr($data);die;
                        $this->UsuarioSenhaHist->save([
                            'usuario_id' => $usuario['Usuario']['id'],
                            'senha' => $usuarioAntes['senha']
                        ]);
						
                        //pr($data);
                        //pr($novaSenha);die;
						$id = $usuario['Usuario']['id'];
                        //pr($id);die;
						$currentFunction = $this->request->params['action']; //function corrente
						$currentController = $this->name; //Controller corrente
						$this->saveAuditLog($id,$currentController,'C',$currentFunction);
						

                        $emailBBC = ( (!empty($this->ParametroGeral->buscarEmailCopia())) ? $this->ParametroGeral->buscarEmailCopia() : null) ;
            
                        if ($this->enviarEmailNovaSenha($usuario['Usuario']['nome'], $usuario['Usuario']['email'], $novaSenha, $emailBBC)) {
                            
								
							
							$retorno['message'] = __('validacao_senha_alterada_recuperar_senha');
                            $retorno['priority'] = 'success';
                        } else {
                            $retorno['message'] = __('validacao_erro_enviar_email_recuperar_senha');
                            $retorno['priority'] = 'danger';
                        }
                    } else {
                        $retorno['message'] = __('validacao_usuario_nao_existe_recuperar_senha');
                        $retorno['priority'] = 'danger';
                    }
                }
            } else {
                $retorno['message'] = __('validacao_login_obrigatorio_recuperar_senha');
                $retorno['priority'] = 'danger';
            }

            header('Content-Type: application/json');
            echo json_encode($retorno);
            die();
        }
    }

    /**
     * Método para enviar um e-mail com uma nova senha para o Usuário
     * @param type $login
     * @param type $sendEmail
     * @param type $senha
     * @return boolean
     */
    private function enviarEmailCadastro($nome = null, $sendEmail = null, $senha = null, $emailBcc = null) {
        if ($nome && $sendEmail && $senha) {
            App::uses('CakeEmail', 'Network/Email');
            $email = new CakeEmail('default');
            $email->from(array(
                'spm@irh.pe.gov.br' => 'SPM'
            ));
            $email->to($sendEmail);
            $email->subject('Cadastro .:SPM:.');
            $email->emailFormat('html');

            $email->bcc($emailBcc);

            $htmlMsg = '<html>
                        <head></head>
                        <body>
                            <h1>Cadastro no Sistema SPM</h1>
                            <br/>
                            Olá "' . $nome . '",
                            <br/>
                            Sua senha para acessar o SPM é: ' . $senha . '
                        </body>
                    </html>';

            return ($email->send($htmlMsg)) ? true : false;
        } else {
            return false;
        }
    }

    /**
     * Método para enviar um e-mail com uma nova senha para o Usuário
     * @param type $login
     * @param type $sendEmail
     * @param type $senha
     * @return boolean
     */
    private function enviarEmailNovaSenha($nome = null, $sendEmail = null, $senha = null, $emailBcc = null) {
        if ($nome && $sendEmail && $senha) {
            App::uses('CakeEmail', 'Network/Email');
            $email = new CakeEmail('default');
            $email->from(array(
                'spm@irh.pe.gov.br' => 'SPM'
            ));

            $email->to($sendEmail);
            $email->subject('Recuperar Senha .:SPM:.');
            $email->emailFormat('html');
            $email->bcc($emailBcc);
            //pr($senha);die;

            $htmlMsg = '<html>
                        <head></head>
                        <body>
                            <h1>Recuperar senha SPM</h1>
                            <br/>
                            Olá "' . $nome . '",
                            <br/>
                            Sua nova senha para acessar o SPM é: ' . $senha . '
                        </body>
                    </html>';

            return ($email->send($htmlMsg)) ? true : false;
        } else {
            return false;
        }
    }


    private function enviarEmailRedefinirSenha($nome = null, $sendEmail = null, $senha = null, $emailBcc = null) {
        if ($nome && $sendEmail && $senha) {
            App::uses('CakeEmail', 'Network/Email');
            $email = new CakeEmail('default');
            $email->from(array(
                'spm@irh.pe.gov.br' => 'SPM'
            ));

            $email->to($sendEmail);
            $email->subject('Redefinição de Senha .:SPM:.');
            $email->emailFormat('html');
            $email->bcc($emailBcc);
            //pr($senha);die;


            $htmlMsg = '<html>
                        <head></head>
                        <body>
                            <h1>Redefinição de senha SPM</h1>
                            <br/>
                            Olá "' . $nome . '",
                            <br/>
                            Sua senha foi redefinida para acessar o SPM é: ' . $senha . '
                        </body>
                    </html>';

            return ($email->send($htmlMsg)) ? true : false;
        } else {
            return false;
        }
    }




    /**
     * Método para carregar a lista de Perfil
     */
    private function carregarListaPerfis() {
        $this->loadModel('Perfil');
        $filtro = new BSFilter();
        $filtro->setTipo('list');
        $condicoes['Perfil.ativado'] = true;
        $filtro->setCondicoes($condicoes);
        $filtro->setCamposOrdenadosString('Perfil.nome');
        $perfis = $this->Perfil->listar($filtro);
        $this->set(compact('perfis'));
    }

    /**
     * Verifica se existe sessão, caso exista limpa a sessão.
     */
    private function limparSessoes() {
        $this->Session->delete($this->sessionUsuario);
        $this->Session->delete($this->sessionFuncaoVinculo);
        $this->Session->delete($this->sessionLotacaoVinculo);
        $this->Session->delete($this->sessionVinculo);
        $this->Session->delete($this->sessionAgendaAtendimento);
        $this->Session->delete($this->sessionAgendaAtendimentoDomicilio);
        $this->Session->delete($this->sessionDependentes);
    }

    private function carregarSessoes($dataUser = array()) {
        if (empty($dataUser)) {
            //Carrega as funções registradas na sessão
            if (!empty($this->Session->read($this->sessionVinculo))) {
                $this->set('usuarioVinculo', $this->Session->read($this->sessionVinculo));
            }

            //Carrega as Agendas de Atendimento
            if (!empty($this->Session->read($this->sessionAgendaAtendimento))) {
                $this->set('agendaAtendimento', $this->Session->read($this->sessionAgendaAtendimento));
            }

            //Carrega as Agendas de Atendimento em Domicilio
            if (!empty($this->Session->read($this->sessionAgendaAtendimentoDomicilio))) {
                $this->set('agendaAtendimentoDomicilio', $this->Session->read($this->sessionAgendaAtendimentoDomicilio));
            }

            //Carrega as Dependentes
            if (!empty($this->Session->read($this->sessionDependentes))) {
                $this->set('usuarioDependentes', $this->Session->read($this->sessionDependentes));
            }
        } else {

            //Carrega os vinculos de acordo com o parametro
            $this->Session->write($this->sessionVinculo, $dataUser['Vinculo']);
            $this->set('usuarioVinculo', $dataUser['Vinculo']);
            //Carrega as as agendas registradas
            $agendaAtendimento = $this->montarListAgendaAtendimento($dataUser['AgendaAtendimento']);
            // pr($dataUser['AgendaAtendimento']);die;
            $this->Session->write($this->sessionAgendaAtendimento, $agendaAtendimento);
            $this->set('agendaAtendimento', $agendaAtendimento);

            //Carrega as agendas em domicilio registradas
            $agendaAtendimentoDomicilio = $this->montarListAgendaAtendimentoDomicilio($dataUser['AgendaAtendimentoDomicilio']);
            $this->Session->write($this->sessionAgendaAtendimentoDomicilio, $agendaAtendimentoDomicilio);
            $this->set('agendaAtendimentoDomicilio', $agendaAtendimentoDomicilio);

            //Carrega as Dependentes
            $this->Session->write($this->sessionDependentes, $this->montarListDependentes($dataUser['Dependente']));
            $this->set('usuarioDependentes', $this->Session->read($this->sessionDependentes));
        }
    }

    private function montarListDependentes($arrDependentes) {
        $arrRetorno = [];
        foreach ($arrDependentes as $dependente) {
            $arrRetorno[$dependente['Dependente']['id']] = $dependente;
        }

        return $arrRetorno;
    }

    private function montarListAgendaAtendimento($arrAgendas) {
        $retorno = [];
        if (!empty($arrAgendas)) {
            // pr($arrAgendas);die;
            foreach ($arrAgendas as $agenda) {
                $arrHoraInicial = explode(':', $agenda['AgendaAtendimento']['hora_inicial']);
                $arrHoraFinal = explode(':', $agenda['AgendaAtendimento']['hora_final']);

                $strHoraInicial = $arrHoraInicial[0] . ':' . $arrHoraInicial[1];
                $strHoraFinal = $arrHoraFinal[0] . ':' . $arrHoraFinal[1];
                $nomes_tipologias = "";
                $ids_tipologias = [];
                if (isset($agenda['Tipologia'])) {
                    foreach ($agenda['Tipologia'] as $key => $tipologia) {
                        if ($key > 0) {
                            $nomes_tipologias = $nomes_tipologias . ", ";
                        }
                        $nomes_tipologias = $nomes_tipologias . $tipologia['nome'];
                        $ids_tipologias[] = $tipologia['id'];
                    }
                }
                $retorno[$agenda['AgendaAtendimento']['id']]['AgendaAtendimento'] = [
                    'id' => $agenda['AgendaAtendimento']['id'],
                    'dia_semana' => $agenda['AgendaAtendimento']['dia_semana'],
                    'hora_inicial' => $strHoraInicial,
                    'hora_final' => $strHoraFinal,
                    'nome_unidade_atendimento' => $agenda['UnidadeAtendimento']['nome'],
                    'permitir_agendamento' => $agenda['AgendaAtendimento']['permitir_agendamento'],
                    'nome_tipologia' => $nomes_tipologias,
                    'unidade_atendimento_id' => ($agenda['AgendaAtendimento']['unidade_atendimento_id']) ? $agenda['AgendaAtendimento']['unidade_atendimento_id'] : "",
                ];
                $retorno[$agenda['AgendaAtendimento']['id']]['Tipologia'] = $ids_tipologias;
            }
        }
        return $retorno;
    }

    private function montarListAgendaAtendimentoDomicilio($arrAgendas) {
        $retorno = [];
        if (!empty($arrAgendas)) {
            foreach ($arrAgendas as $agenda) {
                $arrHoraInicial = explode(':', $agenda['AgendaAtendimentoDomicilio']['hora_inicial']);
                $arrHoraFinal = explode(':', $agenda['AgendaAtendimentoDomicilio']['hora_final']);

                $strHoraInicial = $arrHoraInicial[0] . ':' . $arrHoraInicial[1];
                $strHoraFinal = $arrHoraFinal[0] . ':' . $arrHoraFinal[1];
                $nomes_tipologias = "";
                $ids_tipologias = [];
                if (isset($agenda['Tipologia'])) {
                    foreach ($agenda['Tipologia'] as $key => $tipologia) {
                        if ($key > 0) {
                            $nomes_tipologias = $nomes_tipologias . ", ";
                        }
                        $nomes_tipologias = $nomes_tipologias . $tipologia['nome'];
                        $ids_tipologias[] = $tipologia['id'];
                    }
                }

                // pr($agenda['AgendaAtendimentoDomicilio']['municipio_id']);die;

                $this->loadModel('Municipio');

                $filtro = new BSFilter();
                $filtro->setCamposRetornadosString('nome');
                $filtro->setCamposRetornados(['nome']);
                $condicoes['Municipio.id'] = $agenda['AgendaAtendimentoDomicilio']['municipio_id'];
                $filtro->setCondicoes($condicoes);
                $municipios = $this->Municipio->listar($filtro);
              

                $nomeMunicipio = array_values($municipios)['0'];
                // pr($nomeMunicipio);die;

                $retorno[$agenda['AgendaAtendimentoDomicilio']['id']]['AgendaAtendimentoDomicilio'] = [
                    'id'                        => $agenda['AgendaAtendimentoDomicilio']['id'],
                    'dia_semana'                => $agenda['AgendaAtendimentoDomicilio']['dia_semana'],
                    'hora_inicial'              => $strHoraInicial,
                    'hora_final'                => $strHoraFinal,
                    'nome_unidade_atendimento'  => $agenda['UnidadeAtendimento']['nome'],
                    'nome_municipio'            => $nomeMunicipio,
                    'municipio_id'              => ($agenda['AgendaAtendimentoDomicilio']['municipio_id']) ? $agenda['AgendaAtendimentoDomicilio']['municipio_id'] : "",
                    'nome_tipologia'            => $nomes_tipologias,
                    'unidade_atendimento_id'    => ($agenda['AgendaAtendimentoDomicilio']['unidade_atendimento_id']) ? $agenda['AgendaAtendimentoDomicilio']['unidade_atendimento_id'] : "",
                ];
                $retorno[$agenda['AgendaAtendimentoDomicilio']['id']]['Tipologia'] = $ids_tipologias;
            }
        }
        return $retorno;
    }

    /**
     * Função que monta a lista de vinculos para ser exibido nas edição e visualização
     * @param array $arrVinculos
     * @return array
     */
    private function montarListVinculo($arrVinculos = []) {
        if (!empty($arrVinculos)) {
            foreach ($arrVinculos as $key => $vinculo) {
                $nomesFuncao = '';

                if (!empty($vinculo['Funcao'])) {
                    foreach ($vinculo['Funcao'] as $funcao) {
                        $nomesFuncao .= (!empty($nomesFuncao)) ? ', ' . $funcao['nome'] : $funcao['nome'];
                    }
                }
                $nomesLotacao = '';
                if (!empty($vinculo['Lotacao'])) {
                    foreach ($vinculo['Lotacao'] as $lotacao) {
                        $nomesLotacao .= (!empty($nomesLotacao)) ? ', ' . $lotacao['nome'] : $lotacao['nome'];
                    }
                }

                $arrVinculos[$key]['funcoes'] = $nomesFuncao;
                $arrVinculos[$key]['lotacoes'] = $nomesLotacao;
            }
        }
        return $arrVinculos;
    }

    public function montarLotacoes() {
        if ($this->request->is(array('post', 'put'))) {
            $idOrgao = $this->request->data['id'];

            $this->loadModel('Lotacao');
            $filtro = new BSFilter();
            $filtro->setTipo('list');
            $filtro->setCamposOrdenadosString('Lotacao.nome');
            $condicoes = ['Lotacao.orgao_origem_id' => $idOrgao];
            $filtro->setCondicoes($condicoes);
            echo json_encode($this->Lotacao->listar($filtro));
            die;
        }
    }

    /**
     * Montando array para o saveAll de usuário
     * @param array $data
     * @return array 
     */
    private function montarArrayUsuario($data) {
        unset($data['Vinculo']);
        unset($data['AgendaAtendimento']);
        unset($data['AgendaAtendimentoDomicilio']);
        unset($data['Dependente']);
        unset($data['EnderecoDependente']);
        unset($data['Model']);
        if (isset($data['Usuario']['tipo_usuario_id'])) {
            if (($data['Usuario']['tipo_usuario_id'] == $this->Usuario->peritoCredenciado) || ($data['Usuario']['tipo_usuario_id'] == $this->Usuario->peritoServidor)) {
                $data['AgendaAtendimento'] = ($this->Session->read($this->sessionAgendaAtendimento)) ? $this->Session->read($this->sessionAgendaAtendimento) : [];
                $data['AgendaAtendimentoDomicilio'] = ($this->Session->read($this->sessionAgendaAtendimentoDomicilio)) ? $this->Session->read($this->sessionAgendaAtendimentoDomicilio) : [];
            } else {
                $data['AgendaAtendimento'] = [];
                $data['AgendaAtendimentoDomicilio'] = [];
            }
        }

        $data['Dependente'] = ($this->Session->read($this->sessionDependentes)) ? $this->Session->read($this->sessionDependentes) : [];
        $data['Vinculo'] = $this->montarArrayVinculo();
        return $data;
    }

    /**
     * Monta o array.
     * @return array
     */
    private function montarArrayVinculo() {
        $retorno = [];
        if ($this->Session->read($this->sessionVinculo)) {
            $vinculos = $this->Session->read($this->sessionVinculo);

            if (!empty($vinculos)) {
                foreach ($vinculos as $key => $vinculo) {
                    if (!empty($vinculo['Lotacao']['lotacaoArray'])) {
                        foreach ($vinculo['Lotacao']['lotacaoArray'] as $lotacao) {
                            $vinculo['Lotacao']['Lotacao'][] = $lotacao['id'];
                        }
                    }

                    if (!empty($vinculo['Funcao']['funcaoArray'])) {
                        foreach ($vinculo['Funcao']['funcaoArray'] as $funcao) {
                            $vinculo['Funcao']['Funcao'][] = $funcao['id'];
                        }
                    }

                    if (isset($vinculo['OrgaoOrigem']['orgao_origem_id'])) {
                        $vinculo['Vinculo']['orgao_origem_id'] = $vinculo['OrgaoOrigem']['orgao_origem_id'];
                    }

                    //Limpando campos desnecessarios do array;
                    unset($vinculo['Lotacao']['lotacaoString']);
                    unset($vinculo['Funcao']['funcaoString']);
                    unset($vinculo['Lotacao']['lotacaoArray']);
                    unset($vinculo['Funcao']['funcaoArray']);
                    unset($vinculo['Funcao']['string']);
                    unset($vinculo['Lotacao']['string']);
                    unset($vinculo['OrgaoOrigem']);
                    unset($vinculo['Cargo']);

                    $vinculos[$key] = $vinculo;
                }
                $retorno = $vinculos;
            }
        }
        return $retorno;
    }

    private function validateUsuario() {
        $this->loadModel('Usuario');
        $this->loadModel('AgendaAtendimento');
        if ($this->request->is(array('post', 'put'))) {
            $dataUser = $this->request->data;

            $this->Usuario->set($dataUser);
            $validacoes = array();

            if (!$this->Usuario->validates()) {
                $validacoesUsuario = $this->Usuario->validationErrors;
                array_push($validacoes, $validacoesUsuario);
            }

            if (!empty($dataUser['Usuario']['tipo_usuario_id']) && $dataUser['Usuario']['tipo_usuario_id'] == $this->peritoCredenciado) {
                if ($this->AgendaAtendimento->existeAgendamento($dataUser)) {
                    $arrayAgenda = [];
                    array_push($arrayAgenda, __('validacao_usuario_agenda_atendimento'));
                    $validacoes[count($validacoes) - 1]['AgendaAtendimento'] = $arrayAgenda;
                }
            }

            if (!empty($validacoes)) {
                $this->tratarValidacoes($validacoes);
            }
        }
    }

    /**
     * Método para verificar se o endereco do Dependente deve utilizar o mesmo endereço do usuário. Em caso positivo, o endereço do usuário é vinculado ao dependente
     * @param type $idEnderecoUsuario
     */
    public function tratarEnderecoDependente($idEnderecoUsuario) {
        //Interando os dependentes e setando o endereco ID para eles se estiverem com a flag TRUE
        foreach ($this->request->data['Dependente'] as $key => $dependente) {
            //Endereço do ID
            if (isset($dependente['Dependente']['endereco_servidor']) && $dependente['Dependente']['endereco_servidor'] == "true") {
                $this->request->data['Dependente'][$key]['Dependente']['endereco_id'] = $idEnderecoUsuario;
                unset($this->request->data['Dependente'][$key]['EnderecoDependente']);
            }
        }
    }

    /**
     * Método utilizado para cadastrar um novo Usuário no sistema
     */
    public function adicionar() {

         $this->loadModel('Municipio');
         $municipiosAtendimento = $this->Municipio->listarMunicipiosUF(17);
         $this->set('munipiosAtendimentoDomiciliar', $municipiosAtendimento);
          $this->set('checkedHabilitarSenha', "checked");
         // pr($municipiosAtendimento);die;

        if ($this->request->is('post')) {

            //ATRIBUINDO O VALOR DE TODOS OS CAMPOS AO FORM.
            $this->request->data = $this->montarArrayUsuario($this->request->data);

            $this->validateUsuario();
            $dataSource = $this->Usuario->getDataSource();
            $dataSource->begin();
            $this->loadModel("EnderecoSimples");

            //Salvando endereço antes para pegar os ids dos dependentes
            $this->EnderecoSimples->save($this->request->data["EnderecoUsuario"]);

            //Limpando o endereço do request
            unset($this->request->data["EnderecoUsuario"]);

            //Recuperando o ID inserindo
            $idEndereco = $this->EnderecoSimples->id;

            //Setando o valor do endereço ID do usuário.
            $this->request->data["Usuario"]["endereco_id"] = $idEndereco;

            $this->tratarEnderecoDependente($idEndereco);

            $tipoUsuario = $this->request->data['Usuario']['tipo_usuario_id'];

            $enviarEmailSenha = false;
            if ($tipoUsuario == TipoUsuario::INTERNO || $tipoUsuario == TipoUsuario::PERITO_CREDENCIADO) {

                $novaSenha = Util::gerar_senha(8);
                $this->request->data['Usuario']['senha'] = $novaSenha;
                $enviarEmailSenha = true;
            }

            //Salvando o Usuário
            if ($this->Usuario->saveAll($this->request->data, array('deep' => true))) {





                if ($enviarEmailSenha) {
                    $this->loadModel("ParametroGeral");
                    $emailBBC = ( (!empty($this->ParametroGeral->buscarEmailCopia())) ? $this->ParametroGeral->buscarEmailCopia() : null) ;
                    $this->enviarEmailCadastro($this->request->data['Usuario']['nome'], $this->request->data['Usuario']['email'], $novaSenha, $emailBBC);
                }
                try {
                    $dataSource->commit();
                    $id = $this->Usuario->id;
                    $currentFunction = $this->request->params['action']; //function corrente
                    $currentController = $this->name; //Controller corrente
                    $this->saveAuditLog($id,$currentController,'I',$currentFunction);
                    $this->Session->setFlash(__('Usuário salvo com sucesso'), 'flash_success');

                    //Limpar sessão
                    $this->limparSessoes();
                    $urlReturn = Router::url(array('controller' => 'Usuario', 'action' => 'index'), true);
                    $retorno['status'] = 'success';
                    $retorno['url'] = $urlReturn;

                    echo json_encode($retorno);
                    die;
                } catch (Exception $ex) {

                    $dataSource->rollback();
                    $urlReturn = Router::url(array('controller' => 'Usuario', 'action' => 'adicionar'), true);
                    $retorno['status'] = 'danger';
                    $retorno['url'] = $urlReturn;
                    $retorno['message']['erros'][] = 'Problemas ao cadastrar usuário.';
                    echo json_encode($retorno);
                    die;
                }
            } else {
                $dataSource->rollback();
                $urlReturn = Router::url(array('controller' => 'Usuario', 'action' => 'adicionar'), true);
                $retorno['status'] = 'danger';
                $retorno['url'] = $urlReturn;
                $retorno['message']['erros'][] = 'Problemas ao cadastrar usuário.';
                echo json_encode($retorno);
                die;
            }
        } else {
            //Limpar sessão
            $this->limparSessoes();

            //Carrega campos do Edit
            $this->carregarDadosEdit();

            //render view edit
            $this->render('edit');
        }
    }

    private function getEnderecoById($id) {
        $this->loadModel('Endereco');
        return $this->Endereco->findById($id);
    }

    private function carregarDadosEdit() {
        $this->carregarListaPerfis();
        $this->carregarListasEstados();
        $this->carregarListasMunicipiosUsuarios();
        $this->carregarListasMunicipiosDependentes();
        $this->carregarListasTipoUsuario();
        $this->carregarListasUnidadeAtendimento();
        $this->carregarListasEstadoCivil();
        $this->carregarListasSexos();
        $this->carregarListasOrgaoOrigem();
        $this->carregarListasCargo();
        $this->carregarListasFuncao();
        $this->carregarListasTipologia();
        $this->carregarListasDiasSemana();
        $this->carregarListasQualidade();
    }

    private function carregarListasDiasSemana() {
        $diasSemana = ['Domingo' => 'Domingo',
            'Segunda-feira' => 'Segunda-feira',
            'Terça-feira' => 'Terça-feira',
            'Quarta-feira' => 'Quarta-feira',
            'Quinta-feira' => 'Quinta-feira',
            'Sexta-feira' => 'Sexta-feira',
            'Sábado' => 'Sábado'];
        $this->set(compact('diasSemana'));
    }

    private function carregarListasQualidade() {
        $this->loadModel('Qualidade');
        $filtro = new BSFilter();
        $filtro->setTipo('list');
        $filtro->setCamposOrdenadosString('Qualidade.nome');
        $qualidade = $this->Qualidade->listar($filtro);
        $this->set(compact('qualidade'));
    }

    private function carregarListasTipoUsuario() {
        $this->loadModel('TipoUsuario');
        $filtro = new BSFilter();
        $filtro->setTipo('list');
        $filtro->setCamposOrdenadosString('TipoUsuario.nome');
        $tipoUsuario = $this->TipoUsuario->listar($filtro);
        $this->set(compact('tipoUsuario'));
    }

    private function carregarListasUnidadeAtendimento() {
        $this->loadModel('UnidadeAtendimento');
        $filtro = new BSFilter();
        $filtro->setTipo('list');
        $filtro->setCamposOrdenadosString('UnidadeAtendimento.nome');
        $unidadeAtendimento = $this->UnidadeAtendimento->listar($filtro);

        $condicoes = array('UnidadeAtendimento.atendimento_domicilio' => true);
        $filtro->setCondicoes($condicoes);
        $unidadeAtendimentoDomicilio = $this->UnidadeAtendimento->listar($filtro);

        // pr($unidadeAtendimentoDomicilio);die;

        $this->set(compact('unidadeAtendimento', 'unidadeAtendimentoDomicilio'));
    }

    private function carregarListasEstadoCivil() {
        $this->loadModel('EstadoCivil');
        $filtro = new BSFilter();
        $filtro->setTipo('list');
        $filtro->setCamposOrdenadosString('EstadoCivil.nome');
        $estadoCivil = $this->EstadoCivil->listar($filtro);
        $this->set(compact('estadoCivil'));
    }

    private function carregarListasSexos() {
        $this->loadModel('Sexo');
        $filtro = new BSFilter();
        $filtro->setTipo('list');
        $filtro->setCamposOrdenadosString('Sexo.nome');
        $sexo = $this->Sexo->listar($filtro);
        $this->set(compact('sexo'));
    }

    private function carregarListasOrgaoOrigem() {
        $this->loadModel('OrgaoOrigem');
        $filtro = new BSFilter();
        $filtro->setTipo('list');
        $filtro->setCamposOrdenadosString('OrgaoOrigem.orgao_origem');
        $orgaoOrigem = $this->OrgaoOrigem->listar($filtro);
        $this->set(compact('orgaoOrigem'));
    }

    private function carregarListasCargo() {
        $this->loadModel('Cargo');
        $filtro = new BSFilter();
        $filtro->setTipo('list');
        $filtro->setCamposOrdenadosString('Cargo.nome');
        $cargo = $this->Cargo->listar($filtro);
        $this->set(compact('cargo'));
    }

    private function carregarListasFuncao() {
        $this->loadModel('Funcao');
        $filtro = new BSFilter();
        $filtro->setTipo('list');
        $filtro->setCamposOrdenadosString('Funcao.nome');
        $funcao = $this->Funcao->listar($filtro);
        $this->set(compact('funcao'));
    }

    private function carregarListasTipologia() {
        $this->loadModel('Tipologia');
        $filtro = new BSFilter();
        $filtro->setTipo('list');
        $filtro->setCamposOrdenadosString('Tipologia.nome');
        $tipologia = $this->Tipologia->listar($filtro);
        $this->set(compact('tipologia'));
    }

    public function alterarDados($id = null) {


            // pr($this->temPerfilAdministrador());die;

            


        if ($this->request->is(array('post', 'put'))) {
            $id = CakeSession::read('Auth.User.id');
            $this->request->data['Usuario']['tipo_usuario_id'] = $this->Usuario->buscarTipoUsuario($id);
            $this->request->data['Usuario']['id'] = $id;
            unset($this->request->data['Perfil']);

            $this->request->data = $this->montarArrayUsuario($this->request->data);
            $this->Usuario->id = $id;
            $this->validateUsuario();
            $dataSource = $this->Usuario->getDataSource();
            $dataSource->begin();
            $this->tratarEnderecoDependente($this->request->data['EnderecoUsuario']['id']);

            if ($this->Usuario->saveAll($this->request->data)) {
                $dataSource->commit();


                $currentFunction = $this->request->params['action']; //function corrente
                $currentController = $this->name; //Controller corrente
                $this->saveAuditLog($id,$currentController,'A',$currentFunction);

                $this->Session->setFlash(__('Usuário salvo com sucesso'), 'flash_success');
                $this->limparSessoes();
            }
            $urlReturn = Router::url(array('controller' => 'Usuario', 'action' => 'alterarDados', $id), true);
            $retorno['status'] = 'success';
            $retorno['url'] = $urlReturn;
            echo json_encode($retorno);
            die;
        } else {
   
            if ($id != $this->Auth->user()['id']) {
                throw new NotFoundException(__('Usuário inválido'));
            }
      

            $disabledEndereco = 'false';

            if($this->temPerfilAdministrador() == 'false'){
                $disabledEndereco = 'true'; 
            }

            $this->set('disabledEndereco', $disabledEndereco);

            $this->set('acao', Configure::read('ACAO_ALTERAR_DADOS'));
            $this->set('id', $id);

            //FORM
            $arrFormCreate = ['inputDefaults' => ['class' => 'form-control'],
                'id' => 'formBody',
                'novalidate'];

            $this->set('formCreate', $arrFormCreate);
            $this->set('title', __('usuario_titulo_alterar_dados'));

            $this->set('isRequerid', '*');

            //DISABLED FOR VIEW
            $this->set('formDisabled', false);

            $this->set('formAlteracaoDados', true);

            if (!$id) {
                throw new NotFoundException(__('Usuário inválido'));
            }

            $usuario = $this->Usuario->findById($id);
            $checkedHabilitarSenha = "";
            if(isset($usuario['Usuario']['habilitar_alteracao_senha']) && $usuario['Usuario']['habilitar_alteracao_senha'] == '1'){
                $checkedHabilitarSenha = "checked";
            
            }

            $this->set('checkedHabilitarSenha', $checkedHabilitarSenha);
            $usuario['Usuario']['EmpresaComplete'] = $usuario['Empresa']['nome'];
            #Limpando senha do Usuário : 
            unset($usuario['Usuario']['senha']);
            $usuario['Vinculo'] = $this->montarListVinculo($usuario['Vinculo']);

            $usuario = $this->preEditarUsuario($usuario);
            $this->carregarSessoes($usuario);
            if (!$usuario) {
                throw new NotFoundException(__('Usuário inválido'));
            }

            $this->loadModel('Municipio');
            $municipiosAtendimento = $this->Municipio->listarMunicipiosUF(17);
            $this->set('munipiosAtendimentoDomiciliar', $municipiosAtendimento);

            if (!$this->request->data) {
                $this->request->data = $usuario;
            }
        }

        $this->carregarDadosEdit();
        //render view edit
        $this->render('edit');
    }

    /**
     * Método utilizado para editar um Usuário previamente cadastrado no sistema
     * @param int $id identificador do Usuário que vai ser editado
     * @throws NotFoundException
     */
    public function editar($id = null) {

        if ($this->request->is(array('post', 'put'))) {

            $this->request->data = $this->montarArrayUsuario($this->request->data);

            $tipoUsuario = $this->request->data['Usuario']['tipo_usuario_id'];
            if ($tipoUsuario == USUARIO_PERITO_CREDENCIADO) {
                unset($this->request->data['Vinculo']);
            }


            $enviarEmailSenha = false;
               if(!empty($this->request->data['Usuario']['senha'])){
                   $enviarEmailSenha = true;
               }
            $this->Usuario->id = $id;
            $this->validateUsuario();
            $dataSource = $this->Usuario->getDataSource();
            $dataSource->begin();
            $this->tratarEnderecoDependente($this->request->data['EnderecoUsuario']['id']);


            
            // ----------------------------------------------------------------------------------------- //
            // - Habilitando a alteração de senha após login, caso o usuário esteja sendo desbloqueado --//
            // ----------------------------------------------------------------------------------------- //
            $ativadoPreEdicao = $this->Session->read('ativadoPreEdicao');

            if(isset($this->request->data['Usuario']['ativado']) && $this->request->data['Usuario']['ativado'] == "1" && $ativadoPreEdicao == "0"){
                $this->request->data['Usuario']['habilitar_alteracao_senha'] = "1";
            }

            // ----------------------------------------------------------------------------------------------- //
            // - END - Habilitando a alteração de senha após login, caso o usuário esteja sendo desbloqueado --//
            // ---------------------------------------------------------------------------------------------- //
            // pr($this->request->data);die;
            if ($this->Usuario->saveAll($this->request->data, array('deep' => true))) {


                $currentFunction = $this->request->params['action']; //function corrente
                $currentController = $this->name; //Controller corrente
                $this->saveAuditLog($id,$currentController,'A',$currentFunction);


                if ($enviarEmailSenha) {
                    $this->loadModel("ParametroGeral");
                    $emailBBC = ( (!empty($this->ParametroGeral->buscarEmailCopia())) ? $this->ParametroGeral->buscarEmailCopia() : null) ;
                    $this->enviarEmailRedefinirSenha($this->request->data['Usuario']['nome'], $this->request->data['Usuario']['email'], $this->request->data['Usuario']['senha'], $emailBBC);
                }
                try {
                    $dataSource->commit();
                    $this->Session->setFlash(__('Usuário salvo com sucesso'), 'flash_success');
                    $this->limparSessoes();
                    $urlReturn = Router::url(array('controller' => 'Usuario', 'action' => 'index'), true);
                    $retorno['status'] = 'success';
                    $retorno['url'] = $urlReturn;
                    echo json_encode($retorno);
                    die;
                } catch (Exception $ex) {

                    $dataSource->rollback();
                    $urlReturn = Router::url(array('controller' => 'Usuario', 'action' => 'editar',$id), true);
                    $retorno['status'] = 'danger';
                    $retorno['url'] = $urlReturn;
                    $retorno['message']['erros'][] = 'Problemas ao editar usuário.';
                    echo json_encode($retorno);
                    die;
                }
            } else {

                $urlReturn = Router::url(array('controller' => 'Usuario', 'action' => 'editar', $id), true);
                $retorno['status'] = 'danger';
                $retorno['url'] = $urlReturn;
                $mensagem = "";
                if(isset($this->Usuario->validationErrors)){
                    foreach ($this->Usuario->validationErrors as $key => $value){
                        foreach ($value as $v){
                           foreach ($v as $aux) {
                                $mensagem .= "\n";
                                $mensagem .= $aux['0'];
                                $mensagem .= "\n";
                                $retorno['message']['erros'][] = $mensagem;
                            }
                        }
                    }

                }
                
                echo json_encode($retorno);
                die;
            }
        } else {

            if (!$id){
                throw new NotFoundException(__('Usuário inválido'));
            }


            $disabledEndereco = 'false';

            if($this->temPerfilAdministrador() == 'false'){
                $disabledEndereco = 'true'; 
            }

            $this->set('disabledEndereco', $disabledEndereco);

            $this->loadModel('Municipio');
            $municipiosAtendimento = $this->Municipio->listarMunicipiosUF(17);
            $this->set('munipiosAtendimentoDomiciliar', $municipiosAtendimento);

            $usuario = $this->Usuario->findById($id);

            // Desabilitando a troca de senha do usuário Administrador (00000000000)
            $desabilitaTrocaSenha = false;
            if(Util::limpaDocumentos($usuario['Usuario']['cpf']) == "00000000000" ){
                $desabilitaTrocaSenha = true;
            }

            $this->set('desabilitaTrocaSenha', $desabilitaTrocaSenha);
            


            $ativadoPreEdicao = $usuario['Usuario']['ativado'];
            if(empty($ativadoPreEdicao)){
                $ativadoPreEdicao = "0";
            }else{
                $ativadoPreEdicao = "1";
            }
             
            $this->Session->write('ativadoPreEdicao', $ativadoPreEdicao);   
            

            $checkedHabilitarSenha = "";

            if(isset($usuario['Usuario']['habilitar_alteracao_senha']) && $usuario['Usuario']['habilitar_alteracao_senha'] == '1'){
                $checkedHabilitarSenha = "checked";
            
            }
            $this->set('checkedHabilitarSenha', $checkedHabilitarSenha);


            $usuario['Usuario']['EmpresaComplete'] = $usuario['Empresa']['nome'];

            #Limpando senha do Usuário : 
            unset($usuario['Usuario']['senha']);
            $usuario['Vinculo'] = $this->montarListVinculo($usuario['Vinculo']);

            // pr($usuario['Vinculo']);die;            

            $usuario = $this->preEditarUsuario($usuario);
            $this->carregarSessoes($usuario);

            if (!$usuario) {
                throw new NotFoundException(__('Usuário inválido'));
            }

            if (!$this->request->data) {
                $this->request->data = $usuario;
            }

            $this->carregarDadosEdit();
//render view edit
            $this->render('edit');
        }
    }

    private function preEditarUsuario($usuario) {
        foreach ($usuario['Vinculo'] as $key => $vinculo) {
            $lotacoes = $vinculo['Lotacao'];
            $funcoes = $vinculo['Funcao'];
            unset($usuario['Vinculo'][$key]['Lotacao']);
            unset($usuario['Vinculo'][$key]['Funcao']);

            foreach ($lotacoes as $keyl => $lotacao) {
                $usuario['Vinculo'][$key]['Lotacao']['Lotacao'][$keyl] = $lotacao['id'];
            }

            foreach ($funcoes as $keyf => $funcao) {
                $usuario['Vinculo'][$key]['Funcao']['Funcao'][$keyf] = $funcao['id'];
            }
        }
        return $usuario;
    }

    private function carregarListasMunicipiosUsuarios($listarTodosMunicipios = false) {
        $this->loadModel('Municipio');
        if ($listarTodosMunicipios) {
            $this->set('municipiosUsuarios', $this->Municipio->listarMunicipios());
        } else if (isset($this->request->data['EnderecoUsuario'])) {
            $estado = $this->request->data['EnderecoUsuario']['estado_id'];
            $this->set('municipiosUsuarios', $this->Municipio->listarMunicipiosUF($estado));
        }
    }

    public function carregarListaMunicipio() {
        $this->loadModel('Municipio');

        $estado = $this->request->query['estado_id'];

        $filtro = new BSFilter();
        $filtro->setCamposRetornadosString('id', 'nome');
        $condicoes['Municipio.estado_id'] = $estado;
        $filtro->setCondicoes($condicoes);
        $municipios = $this->Municipio->listar($filtro);

        header('Content-Type: application/json');
        echo json_encode($municipios);
        die();
    }

    private function carregarListasMunicipiosDependentes($listarTodosMunicipios = false) {
        $this->loadModel('Municipio');
        if ($listarTodosMunicipios) {
            $this->set('municipiosDependentes', $this->Municipio->listarMunicipios());
        } else if (isset($this->request->data['EnderecoDependente'])) {
            $estado = $this->request->data['EnderecoDependente']['estado_id'];
            $this->set('municipiosDependentes', $this->Municipio->listarMunicipiosUF($estado));
        }
    }

    private function carregarListasEstados() {
        $this->loadModel('Estado');
        $this->set('estados', $this->Estado->listarEstados());
    }

    /**
     * Método para excluir um Usuário do sistema
     */
    public function deletar($id){
        if ($this->request->is('get')) {
            if (!$id) {
                throw new NotFoundException(__('objeto_invalido', __('Usuario')));
            }
			$checkedHabilitarSenha = "";
            $usuario = $this->Usuario->findById($id);

            if(isset($usuario['Usuario']['habilitar_alteracao_senha']) && $usuario['Usuario']['habilitar_alteracao_senha'] == '1'){
                $checkedHabilitarSenha = "checked";
            
            }
            $this->set('checkedHabilitarSenha', $checkedHabilitarSenha);
            
            #Limpando senha do Usuário : 
            unset($usuario['Usuario']['senha']);

            if (!$usuario) {
                throw new NotFoundException(__('objeto_invalido', __('Usuario')));
            }
            $usuario['Vinculo'] = $this->montarListVinculo($usuario['Vinculo']);

            $usuario = $this->preEditarUsuario($usuario);

            $this->carregarSessoes($usuario);

            $this->request->data = $usuario;

            $this->carregarDadosEdit();
            //render view edit
            $this->render('edit');
        }
    }

    public function processarExclusao() {
        $this->autoRender = false;
        $id = $this->request->data['Usuario']['id'];

        $userLogged = $this->Session->read('Auth.User');

        if ($userLogged['id'] == $id) {
            $this->Session->setFlash(__('validacao_usuario_excluir_si_proprio'), 'flash_alert');
            echo Router::url(array('controller' => 'Usuario', 'action' => 'deletar', $id), true);
            die;
        } else {
            $this->Usuario->getDataSource()->begin();
            if ($this->Usuario->delete($id)) {


                $currentFunction = $this->request->params['action']; //function corrente
                $currentController = $this->name; //Controller corrente
                $this->saveAuditLog($id,$currentController,'E',$currentFunction);


                $this->Usuario->getDataSource()->commit();
                $this->Session->setFlash(__('objeto_excluir_sucesso', __('Usuario')), 'flash_success');
                echo Router::url(array('controller' => 'Usuario', 'action' => 'index'), true);
                die;
            }
        }
    }

    public function obterAgendaAtendimento() {
        $this->layout = 'ajax';
        $retorno = false;
        if (($this->request->is('POST')) && (!empty($this->Session->read($this->sessionAgendaAtendimento)))) {
            $id = $this->request->data['id'];
            $arraySessionAgenda = $this->Session->read($this->sessionAgendaAtendimento);
            $retorno = $arraySessionAgenda[$id];
            // pr($retorno);die;
			
			
			
		$currentFunction = $this->request->params['action']; //function corrente
		$currentController = $this->name; //Controller corrente
		$this->saveAuditLog($id,$currentController,'C',$currentFunction);
			
        }
        echo json_encode($retorno);
        die;
    }
    /*
        Função utilizada no momento da edição dos dados da agenda de atendimento em domicilio.
        Será responsável por fazer a consulta da agenda de atendimento, pelo ID 
        O js será responsável por montar os dados para edição
    */
    public function obterAgendaAtendimentoDomicilio() {
        $this->layout = 'ajax';
        $retorno = false;
        if (($this->request->is('POST')) && (!empty($this->Session->read($this->sessionAgendaAtendimentoDomicilio)))) {
            $id = $this->request->data['id'];
            $arraySessionAgendaDomicilio = $this->Session->read($this->sessionAgendaAtendimentoDomicilio);
            $retorno = $arraySessionAgendaDomicilio[$id];
			
			
			
                $currentFunction = $this->request->params['action']; //function corrente
                $currentController = $this->name; //Controller corrente
                $this->saveAuditLog($id,$currentController,'C',$currentFunction);
			
        }
        echo json_encode($retorno);
        die;
    }

    /**
     * Verifica se é possível remover a tipologia.
     */
    public function verificaRemocaoTipologia() {
        $this->layout = 'ajax';
        $retorno = false;
        if ($this->request->is('POST')) {
            $arrId = $this->request->data;
            $arraySessionAgenda = $this->Session->read($this->sessionAgendaAtendimento);
            if ($arraySessionAgenda) {
                foreach ($arraySessionAgenda as $line) {
                    if (in_array($arrId['id'][0], $line['Tipologia'])) {
                        $retorno = true;
                        break;
                    }
                }
            }
        }
        echo json_encode($retorno);
        die;
    }

    /**
     * Verifica se é possível remover a tipologia.
     */
    public function verificaRemTipoAgndDomicilio() {
        $this->layout = 'ajax';
        $retorno = false;
        if ($this->request->is('POST')) {
            $arrId = $this->request->data;
            $arraySessionAgendaDomicilio = $this->Session->read($this->sessionAgendaAtendimentoDomicilio);
            if ($arraySessionAgendaDomicilio) {
                foreach ($arraySessionAgendaDomicilio as $line) {
                    if (in_array($arrId['id'][0], $line['Tipologia'])) {
                        $retorno = true;
                        break;
                    }
                }
            }
        }
        echo json_encode($retorno);
        die;
    }

    /**
     * Adicionar Dependente
     */
    public function adicionarDependente() {
        $this->validateDependente();
        $dependentes = $this->Session->read($this->sessionDependentes);

        if (is_null($dependentes)) {
            $dependentes = array();
        }

        end($dependentes);
        $key = (empty($dependentes)) ? 0 : key($dependentes) + 1;
        $dependentes[$key]['Dependente'] = [
            'nome' => $this->request->data['nome'],
            'cpf' => $this->request->data['cpf'],
            'rg' => $this->request->data['rg'],
            'data_nascimento' => $this->request->data['data_nascimento'],
            'inscricao_funape' => $this->request->data['inscricao_funape'],
            'qualidade_id' => $this->request->data['qualidade_id'],
            'nome_pai' => $this->request->data['nome_pai'],
            'nome_mae' => $this->request->data['nome_mae'],
            'endereco_servidor' => (isset($this->request->data['endereco_servidor'])) ? $this->request->data['endereco_servidor'] : false];

        // Monta o combo de endereço caso não seja o mesmo do usuário que está sendo cadastrado.
        if (!isset($this->request->data['endereco_servidor'])) {
            $dependentes[$key]['EnderecoDependente'] = ['cep' => $this->request->data['endereco_dependente_cep'],
                'logradouro' => $this->request->data['endereco_dependente_logradouro'],
                'numero' => $this->request->data['endereco_dependente_numero'],
                'complemento' => $this->request->data['endereco_dependente_complemento'],
                'bairro' => $this->request->data['endereco_dependente_bairro'],
                'estado_id' => $this->request->data['endereco_dependente_estado_id'],
                'municipio_id' => $this->request->data['endereco_dependente_municipio_id']];
        }
        $this->Session->write($this->sessionDependentes, $dependentes);
        echo json_encode($dependentes);
        die;
    }

    /**
     * Valida o cadastro de Dependente.
     */
    public function validateDependente() {
        $this->loadModel('Dependente');
        if ($this->request->is('post')) {
            $this->Dependente->set($this->request->data);
            $validacoes = array();
            if (!$this->Dependente->validates()) {
                $validacoesDependente = $this->Dependente->validationErrors;
                array_push($validacoes, $validacoesDependente);
                $this->tratarValidacoes($validacoes);
            }
        }
    }

    /**
     * Deletar dependente
     */
    public function deletarDependente() {
        $retorno = false;
        $id = $this->request->data['key'];
        $dependentes = $this->Session->read($this->sessionDependentes);
        if (!is_null($dependentes)) {
            unset($dependentes[$id]);
            $this->Session->write($this->sessionDependentes, $dependentes);
            $retorno = true;
        }
        echo json_encode($retorno);
        die;
    }

    /**
     * Pegar os dados dos dependentes
     */
    public function obterDependente() {
        $this->layout = 'ajax';
        $retorno = false;
        if (($this->request->is('POST')) && (!empty($this->Session->read($this->sessionDependentes)))) {
            $arraySessionDependente = $this->Session->read($this->sessionDependentes);
            $id = $this->request->data['id'];
            $retorno = $arraySessionDependente[$id];
        }
        if (isset($retorno['Dependente']['data_nascimento']) && $retorno['Dependente']['data_nascimento']) {
            $retorno['Dependente']['data_nascimento'] = Util::inverteData($retorno['Dependente']['data_nascimento']);
        }
        echo json_encode($retorno);
        die;
    }

    /**
     * Adicionar Agenda de atendimento
     */
    public function atualizarDependente() {
        $this->validateDependente();
        $dependentes = $this->Session->read($this->sessionDependentes);
        if (is_null($dependentes)) {
            $dependentes = array();
        }

        $id = $this->request->data['id'];
        $dependentes[$id]['Dependente'] = ['id' => $id,
            'nome' => $this->request->data['nome'],
            'cpf' => $this->request->data['cpf'],
            'rg' => $this->request->data['rg'],
            'data_nascimento' => $this->request->data['data_nascimento'],
            'inscricao_funape' => $this->request->data['inscricao_funape'],
            'qualidade_id' => $this->request->data['qualidade_id'],
            'nome_pai' => $this->request->data['nome_pai'],
            'nome_mae' => $this->request->data['nome_mae'],
            'endereco_servidor' => (isset($this->request->data['endereco_servidor'])) ? $this->request->data['endereco_servidor'] : false];

        // Monta o combo de endereço caso não seja o mesmo do usuário que está sendo cadastrado.
        if (!isset($this->request->data['endereco_servidor']) || $this->request->data['endereco_servidor'] != 'true') {
            $dependentes[$id]['EnderecoDependente'] = ['cep' => $this->request->data['endereco_dependente_cep'],
                'logradouro' => $this->request->data['endereco_dependente_logradouro'],
                'numero' => $this->request->data['endereco_dependente_numero'],
                'complemento' => $this->request->data['endereco_dependente_complemento'],
                'bairro' => $this->request->data['endereco_dependente_bairro'],
                'estado_id' => $this->request->data['endereco_dependente_estado_id'],
                'municipio_id' => $this->request->data['endereco_dependente_municipio_id']];
        }
        $this->Session->write($this->sessionDependentes, $dependentes);
        echo json_encode($dependentes);
        die;
    }

    public function limparSessaoLotacao() {
        $lotacoes = [];
        $this->Session->write($this->sessionLotacaoVinculo, $lotacoes);
        die();
    }

    private function controleDePermissoes($colaborador = false){
        $perfis = $this->Usuario->carregarPermissoesPerfil($this->Auth->user()['id']);
        if(count($perfis)==0){
            $this->Session->setFlash('Usuário sem perfil associado', 'flash_alert');
            $this->Auth->logout();
            return false;
        }else if(count($perfis) > 1){
            $this->Session->write('usuario_session', $this->Auth->user());
            $this->Auth->logout();
            if ($colaborador){
                $this->Session->delete('validaCaptcha');
                $this->Session->delete('validaCaptchaCC');
                $this->Session->delete('showEsqueciSenha');
            }
            $this->redirect(array('action' => 'loginSegundo'));
            return false;
        }else{
            $idPerfis = array_keys($perfis);
            $idPerfil = array_pop($idPerfis);
            if($idPerfil != PERFIL_SERVIDOR_GESTOR ){
                $this->Session->write('usuario_session', $this->Auth->user());
                $this->Auth->logout();
                if ($colaborador){
                    $this->Session->delete('validaCaptcha');
                    $this->Session->delete('validaCaptchaCC');
                    $this->Session->delete('showEsqueciSenha');
                }
                $this->redirect(array('action' => 'loginSegundo'));
                return false;
            }else{
                //fluxo direto
                $this->Session->delete('tentativaCpf');
            }
        }

        $this->carregarPermissoes($idPerfil);
        return true;
    }

    private function addTimeSession($line){
        $times = $this->Session->read('times');
        if(!is_array($times) || empty($times))$times = [];
        $times[] = ['line'=> $line, 'date'=>date('Y-m-d H:i:s')];
        $this->Session->write('times', $times);
    }

    public function userData(){
        pr($this->Auth->user()); die;
    }

    public function verify(){
        //for($i = 0; $i < 100000000;$i++){}
        echo 1;
        die;
    }
}
