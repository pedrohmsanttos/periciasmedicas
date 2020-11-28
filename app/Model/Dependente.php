<?php

App::import("Model", "BSModel");

class Dependente extends BSModel {

    public $useTable = 'dependente';
    public $displayField = "nome";
    public $belongsTo = array(
        'Qualidade' => array('className' => 'Qualidade', 'foreignKey' => 'qualidade_id'),
        'Usuario' => array('className' => 'Usuario', 'foreignKey' => 'usuario_id'),
        'EnderecoDependente' => array('className' => 'EnderecoSimples', 'foreignKey' => 'endereco_id')
    );
    public $validate = array(
        'nome' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'O campo Nome é de preenchimento obrigatório'
            )
        ),
        'qualidade_id' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'O Campo Qualidade é de preenchimento obrigatório'
            )
        ),
        'cpf' => array(
            'validarCPF' => array(
                'rule' => array(
                    'validarCPF'
                ),
                'message' => 'CPF inválido.'
            )
        )
    );

    /**
     * Método para verificar se o CPF é válido
     */
    public function validarCPF() {
        $retorno = true;
        if(isset($this->data[$this->alias]['cpf']) && !empty($this->data[$this->alias]['cpf'])){
            $retorno = Util::validaCPF($this->data[$this->alias]['cpf']);
        }
        return $retorno;
    }

    public function beforeSave($options = array()) {
        //Limpando CPF
        if (isset($this->data[$this->alias]['cpf'])) {
            $this->data[$this->alias]['cpf'] = Util::limpaDocumentos($this->data[$this->alias]['cpf']);
        }
        return parent::beforeSave($options);
    }

    public function resolverDependenciasExclusao($id = null) {
        $filtro = new BSFilter();
        $filtro->setLimitarItensAtivos(false);
        $filtro->setTipo('all');
        $condicoes['Dependente.id'] = $id;
        $filtro->setCondicoes($condicoes);

        $dependente = $this->listar($filtro)[0];
        $endereco = new Endereco();
        $endereco->delete($dependente['Dependente']['endereco_id']);
    }
        
}
