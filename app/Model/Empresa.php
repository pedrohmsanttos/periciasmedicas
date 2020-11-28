<?php

App::import("Model", "BSModel");

class Empresa extends BSModel {
    
    public $useTable = 'empresa';
    public $displayField = "nome";
    public $belongsTo = array(
        'Endereco' => array('className' => 'Endereco', 'foreignKey' => 'endereco_id'),
    );
    public $validate = array(
        'nome' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'O Campo Nome é obrigatório.'
            ),
            'maxLength' => array(
                'rule' => array('maxLength', 255),
                'message' => 'O Nome não pode possuir mais de 255 caracteres.'
            )
        ),
        'cnpj' => array(
            'maxLength' => array(
                'rule' => array('maxLength', 14),
                'message' => 'O CNPJ não pode possuir mais de 14 caracteres.'
            ),
            'validarUnicidade' => array(
                'rule' => array('validarUnicidade'),
                'message' => 'Já existe uma Empresa cadastrada com o CNPJ informado.',
            ),
            'validarCNPJ' => array(
                'rule' => array(
                    'validarCNPJ'
                ),
                'allowEmpty' => true,
                'message' => 'CNPJ inválido.'
            )
        ),
        'nome_responsavel' => array(
            'maxLength' => array(
                'rule' => array('maxLength', 255),
                'message' => 'O Nome do responsável não pode possuir mais de 255 caracteres.'
            )
        ),
        'telefone_responsavel' => array(
            'maxLength' => array(
                'rule' => array('maxLength', 11),
                'message' => 'O Telefone do responsável não pode possuir mais de 11 caracteres.'
            )
        )
    );

    public function validarUnicidade() {

        $retorno = true;

        if (!empty($this->data[$this->alias]['cnpj'])) {
            $filtro = new BSFilter();
            $condicoes = array();
            $condicoes['Empresa.cnpj'] = $this->data[$this->alias]['cnpj'];
            if (isset($this->data[$this->alias]['id'])) {
                $condicoes['Empresa.id != '] = $this->data[$this->alias]['id'];
            }
            $filtro->setTipo('count');
            $filtro->setCondicoes($condicoes);
            $count = $this->listar($filtro);
            if($count > 0){
                $retorno = false;
            }
        }

        return $retorno;
    }

    /**
     * Método para verificar se o CNPJ é válido
     */
    public function validarCNPJ() {
        return Util::valida_cnpj($this->data[$this->alias]['cnpj']);
    }

    public function beforeValidate($options = array()) {
        $this->removerMascaraCnpj();
        $this->removerMascaraTelefone();
        return parent::beforeSave();
    }

    public function beforeSave($options = array()) {
        $this->removerMascaraCnpj();
        $this->removerMascaraTelefone();
        return parent::beforeSave();
    }

    /**
     * Método para remover os caracteres especiais do CNPJ informado pelo usuário
     */
    public function removerMascaraCnpj() {
        if (isset($this->data[$this->alias]['cnpj'])) {
            $this->data[$this->alias]['cnpj'] = Util::limpaDocumentos($this->data[$this->alias]['cnpj']);
        }
    }

    /**
     * Método para remover a mascara do telefone
     */
    public function removerMascaraTelefone() {
        if (isset($this->data[$this->alias]['telefone_responsavel'])) {
            $this->data[$this->alias]['telefone_responsavel'] = Util::removerMascaraTelefone($this->data[$this->alias]['telefone_responsavel']);
        }
    }

}
