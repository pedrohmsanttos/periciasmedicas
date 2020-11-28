<?php

App::import("Model", "BSModel");

class TempUploadArquivo extends BSModel {
    
    public $useTable = 'temp_upload_arquivos';
    
    public $belongsTo = array(
        'Usuario' => array('className' => 'Usuario', 'foreignKey' => 'usuario_id'),
    );

    public function beforeDelete($cascade = false) {}

}