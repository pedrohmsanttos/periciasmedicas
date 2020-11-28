<?php

App::import("Lib", "Util");
// App::import("Plugin/Admin/Controller", "BSController");
App::import("Plugin/Web/Controller", "BSController");

class HomeController extends BSController {

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow();
    }
    
    
    public function index() {
        $this->layout = 'portal';
    }

    public function testeSatisfacao(){

    }
}
