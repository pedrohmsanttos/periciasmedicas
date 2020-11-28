<?php

App::import("Model", "BSModel");

class AgendamentoControle extends BSModel {

    public $useTable = 'agendamento_controle';
    public $displayField = "data";

    public $belongsTo = array(
        'Perito' => array(
            'className' => 'Usuario', 
            'foreignKey' => 'perito_id'
        ),
        'Tipologia' => array(
            'className' => 'Tipologia', 
            'foreignKey' => 'tipologia_id'
        )
    );

}
