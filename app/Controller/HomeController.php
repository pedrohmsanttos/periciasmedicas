<?php

App::import("Lib", "Util");
App::uses ('AppController','Controller');

class HomeController extends AppController {

    public function beforeFilter() {
        parent::beforeFilter();
      //  $this->Auth->allow();
    }
    
    public function checarPermissoes(){
            return false;
    }
    
    public function index() {
        $this->layout = 'ajax';
		//return $this->redirect(array('action' => 'index', 'controller' => 'dashboard' , 'plugin' => 'admin'));
    }
}
