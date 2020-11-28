<?php

App::import("Model", "Endereco");

class EnderecoSimples extends Endereco {

    public $validate = array();

    public function getEndereco($id){
        $db = $this->getDataSource();
        $result = $db->fetchAll(
            'SELECT * from endereco where id = ?',
            array($id)
        );
        return (!empty($result))?$result[0][0]:array();
    }

}
