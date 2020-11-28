<?php

App::import("Model", "BSModel");
class TipoIsencaoParecerTecnico extends BSModel {
    
    public $useTable = 'tipo_isencao';
    public $displayField = "nome";
}
