<?php

App::import("Model", "BSModel");
class Requerimentos extends BSModel {

    var $useDbConfig = 'sim';

    public $useTable = 'requerimentos';

    public $hasOne = array(
        'Assuntos' => array(
            'classname' => 'Assuntos',
            'foreignKey' => false,
            'conditions' => array('Requerimentos.assunto = Assuntos.unassunto')
        ),'Pessoas' => array(
            'classname' => 'Pessoas',
            'foreignKey' => false,
            'conditions' => array('Requerimentos.requerente= Pessoas.unpessoa')
        ),
        'Servidores' => array(
            'classname' => 'Servidores',
            'foreignKey' => false,
            'conditions' => array('Requerimentos.requerente= Servidores.pessoa')
        )
    );


    public function carregaHistoricoMedico($cpf, $rg, $data_nascimento, $primeiro_nome, $arrMatricula = array()){
        $arrMatricula[] = 'ZZZZZZZZZZ'; $arrMatricula[] = 'ZZZZZZZZZZ';
        
        // $condicoes['or']['Pessoas.cpf'] =  Util::limpaDocumentos($cpf);
        // $condicoes['or']['Servidores.matricula in'] = $arrMatricula;

        $condi_data_nascimento['or'] = array(
            "(CAST (DATE (Pessoas.nascimento) AS VARCHAR ) = '" . $data_nascimento . "')" ,
            '(DATE (Pessoas.nascimento) ISNULL)'
        );


        $cpf_ou_matricula['or'] = array(
            array('Pessoas.cpf' => Util::limpaDocumentos($cpf)), 
            array('Servidores.matricula in' => $arrMatricula), 
        ); 

        $identidade['or'] = array(
            array('Pessoas.identidade' => $rg),
            array("trim(Pessoas.identidade) = ''"),
            array("trim(Pessoas.identidade) = '-'"),
            array('CAST(Pessoas.identidade AS NUMERIC) =' => '0')
        );

        $condicoes[] = $condi_data_nascimento;  
        $condicoes[] = $cpf_ou_matricula;
        $condicoes[] = $identidade;
        $condicoes[] = "(Pessoas.nome ILIKE '%$primeiro_nome%'" . ")" ;

        $filtroHM = new BSFilter();
        $filtroHM->setTipo("all");
        $filtroHM->setCondicoes($condicoes);
	    $filtroHM->setCamposOrdenados(array('datarequerimento'=>'desc'));
        $filtroHM->setLimitarItensAtivos(false);

        return $this->listar($filtroHM);

    }

    public function carregaHistoricoMedicoPessoa($id){
        $condicoes["Pessoas.unpessoa"] = $id; ;

        $filtroHM = new BSFilter();
        $filtroHM->setTipo("all");
        $filtroHM->setCondicoes($condicoes);
        $filtroHM->setCamposOrdenados(array('datarequerimento'=>'desc'));
        $filtroHM->setLimitarItensAtivos(false);

        return $this->listar($filtroHM);

    }


    public function carregaUsuarioLaudoParecer($id){

        $joins= array();
        $joins[] = array(
            'table' => 'pessoas',
            'alias' => 'pessoas',
            'type' => 'inner',
            'conditions' => array('pessoas.unpessoa  =Requerimentos.requerente')
        );


        $joins[] = array(
            'table' => 'servidores',
            'alias' => 'servidorpessoa',
            'type' => 'inner',
            'conditions' => array('servidorpessoa.pessoa = pessoas.unpessoa')
        );

        $joins[] = array(
            'table' => 'laudos',
            'alias' => 'laudosP',
            'type' => 'left',
            'conditions' => array('Requerimentos.unrequerimento = laudosP.requerimentolaudo')
        );

        $joins[] = array(
            'table' => 'assuntos',
            'alias' => 'assuntosP',
            'type' => 'left',
            'conditions' => array('assuntosP.unassunto = Requerimentos.assunto')
        );

        $joins[] = array(
            'table' => 'medicos',
            'alias' => 'medicos',
            'type' => 'left',
            'conditions' => array('medicos.crm = laudosP.medico')
        );


        $joins[] = array(
            'table' => 'servidores',
            'alias' => 'servidoresmedicos',
            'type' => 'left',
            'conditions' => array('servidoresmedicos.unservidor = medicos.servidor')
        );


        $joins[] = array(
            'table' => 'pessoas',
            'alias' => 'pessoamedico',
            'type' => 'left',
            'conditions' => array('pessoamedico.unpessoa = servidoresmedicos.pessoa')
        );


        $joins[] = array(
            'table' => 'licencasmedicas',
            'alias' => 'licencasmedicas',
            'type' => 'left',
            'conditions' => array('licencasmedicas.laudo = laudosP.unlaudo')
        );


        $joins[] = array(
            'table' => 'orgaos',
            'alias' => 'orgaospessoa',
            'type' => 'left',
            'conditions' => array('orgaospessoa.unorgao = servidorpessoa.orgao')
        );


        $condicoes = array();
        $condicoes['Requerimentos.unrequerimento'] = $id;

        $filtroHM = new BSFilter();
        $filtroHM->setTipo("all");
        $filtroHM->setCamposRetornadosString(
            'pessoas.nome',
            'pessoas.identidade',
            'servidorpessoa.matricula',
            'orgaospessoa.nome',
            'laudosP.datadespacho',
            'laudosP.conclusao',
            'Requerimentos.datarequerimento',
            'laudosP.laudono',
            'laudosP.deferido',
            'laudosP.diagnostico',
            'pessoamedico.nome',
            'medicos.crm',
            'licencasmedicas.renovacao',
            'licencasmedicas.dias',
            'licencasmedicas.artigos',
            'licencasmedicas.datainicial',
            'assuntosP.unassunto',
            'assuntosP.descricao'

        );
        $filtroHM->setJoins($joins);
        $filtroHM->setCondicoes($condicoes);
        $filtroHM->setLimitarItensAtivos(false);

        return $this->listar($filtroHM);

    }


    public function carregaCid($id){
        $joins[] = array(
            'table' => 'laudos',
            'alias' => 'laudos',
            'type' => 'inner',
            'conditions' => array('Requerimentos.unrequerimento = laudos.requerimentolaudo')
        );


        $joins[] = array(
            'table' => 'laudoscids',
            'alias' => 'laudoscids',
            'type' => 'inner',
            'conditions' => array('laudoscids.laudo = laudos.unlaudo')
        );


        $joins[] = array(
            'table' => 'cids',
            'alias' => 'cids',
            'type' => 'left',
            'conditions' => array('cids.uncid = laudoscids.cid')
        );


        $condicoes = array();
        $condicoes['Requerimentos.unrequerimento'] = $id;

        $filtroHM = new BSFilter();
        $filtroHM->setTipo("all");
        $filtroHM->setCamposRetornadosString(
            'laudoscids.adquiridoporepidemia',
            'cids.cid',
            'cids.enfermidade'

        );
        $filtroHM->setJoins($joins);
        $filtroHM->setCondicoes($condicoes);
        $filtroHM->setLimitarItensAtivos(false);

        return $this->listar($filtroHM);

    }
    public function carregaExigencia($id){
        $joins[] = array(
            'table' => 'laudos',
            'alias' => 'laudos',
            'type' => 'inner',
            'conditions' => array('Requerimentos.unrequerimento = laudos.requerimentolaudo')
        );


        $joins[] = array(
            'table' => 'exigenciaslaudos',
            'alias' => 'exigenciaslaudos',
            'type' => 'inner',
            'conditions' => array('exigenciaslaudos.laudonormalid = laudos.unlaudo')
        );



        $joins[] = array(
            'table' => 'exigenciasmedicas',
            'alias' => 'exigenciasmedicas',
            'type' => 'inner',
            'conditions' => array('exigenciasmedicas.unexigencia = exigenciaslaudos.exigenciaid')
        );


        $condicoes = array();
        $condicoes['Requerimentos.unrequerimento'] = $id;

        $filtroHM = new BSFilter();
        $filtroHM->setTipo("all");
        $filtroHM->setCamposRetornadosString(
            'exigenciasmedicas.exigencia',
            'exigenciaslaudos.datacumprimento',
            'exigenciaslaudos.laudonormalid'

        );
        $filtroHM->setJoins($joins);
        $filtroHM->setCondicoes($condicoes);
        $filtroHM->setLimitarItensAtivos(false);

        return $this->listar($filtroHM);

    }

}
