<?php

App::import("Model", "BSModel");
class TipoInvalidezFisica  extends BSModel {
    
    public $useTable = 'tipo_invalidez_fisica';
    public $displayField = "nome";
    
    public static $TEMPORARIA = 1;
    public static $DEFINITIVA = 2;
    public static $REMONTA_DATA_OBITO = 3;
    public static $REMONTA_MENORIDADE = 4;
    
}
