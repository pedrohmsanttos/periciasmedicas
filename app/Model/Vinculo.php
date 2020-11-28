<?php

App::import("Model", "BSModel");

class Vinculo extends BSModel {
    
    public $useTable = 'vinculo';
    public $displayField = "matricula";
    public $belongsTo = array(
        'OrgaoOrigem' => array('className' => 'OrgaoOrigem', 'foreignKey' => 'orgao_origem_id'),
        'Usuario' => array('className' => 'Usuario', 'foreignKey' => 'usuario_id'),
        'Cargo' => array('className' => 'Cargo', 'foreignKey' => 'cargo_id')
    );
    public $hasAndBelongsToMany = array(
        'Funcao' => array(
            'className' => 'Funcao',
            'joinTable' => 'vinculo_funcao',
            'foreignKey' => 'vinculo_id',
            'associationForeignKey' => 'funcao_id'
        ),
        'Lotacao' => array(
            'className' => 'Lotacao',
            'joinTable' => 'vinculo_lotacao',
            'foreignKey' => 'vinculo_id',
            'associationForeignKey' => 'lotacao_id'
        )
    );
    public $validate = array(
        'orgao_origem_id' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'O campo Órgão de Origem é obrigatório.'
            )
        ),
        'matricula' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'O campo Matrícula é obrigatório.'
            )
        ),
        'cargo_id' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'O campo Cargo é obrigatório.'
            )
        ),
        // 'data_admissao_servidor' => array(
        //     'required' => array(
        //         'rule' => 'notBlank',
        //         'message' => 'O campo Data de Admissão do Servidor é obrigatório.'
        //     )
        // ),

        'data_admissao_servidor' => array(
            'validaDataAdmissao' => array(
                'rule' => array('validaDataAdmissao'),
                'message' => 'O campo Data de Admissão do Servidor é obrigatório.'

            )
        )
    );

    /**
     * Verifica a unicidade de um vinculo
     * @param type $listSession
     * @param type $dataForm
     * @return boolean
     */
    public function verificaUnicidadeOrgao($listSession, $dataForm) {
        $retorno = true;
        if (!empty($listSession)) {
            foreach ($listSession as $row) {
                if (($row['Vinculo']['orgao_origem_id'] == $dataForm['orgao_origem_id']) && ($row['Vinculo']['matricula'] == $dataForm['matricula'])) {
                    $retorno = false;
                    break;
                }
            }
        }
        return $retorno;
    }

    /**
     * Obtem as informações do vinculo
     * @param array $condicoes
     * @return array
     */
    public function obterVinculo($condicoes) {
        $filtro = new BSFilter();
        $filtro->setCondicoes($condicoes);
        $filtro->setTipo('all');
        return $this->listar($filtro);
    }


    /**
     * Validando a data de admissão somente para registros novos
     * @param array data
     * @return array
     */
    public function validaDataAdmissao(){
       if(isset($this->data[$this->alias]['id']) && !empty($this->data[$this->alias]['id'])){
            return true;
        }else{
            if(empty($this->data[$this->alias]['data_admissao_servidor'])){
                return false;
            }else{
                return true;
            }
        }
    }

}
