<?php

App::import("Model", "BSModel");

class Municipio extends BSModel {

    public $displayField = "nome";
    public $useTable = 'municipio';
    public $belongsTo = array('Estado');

    public function listarMunicipios() {
        $filtro = new BSFilter();
        $filtro->setCamposOrdenados(['Municipio.nome' => 'asc']);
        return $this->listar($filtro);
    }

    /**
     * Método para listar todos os municípios associados a um determinado UF
     * @param integer $uf id da UF
     * @return lista de municípios
     */
     public function listarMunicipiosUF($estado) {
        $filtro = new BSFilter();
        $condicoes['Municipio.estado_id'] = $estado;
        $filtro->setCamposRetornadosString('id', 'nome');
        $filtro->setCamposOrdenados(['Municipio.nome' => 'asc']);
        $filtro->setCondicoes($condicoes);
        return $this->listar($filtro);
    }

    public function getMunicipioId($nome){
        $db = $this->getDataSource();
        $result = $db->fetchAll(
            'SELECT id from municipio where upper(nome) = upper(?)',
            array($nome)
        );
        return (!empty($result))?$result[0][0]['id']:null;
    }
    
}
