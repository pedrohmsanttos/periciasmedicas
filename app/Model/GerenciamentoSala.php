<?php

App::import("Model", "BSModel");
App::import("Model", "Vinculo");

class GerenciamentoSala extends BSModel {

    /**
     * Nome da tabela
     * @var string 
     */
    public $useTable = 'gerenciamento_sala';

    /**
     * Variaveis que vem como padrão dentro de um find list.
     * @var string 
     */
    public $belongsTo = array(
        'UsuarioVersao' => array('className' => 'Usuario', 'foreignKey' => 'usuario_versao_id'),
        'UsuarioPerito' => array('className' => 'Usuario', 'foreignKey' => 'usuario_perito_id'),
        'UnidadeAtendimento' => array('className' => 'UnidadeAtendimento', 'foreignKey' => 'unidade_atendimento_id'));
    
    public $hasAndBelongsToMany = array(
        'Tipologia' => array(
            'className' => 'Tipologia',
            'joinTable' => 'ger_sala_tip',
            'foreignKey' => 'ger_sala_id',
            'associationForeignKey' => 'tipologia_id'
        )
    );
    
    public $validate = array(
        'sala' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'O Campo Sala é obrigatório.'
            )
        ),
        'usuario_perito_id' => array(
            'validarUnicidade' => array(
                'rule' => array('validarUnicidade'),
                'message' => 'Este perito já está alocado a uma sala.',
            )
        ),
        'tipologia_id' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'O Campo Tipologia é obrigatório.'
            )
        ),
        'unidade_atendimento_id' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'O Campo Unidade de Atendimento é obrigatório.'
            )
        )
    );

    public function beforeValidate($options = array()) {
        parent::beforeValidate($options);
    }

    public function validarUnicidade() {
        $filtro = new BSFilter();
        $condicoes['GerenciamentoSala.usuario_perito_id'] = $this->data[$this->alias]['usuario_perito_id'];
        $filtro->setTipo('count');
        $filtro->setCondicoes($condicoes);
        $count = $this->listar($filtro);
        return $count == 0;
    }

    public function get($conditions) {
        $filtro = new BSFilter();
        $filtro->setTipo('list');
        $filtro->setCondicoes($conditions);
        $sala = $this->listar($filtro);
        return $sala;
    }
    
    public function buscarSalaPerito($id, $arrTipologiasPermitidas){
        $this->Behaviors->load('Containable');
        $filtro = new BSFilter();
        $condicoes['GerenciamentoSala.usuario_perito_id'] = $id;

        $filtro->setCondicoes($condicoes);
        $filtro->setContain(['Tipologia.nome', 'Tipologia.id', 'UnidadeAtendimento.nome', "UnidadeAtendimento.id"]);
        $filtro->setTipo('all');
        $salas = $this->listar($filtro);
        $sala = null;
        if(!empty($salas)){
            $sala = $salas[0];
            $tipologias = array();
            foreach ($sala['Tipologia'] as $key => $tipologia) {
                if (in_array($tipologia['id'], $arrTipologiasPermitidas) !== false) {
                    $tipologias[] = $tipologia;
                }
            };
            $sala['Tipologia'] = $tipologias;
        }
        return $sala;
    }
}
