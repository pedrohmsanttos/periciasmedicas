<?php

App::import("Model", "BSModel");

class UsuarioSenhaHist extends BSModel {

	public $useTable = 'usuario_senha_hist';

	public $belongsTo = array(
			'Usuario' => array('className' => 'Usuario', 'foreignKey' => 'usuario_id')
	);
}