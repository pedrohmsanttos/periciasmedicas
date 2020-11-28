<?php

App::import("Model", "BSModel");
App::import("Model", "Usuario");

class Tipologia extends BSModel {

    public $useTable = 'tipologia';
    public $displayField = "nome";
    public $validate = array(
        'nome' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'O Campo Nome é de preenchimento obrigatório.'
            ),
            'validarUnicidade' => array(
                'rule' => array('validarUnicidade'),
                'message' => 'Já existe uma Tipologia cadastrada com o nome informado.',
            ),
            'maxLength' => array(
                'rule' => array('maxLength', 255),
                'message' => 'O Nome não pode possuir mais de 255 caracteres.'
            )
        ),
        'legislacao' => array(
            'maxLength' => array(
                'rule' => array('maxLength', 8000),
                'message' => 'O campo Legislação não pode possuir mais de 8000 caracteres.'
            )
        )
    );

    public function validarUnicidade() {

        $filtro = new BSFilter();
        $condicoes['LOWER(Tipologia.nome)'] = trim(mb_strtolower(Util::removerEspacosExtras($this->data[$this->alias]['nome'])));

        if (isset($this->data[$this->alias]['id'])) {
            $condicoes['Tipologia.id !='] = $this->data[$this->alias]['id'];
        }
        $filtro->setTipo('count');
        $filtro->setCondicoes($condicoes);
        $count = $this->listar($filtro);
        return $count == 0;
    }

    public function validarExclusao($id) {
        $retorno = true;
        if (is_numeric($id)) {

            $filtro = new BSFilter();
            $usuario = new Usuario();
            $joins[] = array(
                'table' => 'usuario_tipologia',
                'alias' => 'ut',
                'type' => 'left',
                'conditions' => array('ut.usuario_id = Usuario.id')
            );

            $condicoes['ut.tipologia_id'] = $id;
            $filtro->setJoins($joins);
            $filtro->setCondicoes($condicoes);
            $filtro->setTipo('count');
            $count = $usuario->listar($filtro);
            if ($count == 0 && !$this->isTipologiaDefault($id) ) {
                $retorno = false;
            }
        }
        return $retorno;
    }

    /**
     * Método para saber se a tipologia passada é uma das tipologias padrão do sistemas.
     * @param type $id
     */
    public function isTipologiaDefault($id) {
        $tipologiasCargaInicial = [TIPOLOGIA_LICENCA_MATERNIDADE, TIPOLOGIA_LICENCA_MATERNIDADE_ABORTO, TIPOLOGIA_LICENCA_NATIMORTO, TIPOLOGIA_APOSENTADORIA_INVALIDEZ,
            TIPOLOGIA_ISENCAO_CONTRIBUICAO_PREVIDENCIARIA, TIPOLOGIA_REVERSAO_APOSENTADORIA_INVALIDEZ, TIPOLOGIA_AVALIACAO_HABILITACAO_DEPENDENTES, TIPOLOGIA_PCD,
            TIPOLOGIA_ADMISSAO_PENSIONISTA_MAIOR_INVALIDO, TIPOLOGIA_INFORMACAO_SEGURO_COMPREENSIVO_HABITACIONAL, TIPOLOGIA_READAPTACAO_FUNCAO, TIPOLOGIA_REMANEJAMENTO_FUNCAO,
            TIPOLOGIA_RISCO_VIDA_INSALUBRIDADE, TIPOLOGIA_RECURSO_ADMINISTRATIVO, TIPOLOGIA_SINDICANCIA_INQUERITO_PAD, TIPOLOGIA_COMUNICACAO_DE_ACIDENTE_DE_TRABALHO,
            TIPOLOGIA_EXAME_PRE_ADMISSIONAL, TIPOLOGIA_LICENCA_ACOMPANHAMENTO_FAMILIAR, TIPOLOGIA_REMOCAO, TIPOLOGIA_LICENCA_MEDICA_TRATAMENTO_SAUDE,
            TIPOLOGIA_DESIGNACAO_DE_ASSISTENTE_TECNICO, TIPOLOGIA_APOSENTADORIA_ESPECIAL];
        return in_array($id, $tipologiasCargaInicial);
    }

    /**
     * 
     * @param string $condicoes
     * @param string $camposRetornados
     * @param string $type
     * @return type
     */
    public function obterTipologia($condicoes, $camposRetornados = '', $type = 'all') {
        $filtro = new BSFilter();

        $joins[] = array(
            'table' => 'tipologia_cid',
            'alias' => 'tc',
            'type' => 'left',
            'conditions' => array('tc.tipologia_id = Tipologia.id')
        );
        $joins[] = array(
            'table' => 'cid',
            'alias' => 'Cid',
            'type' => 'left',
            'conditions' => array('Cid.id = tc.cid_id')
        );
        $filtro->setJoins($joins);
        $filtro->setCondicoes($condicoes);
        $filtro->setCamposRetornados($camposRetornados);
        $filtro->setTipo($type);
        return $this->listar($filtro);
    }

    public function getNomeById($id){
        $condicoes =array('Tipologia.id' => $id);
        $filtro = new BSFilter();
        $filtro->setCondicoes($condicoes);
        $filtro->setTipo('list');
        $filtro->setCamposRetornadosString('nome');
        $result = $this->listar($filtro);
        return $result[$id];
    }

    public function getTipo($id){
        $tipo = 'não existe o tipo informado';
        switch($id){
            case TIPO_INQUERITO:
                $tipo = "Inquérito";
                break;
            case TIPO_SINDICANCIA:
                $tipo = "Sindicância";
                break;
            case TIPO_PROCESSO_ADMINISTRATIVO:
                $tipo = "PAD";
                break;
        }
        return $tipo;
    }

}
