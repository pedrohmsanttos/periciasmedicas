<?php

App::import("Model", "BSModel");
App::import("Model", "Usuario");

class Lotacao extends BSModel {

    public $useTable = 'lotacao';
    public $displayField = "nome";
    public $belongsTo = array(
        'Endereco' => array('className' => 'Endereco', 'foreignKey' => 'endereco_id'),
        'OrgaoOrigem' => array('className' => 'OrgaoOrigem', 'foreignKey' => 'orgao_origem_id')
    );
    public $validate = array(
        'nome' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'O Campo Nome é de preenchimento obrigatório.'
            ),
            'validarUnicidade' => array(
                'rule' => array('validarUnicidade'),
                'message' => 'Já existe uma Lotação cadastrada com o nome informado.',
            ),
            'maxLength' => array(
                'rule' => array('maxLength', 255),
                'message' => 'O Nome não pode possuir mais de 255 caracteres.'
            )
        ),
        'orgao_origem_id' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'O Campo Órgão é de preenchimento obrigatório.'
            )
        )
    );

    public function consultarLotacoesOrgao($idOrgao) {
            $filtro = new BSFilter();
            $filtro->setTipo('list');
            $filtro->setCamposOrdenadosString('Lotacao.nome');
            $condicoes = ['Lotacao.orgao_origem_id' => $idOrgao];
            $filtro->setCondicoes($condicoes);
            return $this->listar($filtro);
    }
    
    public function validarUnicidade() {

        $filtro = new BSFilter();
        $condicoes['LOWER(Lotacao.nome)'] = trim(mb_strtolower(Util::removerEspacosExtras($this->data[$this->alias]['nome'])));
        $condicoes['Lotacao.orgao_origem_id'] = $this->data[$this->alias]['orgao_origem_id'];
        if (isset($this->data[$this->alias]['id'])) {
            $condicoes['Lotacao.id !='] = $this->data[$this->alias]['id'];
        }
        $filtro->setTipo('count');
        $filtro->setCondicoes($condicoes);
        $count = $this->listar($filtro);
        return $count == 0;
    }

    /**
     * Método para validar se existe algum Perito ou servidor associado a essa lotacao
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
                'conditions' => array('v.usuario_id = Usuario.id' )
            );
            
            $joins[] = array(
                'table' => 'vinculo_lotacao',
                'alias' => 'vl',
                'type' => 'left',
                'conditions' => array('vl.vinculo_id = v.id' )
            );
            
            $condicoes['vl.lotacao_id'] = $id;
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
