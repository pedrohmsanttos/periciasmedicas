<?php
App::import("Model", "BSModel");
App::import("Model", "Vinculo");
class Atendido extends BSModel
{

    public $useTable = 'atendimento';
    public $displayField = "id";
}