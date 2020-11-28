<?php

App::import("Model", "BSModel");
class Servidores extends BSModel {

    var $useDbConfig = 'sim';
    public $useTable = 'servidores';


    public function listarServidores($data){
        try{

            $filtro = new BSFilter();
            $filtro->setLimitarItensAtivos(false);
            $filtro->setJoins(array(
                array(
                    'table' => 'pessoas',
                    'alias' => 'Pessoas',
                    'type' => 'inner',
                    'conditions' => array('Pessoas.unpessoa = Servidores.pessoa')
                )
            ));
            $filtro->setTipo('all');
            $filtro->setCamposRetornados([
                "Pessoas.unpessoa", "Pessoas.nome", "Pessoas.nascimento", "Pessoas.cpf", "Pessoas.identidade",
                "Servidores.matricula"
            ]);
            if(!isset($data['nome']))$data['nome'] = '';
            if(!isset($data['matricula']))$data['matricula'] = '';
            $cond = array(
                "(Pessoas.nome ilike '%".$data['nome']."%')",
                "(Servidores.matricula ilike '%".$data['matricula']."%')"
            );
            if($data['nascimento']){
                $cond["CAST(Pessoas.nascimento AS DATE) ="] = $data['nascimento'] ;
            }
            if(isset($data['identidade']) && $data['identidade']){
                $cond["CAST(Pessoas.identidade AS NUMERIC) ="] =$data['identidade'];
            }
            if($data['cpf']){
                $cond["Pessoas.cpf"] =$data['cpf'];
            }
            $filtro->setCondicoes($cond);
            $filtro->setRecursive(-1);
            $filtro->setCamposOrdenados(array("Pessoas.nome"));
            $filtro->setLimiteListagem(200);
            $list = $this->listar($filtro);



            return $list;
        }catch (Exception $e){
            //throw $e;
            return [];
        }
    }

    public function getById($id){
        try{

            $filtro = new BSFilter();
            $filtro->setLimitarItensAtivos(false);
            $filtro->setJoins(array(
                array(
                    'table' => 'pessoas',
                    'alias' => 'Pessoas',
                    'type' => 'inner',
                    'conditions' => array('Pessoas.unpessoa = Servidores.pessoa')
                )
            ));
            $filtro->setTipo('all');
            $filtro->setCamposRetornados([
                "Pessoas.unpessoa", "Pessoas.nome", "Servidores.matricula"
            ]);

            $cond["Pessoas.unpessoa"] =$id;

            $filtro->setCondicoes($cond);
            $filtro->setRecursive(-1);
            $filtro->setCamposOrdenados(array("Pessoas.nome"));
            $filtro->setLimiteListagem(1);
            $list = $this->listar($filtro);

            return $list;
        }catch (Exception $e){
            //throw $e;
            return [];
        }
    }
}
