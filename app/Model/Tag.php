<?php
App::import("Model", "BSModel");
class Tag extends BSModel {
    public $useTable = 'tag';
    public $displayField = "nome";
}
