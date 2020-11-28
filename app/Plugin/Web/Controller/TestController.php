<?php

/**
 * Created by PhpStorm.
 * User: thyago.machado
 * Date: 02/06/2016
 * Time: 11:20
 */

App::uses('BSController', 'Web.Controller');
App::uses('HtmlHelper', 'View/Helper');

class TestController extends BSController{

    public function showAllError($key=''){
        if($key != 's3ss10n!') die;
	    @session_start();
        $_SESSION['show_all_error'] = 1;
        echo $_SESSION['show_all_error'];
	    die;
    }

    public function agendamento($id){
        $this->loadModel('Agendamento');
        pr($this->Agendamento->findById( $id));
        die;
    }
}