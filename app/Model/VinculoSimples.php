<?php

App::import("Model", "BSModel");

class VinculoSimples extends BSModel {
    
    public $useTable = 'vinculo';

    public function getVinculo($arrFieldVal){
        $db = $this->getDataSource();
        $result = $db->fetchAll(
            'SELECT * from vinculo where
                usuario_id = :usuario_id and
                orgao_origem_id = :orgao_origem_id and
                cargo_id = :cargo_id and
                data_admissao_servidor = :data_admissao_servidor and
                matricula = :matricula
                ',
            $arrFieldVal
        );
        return (!empty($result))?$result[0][0]:array();
    }
}
