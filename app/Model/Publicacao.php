<?php
App::import("Model", "BSModel");
class Publicacao extends BSModel{

    public $useTable = 'publicacao';

    public $hasAndBelongsToMany = array(
        'Atendimento' => array(
            'className' => 'Atendimento',
            'joinTable' => 'publicacao_atendimento',
            'foreignKey' => 'publicacao_id',
            'associationForeignKey' => 'atendimento_id'
        )
    );

    public $belongsTo = array(
        'UsuarioVersao'   => array('className' => 'Usuario', 'foreignKey' => 'usuario_versao_id')
    );
}