<?php

App::uses('BSFilter', 'Model');
App::uses('AuditableConfig', 'Auditable.Lib');

// App::import("Plugin/Admin/Lib", "Util");
// App::import("Plugin/Admin/Lib", "Carbon");

App::import("Plugin/Web/Lib", "Util");
App::import("Plugin/Web/Lib", "Carbon");


/**
 * 
 * Classe que deve ser extendidas de todos os Controllers do Administrador
 * @author BSDENVOLVIMENTO
 *
 */
class BSController extends Controller {

    private $security_actions = array('adicionar', 'deletar', 'editar', 'visualizar', 'consultar','homologar');
    public $helpers = array('Js', 'Html', 'Form');

    public function __construct($request = null, $response = null) {

        parent::__construct($request, $response);

        $this->uses = array(ucfirst($this->params['controller']));
    }

    public $components = array(
        'Session',
        'Paginator',
        'RequestHandler',
//        'Security',
        'Auth' => array(
            'loginRedirect' => array(
                'controller' => 'index',
                'action' => 'index',
                // 'plugin' => 'admin'
                'plugin' => 'web'
            ),
            'authError' => 'Você não está autorizado a acessar este local.',
            'logoutRedirect' => array(
                'controller' => 'usuario',
                'action' => 'login',
                // 'plugin' => 'admin'
                'plugin' => 'web'
            ),
            'loginAction' => array(
                'controller' => 'usuario',
                'action' => 'login',
                // 'plugin' => 'admin'
                'plugin' => 'web'
            ),
            'authenticate' => array(
                'Form' => array(
                    'passwordHasher' => 'Blowfish',
                    'userModel' => 'Usuario',
                    'fields' => array(
                        'username' => 'cpf',
                        'password' => 'senha'
                    ),
                    'scope' => array('Usuario.senha <> ' => '','Usuario.ativo' => true, 'possuiPerfilAtivo' => true, 'Usuario.ativado' => true)
                )
            )
        )
    );

    /**
	 * Método para tratar o redirecionamento após o usuário salvar um determinado cadastro
	 */
	public function tratarAcaoSalvar() {
		// Caso o usuário tenha clicado no botão "Salvar" o sistema direciona para a tela de consulta
		if (isset ( $this->request->data ['salvarButton'] )) {
			return $this->redirect ( array (
					'action' => 'index' 
			) );
		} else {
			// Caso o usuário tenha clicado no botão "Salvar e Incluir Novo"
			return $this->redirect ( array (
					'action' => 'adicionar' 
			) );
		}
	}
        
//    public function securityBs() {
//        //Liberando os fields que são modificados via javascript para não ser validados
//        $this->Security->unlockedFields = array('recaptcha_challenge_field', 'recaptcha_response_field');
//        //Liberando o ctrl + f5 
//        $this->Security->csrfUseOnce = false;
//        $this->Security->blackHoleCallback = 'blackhole';
//    }
//
//     public function blackhole($type){
//        debug($type);
//        die;
//    }
    
    public function beforeRender() {

        // Caso o usuário tenha a flag para alterar a palavra-chave, o sistema força o direcionamento para a página de alteração de senha
        $habilitar_alteracao_senha = $this->Auth->user('habilitar_alteracao_senha');
		$user = $this->Auth->user();
		if(!empty($user) && !empty($this->Auth->user('id'))) $this->Session->write('usuario_session', $this->Auth->user());
         
        if($this->request->controller != "Usuario" && ($this->request->action != "alterarSenhaLogin" && $this->request->action != "login" && $this->request->action != "logout") ){
            if(isset($habilitar_alteracao_senha) &&  $habilitar_alteracao_senha == '1'){
                $this->Session->setFlash(__('necessario_alterar_senha'), 'flash_alert');
                return $this->redirect(array('controller' => 'Usuario', 'action' => 'alterarSenhaLogin'));
            }else{
                $this->set('userData', $this->Auth->user());
                $this->loadConfigView();
            }
        }else{
            $this->set('userData', $this->Auth->user());
            $this->loadConfigView();
        }    

    }

    /**
     * 
     * Metodo para parar o processamento da página e renderizar a view padrao .
     */
    public function renderView() {
        $this->response->send();
        $this->_stop();
    }

    /**
     * 
     * Metodo para parar o ser chamado caso alguma validação de exclusão falhe.
     */
    public function deleteError($str = "") {

        $this->layout = null;
        $data = Array(
            "status" => "danger",
            "message" => $str
        );
        $this->set('data', $data);
        $this->render('/General/SerializeJson/');
        $this->renderView();
    }

    /**
     * 
     * Metodo para paginar os registros usando BsFilter.
     */
    public function paginar(BSFilter $filtro, $modelConsulta = "") {
        ($modelConsulta == "") ? $filtro->setModelConsulta($this->params['controller']) : $filtro->setModelConsulta($modelConsulta);
        $this->Paginator->settings = $filtro->getScope();
        $this->Paginator->settings['limit'] = $filtro->getLimiteConsulta();

        return $this->paginate($filtro->getModelConsulta());
    }

    /**
     * Metodo que devera ser sobrescrito caso seja necessario desabilitar as checagens de permissoes dos controladores.
     * 
     * @return TRUE por padrao
     */
    public function checarPermissoes() {
        return true;
    }

    /**
     * Metodo que faz a verificacao da acao atual do controlador. Caso nao possua, o usuario sera
     * redirecionado para tela de acesso negado. 
     */
    public function verificaPermissao() {
        if ($this->Session->read('permissoes')) {
            if ($this->checarPermissoes()) {
                $acao = $this->action == 'index' ? 'consultar' : $this->action;

                if (in_array($acao, $this->security_actions)) {
                    $permissao = $this->params['controller'] . "." . $acao;

                    if ((!in_array(strtolower($permissao), array_map('strtolower', $this->Session->read('permissoes')))) && $acao != 'homologar' ) {
                        $this->Session->setFlash(__('Usuario não possui: ' . $permissao), 'flash_alert');
                        // return $this->redirect(array('plugin' => 'admin', 'controller' => 'dashboard', 'action' => 'index'));
                        return $this->redirect(array('plugin' => 'web', 'controller' => 'dashboard', 'action' => 'index'));
                    }
                }
            }
        }
    }

    public function beforeFilter() {
        parent::beforeFilter();
        $this->verificaPermissao();
//        $this->securityBs();
        
        App::import('Vendor', 'Auditable.Lib');
        if($this->Auth->user()) {
        	AuditableConfig::$responsibleId = $this->Auth->user('id');
        	AuditableConfig::$controllerName = ucfirst($this->params['controller']);
        }
    }

    /**
     * Metodo que carrega as funcionalidades da view atual.
     * @return array $arrRetorno
     */
    public function loadConfigView() {
        $controller = ucfirst($this->params['controller']);
        $action = $this->params['action'];
        $this->set('controller', ucfirst($controller) );
        $this->set('currentAction', $action);
        $this->set('nameModel', $this->modelClass);

        //VALIDANDO SE A RENDERIZAÇÃO ESTÁ VINDO DE VISUAILZAR, ADICIONAR OU EDITAR
        if (in_array($action, ['visualizar', 'adicionar', 'editar', 'deletar','homologar', 'visualizarAtendimento'])) {
            //VERIFICA QUAL A ACTION O USUÁRIO ESTÁ.
            switch ($action) {
                case 'visualizarAtendimento':
                    //FORM
                    $class = '';
                    $requerid = '';
                    //CONFIGURACOES
                    $acaoAtual = Configure::read('ACAO_VISUALIZAR');
                    $id = $this->params['data'][$controller]['id'];
                    $disabled = true;
                    $extra = false;
                    //PAGE
                    $title = 'titulo_visualizar';
                    $breadcrumb = 'menu_visualizar';
                    $disabledHomologa = false;
                    break;
                case 'visualizar':
                    //FORM
                    $class = '';
                    $requerid = '';
                    //CONFIGURACOES
                    $acaoAtual = Configure::read('ACAO_VISUALIZAR');
                    $id = $this->params['data'][$controller]['id'];
                    $disabled = true;
                    $extra = false;
                    //PAGE
                    $title = 'titulo_visualizar';
                    $breadcrumb = 'menu_visualizar';
                    $disabledHomologa = false;
                    break;
                case 'adicionar':
                    //FORM
                    $class = 'formInclusao';
                    $requerid = '*';
                    $extra = false;
                    //CONFIGURACOES
                    $acaoAtual = Configure::read('ACAO_INSERIR');
                    $id = null;
                    $disabled = false;
                    //PAGE
                    $title = 'titulo_inserir';
                    $breadcrumb = 'menu_inserir';
                    $disabledHomologa = false;
                    break;
                case 'editar':
                    //FORM
                    $class = 'formEdicao';
                    $requerid = '*';
                    $extra = false;
                    //CONFIGURACOES
                    $acaoAtual = Configure::read('ACAO_ALTERAR');
                    if(isset($this->params['data'][$controller]['id'])){
                        $id = $this->params['data'][$controller]['id'];
                    }else{
                        $id = '';
                    }

                    $disabled = false;
                    //PAGE
                    $title = 'titulo_alterar';
                    $breadcrumb = 'menu_alterar';
                    $disabledHomologa = false;
                    break;
                case 'homologar':
                    //FORM
                    $class = 'formHomologa';
                    $requerid = '';
                    $extra = true;
                    //CONFIGURACOES
                    $acaoAtual = Configure::read('ACAO_HOMOLOGAR');
                    $id = $this->params['data'][$controller]['id'];
                    $disabled = false;
                    $disabledHomologa = true;

                    //PAGE
                    $title = 'titulo_homologar';
                    $breadcrumb = 'menu_alterar';
                    break;
                case 'deletar':
                    //FORM
                    $class = 'formEdicao';
                    $requerid = '';
                    $extra = false;
                    //CONFIGURACOES
                    $acaoAtual = Configure::read('ACAO_EXCLUIR');
                    $id = $this->params['data'][$controller]['id'];
                    $disabled = true;
                    //PAGE
                    $title = 'titulo_deletar';
                    $breadcrumb = 'menu_deletar';
                    $disabledHomologa = false;
                    break;
            }

            //AÇÕES
            $this->set('acao', $acaoAtual);
            $this->set('id', $id);

            //FORM
            $arrFormCreate = ['inputDefaults' => ['class' => 'form-control'],
                'id' => 'formBody',
                'class' => $class,
                'data-action' => $action,
                'novalidate'];
            $this->set('formCreate', $arrFormCreate);
            $this->set('title', $title);
            $this->set('breadcrumb', $breadcrumb);
            $this->set('isRequerid', $requerid);

            //DISABLED FOR VIEW
            $this->set('formDisabled', $disabled);
            $this->set('formDisabledHomologa', $disabledHomologa);
            $this->set('formExtra', $extra);

        }
    }

    public function saveAuditLog($idpklog,$area,$operacao,$funcao){

        $this->loadModel("Auditoria");
        $ip = $_SERVER['REMOTE_ADDR'];
        $dados = array(
            'usuario_versao_id'    => $this->Auth->user('id'),
            'area_sistema'    => strtoupper($area),
            'operacao' => $operacao,
            'nome_funcao' => $funcao,
            'pk_log' =>$idpklog,
            'ip' =>  $ip,
            'data_inclusao'      => date("Y-m-d H:i:s")
        );
        $this->Auditoria->save($dados);

    }

}
