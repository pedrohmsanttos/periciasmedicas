<?php

App::import("Model", "BSModel");

class AgendamentoDisponibilidade extends BSModel {

    public $useTable = 'agendamento_disponibilidade';
    public $displayField = "id";

    public $belongsTo = array(
        'Perito' => array(
            'className' => 'Usuario', 
            'foreignKey' => 'perito_id'
        ),
        'Tipologia' => array(
            'className' => 'Tipologia', 
            'foreignKey' => 'tipologia_id'
        ),
        'UnidadeAtendimento' => array(
            'className' => 'UnidadeAtendimento', 
            'foreignKey' => 'unidade_atendimento_id'
        ),
        'Agendamento' => array(
            'className' => 'Agendamento', 
            'foreignKey' => 'agendamento_id'
        ),
        'Servidor' => array(
            'className' => 'Usuario', 
            'foreignKey' => 'usuario_servidor_id'
        ),
        'UsuarioAlteracao' => array(
            'className' => 'UsuarioAlteracao', 
            'foreignKey' => 'usuario_versao_id'
        )
    );
    
}
