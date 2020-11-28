<?php

App::import("Model", "BSModel");
App::import("Model", "Usuario");

class UnidadeAtendimento extends BSModel {

    public $useTable = 'unidade_atendimento';
    public $displayField = "nome";
    public $belongsTo = array(
        'Endereco' => array('className' => 'Endereco', 'foreignKey' => 'endereco_id'),
        'Usuario' => array('className' => 'Usuario', 'foreignKey' => 'responsavel_id'),
    );
    public $hasAndBelongsToMany = array(
        'MunicipioProximo' =>
        array(
            'className' => 'Municipio',
            'joinTable' => 'unidade_atendimento_municipio',
            'foreignKey' => 'unidade_atendimento_id',
            'associationForeignKey' => 'municipio_id'
        )
    );
    public $validate = array(
        'nome' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'O Campo Nome é de preenchimento obrigatório.'
            ),
            'validarUnicidade' => array(
                'rule' => array('validarUnicidade'),
                'message' => 'Já existe uma Unidade de Atendimento cadastrada com o nome informado.',
            ),
            'maxLength' => array(
                'rule' => array('maxLength', 255),
                'message' => 'O Campo Nome não pode possuir mais de 255 caracteres.'
            )
        ),
        'cnpj' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'O Campo CNPJ é de preenchimento obrigatório.'
            ),
            'maxLength' => array(
                'rule' => array('maxLength', 14),
                'message' => 'O CNPJ não pode possuir mais de 14 caracteres.'
            ),
            'validarUnicidade' => array(
                'rule' => array('validarUnicidadeCpnj'),
                'message' => 'Já existe uma Unidade de Atendimento cadastrada com o CNPJ informado.',
            ),
            'validarCNPJ' => array(
                'rule' => array(
                    'validarCNPJ'
                ),
                'message' => 'CNPJ inválido.'
            )
        ),
        'responsavel_nome' => array(
            'maxLength' => array(
                'rule' => array('maxLength', 255),
                'message' => 'O Campo Nome não pode possuir mais de 255 caracteres.'
            )
        )
    );

    public function beforeValidate($options = Array()) {
        $this->_validateMunicipiosProximos();
        $this->_validateCids();

        //REMOVENDO MASCARAS
        $this->removerMascaraCnpj();
        $this->removerMascaraTelefone();
        return true;
    }

    private function _validateMunicipiosProximos() {
        if (isset($this->data ['MunicipioProximo'])) {
            if ((isset($this->data ['MunicipioProximo'] ['MunicipioProximo']) && count($this->data ['MunicipioProximo'] ['MunicipioProximo']) < 1) || empty($this->data ['MunicipioProximo'] ['MunicipioProximo'])) {
                $this->invalidate('municipiosProximos', 'Selecione ao menos um município proxímo.');
            }
        }
    }

    private function _validateCids() {
        if (isset($this->data ['Cid'])) {
            if ((isset($this->data ['Cid'] ['Cid']) && count($this->data ['Cid'] ['Cid']) < 1) || empty($this->data ['Cid'] ['Cid'])) {
                $this->invalidate('cids', 'Selecione ao menos um CID.');
            }
        }
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

    /**
     * Método para verificar se o CNPJ é válido
     */
    public function validarCNPJ() {
        return Util::valida_cnpj($this->data[$this->alias]['cnpj']);
    }

    public function validarUnicidade() {

        $filtro = new BSFilter();
        $condicoes['LOWER(UnidadeAtendimento.nome)'] = trim(mb_strtolower(Util::removerEspacosExtras($this->data[$this->alias]['nome'])));

        if (isset($this->data[$this->alias]['id'])) {
            $condicoes['UnidadeAtendimento.id !='] = $this->data[$this->alias]['id'];
        }
        $filtro->setTipo('count');
        $filtro->setCondicoes($condicoes);
        $count = $this->listar($filtro);
        return $count == 0;
    }

    public function validarUnicidadeCpnj() {
        $retorno = true;

        if (!empty($this->data[$this->alias]['cnpj'])) {
            $filtro = new BSFilter();
            $condicoes = array();
            $condicoes[$this->alias . '.cnpj'] = $this->data[$this->alias]['cnpj'];
            if (isset($this->data[$this->alias]['id'])) {
                $condicoes[$this->alias . '.id != '] = $this->data[$this->alias]['id'];
            }
            $filtro->setTipo('count');
            $filtro->setCondicoes($condicoes);
            $count = $this->listar($filtro);
            if ($count > 0) {
                $retorno = false;
            }
        }

        return $retorno;
    }

    /**
     * Método para validar se existe alguma especialidade associada ao UnidadeAtendimento antes de excluir o mesmo
     * @param type $id
     * @return type
     */
    public function validarExclusao($id) {
        $retorno = true;
        if (is_numeric($id)) {
            $filtro = new BSFilter();
            $condicoes['Usuario.unidade_atendimento_id'] = $id;
            $filtro->setCondicoes($condicoes);
            $filtro->setTipo('count');
            $usuario = new Usuario();
            $count = $usuario->listar($filtro);
            
            $filtroAgenda = new BSFilter();
            $condicoesAgenda['AgendaAtendimento.unidade_atendimento_id'] = $id;
            $filtroAgenda->setCondicoes($condicoesAgenda);
            $filtroAgenda->setTipo('count');
            $agenda = new AgendaAtendimento();
            $countAgenda = $agenda->listar($filtroAgenda);
            if ($count == 0 && $countAgenda == 0 ) {
                $retorno = false;
            }
        }
        return $retorno;
    }
    
    public function obterUnidadesCid($cids, $tipologiaId) {
        $filtro = new BSFilter();

        $joins = array();
        $condicoes = array();
        if(!empty($cids) && count($cids) > 0 && !empty($cids[0])){
            $cids[] = 0;//só uma garantia que vai ter mais de um elemento
            $joins[] = array(
                'table' => 'unidade_atendimento_cid',
                'alias' => 'unidade_cid',
                'type' => 'left',
                'conditions' => array('unidade_cid.unidade_atendimento_id = UnidadeAtendimento.id')
            );
            $condicoes['unidade_cid.cid_id in'] = $cids;
        }


        $filtro->setJoins($joins);
        $filtro->setTipo('all');
        $filtro->setCamposRetornadosString('id', 'nome');
        $filtro->setCondicoes($condicoes);
        $this->unbindModel(
            array('hasAndBelongsToMany' => array('Cid'))
        );
        return $this->listar($filtro);
    }

    /*
        Consulta as Unidades de Atendimento que atendam em domicilio em um municipio especifico
    */
    public function obterUnidadesCidMunicipio($cids, $atendimentoDomicilio, $municipio, $municipiosProximos, $tipologiaId = '') {
        $filtro = new BSFilter();
        $condicoes = array();
        $joins= array();


        if(!empty($cids) && count($cids) > 0 && !empty($cids[0])){
            $cids[] = 0;//garantia de mais de um elemento
            $joins[] = array(
                'table' => 'unidade_atendimento_cid',
                'alias' => 'UnidadeAtendimentoCid',
                'type' => 'inner',
                'conditions' => array('UnidadeAtendimentoCid.unidade_atendimento_id = UnidadeAtendimento.id')
            );
            $condicoes['UnidadeAtendimentoCid.cid_id in'] = $cids;
        }
        $condicoes['Endereco.municipio_id'] = $municipio;

        if($atendimentoDomicilio == true){
            $condicoes['UnidadeAtendimento.atendimento_domicilio'] = true;
        }

        
        $filtro->setJoins($joins);
        $filtro->setTipo('all');
        $filtro->setCamposRetornadosString('id', 'nome');
        $filtro->setCamposOrdenados('UnidadeAtendimento.id');
        $filtro->setCondicoes($condicoes);


        $resultado = $this->listar($filtro);
        

        if($municipiosProximos == true){
            

            $joins2[] = array(
                'table' => 'unidade_atendimento_cid',
                'alias' => 'UnidadeAtendimentoCid',
                'type' => 'inner',
                'conditions' => array('UnidadeAtendimentoCid.unidade_atendimento_id = UnidadeAtendimento.id')
            );

            $joins2[] = array(
                'table' => 'unidade_atendimento_municipio',
                'alias' => 'UnidadeAtendimentoMunicipio',
                'type' => 'inner',
                'conditions' => array('UnidadeAtendimentoMunicipio.unidade_atendimento_id = UnidadeAtendimento.id')
            );

            $filtro2 = new BSFilter();
            $condicoes2 = array(); 

            // $condicoes2['UnidadeAtendimentoCid.cid_id'] = $cidId;
            if(isset($cids) && !empty($cids)){
                $condicoes2['UnidadeAtendimentoCid.cid_id in'] = $cids;
            }
            $condicoes2['UnidadeAtendimentoMunicipio.municipio_id'] = $municipio;
            $condicoes2['UnidadeAtendimento.atendimento_domicilio'] = true;
            $filtro2->setJoins($joins2);
            $filtro2->setTipo('all');
            $filtro2->setCamposRetornadosString('id', 'nome');
            $filtro2->setCamposOrdenados('UnidadeAtendimento.id');
            $filtro2->setCondicoes($condicoes2);
            $filtro2->setCamposAgrupados('UnidadeAtendimento.id');

            $resultado2 = $this->listar($filtro2);
            $resultado = array_merge($resultado, $resultado2);
        }

        $retorno = array();
        foreach ($resultado as $registro) {
            $retorno[$registro['UnidadeAtendimento']['id']] = $registro['UnidadeAtendimento']['nome'];
        }

        // return $this->listar($filtro);
        // pr($retorno);die;
        return $retorno;
    }
    
    public function obterUnidadesCidAtendimento($idAtendimento) {
        $filtro = new BSFilter();

        $joins[] = array(
            'table' => 'unidade_atendimento_cid',
            'alias' => 'unidade_cid',
            'type' => 'left',
            'conditions' => array('unidade_cid.unidade_atendimento_id = UnidadeAtendimento.id')
        );
        $joins[] = array(
            'table' => 'atendimento',
            'alias' => 'atendimento',
            'type' => 'left',
            'conditions' => array('atendimento.id = '.$idAtendimento)
        );
        $joins[] = array(
            'table' => 'agendamento',
            'alias' => 'agendamento',
            'type' => 'left',
            'conditions' => array('agendamento.id = atendimento.agendamento_id')
        );
        
        $condicoes['unidade_cid.cid_id = agendamento.cid_id and 1 = '] = 1;
        $filtro->setJoins($joins);
        $filtro->setTipo('all');
        $filtro->setCamposRetornadosString('id', 'nome', 'agendamento.cid_id');
        $filtro->setCondicoes($condicoes);
        return $this->listar($filtro);
    }

    public function listarMunicipiosProximos($idUnidade) {
        $filtro = new BSFilter();
        $condicoes['UnidadeAtendimento.id'] = $idUnidade;

        $joins = array();
        $joins[] = array(
            'table' => 'unidade_atendimento_municipio',
            'alias' => 'uam',
            'type' => 'left',
            'conditions' => array('uam.unidade_atendimento_id = UnidadeAtendimento.id')
        );
        $joins[] = array(
            'table' => 'municipio',
            'alias' => 'm',
            'type' => 'left',
            'conditions' => array('m.id = uam.municipio_id')
        );
        $filtro->setJoins($joins);
        $filtro->setCamposRetornadosString('m.id', 'm.nome');
        $filtro->setCamposOrdenados(['m.nome' => 'asc']);
        $filtro->setCondicoes($condicoes);
        return $this->listar($filtro);
    }

    public function getNomeById($id){
        $condicoes =array('UnidadeAtendimento.id' => $id);
        $filtro = new BSFilter();
        $filtro->setCondicoes($condicoes);
        $filtro->setTipo('list');
        $filtro->setCamposRetornadosString('nome');
        $result = $this->listar($filtro);
        return $result[$id];
    }

    public function listarUnidades() {
        $filtro = new BSFilter();
        $filtro->setCamposOrdenados(['UnidadeAtendimento.nome' => 'asc']);
        return $this->listar($filtro);
    }

    public function updateUnidadeAllCids($id){
        if (is_numeric($id) && !$this->hasAllCids($id)) {
            $sql = "delete from unidade_atendimento_cid  WHERE unidade_atendimento_id = " . $id;
            $result = $this->query($sql);
            $sql = "insert into unidade_atendimento_cid select $id as uaid, id from cid where cid.ativo = true";
            $result = $this->query($sql);
        }
    }

    public function hasAllCids($id){
        $sql = "select (select COUNT(*) from cid where cid.ativo = true) as cids_ativos,
          (select COUNT(*) from unidade_atendimento_cid where unidade_atendimento_id = $id) as cids_selecionados";
        $result = $this->query($sql);

        return ($result[0][0]['cids_ativos'] == $result[0][0]['cids_selecionados']);
    }
}
