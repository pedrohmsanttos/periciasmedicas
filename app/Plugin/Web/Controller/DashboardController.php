<?php
// App::uses ('BSController','Admin.Controller');
App::uses ('BSController','Web.Controller');
class DashboardController extends BSController {

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('logout');
    }
    
    public function checarPermissoes(){
    	return false;
    }
    public function index() {
        /*
    	pr($this->Auth->user());
        pr($this->Session->read('perfil'));
        die;
        //*/
    }
}
