<?php

App::import("Model", "BSModel");
class RequisicaoDisponivel extends BSModel {
    
    public $useTable = 'requisicao_disponivel';
    public $displayField = "nome";
}
