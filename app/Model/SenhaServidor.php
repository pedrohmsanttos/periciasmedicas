<?php

App::import("Model", "BSModel");
class SenhaServidor extends BSModel {
 public $useDbConfig = 'contraCheque';
 public $useTable = 'senha_servidor';
   
   

   
    public function buscarSenhaServidor($cpf,$senha){
    	
    	$senhaUpper = strtoupper($senha);
    	$senhaLower = strtolower($senha);
    	$queryString = "
    	SELECT ss.cpf, ss.senha_atual FROM senha_servidor ss where 1 = 1
    	and ( 
    		ss.senha_atual = (SELECT crypt(?,substring(ss.senha_atual from 1 for 2))) or
    		ss.senha_atual = (SELECT crypt(?,substring(ss.senha_atual from 1 for 2))) or
    		ss.senha_atual = (SELECT crypt(?,substring(ss.senha_atual from 1 for 2))) or
    	( 
    	(ss.senha_atual is null or ss.senha_atual = '') and (
    	ss.senha_inicial = (SELECT crypt(?,substring(ss.senha_inicial from 1 for 2))) or
    	ss.senha_inicial = (SELECT crypt(?,substring(ss.senha_inicial from 1 for 2))) or
    	ss.senha_inicial = (SELECT crypt(?,substring(ss.senha_inicial from 1 for 2)))
    	) ) )
    	and ss.cpf = ?
    	limit 1";
        // pr($queryString );die;
        $teste = array($senha,$senhaUpper,$senhaLower,$senha,$senhaUpper,$senhaLower,$cpf);
    	//pr( $teste);
       // die;
    	return $this->query($queryString,array($senha,$senhaUpper,$senhaLower,$senha,$senhaUpper,$senhaLower,$cpf));
    }
    
    public function estaNaBaseContraCheque($cpf){
    	$condicoes['SenhaServidor.cpf'] =  Util::limpaDocumentos($cpf);
    	$filtroSenhaServidor = new BSFilter();
    	$filtroSenhaServidor->setTipo("count");
    	$filtroSenhaServidor->setLimitarItensAtivos(false);
    	$filtroSenhaServidor->setCondicoes($condicoes);
    	$total = $this->listar($filtroSenhaServidor);
        //pr($total);die;
    	
    	if($total>0){
    		return true;
    	}else{
    		return false;
    	}
    }

}
