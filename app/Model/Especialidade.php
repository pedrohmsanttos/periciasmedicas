<?php

App::import("Model", "BSModel");

class Especialidade extends BSModel {

    public $useTable = 'especialidade';
    public $displayField = "nome";
    
    public $validate = array(
        'nome' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'O Campo Nome é de preenchimento obrigatório.'
            ),
            'validarUnicidade' => array(
                'rule' => array('validarUnicidade'),
                'message' => 'Já existe uma Especialidade cadastrada com o nome informado.',
            ),
            'maxLength' => array(
                'rule' => array('maxLength', 255),
                'message' => 'O Nome não pode possuir mais de 255 caracteres.'
            )
        )
    );

    public function listarEspecialidades() {
        $filtro = new BSFilter();
        $filtro->setCamposOrdenados(['Especialidade.nome' => 'asc']);
        return $this->listar($filtro);
    }
    
    public function validarUnicidade() {

        $filtro = new BSFilter();
        $condicoes['LOWER(Especialidade.nome)'] = trim(mb_strtolower(Util::removerEspacosExtras($this->data[$this->alias]['nome'])));

        if (isset($this->data[$this->alias]['id'])) {
            $condicoes['Especialidade.id !='] = $this->data[$this->alias]['id'];
        }
        $filtro->setTipo('count');
        $filtro->setCondicoes($condicoes);
        $count = $this->listar($filtro);
        return $count == 0;
    }
    
    public function validarExclusao($id){
        $retorno = true;
        if (is_numeric($id)) {
            $sql = "SELECT especialidade_id FROM cid_especialidade WHERE especialidade_id = " . $id;
            $result = $this->query($sql);
            if (empty($result)) {
                $retorno = false;
            }
        } 
        return $retorno;
    }

}
