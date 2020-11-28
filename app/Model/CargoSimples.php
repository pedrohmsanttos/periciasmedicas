<?php

App::import("Model", "BSModel");
App::import("Model", "Vinculo");
class CargoSimples extends BSModel {

    public $useTable = 'cargo';

    public function getCargo($arrFieldVal){
        $db = $this->getDataSource();
        $result = $db->fetchAll(
            'SELECT * from cargo where codigo_cargo_sad = :codigo_cargo_sad or upper(nome) = upper(:nome)',
            $arrFieldVal
        );
        return (!empty($result))?$result[0][0]:array();

    }
}
