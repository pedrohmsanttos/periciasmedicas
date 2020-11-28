<?php

App::import("Model", "BSModel");

class TentativaLogin extends BSModel {
    
    public $useTable = 'tentativas_login';
    
    public $belongsTo = array(
        'Usuario' => array('className' => 'Usuario', 'foreignKey' => 'usuario_id'),
    );

}