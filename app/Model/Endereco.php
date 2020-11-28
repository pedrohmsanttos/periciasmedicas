<?php

App::import("Model", "BSModel");

class Endereco extends BSModel {

    public $useTable = 'endereco';
    public $belongsTo = array('Municipio');
    public $validate = array(
        'logradouro' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'O Campo Logradouro (Rua/Av./Praça, etc) é de preenchimento obrigatório.'
            ),
            'maxLength' => array(
                'rule' => array('maxLength', 400),
                'message' => 'O Logradouro (Rua/Av./Praça, etc) não pode possuir mais de 400 caracteres.'
            )
        ),
        'numero' => array(
            'maxLength' => array(
                'rule' => array('maxLength', 10),
                'message' => 'O Número não pode possuir mais de 10 caracteres.'
            ),
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'O Campo Número é de preenchimento obrigatório.'
            )
        ),
        'complemento' => array(
            'maxLength' => array(
                'rule' => array('maxLength', 255),
                'message' => 'O Complemento não pode possuir mais de 255 caracteres.'
            )
        ),
        'bairro' => array(
            'maxLength' => array(
                'rule' => array('maxLength', 255),
                'message' => 'O Bairro não pode possuir mais de 25 caracteres.'
            ),
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'O Campo Bairro é de preenchimento obrigatório.'
            )
        ),
        'cep' => array(
            'maxLength' => array(
                'rule' => array('maxLength', 10),
                'message' => 'O CEP não pode possuir mais de 10 caracteres.'
            ),
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'O Campo CEP é de preenchimento obrigatório.'
            )
        ),
        'municipio_id' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'O Campo Município é de preenchimento obrigatório.'
            )
        ),
        'estado_id' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'O Campo Estado é de preenchimento obrigatório.'
            )
        )
    );

    /**
     * Método para remover a mascara do cep
     */
    public function removerMascaraCEP() {
        if (isset($this->data[$this->alias]['cep'])) {
            $this->data[$this->alias]['cep'] = Util::removerMascaraCEP($this->data[$this->alias]['cep']);
        }
    }

    public function beforeValidate($options = array()) {
        $this->removerMascaraCEP();
    }

    public function beforeSave($options = array()) {
        $this->removerMascaraCEP();
        return parent::beforeSave();
    }

    public function parentNode() {
        return null;
    }

}
