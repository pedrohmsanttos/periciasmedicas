<?php

App::import("Model", "BSModel");

class VinculoFuncaoSimples extends BSModel {
    
    public $useTable = 'vinculo_funcao';


    public function insertSimples($vinculoId, $funcaoId){
        $data = array('vinculo_id'=> $vinculoId, 'funcao_id' => $funcaoId);
        $db = $this->getDataSource();
        $isOk = 0;
        $result = $db->fetchAll(
            'select * from vinculo_funcao where vinculo_id = :vinculo_id and funcao_id = :funcao_id',
            $data
        );
        if(empty($result)){
            $rs = $db->fetchAll(
                "insert into vinculo_funcao VALUES (:vinculo_id, :funcao_id)",
                $data
            );
            if($rs !== false) $isOk = 1;
        }else{
            $isOk = 1;
        }
        return $isOk;
    }
}
