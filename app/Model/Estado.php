<?php

App::import("Model", "BSModel");

class Estado extends BSModel {

    public $displayField = "nome";
    public $useTable = 'estado';
    
    public function listarEstados() {
        $filtro = new BSFilter();
        $filtro->setCamposOrdenados(['Estado.nome' => 'asc']);
        return $this->listar($filtro);
    }

    public function getEstadoId($sigla){
        $db = $this->getDataSource();
        $result = $db->fetchAll(
            'SELECT id from estado where sigla = ?',
            array($sigla)
        );
        return (!empty($result))?$result[0][0]['id']:null;
    }
}
