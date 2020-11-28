<?php

App::import("Model", "BSModel");

class ParametroGeral extends BSModel {

    const DATA_PARECER_DEFAULT_PENSIONISTA_MAIOR_INVALIDO = "2000-01-15";

    public $useTable = 'parametro_geral';
    public $validate = array(
        'tempo_consulta' => array(
            'maxLength' => array(
                'rule' => array('maxLength', 3),
                'message' => 'O Campo Tempo da consulta para Licença Médica (minutos) não pode possuir mais de 3 caracteres.'
            ),
            'maiorZero' => array(
                'rule' => array('maiorZero'),
                'message' => 'O campo Tempo da consulta para Licença Médica (minutos) deve ser maior que zero.'
            )
        ),
        'maior_invalido_anterior' => array(
            'maxLength' => array(
                'rule' => array('maxLength', 8000),
                'message' => 'O Campo Tipologia Dependente Maior Inválido (Data do óbito do ex- segurado anterior a 15/01/2000) não pode possuir mais de 8000 caracteres.'
            )
        ),
        'maior_invalido_partir' => array(
            'maxLength' => array(
                'rule' => array('maxLength', 8000),
                'message' => 'O Campo Tipologia Dependente Maior Inválido (Data do óbito do ex- segurado a partir de 15/01/2000) não pode possuir mais de 8000 caracteres.'
            )
        ),
        'aposentadoria_invalidez_integral' => array(
            'maxLength' => array(
                'rule' => array('maxLength', 8000),
                'message' => 'O Campo Tipologia Aposentadoria por Invalidez Integral não pode possuir mais de 8000 caracteres.'
            )
        ),
        'aposentadoria_invalidez_proporcional' => array(
            'maxLength' => array(
                'rule' => array('maxLength', 8000),
                'message' => 'O Campo Tipologia Aposentadoria por Invalidez Proporcional não pode possuir mais de 8000 caracteres.'
            )
        ),
        'isencao_imposto_renda_temporaria' => array(
            'maxLength' => array(
                'rule' => array('maxLength', 8000),
                'message' => 'O Campo Tipologia Isenção de Imposto de Renda Temporária não pode possuir mais de 8000 caracteres.'
            )
        ),
        'isencao_imposto_renda_definitiva' => array(
            'maxLength' => array(
                'rule' => array('maxLength', 8000),
                'message' => 'O Campo Tipologia Isenção de Imposto de Renda Definitiva não pode possuir mais de 8000 caracteres.'
            )
        ),
        'isencao_contribuicao_previdenciaria_temporaria' => array(
            'maxLength' => array(
                'rule' => array('maxLength', 8000),
                'message' => 'O Campo Tipologia Isenção de Contribuição Previdenciária Temporária não pode possuir mais de 8000 caracteres.'
            )
        ),
        'isencao_contribuicao_previdenciaria_definitiva' => array(
            'maxLength' => array(
                'rule' => array('maxLength', 8000),
                'message' => 'O Campo Tipologia Isenção de Contribuição Previdenciária Definitiva não pode possuir mais de 8000 caracteres.'
            )
        ),
        'quantidade_historico_senha'=>array(
            'rule' => array('naturalNumber', true),
            'message' => 'O campo Tamanho do histórico de senha precisa ser um número'
        ),
        'dias_expiracao_senha' => array(
            'rule' => array('naturalNumber', false),
            'message' => 'O campo Quantidade de dias até senha expirar deve ser maior que zero'
        ),

        'limite_tam_arquivo_upload' => array(
            'maxLength' => array(
                'rule' => array('maxLength', 5),
                'message' => 'Campo "Limite de tamanho do arquivo" só pode ter 5 caracteres'
            ),

            'naturalNumber' => array(
                'rule' => array('naturalNumber', true),
                'message' => 'Campo "Limite de tamanho do arquivo" tem que ser númerico e maior ou igual a 0'
            ), 
        ),

        'limite_qtd_arquivo_upload' => array(
            'maxLength' => array(
                'rule' => array('maxLength', 3),
                'message' => 'Campo "Limite de quantidade de arquivos por usuário" só pode ter 3 caracteres'
            ),

            'naturalNumber' => array(
                'rule' => array('naturalNumber', true),
                'message' => 'Campo "Limite de quantidade de arquivos por usuário" tem que ser númerico e maior ou igual a 0'
            ), 
        )

    );
    
    public function buscarParecerTipologiaMaiorInvalido(){
        $filtro = new BSFilter();
        $condicoes['ParametroGeral.id'] = 1;
        $filtro->setCondicoes($condicoes);
        $filtro->setTipo('all');
        $filtro->setCamposRetornadosString("maior_invalido_anterior", "maior_invalido_partir");
        $parametroGeral = null;
        $parametros = $this->listar($filtro);
        if(count($parametros) > 0){
            $parametroGeral = $parametros[0];
        }
        return $parametroGeral;
    }

    public function maiorZero($options = array()) {
        $tempoConsulta = (int) $this->data[$this->alias]['tempo_consulta'];
        return $tempoConsulta > 0;
    }

    public function buscarIntervaloConsulta() {
        $filtro = new BSFilter();
        $condicoes['ParametroGeral.id'] = 1;
        $filtro->setCondicoes($condicoes);
        $filtro->setTipo('all');
        $filtro->setCamposRetornadosString("tempo_consulta");
        return $this->listar($filtro)[0]['ParametroGeral']['tempo_consulta'];
    }

    public function buscarEmailCopia() {
        $filtro = new BSFilter();
        $condicoes['ParametroGeral.id'] = 1;
        $filtro->setCondicoes($condicoes);
        $filtro->setTipo('all');
        $filtro->setCamposRetornadosString("email_senha_copia");
        return $this->listar($filtro)[0]['ParametroGeral']['email_senha_copia'];
    }

    public function getParametros(){
        $filtro = new BSFilter();
        $condicoes['ParametroGeral.id'] = 1;
        $filtro->setCondicoes($condicoes);
        $filtro->setTipo('all');
        return $this->listar($filtro)[0]['ParametroGeral'];
    }

}
