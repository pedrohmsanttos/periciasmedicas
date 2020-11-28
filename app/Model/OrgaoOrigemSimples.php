<?php

App::import("Model", "BSModel");
App::import("Model", "Usuario");

class OrgaoOrigemSimples extends BSModel {

    public $useTable = 'orgao_origem';

    public function getOrgaoOrigem($nome){
        $db = $this->getDataSource();
        $result = $db->fetchAll(
            'SELECT * from orgao_origem where upper(orgao_origem) = upper(?)',
            array($nome)
        );
        return (!empty($result))?$result[0][0]:array();
    }

}
