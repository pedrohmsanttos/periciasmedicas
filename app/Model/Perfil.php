<?php
App::import("Model", "BSModel");
App::import("Model", "Usuario");

class Perfil extends BSModel {

    public $useTable = 'perfil';
    public $displayField = "nome";
    public $hasAndBelongsToMany = array(
        'Funcionalidade' =>
        array(
            'className' => 'Funcionalidade',
            'joinTable' => 'perfil_funcionalidade',
            'foreignKey' => 'perfil_id',
            'associationForeignKey' => 'funcionalidade_id',
            'unique' => true
        )
    );
    public $validate = array(
        'nome' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'O campo Nome do Perfil é de Preenchimento Obrigatório.'
            ),
            'validarUnicidade' => array(
                'rule' => array('validarUnicidade'),
                'message' => 'Já existe um Perfil cadastrado com o nome informado.',
            ),
            'maxLength' => array(
                'rule' => array('maxLength', 50),
                'message' => 'O Nome não pode possuir mais de 50 caracteres.'
            )
        )
    );

    /**
     * Método para listar todos os Menus e Funcionalidades 
     */
    public function listarMenusFuncionalidades() {

        $condicoes = null;
        $condicoes['Funcionalidade.id_funcionalidade_pai'] = null;
        $filtro = new BSFilter();
        $filtro->setCondicoes($condicoes);
        $filtro->setTipo('all');
        $filtro->setCamposRetornadosString('id', 'nome');
        $filtro->setCamposOrdenados(['Funcionalidade.nome' => 'asc']);
        $funcionalidades = $this->Funcionalidade->listar($filtro);
        $i = 0;

        foreach ($funcionalidades as $key => $funcionalidade) {
            $condicoes = null;
            $condicoes['Funcionalidade.id_funcionalidade_pai'] = $funcionalidade['Funcionalidade']['id'];
            $filtroFuncionalidadesFilhas = new BSFilter();
            $filtroFuncionalidadesFilhas->setTipo('all');
            $filtroFuncionalidadesFilhas->setCondicoes($condicoes);
            $filtroFuncionalidadesFilhas->setCamposRetornadosString('id', 'nome');
            $filtroFuncionalidadesFilhas->setCamposOrdenados(['Funcionalidade.nome' => 'asc']);
            $funcionalidades[$key]['Funcionalidade']['funcionalidadesFilhas'] = $this->Funcionalidade->listar($filtroFuncionalidadesFilhas);
            $i++;
        }
        return $funcionalidades;
    }

    public function validarUnicidade() {

        $filtro = new BSFilter();
        $condicoes['LOWER(Perfil.nome)'] = trim(mb_strtolower(Util::removerEspacosExtras($this->data[$this->alias]['nome'])));

        if (isset($this->data[$this->alias]['id'])) {
            $condicoes['Perfil.id !='] = $this->data[$this->alias]['id'];
        }
        $filtro->setTipo('count');
        $filtro->setCondicoes($condicoes);
        $count = $this->listar($filtro);
        return $count == 0;
    }

    public function beforeSave($options = array()) {
        if(isset($this->data['Funcionalidade'])){
             $this->data['Funcionalidade'] = array_filter($this->data['Funcionalidade'], array($this, 'removerValoresDefaultCake'));
        }
        parent::beforeSave($options);
    }

    /**
     * Método para remover os valores default que o cake atribui aos checkbox
     * @param type $valor
     * @return type
     */
    function removerValoresDefaultCake($valor) {
        return ($valor != 0);
    }

    /**
     * Método para validar se existe algum usuário associado ao perfil antes de excluir o mesmo
     * @param type $id
     * @return type
     */
    public function validarExclusao($id) {
        $retorno = true;
        if (is_numeric($id)) {
            $filtro = new BSFilter();
            $usuario = new Usuario();
            $joins[] = array(
                'table' => 'usuario_perfil',
                'alias' => 'up',
                'type' => 'left',
                'conditions' => array('up.usuario_id = Usuario.id')
            );

            $condicoes['up.perfil_id'] = $id;
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
