<?php

App::import("Model", "BSModel");
class TipoSituacaoParecerTecnico extends BSModel {
    
    public $useTable = 'sit_parecer_tec';
    public $displayField = "nome";
    
    const EM_EXIGENCIA = 1;
    const INDEFERIDO = 2;
    const PROPORCIONAL = 3;
    const INTEGRAL = 4;
    const TEMPORARIO = 5;
    const DEFINITIVO = 6;
    const PROVISORIO = 7;
    const DEFERIDO = 8;
    const APTO = 9;
    const NAO_APTO = 10;
    const SE_ENQUADRA = 11;
    const NAO_SE_ENQUADRA = 12;
    const DEFINITIVA_FISICA = 13;
    const DEFINITIVA_MENTAL = 14;
    const TEMPORARIA_FISICA = 15;
    const TEMPORARIA_MENTAL = 16;
    
    public function listarSituacoes(){
        $filtro = new BSFilter();
        $filtro->setTipo('list');
        $filtro->setCamposOrdenadosString('TipoSituacaoParecerTecnico.nome');
        $condicoes['TipoSituacaoParecerTecnico.grupo = '] = 0;
        $filtro->setCondicoes($condicoes);
        return $this->listar($filtro);
    }
    
}
