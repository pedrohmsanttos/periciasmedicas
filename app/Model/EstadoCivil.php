<?php

App::import("Model", "BSModel");

class EstadoCivil extends BSModel {

    public $displayField = "nome";
    public $useTable = 'estado_civil';

    public function getEstadoCivilId($str){
        $db = $this->getDataSource();
        $result = $db->fetchAll(
            "SELECT id from estado_civil where upper(nome) = upper(?)",
            array($str)
        );
        return (!empty($result))?$result[0][0]['id']:null;
    }

}
