<?php

App::import("Model", "BSModel");

class Cid extends BSModel {

    public $useTable = 'cid';
    public $displayField = "nome";
    public $hasAndBelongsToMany = array(
        'Especialidade' =>
            array(
                'className' => 'Especialidade',
                'joinTable' => 'cid_especialidade',
                'foreignKey' => 'cid_id',
                'associationForeignKey' => 'especialidade_id'
            ),
        'UnidadeAtendimento' =>
            array(
                'className' => 'UnidadeAtendimento',
                'joinTable' => 'unidade_atendimento_cid',
                'foreignKey' => 'cid_id',
                'associationForeignKey' => 'unidade_atendimento_id'

            )
    );
    public $validate = array(
        'nome' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'O Campo CID é de preenchimento obrigatório.'
            ),
            'validarUnicidade' => array(
                'rule' => array('validarUnicidade'),
                'message' => 'Já existe um CID cadastrado com o \'CID\' informado.',
            ),
            'maxLength' => array(
                'rule' => array('maxLength', 255),
                'message' => 'O Campo CID não pode possuir mais de 255 caracteres.'
            )
        ),
        'nome_doenca' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'O Campo Nome da Doença é de preenchimento obrigatório.'
            ),
            'maxLength' => array(
                'rule' => array('maxLength', 255),
                'message' => 'O Campo Nome da Doença não pode possuir mais de 255 caracteres.'
            )
        ),
        'descricao' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'O Campo Descrição é de preenchimento obrigatório.'
            ),
            'maxLength' => array(
                'rule' => array('maxLength', 2000),
                'message' => 'O Campo Descrição não pode possuir mais de 2000 caracteres.'
            )
        )
    );

    public function validarUnicidade() {

        $filtro = new BSFilter();
        $condicoes['LOWER(Cid.nome)'] = trim(mb_strtolower(Util::removerEspacosExtras($this->data[$this->alias]['nome'])));

        if (isset($this->data[$this->alias]['id'])) {
            $condicoes['Cid.id !='] = $this->data[$this->alias]['id'];
        }
        $filtro->setTipo('count');
        $filtro->setCondicoes($condicoes);
        $count = $this->listar($filtro);
        return $count == 0;
    }

    /**
     * Método para validar se existe alguma especialidade associada ao cid antes de excluir o mesmo
     * @param type $id
     * @return type
     */
    public function validarExclusao($id) {
        $retorno = true;
        if (is_numeric($id)) {
            $sql = "SELECT cid_id FROM cid_especialidade WHERE cid_id = " . $id;
            $result = $this->query($sql);
            if (empty($result)) {
                $retorno = false;
            }
        }
        return $retorno;
    }

    /**
     * 
     * @param string $condicoes
     * @param string $camposRetornados
     * @param string $type
     * @return type
     */
    public function obterCid($condicoes, $camposRetornados = '', $type = 'all') {
        $filtro = new BSFilter();
        $filtro->setCondicoes($condicoes);
        $filtro->setCamposRetornados($camposRetornados);
        $filtro->setTipo($type);
        return $this->listar($filtro);
    }

    public function listarCids($ids = '') {
        $filtro = new BSFilter();
        $filtro->setCamposOrdenados(['Cid.nome' => 'asc']);
        return $this->listar($filtro);
    }

    public function listarCidAtendimento($idAtendimento){
        $filtro = new BSFilter();
        $filtro->setJoins(array(
            array(
                'table' => 'atendimento_cid',
                'alias' => 'AtendimentoCID',
                'type' => 'inner',
                'conditions' => array('AtendimentoCID.cid_id = Cid.id')
            )
        ));
        $filtro->setCondicoes(array("AtendimentoCID.atendimento_id" => $idAtendimento));
        $filtro->setCamposRetornados(["id", "nome", "nome_doenca"]);
        $filtro->setCamposOrdenados(['Cid.nome' => 'asc']);
        $filtro->setTipo('all');
        $filtro->setRecursive(-1);
        return $this->listar($filtro);
    }

    public function listarCidsIds($ids){
        try{
            if(!is_array($ids))return array();
            $ids[] = 0;
            $ids[] = 0;

            $filtro = new BSFilter();
            $filtro->setTipo('all');
            $filtro->setCamposRetornados(["Cid.id", "Cid.nome", "Cid.nome_doenca"]);
            $filtro->setCondicoes(array(
                'Cid.id in' => $ids
            ));
            $filtro->setRecursive(-1);
            $resultado= $this->listar($filtro);
            return $resultado;
        }catch (Exception $e){
            return [];
        }
    }

    public function listarCidAgendamento($id){
        try{
            // $this->loadModel("Cid");
            $filtro = new BSFilter();
            $filtro->setJoins(array(
                array(
                    'table' => 'agendamento_cid',
                    'alias' => 'AgendamentoCID',
                    'type' => 'inner',
                    'conditions' => array('AgendamentoCID.cid_id = Cid.id')
                )
            ));
            $filtro->setTipo('all');
            $filtro->setCamposRetornados(["Cid.id", "Cid.nome", "Cid.nome_doenca"]);
            $filtro->setCondicoes(array(
                'AgendamentoCID.agendamento_id' => $id
            ));
            $filtro->setRecursive(-1);
            $resultado= $this->listar($filtro);

            return $resultado;
        }catch (Exception $e){
            return [];
        }
    }

}
