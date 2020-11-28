<?php

App::import("Model", "BSModel");

class TipoUsuario extends BSModel {

    const PERITO_CREDENCIADO = 1;
    const PERITO_SERVIDOR = 2;
    const INTERNO = 3;
    const SERVIDOR_GESTOR = 4;
    
    public $displayField = "nome";
    public $useTable = 'tipo_usuario';

}
