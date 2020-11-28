<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
App::uses ( 'Controller', 'Controller' );
App::import("Lib", "Util");
/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
	
	public $helpers = array('Js','Html', 'Form');
	
	public $components = array (
			'Session',
			'Paginator',
			'RequestHandler',
			'Auth' => array (
					'loginRedirect' => array (
							'controller' => 'index',
							'action' => 'index' 
					),
					'logoutRedirect' => array (
							'controller' => 'usuario',
							'action' => 'login' 
					),
					'loginAction' => array (
							'controller' => 'usuario',
							'action' => 'login',
							'plugin' => null 
					),
					'authenticate' => array (
							'Form' => array (
									'passwordHasher' => 'Blowfish',
									'userModel' => 'Usuario',
									'fields' => array (
											'username' => 'login',
											'password' => 'senha' 
									) 
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
	public function beforeRender() {
		$this->set ( 'userData', $this->Auth->user () );
		$this->_setErrorLayout();
	}

	public function saveAuditLog($area,$acao){

		$this->loadModel("Auditoria");
		$ip = $_SERVER['REMOTE_ADDR'];
		$dados = array(
			'id_usuario'                    => $this->Auth->user (),
			'area_sistema'    => $area,
			'operacao' => $acao,
			'ip' =>  $ip,
			'data_inclusao'      => date("Y-m-d H:i:s")
		);
		$this->Auditoria->save($dados);

	}
}




function _setErrorLayout() {
	

    if($this->name == 'CakeError') {
	    echo "&nbsp;";
	    $this->layout = 'error';
    }
}
