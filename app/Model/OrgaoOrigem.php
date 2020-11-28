<?php

App::import("Model", "BSModel");
App::import("Model", "Usuario");

class OrgaoOrigem extends BSModel {

    public $displayField = "orgao_origem";
    public $useTable = 'orgao_origem';
    public $validate = array(
        'orgao_origem' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'O Campo Orgão de Origem é obrigatório.'
            ),
            'validarUnicidade' => array(
                'rule' => array('validarUnicidade'),
                'message' => 'Já existe um Orgão de Origem cadastrado com o Nome e Sigla informados.',
            ),
            'maxLength' => array(
                'rule' => array('maxLength', 255),
                'message' => 'O Nome não pode possuir mais de 255 caracteres.'
            )
        ),
        'email' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'O Campo E-mail é obrigatório.'
            ),
            'validarEmail' => array(
                'rule' => array('validarEmail'),
                'message' => 'Digite um E-mail válido.',
            )
        ),
        'sigla' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'O Campo Sigla é obrigatório.'
            ),
            'maxLength' => array(
                'rule' => array('maxLength', 10),
                'message' => 'A Sigla não pode possuir mais de 10 caracteres.'
            )
        ),
        'cnpj' => array(
            'maxLength' => array(
                'rule' => array('maxLength', 14),
                'message' => 'O CNPJ não pode possuir mais de 14 caracteres.'
            ),
            'validarCNPJ' => array(
                'rule' => array(
                    'validarCNPJ'
                ),
                'allowEmpty' => true,
                'message' => 'CNPJ inválido.'
            )
        )
    );
    
    /**
     * Informa se o e-mail é valido.
     * @return boolean
     */
    public function validarEmail() {
        return boolval(filter_var($this->data[$this->alias]['email'], FILTER_VALIDATE_EMAIL));
    }

    /**
     * Método para buscar o e-mail de um determinado Orgao
     */
    public function buscarEmailOrgao($idOrgao) {
        $filtro = new BSFilter();
        $filtro->setCamposRetornados("email");
        $filtro->setTipo('all');
        $condicoes['OrgaoOrigem.id'] = $idOrgao;
        $filtro->setCondicoes($condicoes);
        $orgaos = $this->listar($filtro);
        $orgao = null;
        if (!empty($orgaos)) {
            $orgao = $orgaos[0];
        }

        return $orgao;
    }

    /**
     * Método para verificar se o CNPJ é válido
     */
    public function validarCNPJ() {
        return Util::valida_cnpj($this->data[$this->alias]['cnpj']);
    }

    public function beforeValidate($options = array()) {
        $this->removerMascaraCnpj();
        return parent::beforeSave();
    }

    public function beforeSave($options = array()) {
        $this->removerMascaraCnpj();
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

    public function validarUnicidade() {

        $filtro = new BSFilter();
        $condicoes = array();
        $condicoes['LOWER(OrgaoOrigem.orgao_origem)'] = trim(mb_strtolower(Util::removerEspacosExtras($this->data[$this->alias]['orgao_origem'])));
        if ($this->data[$this->alias]['sigla'] != '') {
            $condicoes['LOWER(OrgaoOrigem.sigla)'] = trim(mb_strtolower(Util::removerEspacosExtras($this->data[$this->alias]['sigla'])));
        }
        if (isset($this->data[$this->alias]['id'])) {
            $condicoes['OrgaoOrigem.id != '] = $this->data[$this->alias]['id'];
        }
        $filtro->setTipo('count');
        $filtro->setCondicoes($condicoes);
        $count = $this->listar($filtro);
        return $count == 0;
    }

    public function listarOrgaos() {
        $filtro = new BSFilter();
        $filtro->setCamposOrdenados(['OrgaoOrigem.orgao_origem' => 'asc']);
        return $this->listar($filtro);
    }

    /**
     * Método para validar se existe algum Perito ou servidor associado a esse Orgão de Origem
     * @param type $id
     * @return type
     */
    public function validarExclusao($id) {
        $retorno = true;
        if (is_numeric($id)) {
            $filtro = new BSFilter();
            $usuario = new Usuario();
            $joins[] = array(
                'table' => 'vinculo',
                'alias' => 'v',
                'type' => 'left',
                'conditions' => array('v.usuario_id = Usuario.id')
            );
            $condicoes['v.ativo'] = true;
            $condicoes['v.orgao_origem_id'] = $id;
            $filtro->setJoins($joins);
            $filtro->setCondicoes($condicoes);
            $filtro->setTipo('count');
            $count = $usuario->listar($filtro);
            if ($count == 0) {
                $retorno = false;
            }
        }
        return $retorno;
    }

}
