<?php

App::import("Model", "BSModel");
App::import("Model", "Usuario");

class FuncaoSimples extends BSModel {

    public $useTable = 'funcao';

    public function getFuncao($arrFieldVal){
        $db = $this->getDataSource();
        $result = $db->fetchAll(
            'SELECT * from funcao where codigo_funcao_sad = :codigo_funcao_sad or  upper(nome) = upper(:nome)',
            $arrFieldVal
        );
        return (!empty($result))?$result[0][0]:array();

    }
}
