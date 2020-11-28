<?php

App::import("Model", "BSModel");
App::import("Model", "Usuario");

class Funcao extends BSModel {

    public $useTable = 'funcao';
    public $displayField = "nome";
    public $validate = array(
        'nome' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'O Campo Nome é obrigatório.'
            ),
            'validarUnicidade' => array(
                'rule' => array('validarUnicidade'),
                'message' => 'Já existe uma Função cadastrada com o Nome ou Sigla informados.',
            ),
            'maxLength' => array(
                'rule' => array('maxLength', 255),
                'message' => 'O Nome não pode possuir mais de 255 caracteres.'
            )
        ),
        'sigla' => array(
            'maxLength' => array(
                'rule' => array('maxLength', 10),
                'message' => 'A Sigla não pode possuir mais de 10 caracteres.'
            )
        )
    );

    public function validarUnicidade() {

        $filtro = new BSFilter();
        $condicoes = array();
        $condicoes['OR']['LOWER(Funcao.nome)'] = trim(mb_strtolower(Util::removerEspacosExtras($this->data[$this->alias]['nome'])));
        if ($this->data[$this->alias]['sigla'] != '') {
            $condicoes['OR']['LOWER(Funcao.sigla)'] = trim(mb_strtolower(Util::removerEspacosExtras($this->data[$this->alias]['sigla'])));
        }
        if (isset($this->data[$this->alias]['id'])) {
            $condicoes['Funcao.id != '] = $this->data[$this->alias]['id'];
        }
        $filtro->setTipo('count');
        $filtro->setCondicoes($condicoes);
        $count = $this->listar($filtro);
        return $count == 0;
    }

    /**
     * Método para validar se existe algum Perito ou servidor associado a essa função
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
                'table' => 'vinculo_funcao',
                'alias' => 'vf',
                'type' => 'left',
                'conditions' => array('vf.vinculo_id = v.id' )
            );
            
            $condicoes['vf.funcao_id'] = $id;
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
