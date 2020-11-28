<?php

App::import("Model", "BSModel");

class Feriado extends BSModel {

    public $useTable = 'feriado';
    public $displayField = "nome";
    public $validate = array(
        'nome' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'O Campo Nome é obrigatório.'
            )
        ),
        'data_feriado' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'O Campo Data do Feriado é obrigatório.'
            ),
            'unique' => array(
                'rule' => 'isUnique',
                'message' => 'Já existe uma feriado cadastrado com essa data.'
            )
        )
        
    );
}
