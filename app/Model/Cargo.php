<?php

App::import("Model", "BSModel");
App::import("Model", "Vinculo");
class Cargo extends BSModel {

    public $useTable = 'cargo';
    public $displayField = "nome";
    public $validate = array(
        'nome' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'O Campo Nome é obrigatório.'
            ),
            'validarUnicidade' => array(
                'rule' => array('validarUnicidade'),
                'message' => 'Já existe um Cargo cadastrado com o Nome ou Sigla informados.',
            ),
            'maxLength' => array(
                'rule' => array('maxLength', 255),
                'message' => 'O Nome não pode possuir mais de 255 caracteres.'
            )
        ),
        'sigla' => array(
            'maxLength' => array(
                'rule' => array('maxLength', 50),
                'message' => 'A Sigla não pode possuir mais de 50 caracteres.'
            )
        )
    );

    public function validarUnicidade() {
        $filtro = new BSFilter();
        $condicoes = array();
        $condicoes['OR']['LOWER(Cargo.nome)'] = trim(mb_strtolower(Util::removerEspacosExtras($this->data[$this->alias]['nome'])));
        if ($this->data[$this->alias]['sigla'] != '') {
            $condicoes['OR']['LOWER(Cargo.sigla)'] = trim(mb_strtolower(Util::removerEspacosExtras($this->data[$this->alias]['sigla'])));
        }
        if (isset($this->data[$this->alias]['id'])) {
            $condicoes['Cargo.id != '] = $this->data[$this->alias]['id'];
        }
        $filtro->setTipo('count');
        $filtro->setCondicoes($condicoes);
        $count = $this->listar($filtro);
        return $count == 0;
    }

    public function validarExclusao($id){
        $retorno = true;
        if (is_numeric($id)) {
            $vinculo = new Vinculo();
            $filtro = new BSFilter();
            $condicoes['Vinculo.cargo_id'] = $id;
            $filtro->setCondicoes($condicoes);
            $filtro->setTipo('count');
            $count = $vinculo->listar($filtro);
            if ($count == 0) {
                $retorno = false;
            }
        }
        return $retorno;
    }
}
