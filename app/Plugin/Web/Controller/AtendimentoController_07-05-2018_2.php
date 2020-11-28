<?php

/**
 * Description of AtendimentoController
 *
 * @author BankSystem Software Build
 */
// App::uses('BSController', 'Admin.Controller');
App::uses('BSController', 'Web.Controller');
App::uses('Model', 'TipoSituacaoParecerTecnico');
App::uses('Model', 'ParametroGeral');


class AtendimentoController extends BSController {
    //Documentacao de variaveis ajudam a IDE identificar o tipo da variavel quando a mesma é utilizada;
    /** @var Tipologia */
    public $Tipologia;

    /** @var  Agendamento */
    public $Agendamento;

    public $helpers = array('PForm');

    public $sessionJuntaPeritos = 'juntaPeritos';
    public $sessionNavegacaoDetalhamento = 'navegacaoDetalhamento';
    public $sessionDetalhamentoAtual = 'detalhamentoAtual';
    public $sessionPaginaAnterior = 'paginaAnterior';

    /**
     * Criada para nomear a sessão de chamada
     * @var string 
     */
    public $sessionChamarUsuario = 'chamarUsaurioAtendimento'; /*Tá errada a digitação aqui, não sei se corrigir dá merda em algo, comentando pra facilitar caso achem algum erro futuro*/

    public function beforeRender() {
        parent::beforeRender();
        if (in_array($this->params['action'], ['editar', 'visualizar', 'visualizarAtendimento'])) {
            $this->carregarListas();
            $this->informaTituloEdit();
        }


  
    }

    private function diaDaSemana($strData){
        $arrData = explode('/',$strData);
        $diasDaSemana = array('Domingo', 'Segunda-feira', 'Terça-feira', 'Quarta-feira','Quinta-feira', 'Sexta-feira' ,'Sábado');
        if(count($arrData) > 1){
            $numDiaSemana = date( "w", strtotime("{$arrData[2]}-{$arrData[1]}-{$arrData[0]}"));
            return $diasDaSemana[$numDiaSemana];
        }else{
            return '';
        }
    }

    private function adicionarDadosCidData($atendimento) {

        // e
    }

    public function consultarProcessos() {

        if ($this->request->is('GET')) {
            $limitConsulta = $this->request->query['limitConsulta'];
            $tipologia = $this->request->query['tipologia_id'];
            $status = $this->request->query['tipo_situacao'];
            $data_inicial = $this->request->query['data_inicial'];
            $data_final = $this->request->query['data_final'];
            $condicoes = null;

            $numProcesso = $this->request->query['numero_processo'];
            $cpf = $this->request->query['cpf'];
            $nome = $this->request->query['nome'];

            if(!empty($numProcesso)){
                $condicoes['Atendimento.id'] = $numProcesso;
            }
            if(!empty($nome)){
                $condicoes['Usuario.nome ILIKE '] = '%' . $nome . '%';
            }
            if(!empty($cpf)){
                $condicoes['Usuario.cpf'] = Util::limpaDocumentos($cpf);
            }

            if (!empty($tipologia)) {
                $condicoes['AgendamentoServidor.tipologia_id'] = $tipologia;
            }
            if (!empty($status)) {
                $condicoes['Atendimento.situacao_id'] = $status;
            }

            if (!empty($data_inicial)) {
                $condicoes['cast(Atendimento.data_inclusao as date) >= '] = Util::toDBDataHora($data_inicial);
            }

            if (!empty($data_final)) {
                $condicoes['cast(Atendimento.data_inclusao as date) <= '] = Util::toDBDataHora($data_final);
            }
            if ($this->Auth->user('tipo_usuario_id') == USUARIO_SERVIDOR) {
                $condicoes['Atendimento.usuario_id'] = $this->Auth->user('id');
            }
            $condicoes['Atendimento.id NOT IN (SELECT atedimento_pai_id FROM ATENDIMENTO where atedimento_pai_id is not null) and 1 = '] = 1;

            $filtro = new BSFilter();

            $filtro->setTipo('all');
            $this->Atendimento->Behaviors->load('Containable');
            $filtro->setLimiteConsulta($limitConsulta);
            $filtro->setContain(array('TipoSituacaoParecerTecnico'));
            $filtro->setCamposRetornadosString('Tipologia.nome', 'Atendimento.duracao', 'Atendimento.status_atendimento', 'Atendimento.data_inclusao', 'TipoSituacaoParecerTecnico.nome',
                'Atendimento.id', 'Atendimento.atedimento_pai_id', 'Usuario.nome', 'RecursoTipologia.nome');
            $joins = array();
            $joins[] = array(
                'table' => 'agendamento',
                'alias' => 'AgendamentoServidor',
                'type' => 'left',
                'conditions' => array('AgendamentoServidor.id = Atendimento.agendamento_id')
            );
            $joins[] = array(
                'table' => 'tipologia',
                'alias' => 'Tipologia',
                'type' => 'left',
                'conditions' => array('Tipologia.id = AgendamentoServidor.tipologia_id')
            );

            $joins[] = array(
                'table' => 'usuario',
                'alias' => 'Usuario',
                'type' => 'left',
                'conditions' => array('AgendamentoServidor.usuario_servidor_id = Usuario.id')
            );

            $joins[] = array(
                'table' => 'tipologia',
                'alias' => 'RecursoTipologia',
                'type' => 'left',
                'conditions' => array('RecursoTipologia.id = AgendamentoServidor.recurso_tipologia_id')
            );

            $currentFunction = $this->request->params['action']; //function corrente
            $currentController = $this->name; //Controller corrente
            $this->saveAuditLog(null,$currentController,'C',$currentFunction);

            $filtro->setJoins($joins);
            $filtro->setCondicoes($condicoes);
            $filtro->setCamposOrdenados(array('Atendimento.id' => 'desc'));
            $this->set('processos', $this->paginar($filtro));
            $this->set('limiteConsultaSelecionado', $limitConsulta);
        }
    }




public function consultarLaudoAtendimento() {

        if ($this->request->is('GET')) {
            $limitConsulta = $this->request->query['limitConsulta'];
            $tipologia = $this->request->query['tipologia_id'];
            $condicoes = array();

            $numProcesso = $this->request->query['numero_processo'];

            if(!empty($numProcesso)){
                $condicoes['Atendimento.id'] = $numProcesso;
            }

            if (!empty($tipologia)) {
                $condicoes['AgendamentoServidor.tipologia_id'] = $tipologia;
            }

            $condicoes['Atendimento.id NOT IN (SELECT atedimento_pai_id FROM ATENDIMENTO where atedimento_pai_id is not null) and 1 = '] = 1;

            $condicoes['Atendimento.status_atendimento = '] = 'Finalizado';
            
            // var_dump($condicoes['Atendimento.ativo ='],$condicoes);    

            //pr($condicoes);die;

            $filtro = new BSFilter();

            $filtro->setTipo('all');
            $this->Atendimento->Behaviors->load('Containable');
            $filtro->setLimiteConsulta($limitConsulta);
            $filtro->setContain(array('TipoSituacaoParecerTecnico'));
            $filtro->setCamposRetornadosString('Tipologia.nome', 'Atendimento.duracao', 'Atendimento.status_atendimento', 'Atendimento.data_inclusao', 'TipoSituacaoParecerTecnico.nome',
                'Atendimento.id', 'Atendimento.atedimento_pai_id', 'Usuario.nome', 'RecursoTipologia.nome');
            $joins = array();
            $joins[] = array(
                'table' => 'agendamento',
                'alias' => 'AgendamentoServidor',
                'type' => 'left',
                'conditions' => array('AgendamentoServidor.id = Atendimento.agendamento_id')
            );
            $joins[] = array(
                'table' => 'tipologia',
                'alias' => 'Tipologia',
                'type' => 'left',
                'conditions' => array('Tipologia.id = AgendamentoServidor.tipologia_id')
            );

            $joins[] = array(
                'table' => 'usuario',
                'alias' => 'Usuario',
                'type' => 'left',
                'conditions' => array('AgendamentoServidor.usuario_servidor_id = Usuario.id')
            );

            $joins[] = array(
                'table' => 'tipologia',
                'alias' => 'RecursoTipologia',
                'type' => 'left',
                'conditions' => array('RecursoTipologia.id = AgendamentoServidor.recurso_tipologia_id')
            );

            
            $currentFunction = $this->request->params['action']; //function corrente
            $currentController = $this->name; //Controller corrente
            $this->saveAuditLog(null,$currentController,'C',$currentFunction);

            $filtro->setJoins($joins);
            $filtro->setCondicoes($condicoes);
            // pr($condicoes);die;
            $filtro->setCamposOrdenados(array('Atendimento.id' => 'desc'));
            $this->set('processos', $this->paginar($filtro));
            $this->set('limiteConsultaSelecionado', $limitConsulta);
        }
    }



    public function index_processos() {
        $this->carregarListaTipologia();
        $this->listarSituacoesParecer();
        $this->carregarListasOrgaoOrigem();
    }

    public function index_laudo() {
        $this->carregarListaTipologia();
        $this->listarSituacoesParecer();
        $this->carregarListasOrgaoOrigem();
    }

    private function carregarListasOrgaoOrigem() {
        $this->loadModel('OrgaoOrigem');
        $filtro = new BSFilter();
        $filtro->setTipo('list');
        $filtro->setCamposOrdenadosString('OrgaoOrigem.orgao_origem');
        $orgaoOrigem = $this->OrgaoOrigem->listar($filtro);
        $this->set(compact('orgaoOrigem'));
    }

    public function consultarProcessosPublicacao() {

        if ($this->request->is('GET')) {

            $dataIni = $this->request->query['data_inicial'];
            $dataFim = $this->request->query['data_final'];
            $nomeUsuario = $this->request->query['nome'];
            $cpfUsuario = Util::limpaDocumentos($this->request->query['cpf']);
            $orgaoOrigem = $this->request->query['orgaoOrigem'];

            if(!empty($dataIni)){
                $dataIni = Util::toDBData($dataIni);
            }else{
                $dataIni = date('Y-m-d');
            }

            if(!empty($dataFim)){
                $dataFim = Util::toDBData($dataFim);
            }else{
                $dataFim = date('Y-m-d');
            }

            $currentFunction = $this->request->params['action']; //function corrente
            $currentController = $this->name; //Controller corrente
            $this->saveAuditLog(null,$currentController,'C',$currentFunction);

            $this->set('publicacoes', $this->listarProcessosPublicacao($dataIni, $dataFim, $nomeUsuario, $cpfUsuario, $orgaoOrigem));

            $this->set('data_inicial', Util::toBrDataHora($dataIni));
            $this->set('data_final', Util::toBrDataHora($dataFim));
        }
    }



    public function index_processos_publicacao() {
        $data_inicial="";
        $data_final = "";
        $this->set('data_inicial', $data_inicial);
        $this->set('data_final', $data_final );
        $this->carregarListasOrgaoOrigem();
    }

    public function enviarPublicacao(){

        $this->loadModel('Publicacao');
        $this->loadModel('ParametroGeral');
        $parametros = $this->ParametroGeral->getParametros();


        $dataSource = $this->Publicacao->getDataSource();
        $dataSource->begin();
        $dataIni = $this->request->data['Publicacao']['data_inicial'];
        $dataFim = $this->request->data['Publicacao']['data_final'];

        if(!empty($dataIni)){
            $dataIni = Util::toDBData($dataIni);
        }else{
            $dataIni = date('Y-m-d');
        }
        if(!empty($dataFim)){
            $dataFim = Util::toDBData($dataFim);
        }else{
            $dataFim = date('Y-m-d');
        }
        $this->request->data['Publicacao']['data_inicial'] = $dataIni;
        $this->request->data['Publicacao']['data_final'] = $dataFim;
        $this->request->data['Publicacao']['diretor_presidente'] = $parametros['diretor_presidente'];
        $this->request->data['Publicacao']['data_publicacao'] = date('Y-m-d H:i:s');
        $this->request->data['Publicacao']['usuario_versao_id'] =  CakeSession::read('Auth.User.id');


        if ($this->Publicacao->saveAll($this->request->data, array('deep' => true))) {

            $id = $this->Publicacao->id;
            $currentFunction = $this->request->params['action']; //function corrente
            $currentController = $this->name; //Controller corrente
            $this->saveAuditLog($id,$currentController,'I',$currentFunction);

            $this->Session->setFlash(__('objeto_salvo_sucesso', __('Publicação')), 'flash_success');
            $dataSource->commit();
        }
        $ids = $this->request->data['Atendimento']['Atendimento'];

        $this->set('publicacaoId',$this->Publicacao->id);
        $this->set('email_publicacao', $parametros['email_publicacao']);
        $this->set('publicacoes', $this->listarProcessosPublicacao($dataIni, $dataFim, true, $ids ));
    }




    public function reenviarPublicacao($id){


        if (!$id) {
            throw new NotFoundException(__('objeto_invalido', 'Publicação'));
        }

        $this->loadModel('Publicacao');
        $this->loadModel('ParametroGeral');
        $parametros = $this->ParametroGeral->getParametros();
        $this->set('email_publicacao', $parametros['email_publicacao']);
        $this->set('publicacaoId',$id);

    }

    public function listarSituacoesParecer() {
        $this->loadModel('TipoSituacaoParecerTecnico');
        $this->set('situacoes', $this->TipoSituacaoParecerTecnico->listarSituacoes());
        $this->set('limiteConsultaSelecionado', 10);
    }

    public function  getCargoOrgaoAgrupados($usuario_id){
        $this->loadModel("Vinculo");
        $filtro = new BSFilter();
        $filtro->setTipo('list');

        $filtro->setCamposRetornadosString(
            "Cargo.nome",
            "OrgaoOrigem.orgao_origem"
        );
        $joins = array();
        $joins[] = array(
            'table' => 'cargo',
            'alias' => 'Cargo',
            'type' => 'left',
            'conditions' => array('Cargo.id = Vinculo.cargo_id')
        );

        $joins[] = array(
            'table' => 'orgao_origem',
            'alias' => 'OrgaoOrigem',
            'type' => 'left',
            'conditions' => array('OrgaoOrigem.id = Vinculo.orgao_origem_id')
        );
        $filtro->setJoins($joins);

        $condicoes = array();
        $condicoes['Vinculo.usuario_id = '] = $usuario_id;
        $filtro->setCondicoes($condicoes);

        $cargoOrgao = $this->Vinculo->listar($filtro);
        $cargos = array_keys($cargoOrgao);

        return array("cargo"=>implode(", ", $cargos), "orgao"=>implode(", ", $cargoOrgao) );
    }

    /**
     * 
     */
    public function index() {
        // die('aqui');
        $this->Session->delete($this->sessionChamarUsuario);

        $tipoUsuarioLogado = $this->Auth->user()['tipo_usuario_id'];
        if ($tipoUsuarioLogado == USUARIO_SERVIDOR) {
            $this->Session->setFlash(__('erro_acesso_negado_atendimento_servidor'), 'flash_alert');
            // return $this->redirect(array('plugin' => 'admin', 'controller' => 'dashboard', 'action' => 'index'));
            return $this->redirect(array('plugin' => 'web', 'controller' => 'dashboard', 'action' => 'index'));
        }
        $this->loadModel("Agendamento");
        $this->carregarListaTipologia();
        $this->request->data['Agendamento']['data'] = date('d/m/Y');
        if ($tipoUsuarioLogado == USUARIO_PERITO_CREDENCIADO || $tipoUsuarioLogado == USUARIO_PERITO_SERVIDOR) {
            $this->loadModel("Usuario");
            $usuario = $this->Usuario->findById($this->Auth->user()['id']);

            $arrAgendaAtendimento = $usuario['AgendaAtendimento'];
            $arrTipologiaAgendaHoje = array();
            $diaSemanaHoje = $this->diaDaSemana(date("d/m/Y"));
            foreach($arrAgendaAtendimento as  $agendaAtendimento){
                if($diaSemanaHoje == $agendaAtendimento['AgendaAtendimento']['dia_semana']){
                    foreach($agendaAtendimento['Tipologia'] as $tipologia){
                        $arrTipologiaAgendaHoje[] = $tipologia['id'];
                    }
                }
            }
            $arrTipologiaAgendaHoje = array_unique($arrTipologiaAgendaHoje);

            $this->loadModel('Agendamento');
            $this->loadModel('GerenciamentoSala');
            $salaPerito = $this->GerenciamentoSala->buscarSalaPerito($this->Auth->user()['id'], $arrTipologiaAgendaHoje);

            if (!is_null($salaPerito)) {
                $this->set('unidadeAtendimento', $salaPerito['UnidadeAtendimento']['nome']);
                $tipologias = "";
                foreach ($salaPerito['Tipologia'] as $key => $tipologia) {
                    if ($key > 0) {
                        $tipologias = $tipologias . ", ";
                    };
                    $tipologias = $tipologias . $tipologia['nome'];
                };
                $this->set('tipologia', $tipologias);
            } else {
                $this->set('unidadeAtendimento', '');
                $this->set('tipologia', '');
            }

            $this->set('agendamentos', $this->Agendamento->listarAgendamentosConfimados($salaPerito, $usuario));
            $this->render('index_perito');
        }
        if (CakeSession::read('perfil') != PERFIL_ADMINISTADOR){
            $this->set('lockUnidade', $this->Auth->user()['unidade_atendimento_id']);
        }else{
            $this->set('lockUnidade', '');
        }
        $this->set('unidadeAtendimentoIdUser', $this->Auth->user('unidade_atendimento_id'));
        $this->carregarListaUnidadeAtendimento();
    }

    public function atenderProximo(){
        $this->loadModel("Agendamento");
        $this->loadModel("Usuario");
        $this->Session->delete("usuarioHistoricoAjax");


        $usuario = $this->Usuario->findById($this->Auth->user()['id']);

        $arrAgendaAtendimento = $usuario['AgendaAtendimento'];
        $arrTipologiaAgendaHoje = array();
        $diaSemanaHoje = $this->diaDaSemana(date("d/m/Y"));
        foreach($arrAgendaAtendimento as  $agendaAtendimento){
            if($diaSemanaHoje == $agendaAtendimento['AgendaAtendimento']['dia_semana']){
                foreach($agendaAtendimento['Tipologia'] as $tipologia){
                    if($tipologia['id'] != TIPOLOGIA_DESIGNACAO_DE_ASSISTENTE_TECNICO){
                        $arrTipologiaAgendaHoje[] = $tipologia['id'];
                    }
                }
            }
        }
        $arrTipologiaAgendaHoje = array_unique($arrTipologiaAgendaHoje);

        $this->loadModel('GerenciamentoSala');
        $idPerito = $this->Auth->user()['id'];
        $sala = $this->GerenciamentoSala->buscarSalaPerito($idPerito, $arrTipologiaAgendaHoje);

        $proximoAgendamento = null;
        if (!is_null($sala)) {
            $proximoAgendamento = $this->Agendamento->buscarProximoAgendamentoConfirmado($usuario, $sala['UnidadeAtendimento']['id'], $sala['Tipologia']);
            if(empty($proximoAgendamento)){
                $this->Session->setFlash(__('erro_nenhum_servidor_aguardando_atendimento'), 'flash_alert');
                return $this->redirect(array('controller' => 'Atendimento', 'action' => 'index'));
            }
        } else {
            //DESIGNACAO NÃO PRECISA DE SALA, NEM ATENDIMENTO DOMICILIAR EM ENDERECO ESPECIFICO
            $proximoAgendamento = $this->Agendamento->buscarProximoAgendamentoConfirmado($usuario);
            if(empty($proximoAgendamento)){
                $this->Session->setFlash('Você não está associado a nenhuma sala de atendimento ou não existem atendimentos disponíveis.', 'flash_alert');
                return $this->redirect(array('controller' => 'Atendimento', 'action' => 'index'));
            }
        }
        //debug($proximoAgendamento); die;

        if ($proximoAgendamento){
            $dataSource = $this->Atendimento->getDataSource();
            $dataSource->begin();

            $atendimento = array(
                'agendamento_id' => $proximoAgendamento['Agendamento']['id'],
                'usuario_id' => $proximoAgendamento['Agendamento']['usuario_servidor_id']
            );


            //procura pelo atendimento que gerou a exigência
            $achaAg = $proximoAgendamento['Agendamento']['id'];
            $pegaAg = $this->Agendamento->findById($achaAg);
            $exig = $pegaAg['Agendamento']['num_exigencia'];
            $check_exig = $pegaAg['Agendamento']['chkbx_exigencia'];


            
            //se encontrar e a checkbox tiver sido marcada, faz uma cópia dos campos que estavam preenchidos no atendimento que gerou a exigência
            if ((isset($exig)) && (!empty($exig)) && ($check_exig == '1')){
                $atendimentoOrig = $this->Atendimento->findById($exig);                

                $arrExig = array();

                 $atendimento['invalidez_fisica_id'] = (!empty( $atendimentoOrig['Atendimento']['invalidez_fisica_id']) ) ? $atendimentoOrig['Atendimento']['invalidez_fisica_id'] : "";
                $atendimento['incap_atos_vida_civil_id'] = (!empty( $atendimentoOrig['Atendimento']['incap_atos_vida_civil_id']) ) ? $atendimentoOrig['Atendimento']['incap_atos_vida_civil_id'] : "";
                $atendimento['historico_doenca_atual'] = (!empty( $atendimentoOrig['Atendimento']['historico_doenca_atual']) ) ? $atendimentoOrig['Atendimento']['historico_doenca_atual'] : "";
                $atendimento['antecedentes_pessoais_familiares'] = (!empty( $atendimentoOrig['Atendimento']['antecedentes_pessoais_familiares']) ) ? $atendimentoOrig['Atendimento']['antecedentes_pessoais_familiares'] : "";
                $atendimento['altura'] = (!empty( $atendimentoOrig['Atendimento']['altura']) ) ? $atendimentoOrig['Atendimento']['altura'] : "";
                $atendimento['peso'] = (!empty( $atendimentoOrig['Atendimento']['peso']) ) ? $atendimentoOrig['Atendimento']['peso'] : "";
                $atendimento['temperatura'] = (!empty( $atendimentoOrig['Atendimento']['temperatura']) ) ? $atendimentoOrig['Atendimento']['temperatura'] : "";
                $atendimento['faceis'] = (!empty( $atendimentoOrig['Atendimento']['faceis']) ) ? $atendimentoOrig['Atendimento']['faceis'] : "";
                $atendimento['estado_nutricao'] = (!empty( $atendimentoOrig['Atendimento']['estado_nutricao']) ) ? $atendimentoOrig['Atendimento']['estado_nutricao'] : "";
                $atendimento['mucoses_visiveis'] = (!empty( $atendimentoOrig['Atendimento']['mucoses_visiveis']) ) ? $atendimentoOrig['Atendimento']['mucoses_visiveis'] : "";
                $atendimento['atitude'] = (!empty( $atendimentoOrig['Atendimento']['atitude']) ) ? $atendimentoOrig['Atendimento']['atitude'] : "";
                $atendimento['tecido_celular_subcutaneo'] = (!empty( $atendimentoOrig['Atendimento']['tecido_celular_subcutaneo']) ) ? $atendimentoOrig['Atendimento']['tecido_celular_subcutaneo'] : "";
                $atendimento['pele_faneros'] = (!empty( $atendimentoOrig['Atendimento']['pele_faneros']) ) ? $atendimentoOrig['Atendimento']['pele_faneros'] : "";
                $atendimento['defeitos_fisicos'] = (!empty( $atendimentoOrig['Atendimento']['defeitos_fisicos']) ) ? $atendimentoOrig['Atendimento']['defeitos_fisicos'] : "";
                $atendimento['tensao_arterial'] = (!empty( $atendimentoOrig['Atendimento']['tensao_arterial']) ) ? $atendimentoOrig['Atendimento']['tensao_arterial'] : "";
                $atendimento['pulso'] = (!empty( $atendimentoOrig['Atendimento']['pulso']) ) ? $atendimentoOrig['Atendimento']['pulso'] : "";
                $atendimento['observacoes'] = (!empty( $atendimentoOrig['Atendimento']['observacoes']) ) ? $atendimentoOrig['Atendimento']['observacoes'] : "";
                $atendimento['observacoes_exigencias'] = (!empty( $atendimentoOrig['Atendimento']['observacoes_exigencias']) ) ? $atendimentoOrig['Atendimento']['observacoes_exigencias'] : "";
                $atendimento['observacoes_cid'] = (!empty( $atendimentoOrig['Atendimento']['observacoes_cid']) ) ? $atendimentoOrig['Atendimento']['observacoes_cid'] : "";
                $atendimento['procedimento_exames'] = (!empty( $atendimentoOrig['Atendimento']['procedimento_exames']) ) ? $atendimentoOrig['Atendimento']['procedimento_exames'] : "";
                $atendimento['aparelho_respiratorio'] = (!empty( $atendimentoOrig['Atendimento']['aparelho_respiratorio']) ) ? $atendimentoOrig['Atendimento']['aparelho_respiratorio'] : "";
                $atendimento['aparelho_digestivo'] = (!empty( $atendimentoOrig['Atendimento']['aparelho_digestivo']) ) ? $atendimentoOrig['Atendimento']['aparelho_digestivo'] : "";
                $atendimento['aparelho_linfo_hemopoetico'] = (!empty( $atendimentoOrig['Atendimento']['aparelho_linfo_hemopoetico']) ) ? $atendimentoOrig['Atendimento']['aparelho_linfo_hemopoetico'] : "";
                $atendimento['aparelho_genitor_urinario'] = (!empty( $atendimentoOrig['Atendimento']['aparelho_genitor_urinario']) ) ? $atendimentoOrig['Atendimento']['aparelho_genitor_urinario'] : "";
                $atendimento['aparelho_osteo_articular'] = (!empty( $atendimentoOrig['Atendimento']['aparelho_osteo_articular']) ) ? $atendimentoOrig['Atendimento']['aparelho_osteo_articular'] : "";
                $atendimento['exame_neuro_psiquiatrico'] = (!empty( $atendimentoOrig['Atendimento']['exame_neuro_psiquiatrico']) ) ? $atendimentoOrig['Atendimento']['exame_neuro_psiquiatrico'] : "";
                $atendimento['sensibilidade_geral_especial'] = (!empty( $atendimentoOrig['Atendimento']['sensibilidade_geral_especial']) ) ? $atendimentoOrig['Atendimento']['sensibilidade_geral_especial'] : "";
                $atendimento['exames_complementares'] = (!empty( $atendimentoOrig['Atendimento']['exames_complementares']) ) ? $atendimentoOrig['Atendimento']['exames_complementares'] : "";
                $atendimento['diagnostico'] = (!empty( $atendimentoOrig['Atendimento']['diagnostico']) ) ? $atendimentoOrig['Atendimento']['diagnostico'] : "";
                $atendimento['aposentado'] = (!empty( $atendimentoOrig['Atendimento']['aposentado']) ) ? $atendimentoOrig['Atendimento']['aposentado'] : "";
                $atendimento['pensionista'] = (!empty( $atendimentoOrig['Atendimento']['pensionista']) ) ? $atendimentoOrig['Atendimento']['pensionista'] : "";
                $atendimento['dependente_maior_invalido'] =(!empty( $atendimentoOrig['Atendimento']['dependente_maior_invalido']) ) ? $atendimentoOrig['Atendimento']['dependente_maior_invalido'] : "";
                $atendimento['patologia_remonta_lc'] = (!empty( $atendimentoOrig['Atendimento']['patologia_remonta_lc']) ) ? $atendimentoOrig['Atendimento']['patologia_remonta_lc'] : "";
                $atendimento['data_parecer'] = (!empty( $atendimentoOrig['Atendimento']['data_parecer']) ) ? $atendimentoOrig['Atendimento']['data_parecer'] : "";
                $atendimento['duracao'] = (!empty( $atendimentoOrig['Atendimento']['duracao']) ) ? $atendimentoOrig['Atendimento']['duracao'] : "";
                $atendimento['data_dependente_invalido'] = (!empty( $atendimentoOrig['Atendimento']['data_dependente_invalido']) ) ? $atendimentoOrig['Atendimento']['data_dependente_invalido'] : "";
                $atendimento['data_dependente_inc_atos_vida'] = (!empty( $atendimentoOrig['Atendimento']['data_dependente_inc_atos_vida']) ) ? $atendimentoOrig['Atendimento']['data_dependente_inc_atos_vida'] : "";
                $atendimento['data_insencao_temporaria'] = (!empty( $atendimentoOrig['Atendimento']['data_insencao_temporaria']) ) ? $atendimentoOrig['Atendimento']['data_insencao_temporaria'] : "";
                $atendimento['isencao_id'] = (!empty( $atendimentoOrig['Atendimento']['isencao_id']) ) ? $atendimentoOrig['Atendimento']['isencao_id'] : "";
                $atendimento['situacao_id'] = (!empty( $atendimentoOrig['Atendimento']['situacao_id']) ) ? $atendimentoOrig['Atendimento']['situacao_id'] : "";
                //Pegando o cid preenchido
                $atendimento['cid_id'] = (!empty( $atendimentoOrig['Cid']['0']['id']) ) ? $atendimentoOrig['Cid']['0']['id'] : "";
                $atendimento['parecer'] = (!empty( $atendimentoOrig['Atendimento']['parecer']) ) ? $atendimentoOrig['Atendimento']['parecer'] : "";
                $atendimento['id_municipio_atend_domicilio'] = (!empty( $atendimentoOrig['Atendimento']['id_municipio_atend_domicilio']) ) ? $atendimentoOrig['Atendimento']['id_municipio_atend_domicilio'] : "";
                $atendimento['questionamentos'] = (!empty( $atendimentoOrig['Atendimento']['questionamentos']) ) ? $atendimentoOrig['Atendimento']['questionamentos'] : "";
                $atendimento['numero_inspecao'] = (!empty( $atendimentoOrig['Atendimento']['numero_inspecao']) ) ? $atendimentoOrig['Atendimento']['numero_inspecao'] : "";
                $atendimento['necessario_inspecao'] = (!empty( $atendimentoOrig['Atendimento']['necessario_inspecao']) ) ? $atendimentoOrig['Atendimento']['necessario_inspecao'] : "";   
            }

            $atendimento = $this->Atendimento->save($atendimento);
            if(!$atendimento){
                debug($this->Atendimento->validationErrors);
                die;
            }

            $id = $this->Atendimento->id;
            $currentFunction = $this->request->params['action']; //function corrente
            $currentController = $this->name; //Controller corrente
            $this->saveAuditLog($id,$currentController,'I',$currentFunction);

            $proximoAgendamento['Agendamento']['status_agendamento'] = "Em Atendimento";

            if(!empty($sala['GerenciamentoSala']['sala'])){
                $proximoAgendamento['Agendamento']['sala'] = $sala['GerenciamentoSala']['sala'];
            }
            $proximoAgendamento['Agendamento']['data_hora'] = isset($proximoAgendamento['Agendamento']['data_hora'])?Util::toDBDataHora($proximoAgendamento['Agendamento']['data_hora']):"";
            $this->Agendamento->updateFields($proximoAgendamento['Agendamento'],
                array('id'=>$proximoAgendamento['Agendamento']['id']));

            $this->Session->write("PROXIMO_ATENDIMENTO", $atendimento['Atendimento']['id']);

            $dataSource->commit();
            return $this->redirect(array('controller' => 'Atendimento', 'action' => 'editar'));
        }
    }

    private function carregarListas() {
        $this->carregarListasSexos();
        $this->carregarListaSituacoes();
        $this->carregarListaIsencao();
        $this->carregarListaRequisicoes();
        $this->carregarListaAtendimentosServidor();
        $this->carregarListaTipoInvalidez();
        $this->carregarListaQualidades();
        $this->carregarListaModos();
    }

    private function carregarListaModos() {
        $arrayModos = [Atendimento::MODO_INICIAL => Atendimento::MODO_INICIAL, Atendimento::MODO_PRORROGACAO => Atendimento::MODO_PRORROGACAO];
        $this->set('modos', $arrayModos);
    }

    private function carregarListaTipoInvalidez() {
        $this->loadModel('TipoInvalidezFisica');
        $filtro = new BSFilter();
        $filtro->setTipo('list');
        $filtro->setCamposOrdenadosString('TipoInvalidezFisica.nome');
        $tiposInvalidezFisica = $this->TipoInvalidezFisica->listar($filtro);
        $this->set(compact('tiposInvalidezFisica'));
    }

    private function carregarListaIsencao() {
        $this->loadModel('TipoIsencaoParecerTecnico');
        $filtro = new BSFilter();
        $filtro->setTipo('list');
        $filtro->setCamposOrdenadosString('TipoIsencaoParecerTecnico.nome');
        $tipoIsecao = $this->TipoIsencaoParecerTecnico->listar($filtro);
        $this->set(compact('tipoIsecao'));
    }

    private function carregarListaAtendimentosServidor() {
        $this->loadModel('Atendimento');
        $filtro = new BSFilter();
        $idUsuarioServidor = $this->request->data['Atendimento']['usuario_id'];
        $idAtendimentoPai = $this->request->data['Atendimento']['atedimento_pai_id'];
        $condicoes['usuario_id'] = $idUsuarioServidor;
        $condicoes['Atendimento.id != '] = $this->request->data['Atendimento']['id'];
        $statusAtendimento = $this->request->data['Atendimento']['status_atendimento'];
        if ($statusAtendimento == "Pendente"  || $statusAtendimento=="Salvo") {
            $condicoes['OR']['Atendimento.id NOT IN (SELECT atedimento_pai_id FROM ATENDIMENTO WHERE usuario_id = ' . $idUsuarioServidor . ' and atedimento_pai_id is not null) and 1 = '] = 1;
            if ($idAtendimentoPai) {
                $condicoes['OR']['Atendimento.id'] = $idAtendimentoPai;
            }
        } else {
            if ($this->params['action'] === 'visualizarAtendimento') {
                $atendimentoPai = $this->request->data['Atendimento']['atedimento_pai_id'];
                if (isset($atendimentoPai) && $atendimentoPai) {
                    $condicoes['Atendimento.id'] = $atendimentoPai;
                }
            }
        }
        $filtro->setTipo('all');
        $filtro->setCondicoes($condicoes);
        $this->Atendimento->Behaviors->load('Containable');
        $filtro->setContain(array('TipoSituacaoParecerTecnico'));
        $filtro->setCamposRetornadosString('Tipologia.id', 'Tipologia.nome', 'Atendimento.status_atendimento',
            'Atendimento.data_inclusao', 'TipoSituacaoParecerTecnico.nome', 'Atendimento.id', 'Atendimento.atedimento_pai_id');
        $joins = array();
        $joins[] = array(
            'table' => 'agendamento',
            'alias' => 'AgendamentoServidor',
            'type' => 'left',
            'conditions' => array('AgendamentoServidor.id = Atendimento.agendamento_id')
        );
        $joins[] = array(
            'table' => 'tipologia',
            'alias' => 'Tipologia',
            'type' => 'left',
            'conditions' => array('Tipologia.id = AgendamentoServidor.tipologia_id')
        );
        $filtro->setJoins($joins);
        $filtro->setCamposOrdenados(array('Atendimento.data_inclusao'=>'desc'));
        $historicoLicencas = $this->Atendimento->listar($filtro);
        $this->set(compact('historicoLicencas'));
    }

    private function carregarListaSituacoes() {
        $this->loadModel('TipoSituacaoParecerTecnico');
        $filtro = new BSFilter();
        $filtro->setTipo('list');
        $tipologia = $this->request->data['Agendamento']['tipologia_id'];
        if($tipologia == TIPOLOGIA_RECURSO_ADMINISTRATIVO){
            $this->loadModel('Atendimento');
            $tipologia= $this->Atendimento->getTipologiaIdAtendimento($this->request->data['Agendamento']['numero_processo']);
        }
        $condicoes = array();
        $sitParTecnico = array();
        $sitParTecnico[]  = TipoSituacaoParecerTecnico::EM_EXIGENCIA;

        if (in_array($tipologia, array(TIPOLOGIA_REMOCAO, TIPOLOGIA_REMANEJAMENTO_FUNCAO, TIPOLOGIA_READAPTACAO_FUNCAO)))
        {
            $condicoes['TipoSituacaoParecerTecnico.id IN '] = array(TipoSituacaoParecerTecnico::EM_EXIGENCIA, TipoSituacaoParecerTecnico::INDEFERIDO,
                TipoSituacaoParecerTecnico::TEMPORARIO, TipoSituacaoParecerTecnico::DEFINITIVO);
            $sitParTecnico[] =TipoSituacaoParecerTecnico::INDEFERIDO;
            $sitParTecnico[] =TipoSituacaoParecerTecnico::TEMPORARIO;
            $sitParTecnico[] =TipoSituacaoParecerTecnico::DEFINITIVO;
            $condicoes['TipoSituacaoParecerTecnico.id IN '] = $sitParTecnico;
        }
        if ($tipologia == TIPOLOGIA_REVERSAO_APOSENTADORIA_INVALIDEZ)
        {
            $condicoes['TipoSituacaoParecerTecnico.id IN '] = array(TipoSituacaoParecerTecnico::EM_EXIGENCIA, TipoSituacaoParecerTecnico::INDEFERIDO,
                TipoSituacaoParecerTecnico::DEFERIDO);
            $sitParTecnico[] =TipoSituacaoParecerTecnico::INDEFERIDO;
            $sitParTecnico[] =TipoSituacaoParecerTecnico::DEFERIDO;
            $condicoes['TipoSituacaoParecerTecnico.id IN '] = $sitParTecnico;

        }

        if ($tipologia == TIPOLOGIA_COMUNICACAO_DE_ACIDENTE_DE_TRABALHO)
        {
            $sitParTecnico[] =TipoSituacaoParecerTecnico::SE_ENQUADRA;
            $sitParTecnico[] =TipoSituacaoParecerTecnico::NAO_SE_ENQUADRA;
            $sitParTecnico[] =TipoSituacaoParecerTecnico::EM_EXIGENCIA;
            $condicoes['TipoSituacaoParecerTecnico.id IN'] = $sitParTecnico;    
        }

        if ($tipologia == TIPOLOGIA_ISENCAO_CONTRIBUICAO_PREVIDENCIARIA)
        {
            $sitParTecnico[] =TipoSituacaoParecerTecnico::DEFINITIVO;
            $sitParTecnico[] =TipoSituacaoParecerTecnico::TEMPORARIO;
            $sitParTecnico[] =TipoSituacaoParecerTecnico::NAO_SE_ENQUADRA;
            $condicoes['TipoSituacaoParecerTecnico.id IN '] = $sitParTecnico;
        }

        if (in_array($tipologia, array(
                TIPOLOGIA_APOSENTADORIA_INVALIDEZ,
                TIPOLOGIA_PCD, TIPOLOGIA_INFORMACAO_SEGURO_COMPREENSIVO_HABITACIONAL, TIPOLOGIA_EXAME_PRE_ADMISSIONAL,
                TIPOLOGIA_RECURSO_ADMINISTRATIVO)))
        {
            $sitParTecnico[] =TipoSituacaoParecerTecnico::INDEFERIDO;
            $sitParTecnico[] =TipoSituacaoParecerTecnico::INTEGRAL;
            $sitParTecnico[] =TipoSituacaoParecerTecnico::PROPORCIONAL;
            $condicoes['TipoSituacaoParecerTecnico.id IN '] = $sitParTecnico;
        }
        if (in_array($tipologia, array(TIPOLOGIA_LICENCA_ACOMPANHAMENTO_FAMILIAR, TIPOLOGIA_LICENCA_MEDICA_TRATAMENTO_SAUDE)))
        {
            $condicoes['TipoSituacaoParecerTecnico.id IN '] = array(TipoSituacaoParecerTecnico::EM_EXIGENCIA, TipoSituacaoParecerTecnico::INDEFERIDO,
                TipoSituacaoParecerTecnico::DEFERIDO);

            $sitParTecnico[] =TipoSituacaoParecerTecnico::INDEFERIDO;
            $sitParTecnico[] =TipoSituacaoParecerTecnico::DEFERIDO;

            $condicoes['TipoSituacaoParecerTecnico.id IN '] = $sitParTecnico;
        }

        if(in_array($tipologia, array(TIPOLOGIA_LICENCA_MATERNIDADE, TIPOLOGIA_LICENCA_MATERNIDADE_ABORTO, TIPOLOGIA_LICENCA_NATIMORTO)))
        {
            $sitParTecnico[] =TipoSituacaoParecerTecnico::DEFERIDO;
            $condicoes['TipoSituacaoParecerTecnico.id IN '] = $sitParTecnico;
        }

        if (in_array($tipologia, array(TIPOLOGIA_ADMISSAO_PENSIONISTA_MAIOR_INVALIDO, TIPOLOGIA_AVALIACAO_HABILITACAO_DEPENDENTES)))
        {
            $condicoes['TipoSituacaoParecerTecnico.id IN '] =
                array(
                    TipoSituacaoParecerTecnico::SE_ENQUADRA,
                    TipoSituacaoParecerTecnico::EM_EXIGENCIA,
                    TipoSituacaoParecerTecnico::NAO_SE_ENQUADRA);
        }

        if ($tipologia == TIPOLOGIA_EXAME_PRE_ADMISSIONAL) {
            $condicoes['TipoSituacaoParecerTecnico.id IN '] = array(TipoSituacaoParecerTecnico::DEFERIDO, TipoSituacaoParecerTecnico::INDEFERIDO);
        }

        if( $tipologia == TIPOLOGIA_APOSENTADORIA_ESPECIAL) {
            $sitParTecnico[] =TipoSituacaoParecerTecnico::SE_ENQUADRA;
            $sitParTecnico[] =TipoSituacaoParecerTecnico::NAO_SE_ENQUADRA;
            $sitPartecnico[] =TipoSituacaoParecerTecnico::EM_EXIGENCIA;
            $condicoes['TipoSituacaoParecerTecnico.id IN '] = $sitParTecnico;
        }

        if($tipologia == TIPOLOGIA_RISCO_VIDA_INSALUBRIDADE){
            $sitParTecnico[] =TipoSituacaoParecerTecnico::DEFERIDO;
            $sitParTecnico[] =TipoSituacaoParecerTecnico::INDEFERIDO;
            $sitPartecnico[] =TipoSituacaoParecerTecnico::EM_EXIGENCIA;
            $condicoes['TipoSituacaoParecerTecnico.id IN '] = $sitParTecnico;
        }

        if($tipologia == TIPOLOGIA_SINDICANCIA_INQUERITO_PAD){
            $condicoes['TipoSituacaoParecerTecnico.id'] = TipoSituacaoParecerTecnico::EM_EXIGENCIA;
        }

        $filtro->setCondicoes($condicoes);
        $filtro->setCamposOrdenadosString('TipoSituacaoParecerTecnico.nome');
        $situacoes = $this->TipoSituacaoParecerTecnico->listar($filtro);
        $this->set(compact('situacoes'));

    }

    private function carregarListaRequisicoes() {
        $this->loadModel('RequisicaoDisponivel');
        $filtro = new BSFilter();
        $filtro->setTipo('list');
        $filtro->setCamposOrdenadosString('RequisicaoDisponivel.nome');
        $requisicoes = $this->RequisicaoDisponivel->listar($filtro);
        $this->set(compact('requisicoes'));
    }

    private function carregarListaUnidadeAtendimento() {
        $this->loadModel('UnidadeAtendimento');
        $filtro = new BSFilter();
        $filtro->setTipo('list');
        $filtro->setCamposOrdenadosString('UnidadeAtendimento.nome');
        $this->UnidadeAtendimento->unbindModel(
            array('hasAndBelongsToMany' => array('Cid'))
        );
        $unidadeAtendimento = $this->UnidadeAtendimento->listar($filtro);
        $this->set(compact('unidadeAtendimento'));
    }

    private function carregarListaAtendimentos($idAgendamento = null) {
        $filtro = new BSFilter();
        $filtro->setTipo('all');
       $filtro->setContain(array('TipoUsuario'));
        $condicoes = array();
        $condicoes['Atendimento.agendamento_id'] = $idAgendamento;
        $filtro->setCondicoes($condicoes);
        return $this->Atendimento->listar($filtro);
    }

    private function carregarListasSexos() {
        $this->loadModel('Sexo');
        $filtro = new BSFilter();
        $filtro->setTipo('list');
        $filtro->setCamposOrdenadosString('Sexo.nome');
        $sexo = $this->Sexo->listar($filtro);
        $this->set(compact('sexo'));
    }

    private function montarArrayAtendimento() {
        $peritos = ($this->Session->read($this->sessionJuntaPeritos)) ? $this->Session->read($this->sessionJuntaPeritos) : [];
        $this->request->data['Atendimento']['Perito'] = array();
        foreach ($peritos as $perito) {
            $this->request->data['Atendimento']['Perito'][] = $perito['Perito']['id'];
        }
        $this->Session->write($this->sessionJuntaPeritos, $peritos);
    }

    private function carregarSessoes($dataAtendimento = array()) {
        if (empty($dataAtendimento)) {
            //Carrega as funções registradas na sessão

            if (!empty($this->Session->read($this->sessionJuntaPeritos))) {
                $this->set('peritos', $this->Session->read($this->sessionJuntaPeritos));
            }
        } else {

            $peritos = $dataAtendimento['Perito'];
            // pr($peritos);die;
            $arraySessao = array();
            foreach ($peritos as $perito) {
                $arraySessao[$perito['id']]['Perito'] = array('id' => $perito['id'], 'nome' => $perito['nome'], 'numero_registro' => $perito['numero_registro']);
            }

            //Carrega os vinculos de acordo com o parametro
            $this->Session->write($this->sessionJuntaPeritos, $arraySessao);
            // pr($arraySessao);die;
            $this->set('peritos', $this->Session->read($this->sessionJuntaPeritos));
            


        }
    }

    public function deletarPeritoSession() {
        $retorno = false;
        $id = $this->request->data['id'];
        $peritos = $this->Session->read($this->sessionJuntaPeritos);
        if (!is_null($peritos)) {
            unset($id);
            $this->Session->write($this->sessionJuntaPeritos, $peritos);
            $retorno = true;
        }
        echo json_encode($retorno);
        die;
    }

    public function deletar($id){
        // if (CakeSession::read('Auth.User.cpf') != '00000000000') {
        if (CakeSession::read('Auth.User.admin') != 'true') {
            $this->Session->setFlash('Não é possível excluir um atendimento.', 'flash_alert');
            $this->redirect(array('controller' => 'Atendimento', 'action' => 'index_processos'));
        }
        if(empty($id))throw  new Exception("Valor inválido");

        // die($id);

        $this->loadModel('Atendimento');
        $this->loadModel('Agendamento');


        $dados = $this->Atendimento->findById($id);

        $agendamento = $dados['Agendamento'];
        $agendamento['sala'] = '';
        $agendamento['status_agendamento'] = 'Aguardando Atendimento';
        $agendamento['agendamento_encaminhado_sala'] = '';


        $this->loadModel("Cid");
        $cids = $this->arrCidAgendamento($agendamento['id']);
        if(isset($agendamento['cid_id']))$cids[] =$agendamento['cid_id'];
        $agendamento['Cids'] = array_unique($cids);

        if($this->Agendamento->save($agendamento, array('validate' => false))){
            $atendimento = $dados['Atendimento'];
            $idSolicitante = $this->request->data['idSolicitante'];
            $motivoExclusao= $this->request->data['motivo'];
            $db = $this->Atendimento->getDataSource();
            $db->fetchAll("update atendimento set solicitante_exclusao = ?, motivo_exclusao = ? where id = ?",
                array(  $idSolicitante, $motivoExclusao, $atendimento['id']));

            $this->Atendimento->delete($id);

            $currentController = $this->name; //Controller corrente
            $this->saveAuditLog($id,$currentController,'E',"deletar");

            echo 1;
        }else{
            $error = json_encode($this->Agendamento->validationErrors);
            throw new Exception($error);
        };
        die;
    }

    public function voltarAtendimentoAnterior() {
        $sessaoPaginaAnterior = $this->Session->read($this->sessionPaginaAnterior);
        $sessaoNavegacao = $this->Session->read($this->sessionNavegacaoDetalhamento);
        $id = $sessaoPaginaAnterior['id'];
        $acao = $sessaoPaginaAnterior['acao'];
        $sessaoDetalhamentoAtual = array();

        if (array_key_exists($id, $sessaoNavegacao)) {
            $sessaoDetalhamentoAtual['idAnterior'] = $sessaoNavegacao[$id]['id'];
            $sessaoDetalhamentoAtual['acaoAnterior'] = $sessaoNavegacao[$id]['acao'];
            $sessaoDetalhamentoAtual['idAtual'] = $id;
            $this->Session->write($this->sessionDetalhamentoAtual, $sessaoDetalhamentoAtual);
        }

        if ($acao == 'editar' || $acao == 'visualizar') {
            $this->Session->write('PROXIMO_ATENDIMENTO', $id);
        }

        echo Router::url(array('controller' => "Atendimento", 'action' => $acao), true);
        exit;
    }

    public function visualizarAtendimento($idModal = null) {
        $this->set('formDisabledCID', 'disabled');
        $this->set('bloquearEdicao', true);
        $this->set('bloquearExclusao', true);
        

        $sessaoDetalhamentoAtual = $this->Session->read($this->sessionDetalhamentoAtual);
        $sessaoHistoricoDetalhamento = $this->Session->read($this->sessionNavegacaoDetalhamento);
        $id = $sessaoDetalhamentoAtual['idAtual'];
        if($idModal){
            $id = $idModal;
            $this->layout = 'minimal';
            $this->set('complementoIdTabs', $idModal);
            $this->set('isModal', 1);
        }

		if (!$id) {
            throw new NotFoundException(__('objeto_invalido', __('Atendimento')));
        }
        $paginaAnterior = array();
        $paginaAnterior['acao'] = $sessaoDetalhamentoAtual['acaoAnterior'];
        $paginaAnterior['id'] = $sessaoDetalhamentoAtual['idAnterior'];
        $this->Session->write($this->sessionPaginaAnterior, $paginaAnterior);
        $atendimento = $this->Atendimento->findById($id);


         if(empty( $atendimento['Agendamento']['ativo'] )){
            $this->Session->setFlash("O Agendamento associado à esse Processo foi excluído", 'flash_alert');
                return $this->redirect(array('action' => 'index_processos'));
            }

        if($atendimento['Atendimento']['status_atendimento'] == 'Finalizado'){
            $this->set('download_laudo',$id );
        }

        if (!$atendimento) {
            throw new NotFoundException(__('objeto_invalido', __('Atendimento')));
        }

        $atendimentoCids = $this->carregaAtendimentoCID($id);
        $atendimentoCids = array_unique($atendimentoCids, SORT_REGULAR);       
        $this->set('atendimentoCids', $atendimentoCids);

        $currentFunction = $this->request->params['action']; //function corrente
        $currentController = $this->name; //Controller corrente
        $this->saveAuditLog($id,$currentController,'V',$currentFunction);

        $this->set('isRequerid', false);
        $this->set('formDisabled', true);
        $this->set('detalharArquivo', true);
        $this->set('acaoAnterior', $sessaoDetalhamentoAtual['acaoAnterior']);
        if(!$idModal){
            $this->set('id', $sessaoHistoricoDetalhamento[$id]['id']);
            $acaoAnterior = $sessaoHistoricoDetalhamento[$id]['acao'];
            $this->set('currentAction', $acaoAnterior);
            if ($acaoAnterior == 'visualizarAtendimento') {
                $this->set('idAnterior', $sessaoHistoricoDetalhamento[$sessaoHistoricoDetalhamento[$id]['id']]['id']);
            } else {
                $this->set('idAnterior', "");
            }
        }
        if (!$this->request->data) {
            $this->request->data = $atendimento;
        }
        $tipologiaAgendamento = intval($this->request->data['Agendamento']['tipologia_id']);
        if($tipologiaAgendamento == TIPOLOGIA_RECURSO_ADMINISTRATIVO){
            $tipologiaAgendamento = $this->carregarRecursoAdministrativo();
        }
        if($tipologiaAgendamento == TIPOLOGIA_COMUNICACAO_DE_ACIDENTE_DE_TRABALHO){
            $agCat = isset($this->request->data['AgendamentoCAT'])?$this->request->data['AgendamentoCAT']:array();
            $this->carregarCAT();
            $this->request->data['AgendamentoCAT'] = array_merge($this->request->data['AgendamentoCAT'], $agCat);
            
        }

        if($tipologiaAgendamento == TIPOLOGIA_INSPECAO){
            $this->carregarInspecao();
        }

        if($tipologiaAgendamento == TIPOLOGIA_ADMISSAO_PENSIONISTA_MAIOR_INVALIDO){
            $dadosPretenso['cpf_pretenso'] = $this->request->data['Agendamento']['cpf_pretenso'];
            $dadosPretenso['nome_pretenso'] = $this->request->data['Agendamento']['nome_pretenso'];
            $dadosPretenso['sexo_id_pretenso'] = $this->request->data['Agendamento']['sexo_id_pretenso'];
            $dadosPretenso['data_nascimento_pretenso'] = Util::toBrDataHora($this->request->data['Agendamento']['data_nascimento_pretenso']);

            // pr($dadosPretenso);die;
            $this->set('dadosPretenso', $dadosPretenso);
        }

        $this->set('tipologiaAgendamento', $tipologiaAgendamento);

        $this->carregarSessoes( $this->request->data);
        $this->loadModel('Tipologia');
        
        $dadosServidor = $this->montarAgendamento( $this->request->data['Agendamento']['id']);
        $this->set('dadosServidor', $dadosServidor);
        $this->request->data['servidor_id'] = $dadosServidor['Usuario']['id'];
        $this->adicionarDadosCidData( $this->request->data);
        $this->loadModel('Agendamento');
        $this->set('acompanhado', $this->Agendamento->findById( $this->request->data['Agendamento']['id']));

        $tipologia = $this->Tipologia->findById( $this->request->data['Agendamento']['tipologia_id']);
        $this->set('tipologias', array($tipologia['Tipologia']['id'] => $tipologia['Tipologia']['nome']));
        $this->carregarListas();
        //render view edit
        if($tipologiaAgendamento == TIPOLOGIA_LICENCA_MEDICA_TRATAMENTO_SAUDE){
            $this->set('tratamentoAcidente' , $this->request->data['Agendamento']['tratamento_acidente']);
            $this->set('tratamentoAcidenteProcesso', $this->request->data['Agendamento']['tratamento_acidente_processo']);
        }
        $cpfUsuario = $dadosServidor['Usuario']['cpf'];
        $vinculos = $dadosServidor['Vinculos'];
        $arrMatricula = array();
        foreach ($vinculos as $vinculo){
            $itemVinculo = $vinculo["Vinculo"];
            $valMatricula = intval($itemVinculo['matricula']);
            if($valMatricula > 0 ) $arrMatricula[] = $valMatricula;
        }
        $arrMatricula = array_unique($arrMatricula);

        

        // $data_nascimento_servidor = Util::toDBData( $dadosServidor['Usuario']['data_nascimento'] );
        
        // $nome_servidor_historico = $dadosServidor['Usuario']['nome'];
        // $primeiro_nome_servidor  = explode(" ", $nome_servidor_historico);
        // $primeiro_nome_servidor = strtolower(  $primeiro_nome_servidor['0'] );

        // $rg = $dadosServidor['Usuario']['rg'];
        
         //pr($this->request->data);
         //die;

        $this->carregarHistoricoMedico($cpfUsuario, $arrMatricula);
        if($tipologiaAgendamento == TIPOLOGIA_EXAME_PRE_ADMISSIONAL){
            $this->render('edit_pre_admissional');
        }else{
            $this->render('edit');
        }


    }

    private function preVisualizarDetalharAtendimento($id = null, $idAtendimentoAnterior = null, $acaoAnterior = null) {

        if (!$id) {
            throw new NotFoundException(__('objeto_invalido', __('Atendimento')));
        }

        $atendimento = $this->Atendimento->findById($id);

        if (!$atendimento) {
            throw new NotFoundException(__('objeto_invalido', __('Atendimento')));
        }

        $sessaoNavegacao = $this->Session->read($this->sessionNavegacaoDetalhamento);

        if (is_null($sessaoNavegacao)) {
            $sessaoNavegacao = array();
        }

        $sessaoDetalhamentoAtual = array();
        if (array_key_exists($id, $sessaoNavegacao)) {
            $sessaoDetalhamentoAtual['idAnterior'] = $sessaoNavegacao[$id]['id'];
            $sessaoDetalhamentoAtual['acaoAnterior'] = $sessaoNavegacao[$id]['acao'];
        } else {
            $sessaoNavegacao[$id] = array('id' => $idAtendimentoAnterior, 'acao' => $acaoAnterior);
            $sessaoDetalhamentoAtual['idAnterior'] = $idAtendimentoAnterior;
            $sessaoDetalhamentoAtual['acaoAnterior'] = $acaoAnterior;
        }

        $sessaoDetalhamentoAtual['idAtual'] = $id;
        $this->Session->write($this->sessionDetalhamentoAtual, $sessaoDetalhamentoAtual);

        $this->set('sessaoNavegacao', $sessaoNavegacao);

        $this->Session->write($this->sessionNavegacaoDetalhamento, $sessaoNavegacao);
        echo Router::url(array('controller' => "Atendimento", 'action' => 'visualizarAtendimento'), true);
        exit;
    }

    public function detalharAtendimento() {
        if ($this->request->is(array('post'))) {
            $id = $this->request->data['idProximo'];

            $idAtendimentoAnterior = null;
            if (isset($this->request->data['idAnterior'])) {
                $idAtendimentoAnterior = $this->request->data['idAnterior'];
            }
            $acaoAnterior = $this->request->data['acao'];


            $currentFunction = $this->request->params['action']; //function corrente
            $currentController = $this->name; //Controller corrente
            $this->saveAuditLog($id,$currentController,'C',$currentFunction);


            $this->preVisualizarDetalharAtendimento($id, $idAtendimentoAnterior, $acaoAnterior);
        }
    }

    public function preVisualizar($id) {
        $this->Session->write("PROXIMO_ATENDIMENTO", $id);

        $this->loadModel('Agendamento');

        $atendimento = $this->Atendimento->findById($id);
        $idAgendamento =  $atendimento['Atendimento']['agendamento_id'];

        $agendamento = $this->Agendamento->findById($idAgendamento);

        if(empty($agendamento)){
            $this->Session->setFlash(__('O Agendamento associado a esse Atendimento foi excluído!'), 'flash_alert');
            return $this->redirect(array('plugin' => 'web', 'controller' => 'Atendimento', 'action' => 'indexAtendimentoPendentes'));
        }else{
            return $this->redirect(array('plugin' => 'web', 'controller' => 'Atendimento', 'action' => 'visualizar'));
        }
        // return $this->redirect(array('plugin' => 'admin', 'controller' => 'Atendimento', 'action' => 'visualizar'));
    }

    public function visualizar() {

        $idAtendimento = $this->Session->read("PROXIMO_ATENDIMENTO");

        if (!$idAtendimento) {
            throw new NotFoundException(__('objeto_invalido', __('Atendimento')));
        }

        $atendimento = $this->Atendimento->findById($idAtendimento);

        $atendimentoCids = $this->carregaAtendimentoCID($idAtendimento);

        $this->set('atendimentoCids', $atendimentoCids);

        if (!$atendimento) {
            throw new NotFoundException(__('objeto_invalido', __('Atendimento')));
        }
        if (!$atendimento['Atendimento']['data_parecer']) {
            $atendimento['Atendimento']['data_parecer'] = Util::inverteData($atendimento['Agendamento']['data_a_partir']);
        }

        $this->set('formDisabledCID', 'disabled');
        
        
         $id = $idAtendimento;
                $currentFunction = $this->request->params['action']; //function corrente
                $currentController = $this->name; //Controller corrente
                $this->saveAuditLog($id,$currentController,'V',$currentFunction);
        
        $this->limparSessoes();
        $this->carregarSessoes($atendimento);
        $this->request->data = $atendimento;

        $tipologiaAgendamento = intval($this->request->data['Agendamento']['tipologia_id']);
        if($tipologiaAgendamento == TIPOLOGIA_RECURSO_ADMINISTRATIVO){
            $tipologiaAgendamento = $this->carregarRecursoAdministrativo();
        }
        $this->set('tipologiaAgendamento', $tipologiaAgendamento);

        if($tipologiaAgendamento == TIPOLOGIA_COMUNICACAO_DE_ACIDENTE_DE_TRABALHO){
            $this->carregarCAT();
        }

        if($tipologiaAgendamento == TIPOLOGIA_INSPECAO){
            $this->carregarInspecao();
        }

        if($tipologiaAgendamento == TIPOLOGIA_LICENCA_MEDICA_TRATAMENTO_SAUDE){
            $this->set('tratamentoAcidente' , $this->request->data['Agendamento']['tratamento_acidente']);
            $this->set('tratamentoAcidenteProcesso', $this->request->data['Agendamento']['tratamento_acidente_processo']);
        }

        if($tipologiaAgendamento == TIPOLOGIA_ADMISSAO_PENSIONISTA_MAIOR_INVALIDO){
            $dadosPretenso['cpf_pretenso'] = $this->request->data['Agendamento']['cpf_pretenso'];
            $dadosPretenso['nome_pretenso'] = $this->request->data['Agendamento']['nome_pretenso'];
            $dadosPretenso['sexo_id_pretenso'] = $this->request->data['Agendamento']['sexo_id_pretenso'];
            $dadosPretenso['data_nascimento_pretenso'] = Util::toBrDataHora($this->request->data['Agendamento']['data_nascimento_pretenso']);

            // pr($dadosPretenso);die;
            $this->set('dadosPretenso', $dadosPretenso);
        }

        $this->loadModel('Tipologia');
        $dadosServidor = $this->montarAgendamento($this->request->data['Agendamento']['id']);
        $this->set('dadosServidor', $dadosServidor);
        $this->request->data['servidor_id'] = $dadosServidor['Usuario']['id'];

        $this->loadModel('Agendamento');
        $this->set('acompanhado', $this->Agendamento->findById($this->request->data['Agendamento']['id']));

        $tipologia = $this->Tipologia->findById($this->request->data['Agendamento']['tipologia_id']);
        $this->set('tipologias', array($tipologia['Tipologia']['id'] => $tipologia['Tipologia']['nome']));
        $this->adicionarDadosCidData($atendimento);
        $this->carregarListaQualidades();

        if($tipologiaAgendamento == TIPOLOGIA_EXAME_PRE_ADMISSIONAL){
            $this->render('edit_pre_admissional');
        }else{
            $this->render('edit');
        }
    }

    public function exportar_processos() {
        if ($this->request->is('GET')) {

            $idsProcessos = array();
            if (isset($this->request->query['procesos_selecionados'])) {
                $idsProcessos = $this->request->query['procesos_selecionados'];
            }
            $filtro = new BSFilter();
            if (count($idsProcessos) == 1) {
                $condicoes['Atendimento.id'] = $idsProcessos;
            } else {
                $condicoes['Atendimento.id IN'] = $idsProcessos;
            }
            $filtro->setCondicoes($condicoes);
            $filtro->setTipo('all');
            $filtro->setCamposRetornadosString('Atendimento.id', 'Servidor.nome', 'Tipologia.nome', 'Atendimento.data_inclusao', 'TipoSituacaoParecerTecnico.nome', 'Atendimento.data_parecer', 'Atendimento.duracao');
            $joins = array();
            $joins[] = array(
                'table' => 'agendamento',
                'alias' => 'AgendamentoServidor',
                'type' => 'inner',
                'conditions' => array('AgendamentoServidor.id = Atendimento.agendamento_id')
            );
            $joins[] = array(
                'table' => 'tipologia',
                'alias' => 'Tipologia',
                'type' => 'inner',
                'conditions' => array('Tipologia.id = AgendamentoServidor.tipologia_id')
            );
            $filtro->setJoins($joins);
            $this->loadModel('Atendimento');
            $this->set('processos', $this->Atendimento->listar($filtro));
            $this->set('totalProcessos', count($this->Atendimento->listar($filtro)));
            $this->layout = 'pdf';
            $this->render();
        } else {
            Router::redirect([], 'CrasCreas');
        }
    }

    private function verificaExisteAtendimentoVigente($atendimento) {
        $nomeAcompanhado = $atendimento['Agendamento']['cpf_acompanhado'];
        $dataNascimentoAcompanhado = $atendimento['Agendamento']['data_nascimento_acompanhado'];
        $situacaoAtendimento = $atendimento['Atendimento']['situacao_id'];
        if (!is_null($situacaoAtendimento) && $atendimento['Agendamento']['tipologia_id'] === TIPOLOGIA_LICENCA_ACOMPANHAMENTO_FAMILIAR) {

            $this->loadModel('Atendimento');
            $this->loadModel('TipoSituacaoParecerTecnico');

            $filtro = new BSFilter();
            $filtro->setTipo('all');
            $condicoes = [];

            $condicoes['situacao_id NOT IN'] = [TipoSituacaoParecerTecnico::EM_EXIGENCIA, TipoSituacaoParecerTecnico::INDEFERIDO];
            $condicoes['LOWER(Agendamento.nome_acompanhado_sem_abreviacao)'] = Util::trataString($nomeAcompanhado);
            $condicoes['Agendamento.data_nascimento_acompanhado'] = Util::inverteData($dataNascimentoAcompanhado);

            if (isset($atendimento['Atendimento']['id']) && $atendimento['Atendimento']['id']) {
                $condicoes['Atendimento.id !='] = $atendimento['Atendimento']['id'];
            }

            $filtro->setCondicoes($condicoes);
            $atendimento = $this->Atendimento->listar($filtro);

            if (!empty($atendimento)) {
                $this->Session->setFlash(__('Já existe um servidor para esse acompanhamento'), 'flash_alert');
            }
        }
    }

    public function atendimentoFinalizadoSucesso($id) {
        $situacaoAtendimento = $this->Atendimento->buscarSituacao($id);
        $this->set('situacaoAtendimento', $situacaoAtendimento['Atendimento']['situacao_id']);
        $this->set("id", $id);
    }

    private function carregarInspecao(){
        $this->carregarTagGrupo('cfa_piso');
        $this->carregarTagGrupo('cfa_paredes');
        $this->carregarTagGrupo('cfa_escadas');
        $this->carregarTagGrupo('cfa_iluminacao');
        $this->carregarTagGrupo('cfa_inst_eletricas');
        $this->carregarTagGrupo('cfa_maquinas');
        $this->carregarTagGrupo('cfa_higiene');
        $this->carregarTagGrupo('cfa_instalacoes');
        $this->carregarTagGrupo('cfa_cxps');
        $this->carregarTagGrupo('cfa_epi');
    }

    private function carregarTagGrupo($grupo){
        $this->loadModel('Tag');
        $filtro = new BSFilter();
        $condicoes = array( 'Tag.grupo' => $grupo );
        $filtro->setCondicoes($condicoes);
        $filtro->setCamposRetornados(array('Tag.id','Tag.nome'));
        $filtro->setTipo('list');
        $this->set($grupo, $this->Tag->listar($filtro));
    }

    public function carregaAtendimentoCID($idAtendimento){
        $this->loadModel('Cid');
        $this->loadModel('Agendamento');

        $atendimentosCid = array();
        $arrayAtendimentoCid = array();

        $atendimentoCidId =  array();

        /*//recupera todas as informações do agendamento atual pra serem usadas caso não exista um num de 
        $atendInfoOrig = $this->Atendimento->findById($idAtendimento);
        //recupera o número de exigência(que é o id de um atendimento anterior) setado no atendimento   
        $achaAgendamento = $atendInfoOrig['Atendimento']['agendamento_id'];
        $agendEncontrado = $this->Agendamento->findById($achaAgendamento);
        $atendExig = $agendEncontrado['Agendamento']['num_exigencia'];        

        if(isset($atendExig)  && !empty($atendExig)){

            $atendPeloNumExigencia = $this->Atendimento->findById($atendExig);
            $atendPeloNumExigencia_id = $atendPeloNumExigencia['Atendimento']['id'];

            // pr($atendPeloNumExigencia);die;
            $this->set('arrAtendimento', $atendPeloNumExigencia);
        }else{
            // pr($atendInfoOrig);die;
            $this->set('arrAtendimento', $atendInfoOrig);
        }*/

        if($this->Atendimento->findById($idAtendimento)['Atendimento']['cid_id'] != ""){
            $cid = $this->Cid->findById($this->Atendimento->findById($idAtendimento)['Atendimento']['cid_id']);
             
            $atendimentoCidId['idCid'] = $cid['Cid']['id'];
            $atendimentoCidId['codigoCid'] = $cid['Cid']['nome'];
            $atendimentoCidId['descricaoCid'] = $cid['Cid']['nome_doenca'];

            array_push( $atendimentosCid,  $atendimentoCidId);
        }
        $this->loadModel("Cid");
        $consultaAtendimentosCID = $this->Cid->listarCidAtendimento($idAtendimento);

        if(isset($consultaAtendimentosCID) && !empty($consultaAtendimentosCID)){
            foreach ($consultaAtendimentosCID as $ateCid) {

                $arrayAtendimentoCid['idCid'] = $ateCid['Cid']['id'];
                $arrayAtendimentoCid['codigoCid'] = $ateCid['Cid']['nome'];
                $arrayAtendimentoCid['descricaoCid'] = $ateCid['Cid']['nome_doenca'];

                array_push($atendimentosCid, $arrayAtendimentoCid);
                    
            }
        }

        return $atendimentosCid;
    }
 
    public function editar( $idAtendimento__ = null ){
        $this->loadModel('Vinculo');
        $this->loadModel('Agendamento');
        $this->loadModel('AgendamentoCAT');

        

        //$this->loadModel('AtendimentoCID');
        $idAtendimento = $this->Session->read("PROXIMO_ATENDIMENTO");
        
        $idAtendimento = empty($idAtendimento)?$idAtendimento__:$idAtendimento;
        if (!$idAtendimento) {
            throw new NotFoundException(__('objeto_invalido', __('Atendimento')));
        }

        $atendimento = $this->Atendimento->findById($idAtendimento);
        // pr($idAtendimento);die;
        $this->set('formDisabledCID', '');

        if (!$atendimento) {
            throw new NotFoundException(__('objeto_invalido', __('Atendimento')));
        }
        //pr($atendimento);

        $this->verificaExisteAtendimentoVigente($atendimento);

        if (!$atendimento['Atendimento']['data_parecer']) {
            $atendimento['Atendimento']['data_parecer'] = Util::toBrDataHora($atendimento['Agendamento']['data_a_partir']);
        }

        if (!$atendimento['Atendimento']['duracao']) {
            $atendimento['Atendimento']['duracao'] = $atendimento['Agendamento']['duracao'];
        }


        if (!$this->request->data) {
            $this->carregarSessoes($atendimento);
            $this->request->data = $atendimento;
            //pr($atendimento);
        } else {

            $this->request->data['Agendamento'] = $atendimento['Agendamento'];
            if($this->request->data['Agendamento']['tipologia_id'] == TIPOLOGIA_SINDICANCIA_INQUERITO_PAD){
                $dataHora = Util::toDBDataHora($this->request->data['Agendamento']['data_hora']);
                //$this->request->data['Agendamento']['data_livre'] = substr($dataHora, 0, 10);
               // $this->request->data['Agendamento']['hora_livre'] = substr($dataHora,11, 5);
            }
        }

        /*$atendExig = $this->data['Agendamento']['num_exigencia']; //ok
        if(isset($atendExig)){

            $atendPeloNumExigencia = $this->Atendimento->findById($atendExig);
            $atendPeloNumExigencia_id = $atendPeloNumExigencia['Atendimento']['id'];

            $this->set('arrAtendimento', $atendPeloNumExigencia);
        }*/

        $this->loadModel("Cid");
        $cids = $this->arrCidAgendamento($atendimento['Agendamento']['id']);
        if(isset($atendimento['Agendamento']['cid_id']))$cids[] =$atendimento['Agendamento']['cid_id'];
        $this->request->data['Agendamento']['Cids'] = array_unique($cids);
        $tipologiaAgendamento = intval($this->request->data['Agendamento']['tipologia_id']);
        if($tipologiaAgendamento == TIPOLOGIA_RECURSO_ADMINISTRATIVO){
            if ($this->request->is(array('post', 'put'))) {
                $tipologiaAgendamento = $this->carregarRecursoAdministrativo(false);
            }else{
                $tipologiaAgendamento = $this->carregarRecursoAdministrativo();
            }
        }
        $this->set('tipologiaAgendamento', $tipologiaAgendamento);


        $atendimentoCids = $this->carregaAtendimentoCID($idAtendimento);
        $atendimentoCids = array_unique($atendimentoCids, SORT_REGULAR); 
        $this->set('atendimentoCids', $atendimentoCids);
       
        if($tipologiaAgendamento == TIPOLOGIA_LICENCA_MEDICA_TRATAMENTO_SAUDE){
            $this->set('tratamentoAcidente' , $this->request->data['Agendamento']['tratamento_acidente']);
            $this->set('tratamentoAcidenteProcesso', $this->request->data['Agendamento']['tratamento_acidente_processo']);
        }

        if($tipologiaAgendamento == TIPOLOGIA_COMUNICACAO_DE_ACIDENTE_DE_TRABALHO){
            $agCat = isset($this->request->data['AgendamentoCAT'])?$this->request->data['AgendamentoCAT']:array();
            $this->carregarCAT();
            $this->request->data['AgendamentoCAT'] = array_merge($this->request->data['AgendamentoCAT'], $agCat);
        }

        if($tipologiaAgendamento == TIPOLOGIA_INSPECAO){
            $this->carregarInspecao();
        }

        if($tipologiaAgendamento == TIPOLOGIA_ADMISSAO_PENSIONISTA_MAIOR_INVALIDO){
            $dadosPretenso['cpf_pretenso'] = $this->request->data['Agendamento']['cpf_pretenso'];
            $dadosPretenso['nome_pretenso'] = $this->request->data['Agendamento']['nome_pretenso'];
            $dadosPretenso['sexo_id_pretenso'] = $this->request->data['Agendamento']['sexo_id_pretenso'];
            $dadosPretenso['data_nascimento_pretenso'] = Util::toBrDataHora($this->request->data['Agendamento']['data_nascimento_pretenso']);

            // pr($dadosPretenso);die;
            $this->set('dadosPretenso', $dadosPretenso);
        }

        if ($this->request->is(array('post', 'put'))){
            $this->Atendimento->id = $idAtendimento;

            //$this->montarArrayAtendimento();
            $finalizarAtendimento = false;

            if(isset($this->request->data['Agendamento']['tipologia_id'])&&
                $this->request->data['Agendamento']['tipologia_id'] == TIPOLOGIA_COMUNICACAO_DE_ACIDENTE_DE_TRABALHO){
                $this->request->data['AtendimentoCAT']['atendimento_id']= $this->request->data['Atendimento']['id'];
            }

            $dataSource = $this->Atendimento->getDataSource();
            $dataSource->begin();
            //pr($this->request->data); die;

            if ($this->request->data['Atendimento']['emitir_laudo'] == 'true' || isset($this->request->data['finalizarAtendimento'])) {
                $this->request->data['Atendimento']['status_atendimento'] = "Finalizado";

                $this->Agendamento->updateAll(array("status_agendamento" => "'Atendido'"), array('id' => $this->request->data['Atendimento']['agendamento_id']));

                if(isset($this->request->data['Agendamento'])){
                    $this->request->data['Agendamento']['status_agendamento'] = 'Atendido';
                }
                $finalizarAtendimento = true;
            } else {
                $this->request->data['Atendimento']['status_atendimento'] = "Salvo";
            }
            //$this->request->data['Atendimento']['data_parecer'] = Util::toDBData($this->request->data['Atendimento']['data_parecer']);

            if(isset($this->request->data['Atendimento']['modo']) && $this->request->data['Atendimento']['modo'] ==="")$this->request->data['Atendimento']['modo'] = null;

            $config = array();
            if(!$finalizarAtendimento){
                $config['validate'] = false;
            }
            $config['deep'] = true;
            $this->Agendamento->setIgnoreValidation(true);
            //pr($this->request->data); die;
            if ($this->Atendimento->saveAll($this->request->data, $config)){
                // pr($this->request->data);die;
                // pr($this->request->data['AgendamentoCAT']); die();
                if(isset($this->request->data['AgendamentoCAT']) && !empty(isset($this->request->data['AgendamentoCAT']))){
                    $this->AgendamentoCAT->saveAll($this->request->data['AgendamentoCAT']);
                }
                $this->Agendamento->setIgnoreValidation(false);


              // $log = $this->Atendimento->getDataSource()->getLog(false, false);
              // pr($log);
              // die;
                
                $id = $idAtendimento;
                $currentFunction = $this->request->params['action']; //function corrente
                $currentController = $this->name; //Controller corrente
                $this->saveAuditLog($id,$currentController,'A',$currentFunction);
                $this->Session->setFlash(__('objeto_salvo_sucesso', __('Atendimento')), 'flash_success');

                $dataSource->commit();
                if ($finalizarAtendimento == true){


                    return $this->redirect(array('controller' => 'Atendimento', 'action' => 'atendimentoFinalizadoSucesso', $this->request->data['Atendimento']['id']));
                } else {
                    return $this->redirect(array('controller' => 'Atendimento', 'action' => 'editar'));

                    

                }
            } else {
                $this->Agendamento->setIgnoreValidation(true);
                if($this->Session->read('show_all_error') == 1){
                    pr($this->Atendimento->validationErrors);
                }
                $this->carregarSessoes([]);
            }
        } else {

            $this->limparSessoes();

        }

        $this->adicionarDadosCidData($atendimento);



        $this->loadModel('Tipologia');
        $dadosServidor = $this->montarAgendamento($atendimento['Agendamento']['id']);
        $this->set('dadosServidor', $dadosServidor);
        $this->request->data['servidor_id'] = $dadosServidor['Usuario']['id'];

        if (empty($this->request->data['Atendimento']['parecer'])){
            if ($dadosServidor['Usuario']['data_obito']) {
                $this->loadModel('ParametroGeral');
                $parametroGeral = $this->ParametroGeral->buscarParecerTipologiaMaiorInvalido();
                if (Util::inverteData($dadosServidor['Usuario']['data_obito']) < ParametroGeral::DATA_PARECER_DEFAULT_PENSIONISTA_MAIOR_INVALIDO) {
                    $parecer = $parametroGeral['ParametroGeral']['maior_invalido_anterior'];
                } else {
                    $parecer = $parametroGeral['ParametroGeral']['maior_invalido_partir'];
                }
                $this->request->data['Atendimento']['parecer'] = $parecer;
            }
        }
        $this->set('acompanhado', $this->Agendamento->findById($atendimento['Agendamento']['id']));

        $tipologia = $this->Tipologia->findById($atendimento['Agendamento']['tipologia_id']);
        $this->set('tipologias', array($tipologia['Tipologia']['id'] => $tipologia['Tipologia']['nome']));

        $this->carregarListaQualidades();
        //render view edit

        $vinculos = $dadosServidor['Vinculos'];
        $arrMatricula = array();
        foreach ($vinculos as $vinculo){
            $itemVinculo = $vinculo["Vinculo"];
            $valMatricula = intval($itemVinculo['matricula']);
            if($valMatricula > 0 ) $arrMatricula[] = $valMatricula;
        }
        $arrMatricula = array_unique($arrMatricula);

        $cpfUsuario = $dadosServidor['Usuario']['cpf'];
        $this->carregarHistoricoMedico($cpfUsuario, $arrMatricula);

        if($tipologiaAgendamento == TIPOLOGIA_EXAME_PRE_ADMISSIONAL){
            $this->render('edit_pre_admissional');
        }else{
            $this->render('edit');
        }

    

    }




    public function carregarHistoricoMedico($cpfUsuario, $arrMatricula = array()){
        $this->loadModel("Requerimentos");
        $this->loadModel("Usuario");

        $usuario = $this->Usuario->find('first', array(
                'conditions' => array(
                    'Usuario.cpf' => $cpfUsuario
                )
            )
        );
        $usuarioHistoricoAjax = ($this->Session->read("usuarioHistoricoAjax"));
        $this->set('servidorHistAjax', '');
        if($usuarioHistoricoAjax){
            $listHistoricoMedico = $this->Requerimentos->carregaHistoricoMedicoPessoa($usuarioHistoricoAjax);
            $this->loadModel("Servidores");
            $dados = $this->Servidores->getById($usuarioHistoricoAjax);
            $this->set('servidorHistAjax', "{$dados[0]['Servidores']['matricula']} &nbsp;--- &nbsp; {$dados[0]['Pessoas']['nome']}");
        }else{
            $data_nascimento    =  Util::toDBData( $usuario['Usuario']['data_nascimento'] );
            $primeiro_nome      =  strtolower( explode(" ", $usuario['Usuario']['nome'])['0'] );
            $rg                 =  $usuario['Usuario']['rg'];

            $listHistoricoMedico = $this->Requerimentos->carregaHistoricoMedico($cpfUsuario,$rg, $data_nascimento, $primeiro_nome, $arrMatricula);
        }
        $this->set("listHistoricoMedico",$listHistoricoMedico);

    }

    public function carregarRecursoAdministrativo($load = true){
        $this->loadModel("Atendimento");
        $this->loadModel("Agendamento");
        $filtro = new BSFilter();

        $condicoes = array(
            'Atendimento.id' =>   $this->request->data['Agendamento']['numero_processo']
        );
        $filtro->setCondicoes($condicoes);
        $filtro->setTipo('all');
        $atendimentoProcessoAdm = $this->Atendimento->listar($filtro);

        $tipologiaAgendamento = '';

        if(count($atendimentoProcessoAdm) > 0){

            $atendimentoProcessoAdm = $atendimentoProcessoAdm[0];
            $this->set('atendimentoProcessoAdm', $atendimentoProcessoAdm);

            $tipologiaAgendamento = intval($this->Atendimento->getTipologiaIdAtendimento($this->request->data['Agendamento']['numero_processo']));

            $this->request->data['tipologia_processo_adm'] = $tipologiaAgendamento;
            $this->set('tipologiaAgendamento', $tipologiaAgendamento);

            if($this->request->data['Atendimento']['status_atendimento'] == 'Pendente'){
                foreach($this->request->data as $nome_model =>&$model){
                    if(in_array($nome_model, array('Servidor', 'Agendamento'))){
                        continue;
                    }
                    if($nome_model == 'Atendimento' && $load){
                        foreach($model as $nome_param => &$param){
                            if(in_array($nome_param, array('id', 'usuario_id', 'agendamento_id', 'status_atendimento'))){
                                continue;
                            }else{
                                if(!isset($atendimentoProcessoAdm[$nome_model]) || !isset($atendimentoProcessoAdm[$nome_model][$nome_param])){
                                    continue;
                                }else{
                                    if($nome_param == 'data_parecer'){
                                        $param =  Util::inverteData($atendimentoProcessoAdm[$nome_model][$nome_param]);
                                    }else{
                                        $param =  $atendimentoProcessoAdm[$nome_model][$nome_param];
                                    }

                                }
                            }
                        }
                    }else{
                        $carregar = $load;
                        if($nome_model = 'AtendimentoCAT'){
                            $carregar = true;
                        }
                        if(is_array($model) && $carregar){
                            foreach($model as $nome_param => &$param){
                                if($nome_param == 'id'){
                                    continue;
                                }else if($nome_param == 'atendimento_id'){
                                    if(!isset($atendimentoProcessoAdm[$nome_model]) || !isset($atendimentoProcessoAdm[$nome_model][$nome_param])){
                                        continue;
                                    }else{
                                        $param = $this->request->data['Atendimento']['id'];
                                    }
                                }else{
                                    if(!isset($atendimentoProcessoAdm[$nome_model]) || !isset($atendimentoProcessoAdm[$nome_model][$nome_param])){
                                        continue;
                                    }else{
                                        $param =  $atendimentoProcessoAdm[$nome_model][$nome_param];
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        return $tipologiaAgendamento;
    }

    public function carregarCAT(){
        $this->set('formAtendimento', true);
        $this->loadModel('Vinculo');
        $this->loadModel('Agendamento');


        $dadosAgendmento = $this->Agendamento->getAgendamento($this->request->data['Atendimento']['agendamento_id'], true);

        $this->request->data['AgendamentoCAT'] = $dadosAgendmento['AgendamentoCAT'];

        //LIGAÇÃO ENTRE CAT E ATENDIMENTO
        $this->request->data['AtendimentoCAT']['atendimento_id'] = $this->request->data['Atendimento']['id'];
        $idUsuario = CakeSession::read('Auth.User.id');

        $isMedico = false;
        $isEngenheiro = false;
        $vinculos =  $this->Vinculo->find('all',['conditions' =>['Vinculo.usuario_id = '=> $idUsuario, 'Vinculo.ativo' => 't']]);

        if(!empty($vinculos)){
            foreach($vinculos as $vinculo){
                if(!empty($vinculo['Cargo'])){
                    switch($vinculo['Cargo']['nome']){
                        case 'MEDICO':
                            $isMedico = true;
                            break;
                        case 'ENGENHEIRO':
                            $isEngenheiro = true;
                            break;
                    }
                }
                foreach($vinculo['Funcao'] as $funcao){
                    switch($funcao['nome']){
                        case 'MEDICO':
                            $isMedico = true;
                            break;
                        case 'ENGENHEIRO':
                            $isEngenheiro = true;
                            break;
                    }
                }
            }
        }
        $this->set('isMedico', $isMedico);
        $this->request->data['AtendimentoCAT']['salvo_medico'] = $this->request->data['AtendimentoCAT']['salvo_medico'] || $isMedico;

        $this->set('isEngenheiro', $isEngenheiro);
        $this->request->data['AtendimentoCAT']['salvo_engenheiro'] = $this->request->data['AtendimentoCAT']['salvo_engenheiro'] || $isEngenheiro;
    }

    public function obterInternaloEntreDatas() {
        $this->layout = 'ajax';
        $data1 = new DateTime($this->request->data['inputDataAtual']);
        $data2 = new DateTime(Util::inverteData($this->request->data['dataFinal']));
        $intervalo = $data1->diff($data2);
        echo "(prazo: {$intervalo->y} anos, {$intervalo->m} meses e {$intervalo->d} dias)";
        die;
    }

    public function indexAtendimentoPendentes() {
        $tipoUsuarioLogado = $this->Auth->user()['tipo_usuario_id'];
        if ($tipoUsuarioLogado != USUARIO_PERITO_CREDENCIADO && $tipoUsuarioLogado != USUARIO_PERITO_SERVIDOR) {
            $this->Session->setFlash(__('erro_acesso_negado_atendimento_servidor'), 'flash_alert');
            // return $this->redirect(array('plugin' => 'admin', 'controller' => 'dashboard', 'action' => 'index'));
            return $this->redirect(array('plugin' => 'web', 'controller' => 'dashboard', 'action' => 'index'));
        }
        $this->carregarListaTipologia();
    }

    /**
     * Carregar lista de qualidades
     */
    private function carregarListaQualidades() {
        $this->loadModel('Qualidade');
        $filtro = new BSFilter();
        $filtro->setTipo('list');
        $filtro->setCamposOrdenadosString('Qualidade.nome');
        $qualidades = $this->Qualidade->listar($filtro);
        $this->set(compact('qualidades'));
    }

    private function montarAgendamento($id) {
        $this->loadModel('Agendamento');

        $filtro = new BSFilter();
        $filtro->setTipo('all');
        $condicoes = ['Agendamento.id' => $id];
        $joins = array();
        $joins[] = array(
            'table' => 'usuario',
            'alias' => 'Usuario',
            'type' => 'left',
            'conditions' => array('Usuario.id = Agendamento.usuario_servidor_id')
        );

        $filtro->setJoins($joins);
        $filtro->setCondicoes($condicoes);
        $filtro->setCamposRetornadosString('Agendamento.id', 'Usuario.id', 'Usuario.nome', 'Usuario.cpf', 'Usuario.sexo_id', 'Usuario.data_nascimento', "Agendamento.tipologia_id", "Usuario.data_obito", "Usuario.rg");
        $filtro->setCamposOrdenadosString('Agendamento.id');
        $agendamento = $this->Agendamento->listar($filtro);
        if (empty($agendamento)) {
            throw new NotFoundException(__('objeto_invalido', __('Atendimento')));
        }

        $arrAgenVinculo = (!empty($agendamento)) ? $agendamento[0] : [];

        $vinculos = $this->montarVinculosAtendimento($arrAgenVinculo);
        $dependentes = $this->montarDependentes($arrAgenVinculo);
        $arrayAgendamento = array();
        $arrayAgendamento = $arrAgenVinculo;

        $anoAtual = (int) date('Y');
        foreach ($vinculos as $key => $vinculo) {
            if (!is_null($vinculo['Vinculo']['data_admissao_servidor'])) {
                $vinculos [$key]['Vinculo']['stringVerificaAnos'] = " (" . Util::calc_idade(date('d/m/Y', strtotime($vinculo['Vinculo']['data_admissao_servidor']))) . __('atendimento_atendimento_string_anos_servico') . ")";
            }else{
                $vinculos [$key]['Vinculo']['stringVerificaAnos'] = "";
            }
        }

        $arrayAgendamento['Vinculos'] = $vinculos;
        $arrayAgendamento['Dependentes'] = $dependentes;
        if (!is_null($arrayAgendamento['Usuario']['data_nascimento'])) {
            $arrayAgendamento['Usuario']['idade'] = Util::calc_idade($arrayAgendamento['Usuario']['data_nascimento']);
            $arrayAgendamento['Usuario']['data_nascimento'] = Util::toBrData($arrayAgendamento['Usuario']['data_nascimento']);
        }
        return $arrayAgendamento;
    }

    private function montarDependentes($agendamento = array()) {
        $dependentes = [];
        if (!empty($agendamento)) {
            $this->loadModel('Dependente');
            $filtro = new BSFilter();
            $filtro->setTipo('all');
            $condicoesVinculo = ['Dependente.usuario_id' => $agendamento['Usuario']['id']];

            $filtro->setCondicoes($condicoesVinculo);
            $filtro->setCamposRetornadosString('Dependente.id', 'Dependente.nome', 'Dependente.cpf', 'Dependente.rg', 'Dependente.data_nascimento');
            $filtro->setCamposOrdenadosString('Dependente.id');
            $dependentes = $this->Dependente->listar($filtro);
        }

        return $dependentes;
    }

    private function montarVinculosAtendimento($agendamento = array()) {
        $vinculos = [];
        if (!empty($agendamento)) {
            $this->loadModel('Vinculo');
            $filtroVinculos = new BSFilter();
            $filtroVinculos->setTipo('all');
            $condicoesVinculo = ['Vinculo.usuario_id' => $agendamento['Usuario']['id']];

            $joinsVinculo = array();

            //Join Lotação
            $joinsVinculo[] = array(
                'table' => 'vinculo_lotacao',
                'alias' => 'vl',
                'type' => 'left',
                'conditions' => array('vl.vinculo_id = Vinculo.id')
            );
            $joinsVinculo[] = array(
                'table' => 'lotacao',
                'alias' => 'Lotacao',
                'type' => 'left',
                'conditions' => array('Lotacao.id = vl.lotacao_id')
            );

            //Join Função
            $joinsVinculo[] = array(
                'table' => 'vinculo_funcao',
                'alias' => 'vf',
                'type' => 'left',
                'conditions' => array('vf.vinculo_id = Vinculo.id')
            );
            $joinsVinculo[] = array(
                'table' => 'funcao',
                'alias' => 'Funcao',
                'type' => 'left',
                'conditions' => array('Funcao.id = vf.funcao_id')
            );

            $filtroVinculos->setJoins($joinsVinculo);
            $filtroVinculos->setCondicoes($condicoesVinculo);
            $filtroVinculos->setCamposRetornadosString('Vinculo.id', 'Vinculo.matricula', 'OrgaoOrigem.orgao_origem', 'Cargo.nome', 'Vinculo.data_admissao_servidor', 'array_to_string(array_agg(distinct(Funcao.nome)), \' / \') as Funcao__nome', 'array_to_string(array_agg(distinct(Lotacao.nome)), \' / \') as Lotacao__nome'
            );
            $filtroVinculos->setCamposOrdenadosString('Vinculo.id');
            $filtroVinculos->setCamposAgrupadosString('Vinculo.id', 'OrgaoOrigem.orgao_origem', 'Cargo.nome', 'Vinculo.data_admissao_servidor');
            $vinculos = $this->Vinculo->listar($filtroVinculos);
        }

        return $vinculos;
    }

    /**
     * Carrega as tipologias
     */
    private function carregarListaTipologia() {
        $this->loadModel("Tipologia");
        $filtro = new BSFilter();
        $filtro->setTipo('list');
        $filtro->setCamposOrdenadosString('Tipologia.nome');
        $tipologias = $this->Tipologia
                ->listar($filtro);
        $this->set(compact('tipologias'));
    }

    private function limparSessoes() {

        $this->Session->delete($this->sessionJuntaPeritos);
        $this->Session->delete($this->sessionNavegacaoDetalhamento);
        $this->Session->delete($this->sessionDetalhamentoAtual);
        
    }

    public function adicionarPerito() {
        $peritos = $this->Session->read($this->sessionJuntaPeritos);

        if (is_null($peritos)) {
            $peritos = array();
        }

        $id = $this->request->data['idPerito'];
        $nome = $this->request->data['nomePerito'];
        $numero_registro = $this->request->data['numeroRegistroPerito'];

        $novoPerito = array();
        if (!key_exists($id, $peritos)) {
            $peritos[$id]['Perito'] = ['id' => $id, 'nome' => $nome, 'numero_registro' => $numero_registro];
            $novoPerito[0] = ['id' => $id, 'nome' => $nome, 'numero_registro' => $numero_registro];
        }
        
        $this->Session->write($this->sessionJuntaPeritos, $peritos);
        echo json_encode($novoPerito);
        die;
    }

    public function getNomeJuntaPerito() {
        $this->layout = 'ajax';
        if ($this->request->is([ 'get', 'post'])) {
            $nome = $this->request->query['term'];

            $this->loadModel('Usuario');
            $filtro = new BSFilter();
            $filtro->setTipo('all');
            $filtro->setCamposRetornados([ 'Usuario.id', 'Usuario.nome', 'Usuario.numero_registro']);
            $filtro->setCamposOrdenadosString('Usuario.nome');

            $condicoes['Usuario.nome ILIKE '] = '%' . $nome . '%';
            $condicoes['Usuario.tipo_usuario_id'] = [USUARIO_PERITO_CREDENCIADO, USUARIO_PERITO_SERVIDOR];

            $filtro->setCondicoes($condicoes);

            $arrUsuario = $this->Usuario->listar($filtro);
            $arrayRetorno = array();
            foreach ($arrUsuario as $key => $line) {
                $objTmp = new stdClass();
                $nome = $line['Usuario']['nome'];
                $objTmp->id = $line['Usuario']['id'];
                $objTmp->nome = $line['Usuario']['nome'];
                $objTmp->numeroRegistro = $line['Usuario']['numero_registro'];
                $objTmp->label = $nome;
                $objTmp->value = $nome;
                $arrayRetorno[] = $objTmp;
            }

            echo json_encode($arrayRetorno);
            die;
        }
    }

    public function getNumeroRegistroPerito() {
        $this->layout = 'ajax';
        if ($this->request->is([ 'get', 'post'])) {
            $numero_registro = $this->request->query['term'];

            $this->loadModel('Usuario');
            $filtro = new BSFilter();
            $filtro->setTipo('all');
            $filtro->setCamposRetornados([ 'Usuario.id', 'Usuario.nome', 'Usuario.numero_registro']);
            $filtro->setCamposOrdenadosString('Usuario.nome');

            $condicoes['Usuario.numero_registro ILIKE '] = '%' . $numero_registro . '%';
            $condicoes['Usuario.tipo_usuario_id'] = [USUARIO_PERITO_CREDENCIADO, USUARIO_PERITO_SERVIDOR];

            $filtro->setCondicoes($condicoes);

            $arrUsuario = $this->Usuario->listar($filtro);
            $arrayRetorno = array();
            foreach ($arrUsuario as $key => $line) {
                $objTmp = new stdClass();
                $numero_registro = $line['Usuario']['numero_registro'];
                $objTmp->id = $line['Usuario']['id'];
                $objTmp->nome = $line['Usuario']['nome'];
                $objTmp->numeroRegistro = $numero_registro;
                $objTmp->label = $numero_registro;
                $objTmp->value = $numero_registro;
                $arrayRetorno[] = $objTmp;
            }

            echo json_encode($arrayRetorno);
            die;
        }
    }


    public function getNumeroInspecao($idUser) {
        $this->layout = 'ajax';
        if ($this->request->is([ 'get', 'post'])) {
            $numero_inspecao = $this->request->query['term'];

            $this->loadModel('Atendimento');
            $filtro = new BSFilter();
            $filtro->setTipo('all');
            $filtro->setCamposRetornados([ 'Atendimento.id']);

            $condicoes['Atendimento.usuario_id'] = $idUser;
            $condicoes['(cast ("Atendimento".id as VARCHAR)) ILIKE'] ='%'.$numero_inspecao. '%';
            $condicoes['Atendimento.status_atendimento'] = 'Finalizado';

            $condicoes['Agendamento.tipologia_id'] = TIPOLOGIA_INSPECAO;

            $filtro->setCondicoes($condicoes);

            $arrAtendimento = $this->Atendimento->listar($filtro);
            $arrayRetorno = array();
            foreach ($arrAtendimento as $key => $line) {
                $objTmp = new stdClass();
                $numero_inspecao = $line['Atendimento']['id'];
                $objTmp->numero = $numero_inspecao;
                $objTmp->label = $numero_inspecao;
                $objTmp->value = $numero_inspecao;
                $arrayRetorno[] = $objTmp;
            }

            echo json_encode($arrayRetorno);
            die;
        }
    }


    public function confirmarAgendamento($id, $confirmarAgendamento = false) {
        $this->loadModel("Agendamento");

        $confirmarAgendamento = (boolean) $confirmarAgendamento;

        $agendamento = array('Agendamento' => array('id' => $id,
                'agendamento_confirmado' => $confirmarAgendamento,
                'status_agendamento' => ($confirmarAgendamento) ? "Aguardando Atendimento" : "Agendado"));

        $agendamentoAll = $this->Agendamento->find('first', array(
            'conditions' => array('Agendamento.id' => $id),
            'recursive' => -1
        ));

        $agendamento = Hash::merge($agendamentoAll, $agendamento);

        $dataAgendamento = $this->Agendamento->buscarDataAgendamento($id);
        $dataAgendamento = Util:: toDBDataHora($dataAgendamento, true);
        $dataAgendamento = date("Y-m-d H:i:s", strtotime($dataAgendamento));

        $tempoConsulta = null;
        $prioritario = false;
        if(isset($this->params['url']['prioritario']) && $this->params['url']['prioritario']){
            $prioritario = true;
            $prioritarioAgendamentoDia = $this->Agendamento->find('first', array(
                'conditions' => array(
                    'Agendamento.data_hora >=' => date("Y-m-d 00:00:00"),
                    'Agendamento.data_hora <=' => date("Y-m-d 23:59:59"),
                    'Agendamento.tempo_consulta >=' => 1
                ),
                'limit' => 1,
                'order' => array('Agendamento.tempo_consulta DESC'),
                'recursive' => -1
            ));
            if(count($prioritarioAgendamentoDia) > 0){
                $tempoConsulta = $prioritarioAgendamentoDia['Agendamento']['tempo_consulta'] + 1;
            }else{
                $tempoConsulta = 1;
            }
        }

        $proximaDataHoraPosHorario = $dataAgendamento;
        $arrayRetorno = array();

        $posHorario = false;
        if (!$prioritario && $confirmarAgendamento && strtotime($dataAgendamento) < strtotime(date("Y-m-d H:i"))) {
            $posHorario = true;
            $ultimoConfirmadoAgendamentoDia = $this->Agendamento->find('first', array(
                'conditions' => array(
                    'Agendamento.data_hora >=' => date("Y-m-d H:i:s"),
                    'Agendamento.data_hora <=' => date("Y-m-d 23:59:59"),
                    'Agendamento.status_agendamento' => "Aguardando Atendimento"
                ),
                'limit' => 1,
                'order' => array('Agendamento.data_hora DESC'),
                'recursive' => -1
            ));
            if(count($ultimoConfirmadoAgendamentoDia) > 0){
                $dataHora = Util::toDBDataHora($ultimoConfirmadoAgendamentoDia['Agendamento']['data_hora'], true);
                $proximaDataHoraPosHorario = date("Y-m-d H:i:s", strtotime($dataHora) +5*60);
            }else{
                $proximaDataHoraPosHorario = date("Y-m-d H:i:s" , strtotime(date("Y-m-d H:i:s")) +5*60 );
            }
        }

        if (empty($arrayRetorno)) {
            if ($prioritario) {
                $agendamento['Agendamento']['tempo_consulta'] = $tempoConsulta;
            }else{
                $agendamento['Agendamento']['tempo_consulta'] = null;
            }

            if ($posHorario) {
                $agendamento['Agendamento']['data_hora'] = $proximaDataHoraPosHorario;
                $agendamento['Agendamento']['encaixe'] = true;
            }

            if ($this->Agendamento->save($agendamento, false)) {
                $id = $this->Agendamento->id;
                $currentFunction = $this->request->params['action']; //function corrente
                $currentController = $this->name; //Controller corrente
                $this->saveAuditLog($id, $currentController, 'A', $currentFunction);

                $arrayRetorno['status'] = "success";
                if ($confirmarAgendamento) {
                    $arrayRetorno['msg'] = __('info_agendamento_confirmado_sucesso');
                } else {
                    $arrayRetorno['msg'] = __('info_agendamento_desconfirmado_sucesso');
                }
            } else {
                @session_start();
                if (isset($_SESSION['show_all_error']) && $_SESSION['show_all_error'] == 1) {
                    pr($agendamento);
                    pr($this->Agendamento->validationErrors);
                    die;
                }
                $arrayRetorno ['error'] = 1;
                $arrayRetorno['status'] = "danger";
                $arrayRetorno['msg'] = __('erro_confirmacao_agendamento');
            }
        }

        echo json_encode($arrayRetorno);
        die;
    }

    /**
     * Metodo responsavel para listar a consulta
     */
    public function consultar() {
        $this->layout = 'ajax';
        $this->loadModel("Agendamento");
        $this->loadModel("Atendimento");
        $this->loadModel("Tipologia");
        // die('aqy');
        if ($this->request->is('GET')) {
            $limitConsulta = $this->request->query['data']['Agendamento']['limitConsulta'];

            if (!$this->request->query['data']['Agendamento']['data']) {
                $this->Agendamento->invalidate('data', 'Escolha um dia para listar os agendamentos');
            }

            $condicoes = $this->condicoesConsulta();
            $filtro = new BSFilter();
            $filtro->setTipo('all');
            $filtro->setCondicoes($condicoes);
            $filtro->setCamposRetornadosString("id", "data_hora", "sala", "Tipologia.nome", "UsuarioServidor.nome", "UsuarioServidor.cpf", "agendamento_confirmado", "status_agendamento", "agendamento_encaminhado_sala", "numero_processo", "tempo_consulta", "encaixe");
            $filtro->setCamposOrdenados(['Agendamento.data_hora']);

            $agendamentos = $this->Agendamento->listar($filtro);
            foreach ($agendamentos as &$agendamento){
                if($agendamento['Agendamento']['numero_processo']){
                    $tipologiaIdProcesso = $this->Atendimento->getTipologiaIdAtendimento($agendamento['Agendamento']['numero_processo']);
                    $tipologiaProcesso = $this->Tipologia->getNomeById($tipologiaIdProcesso);
                    $agendamento['Agendamento']['tipologia_processo'] = $tipologiaProcesso;
                }
            }
            $currentFunction = $this->request->params['action']; //function corrente
            $currentController = $this->name; //Controller corrente
            $this->saveAuditLog(null,$currentController,'C',$currentFunction);

            $this->set('agendamentos', $agendamentos);
            $this->set('limiteConsultaSelecionado', $limitConsulta);
        }
    }

    public function consultarAtendimentosPendentes() {
        $this->layout = 'ajax';
        $this->loadModel("Atendimento");
        $this->loadModel("Tipologia");


        if ($this->request->is('GET')) {

            $limitConsulta = $this->request->query['data']['Atendimento']['limitConsulta'];

            $tipologia = $this->request->query['data']['Atendimento']['tipologia_id'];
            $nome = $this->request->query['data']['Atendimento']['nome_servidor'];
            $data = $this->request->query['data']['Atendimento']['data'];
            $numero = $this->request->query['data']['Atendimento']['numero'];

            $condicoes = array();
            $condicoes['Atendimento.status_atendimento in'] = array("Pendente", "Salvo");
            $condicoes['Agendamento.ativo'] = true;

            if (!empty($tipologia)) {
                $condicoes['Agendamento.tipologia_id'] = $tipologia;
            }
            if (!empty($nome)) {
                $condicoes['Servidor.nome ILIKE '] = '%' . $nome . '%';
            }
            if (!empty($data)) {
                $condicoes['CAST(Atendimento.data_inclusao as date) = '] = $data;
            }
            if (!empty($numero)) {
                $condicoes['Atendimento.id'] = $numero;
            }


            $joins = array();
            $joins[] = array(
                'table' => 'tipologia',
                'alias' => 'Tipologia',
                'type' => 'left',
                'conditions' => array('Tipologia.id = Agendamento.tipologia_id')
            );
            $joins[] = array(
                'table' => 'sit_parecer_tec',
                'alias' => 'SituacaoParecer',
                'type' => 'left',
                'conditions' => array('SituacaoParecer.id = Atendimento.situacao_id')
            );

            $filtro = new BSFilter();
            $filtro->setTipo('all');
            $filtro->setCondicoes($condicoes);
            $filtro->setJoins($joins);
            $filtro->setLimiteConsulta($limitConsulta);
            $filtro->setCamposRetornadosString(
                "id",
                "data_inclusao",
                "SituacaoParecer.nome",
                "Tipologia.nome",
                "Tipologia.id",
                "Servidor.nome",
                "status_atendimento",
                "Agendamento.numero_processo",
                "Agendamento.id");

            $atendimentosPendentes = $this->paginar($filtro);
            // die('aq');

            foreach($atendimentosPendentes as  &$atendimentosPendente){
                if($atendimentosPendente['Tipologia']['id'] == TIPOLOGIA_RECURSO_ADMINISTRATIVO){
                    $tipologia_id = $this->Atendimento->getTipologiaIdAtendimento($atendimentosPendente['Agendamento']['numero_processo']);
                    $atendimentosPendente['Agendamento']['tipologia_processo'] = $this->Tipologia->getNomeById($tipologia_id);
                }
            }
            
             
                $currentFunction = $this->request->params['action']; //function corrente
                $currentController = $this->name; //Controller corrente
                $this->saveAuditLog(null,$currentController,'C',$currentFunction);
            
            $this->set('atendimentosPendentes', $atendimentosPendentes);

            $this->set('limiteConsultaSelecionado', $limitConsulta);
        }
    }

    public function preEditarAtendimento($id) {
        
        $this->Session->delete("usuarioHistoricoAjax");
        $this->Session->write("PROXIMO_ATENDIMENTO", $id);

        $this->loadModel('Agendamento');

        $atendimento = $this->Atendimento->findById($id);

        if(isset($atendimento['Agendamento']['num_exigencia']) && !empty($atendimento['Agendamento']['num_exigencia'])){
            $id = $atendimento['Agendamento']['num_exigencia'];
            $atendimento = $this->Atendimento->findById($id);
        }

        // pr($atendimento);die;
        $idAgendamento =  $atendimento['Atendimento']['agendamento_id'];

        $agendamento = $this->Agendamento->findById($idAgendamento);

        if(empty($agendamento)){
            $this->Session->setFlash(__('O Agendamento associado a esse Atendimento foi excluído!'), 'flash_alert');
            return $this->redirect(array('plugin' => 'web', 'controller' => 'Atendimento', 'action' => 'indexAtendimentoPendentes'));
        }else{
            return $this->redirect(array('plugin' => 'web', 'controller' => 'Atendimento', 'action' => 'editar'));
        }


    }

    /**
     * Monta as condições para o consultar
     * @return array
     */
    private function condicoesConsulta() {
        $tipologia = $this->request->query['data']['Agendamento']['Tipologia'];
        $cpf = $this->request->query['data']['Agendamento']['cpf'];
        $hora_inicial = $this->request->query['data']['Agendamento']['hora_inicial'];
        $hora_final = $this->request->query['data']['Agendamento']['hora_final'];
        $data = $this->request->query['data']['Agendamento']['data'];
        $unidade = $this->request->query['data']['Agendamento']['Unidade'];


        $condicoes = null;

        if (!empty($tipologia)) {
            $condicoes['Agendamento.tipologia_id'] = $tipologia;
        }
        if (!empty($cpf)) {
            $condicoes['UsuarioServidor.cpf'] = Util::limpaDocumentos($cpf);
        }
        if (CakeSession::read('perfil') == PERFIL_ADMINISTADOR){
            if(!empty($unidade)){
                $condicoes['Agendamento.unidade_atendimento_id'] = $unidade;
            }
        }else{
            $condicoes['Agendamento.unidade_atendimento_id'] = $this->Auth->user()['unidade_atendimento_id'];
        }

        if (!empty($data)) {
            $data = $this->montarDataConsulta($data, $hora_inicial, $hora_final);
            $arrOr = array();
            $arrOr[] =  array(
                    'Agendamento.data_hora >= '=> Util::toDBDataHora($data['inicial']),
                    'Agendamento.data_hora <= '=> Util::toDBDataHora($data['final']));
            $arrOr[] = array(
                'Agendamento.tipologia_id in' => array(TIPOLOGIA_RISCO_VIDA_INSALUBRIDADE, TIPOLOGIA_COMUNICACAO_DE_ACIDENTE_DE_TRABALHO),
                'Agendamento.status_agendamento <> ' => 'Atendido'
            );
            $condicoes[] = array('or' => $arrOr);
        }
        //pr($condicoes); die;
        return $condicoes;
    }

    /**
     * Função para montar o array de datas para a consulta de um atendimento.
     * @param string $data
     * @param string $horaInicial
     * @param string $horaFinal
     * @return array
     */
    private function montarDataConsulta($data, $horaInicial, $horaFinal) {
        $arrayRetorno = [];
        if ($horaInicial) {
            $arrayRetorno['inicial'] = $data . " " . $horaInicial . ":00";
        } else {
            $arrayRetorno['inicial'] = $data . " 00:00:00";
        }

        if ($horaFinal) {
            $arrayRetorno['final'] = $data . " " . $horaFinal . ":00";
        } else {
            $arrayRetorno['final'] = $data . " 23:59:59";
        }

        return $arrayRetorno;
    }

    /**
     * 
     * @throws NotFoundException
     */
    private function informaTituloEdit() {
        $this->loadModel('Atendimento');
        $this->loadModel('Tipologia');

        $idAtendimento = $this->request->data['Atendimento']['id'];
        if($this->request->data['Agendamento']['tipologia_id'] == TIPOLOGIA_RECURSO_ADMINISTRATIVO){
            $tipologiaExtra = $this->Tipologia->getNomeById($this->request->data['tipologia_processo_adm']);
        }

        if (!$idAtendimento) {
            throw new NotFoundException(__('objeto_invalido', __('Atendimento')));
        }

        $atendimento = $this->Atendimento->findById($idAtendimento);
        if (!$atendimento) {
            throw new NotFoundException(__('objeto_invalido', __('Atendimento')));
        }

        switch ($atendimento['Agendamento']['tipologia_id']) {
            case TIPOLOGIA_ADMISSAO_PENSIONISTA_MAIOR_INVALIDO:
                $this->set('tituloHeadEdit', __('atendimento_laudo_pensao_maior_invalido_titulo'));
                break;
            case TIPOLOGIA_LICENCA_MEDICA_TRATAMENTO_SAUDE:
                $this->set('tituloHeadEdit', __('atendimento_laudo_licenca_medica_tratamento_saude'));
                break;
            case TIPOLOGIA_APOSENTADORIA_INVALIDEZ:
                $this->set('tituloHeadEdit', __('atendimento_laudo_aposentadoria_invalidez_titulo'));
                break;
            case TIPOLOGIA_ATECIPACAO_LICENCA:
                $this->set('tituloHeadEdit', __('atendimento_laudo_tratamento_saude_inicial'));
                break;
            case TIPOLOGIA_AVALIACAO_HABILITACAO_DEPENDENTES:
                $this->set('tituloHeadEdit', __('atendimento_laudo_avaliacao_habilitacao_dependentes'));
                break;
            case TIPOLOGIA_EXAME_PRE_ADMISSIONAL:
                $this->set('tituloHeadEdit', __('atendimento_laudo_exame_pre_admissional'));
                break;
            case TIPOLOGIA_INFORMACAO_SEGURO_COMPREENSIVO_HABITACIONAL:
                $this->set('tituloHeadEdit', __('atendimento_laudo_informacao_seguro_compreensivo_habitacional'));
                break;
            case TIPOLOGIA_ISENCAO_CONTRIBUICAO_PREVIDENCIARIA:
                $this->set('tituloHeadEdit', __('atendimento_laudo_isencao_contribuicao_previdenciaria'));
                break;
            case TIPOLOGIA_LICENCA_ACOMPANHAMENTO_FAMILIAR:
                $this->set('tituloHeadEdit', __('atendimento_laudo_acompanhamento_pessoa_familia'));
                break;
            case TIPOLOGIA_LICENCA_MATERNIDADE:
                $this->set('tituloHeadEdit', __('atendimento_laudo_licenca_maternidade'));
                break;
            case TIPOLOGIA_LICENCA_MATERNIDADE_ABORTO:
                $this->set('tituloHeadEdit', __('atendimento_laudo_licenca_maternidade_aborto'));
                break;
            case TIPOLOGIA_LICENCA_NATIMORTO:
                $this->set('tituloHeadEdit', __('atendimento_laudo_licenca_maternidade_natimorto'));
                break;
            case TIPOLOGIA_PCD:
                $this->set('tituloHeadEdit', __('atendimento_laudo_pcd'));
                break;
            case TIPOLOGIA_READAPTACAO_FUNCAO:
                $this->set('tituloHeadEdit', __('atendimento_laudo_readaptacao_funcao'));
                break;
            case TIPOLOGIA_RECURSO_ADMINISTRATIVO:
                $this->set('tituloHeadEdit', 'Recurso Administrativo - '.$tipologiaExtra);
                break;
            case TIPOLOGIA_REMANEJAMENTO_FUNCAO:
                $this->set('tituloHeadEdit', __('atendimento_laudo_remanejamento_funcao'));
                break;
            case TIPOLOGIA_REVERSAO_APOSENTADORIA_INVALIDEZ:
                $this->set('tituloHeadEdit', __('atendimento_laudo_reversao_aposentadoria'));
                break;
            case TIPOLOGIA_RISCO_VIDA_INSALUBRIDADE:
                $this->set('tituloHeadEdit', __('atendimento_laudo_risco_vida'));
                break;
            case TIPOLOGIA_REMOCAO:
                $this->set('tituloHeadEdit', __('atendimento_laudo_remocao'));
                break;
              case TIPOLOGIA_DESIGNACAO_DE_ASSISTENTE_TECNICO:
                $this->set('tituloHeadEdit', __('atendimento_laudo_designacao_assistente_tecnico'));
                break;  
            case TIPOLOGIA_SINDICANCIA_INQUERITO_PAD:
                $tipo = $this->Tipologia->getTipo($atendimento['Agendamento']['tipo']);
                $this->set('tituloHeadEdit', 'Laudo de '. $tipo);
                break;
            case TIPOLOGIA_COMUNICACAO_DE_ACIDENTE_DE_TRABALHO:
                $this->set('tituloHeadEdit', __('atendimento_laudo_comunicacao_de_acidente_de_trabalho'));
                break;
            case TIPOLOGIA_APOSENTADORIA_ESPECIAL:
                $this->set('tituloHeadEdit', 'Laudo Aposentadoria Especial');
                break;
            case TIPOLOGIA_INSPECAO:
                $this->set('tituloHeadEdit', 'Inspeção');
                break;
            default :
                $this->set('tituloHeadEdit', __('Default'));
                break;
        }
    }

    public function getCidId() {
        $arrayRetorno = array();
        $cid = $this->request->query['term'];
        if ($cid) {
            $this->loadModel('Cid');

            $filtro = new BSFilter();
            $filtro->setTipo('all');
            $filtro->setCamposRetornadosString('Cid.id', 'Cid.nome', '"Cid"."nome"|| \' - \' ||"Cid"."nome_doenca" AS "Cid__descricao"');
            $filtro->setCondicoes(['Cid.nome ILIKE ' => '%' . $cid . '%']);
            $filtro->setCamposOrdenadosString('Cid.nome');
            $arrCids = $this->Cid->listar($filtro);

            foreach ($arrCids as $line) {
                $objTmp = new stdClass();
                $nome = $line['Cid']['nome'];
                $objTmp->id = $line['Cid']['id'];
                $objTmp->descricao = $line['Cid']['descricao'];
                $objTmp->label = $nome;
                $objTmp->value = $nome;
                $arrayRetorno[] = $objTmp;
            }
        }
        echo json_encode($arrayRetorno);
        die;
    }

    /**
     * Função para verificação da consulta da chamada da Sala
     */
    public function consultarChamadaSala() {
        $this->layout = "ajax";
        $sessionUsuario = ($this->Session->read($this->sessionChamarUsuario)) ? $this->Session->read($this->sessionChamarUsuario) : [];
        $valorAtribuir = Util::limpaDocumentos($this->request->query['servidor_cpf']) . '_' . trim($this->request->query['sala']);
        if ($sessionUsuario) {
            if (!key_exists($valorAtribuir, $sessionUsuario)) {
                $sessionUsuario[$valorAtribuir] = true;
                $this->Session->write($this->sessionChamarUsuario, $sessionUsuario);
                echo json_encode(true);
                exit();
            } else {
                echo json_encode(false);
                exit();
            }
        } else {
            $sessionUsuario[$valorAtribuir] = true;
            $sessionUsuario['idAgendamento'] = $this->request->query['idAgendamento'];
            $this->Session->write($this->sessionChamarUsuario, $sessionUsuario);
            echo json_encode(true);
            exit();
        }
    }

    /**
     * Função para retirar o aviso.
     */
    public function avisoConfirmadoSala() {
        $this->layout = "ajax";

        $sessionUsuario = ($this->Session->read($this->sessionChamarUsuario)) ? $this->Session->read($this->sessionChamarUsuario) : [];
        $valorAtribuir = Util::limpaDocumentos($this->request->query['servidor_cpf']) . '_' . trim($this->request->query['sala']);
        if ($sessionUsuario) {
            if (key_exists($valorAtribuir, $sessionUsuario)) {
                $this->loadModel('Agendamento');
                $this->Agendamento->updateAll(array('agendamento_encaminhado_sala' => true), array('id' => $sessionUsuario['idAgendamento']));
                $sessionUsuario[$valorAtribuir] = false;
                $this->Session->write($this->sessionChamarUsuario, $sessionUsuario);
                echo json_encode(true);
                exit();
            }
        }
        echo json_encode(false);
        exit();
    }

    public function download_exigencias($id) {
        if ($this->request->is('GET')) {
            $this->loadModel('Tipologia');

            $filtro = new BSFilter();
            $condicoes = array();
            $condicoes['Atendimento.id'] = $id;
            $filtro->setCondicoes($condicoes);
            $filtro->setTipo('all');

            $filtro->setCamposRetornadosString(
                'Atendimento.id',
                'Atendimento.data_limite_exigencia',
                'Atendimento.data_inclusao',
                'Atendimento.observacoes_exigencias',
                'Agendamento.tipologia_id',
                'Agendamento.tipo',
                'Agendamento.sala',
                'Perito.nome',
                'Perito.numero_registro',
                'Agendamento.num_exigencia'
            );
            $joins = array();
            $joins[] = array(
                'table' => 'usuario',
                'alias' => 'Perito',
                'type' => 'left',
                'conditions' => array('Perito.id = Atendimento.usuario_versao_id')
            );
            $filtro->setJoins($joins);
            $filtro->setContain(['RequisicaoDisponivel', 'Agendamento.Tipologia.nome', 'Agendamento.tipologia_id', 'Agendamento.usuario_servidor_id'
                , 'Agendamento.UsuarioServidor', 'Agendamento.UsuarioServidor.Sexo']);
            $this->Atendimento->Behaviors->load('Containable');
            $this->loadModel('Atendimento');
            $arrAtendimento = $this->Atendimento->listar($filtro);
            $atendimento = (count($arrAtendimento) > 0) ? $arrAtendimento[0] : [];
            $this->set('atendimento', $atendimento);

            $exigencia = $atendimento['Agendamento']['num_exigencia'];
            $this->set('idExig', $exigencia);

            if($atendimento['Agendamento']['tipologia_id'] ==TIPOLOGIA_SINDICANCIA_INQUERITO_PAD){
                $tipo = $this->Tipologia->getTipo($atendimento['Agendamento']['tipo']);
                $this->set('tituloExigencias', __($tipo));
            }else{
                $this->set('tituloExigencias', __($atendimento['Agendamento']['Tipologia']['nome']));
            }


            $currentFunction = $this->request->params['action']; //function corrente
            $currentController = $this->name; //Controller corrente
            $this->saveAuditLog($id,$currentController,'V',$currentFunction);


            $this->layout = 'pdf';
        }
    }

    public function download_laudo($id) {
        if ($this->request->is('GET')) {
            $this->loadModel('Agendamento');

            $filtro = new BSFilter();
            $condicoes = array();
            $condicoes['Atendimento.id'] = $id;
            $filtro->setCondicoes($condicoes);
            $filtro->setTipo('all');
            $filtro->setCamposRetornadosString(
                'Atendimento.id',
                'Atendimento.data_emissao_laudo',
                'Atendimento.parecer',
                'AtendimentoInspecao.recomendacoes',
                'Atendimento.observacoes_cid',
                'Atendimento.invalidez_fisica_id',
                'Atendimento.incap_atos_vida_civil_id',
                'Servidor.id',
                'Servidor.nome',
                'Servidor.cpf',
                'Sexo.nome',
                'Servidor.data_nascimento',
                'Servidor.nome',
                'Servidor.telefone',
                'Servidor.email',
                'Servidor.data_obito',
                'Tipologia.id',
                'Tipologia.nome',
                'Atendimento.data_inclusao',
                'TipoSituacaoParecerTecnico.id',
                'TipoSituacaoParecerTecnico.nome',
                'Atendimento.data_parecer',
                'Atendimento.duracao',
                'Atendimento.modo',
                'Endereco.logradouro',
                'Endereco.numero',
                'Endereco.complemento',
                'Endereco.bairro',
                'Municipio.nome',
                'Estado.sigla',
                'PreAdmissional.pne_pne',
                'PreAdmissional.pne_resultado',
                'Agendamento.id',
                'Agendamento.data_obito',
                'Agendamento.vinculo',
                'Agendamento.data_inclusao',
                'Agendamento.tipo',
                'Agendamento.numero_processo',
                'Agendamento.sala',
                'Agendamento.cpf_pretenso',
                'Agendamento.nome_pretenso',
                'Agendamento.data_nascimento_pretenso',
                'Agendamento.sexo_id_pretenso',
                'AgendamentoServidor.tipo',
                'Agendamento.num_exigencia',
                'Atendimento.situacao_id',
                'ParecerSituacao.nome',
                'Atendimento.numero_nr',
                'Atendimento.numero_anexo',
                'Atendimento.letra',
                'Atendimento.natureza_agente',
                'Atendimento.data_dependente_invalido',
                'Atendimento.data_dependente_inc_atos_vida',
                'Atendimento.data_insencao_temporaria',
                'Atendimento.isencao_id',
                'AgendamentoServidor.numero_processo',
                'Perito.nome',
                'Perito.numero_registro'
            );
            $joins = array();
            $joins[] = array(
                'table' => 'agendamento',
                'alias' => 'AgendamentoServidor',
                'type' => 'inner',
                'conditions' => array('AgendamentoServidor.id = Atendimento.agendamento_id')
            );


            $joins[] = array(
                'table' => 'tipologia',
                'alias' => 'Tipologia',
                'type' => 'inner',
                'conditions' => array('Tipologia.id = AgendamentoServidor.tipologia_id')
            );
            $joins[] = array(
                'table' => 'sexo',
                'alias' => 'Sexo',
                'type' => 'left',
                'conditions' => array('Sexo.id = Servidor.sexo_id')
            );

            $joins[] = array(
                'table' => 'endereco',
                'alias' => 'Endereco',
                'type' => 'left',
                'conditions' => array('Endereco.id = Servidor.endereco_id')
            );

            $joins[] = array(
                'table' => 'municipio',
                'alias' => 'Municipio',
                'type' => 'left',
                'conditions' => array('Municipio.id = Endereco.municipio_id')
            );

            $joins[] = array(
                'table' => 'estado',
                'alias' => 'Estado',
                'type' => 'left',
                'conditions' => array('Estado.id = Endereco.estado_id')
            );

            $joins[] = array(
                'table' => 'sit_parecer_tec',
                'alias' => 'ParecerSituacao',
                'type' => 'left',
                'conditions' => array('ParecerSituacao.id = Atendimento.situacao_id')
            );

            $joins[] = array(
                'table' => 'usuario',
                'alias' => 'Perito',
                'type' => 'left',
                'conditions' => array('Perito.id = Atendimento.usuario_versao_id')
            );

            $filtro->setJoins($joins);
            $this->loadModel('Atendimento');
            $arrAtendimento = $this->Atendimento->listar($filtro);
            $atendimento = (count($arrAtendimento) > 0) ? $arrAtendimento[0] : [];
            //pr($atendimento);die();
            $this->loadModel("Usuario");
            
            $this->set("juntaPeritos", $this->Usuario->listJuntaPeritos($id));

            if($atendimento['Atendimento']['situacao_id'] == SITUACAO_EM_EXIGENCIA){
                $this->download_exigencias($id);
                $this->render('download_exigencias');
                return;
            }

            $idTipo = isset($atendimento['AgendamentoServidor']['tipo'])?$atendimento['AgendamentoServidor']['tipo']:'';

            // $dataParecer = $atendimento['Atendimento']['data_parecer'];
            $dataParecer = $atendimento['Atendimento']['data_inclusao'];
            $this->set('dataParecer', $dataParecer);

            $tipologiaId = $atendimento['Tipologia']['id'];
            if($tipologiaId == TIPOLOGIA_RECURSO_ADMINISTRATIVO){
                $this->set('recursoAdm', "(Recurso Administrativo)");
                $tipologiaLaudo = $this->Atendimento->getTipologiaIdAtendimento($atendimento['AgendamentoServidor']['numero_processo']);
                $agendamentoOriginal = $this->Agendamento->getAgendamentoOriginal($atendimento['AgendamentoServidor']['numero_processo']);

                // pr($agendamentoOriginal);die;

                $dataAgendamentoOrg = $agendamentoOriginal['data_hora'];
                $this->set('dataAgendamentoOrg', $dataAgendamentoOrg);

                $idTipo = isset($agendamentoOriginal['tipo'])?$agendamentoOriginal['tipo']:'';
                $atendimentoOrig = $this->Atendimento->getAtendimentoByAgendamento($agendamentoOriginal['id']);
                $this->set("idOrig", $atendimentoOrig['id']);
            }else{
                $tipologiaLaudo = $tipologiaId;
            }

            if($atendimento['Tipologia']['id'] == TIPOLOGIA_RECURSO_ADMINISTRATIVO){
                $this->set('recursoAdm', "(Recurso Administrativo)");
                $idAgendamentoOrig = $this->Agendamento->getAgendamentoIdOriginal($atendimento['Agendamento']['numero_processo']);


            }


            $this->loadModel('Tipologia');
            $nomeTipologia = $this->Tipologia->getNomeById($tipologiaLaudo);
            $this->set('nomeTipologia', $nomeTipologia);

            $layoutRender = $this->informaArquivoLaudo($tipologiaLaudo, $idTipo);
            // pr($atendimento);die;
            $this->set('atendimento', $atendimento);

            if($tipologiaLaudo == TIPOLOGIA_EXAME_PRE_ADMISSIONAL){
                $idSituacao = $atendimento['TipoSituacaoParecerTecnico']['id'];

                if($idSituacao == TipoSituacaoParecerTecnico::NAO_APTO ){ //Indeferido  - sit_parecer_tec
                    $this->set('apto', "não apto ");
                }else if($idSituacao == TipoSituacaoParecerTecnico::APTO){
                    $this->set('apto', "apto");
                }else{
                    $this->set('apto', "");
                }
            }

            $cargoOrgao = $this->getCargoOrgaoAgrupados($atendimento['Servidor']['id']);
            $this->set(compact('cargoOrgao'));

            $parecer = $atendimento['Atendimento']['parecer'];
            $this->set('parecer', $parecer);

            $exigencia = $atendimento['Agendamento']['num_exigencia'];
            $this->set('idExig', $exigencia);
            //pr($exigencia);die();

            $this->set('pne', $atendimento['PreAdmissional']['pne_pne']);

            $this->set('pne_resultado', $atendimento['PreAdmissional']['pne_resultado']);

            $endereco = $atendimento['Endereco']['logradouro'];
            if(trim($atendimento['Endereco']['numero'])!= ""){
                $endereco .= ", ".$atendimento['Endereco']['numero'];
            }
            if(trim($atendimento['Endereco']['complemento'])!= ""){
                $endereco .= ", ".$atendimento['Endereco']['complemento'];
            }
            if(trim($atendimento['Endereco']['bairro'])!= ""){
                $endereco .= ", ".$atendimento['Endereco']['bairro'];
            }

            $endereco .= ", ".$atendimento['Municipio']['nome'];
            $endereco .= "/".$atendimento['Estado']['sigla'];

            $this->set('endereco', $endereco);

            $currentFunction = $this->request->params['action']; //function corrente
            $currentController = $this->name; //Controller corrente
            $this->saveAuditLog($id,$currentController,'V',$currentFunction);

            $this->layout = 'pdf';
            $this->render($layoutRender);
        }
    }
    /**
     * 
     */
    private function informaArquivoLaudo($idTipologia, $idTipo='') {
        $this->loadModel('Tipologia');

        switch ($idTipologia) {
            case TIPOLOGIA_ADMISSAO_PENSIONISTA_MAIOR_INVALIDO:
                $this->set('tituloLaudo', __('atendimento_laudo_pensao_maior_invalido_titulo'));
                $layout = 'laudo_pensionista_dependente';
                break;
            case TIPOLOGIA_LICENCA_MEDICA_TRATAMENTO_SAUDE:
                $this->set('tituloLaudo', __('atendimento_laudo_licenca_tratamento_saude'));
                $layout = 'laudo_licenca_tratamento_saude';
                break;
            case TIPOLOGIA_APOSENTADORIA_INVALIDEZ:
                $this->set('tituloLaudo', __('atendimento_laudo_aposentadoria_invalidez_titulo'));
                $layout = 'laudo_aposentadoria_invalidez';
                break;
            case TIPOLOGIA_ATECIPACAO_LICENCA:
                $this->set('tituloLaudo', __(''));
                $layout = 'laudo_with_header';
                break;
            case TIPOLOGIA_AVALIACAO_HABILITACAO_DEPENDENTES:
                $this->set('tituloLaudo', __('atendimento_laudo_avaliacao_habilitacao_dependentes'));
                $layout = 'laudo_dependente';
                break;
            case TIPOLOGIA_EXAME_PRE_ADMISSIONAL:
                $this->set('tituloLaudo', __(''));
                $layout = 'laudo_exame_pre_admissional';
                break;
            case TIPOLOGIA_INFORMACAO_SEGURO_COMPREENSIVO_HABITACIONAL:
                $this->set('tituloLaudo', __('INFORMAÇÃO PARA SEGURO COMPREENSIVO HABITACIONAL'));
                $layout = 'laudo_with_header';
                break;
            case TIPOLOGIA_ISENCAO_CONTRIBUICAO_PREVIDENCIARIA:
                $this->set('tituloLaudo', __('atendimento_laudo_isencao_contribuicao_previdenciaria'));
                $layout = 'laudo_isencao_contribuicao';
                break;
            case TIPOLOGIA_LICENCA_ACOMPANHAMENTO_FAMILIAR:
                $this->set('tituloLaudo', __('atendimento_laudo_licenca_acompanhamento_familiar'));
                $layout = 'laudo_licenca_acompanhamento_familiar';
                break;
            case TIPOLOGIA_LICENCA_MATERNIDADE:
                $this->set('tituloLaudo', __('atendimento_laudo_licenca_maternidade'));
                $layout = 'laudo_licenca_maternidade';
                break;
            case TIPOLOGIA_LICENCA_MATERNIDADE_ABORTO:
                $this->set('tituloLaudo', __('atendimento_laudo_licenca_maternidade_aborto'));
                $layout = 'laudo_licenca_maternidade_aborto';
                break;
            case TIPOLOGIA_LICENCA_NATIMORTO:
                $this->set('tituloLaudo', __('atendimento_laudo_licenca_maternidade_natimorto'));
                $layout = 'laudo_licenca_maternidade_natimorto';
                break;
            case TIPOLOGIA_PCD:
                $this->set('tituloLaudo', __(''));
                $layout = 'laudo_with_header';
                break;
            case TIPOLOGIA_READAPTACAO_FUNCAO:
                $this->set('tituloLaudo', __('atendimento_laudo_readaptacao_funcao'));
                $layout = 'laudo_readaptacao_funcao';
                break;
            case TIPOLOGIA_RECURSO_ADMINISTRATIVO:
                $this->set('tituloLaudo', __(''));
                $layout = 'laudo_with_header';
                break;
            case TIPOLOGIA_REMANEJAMENTO_FUNCAO:
                $this->set('tituloLaudo', __('atendimento_laudo_remanejamento_funcao'));
                $layout = 'laudo_remanejamento_funcao';
                break;
            case TIPOLOGIA_REVERSAO_APOSENTADORIA_INVALIDEZ:
                $this->set('tituloLaudo', __('atendimento_laudo_reversao_aposentadoria'));
                // $layout = 'laudo_with_header';
                $layout = 'laudo_reversao_aposentadoria';
                break;
            case TIPOLOGIA_RISCO_VIDA_INSALUBRIDADE:
                $this->set('tituloLaudo', __(''));
                $this->set('tituloLaudo', __('atendimento_laudo_risco_vida_insalubridade'));
                $layout = 'laudo_risco_vida_insalubridade';
                break;
            case TIPOLOGIA_REMOCAO:
                $this->set('tituloLaudo', __('atendimento_laudo_remocao'));
                $layout = 'laudo_remocao';
                break;
            case TIPOLOGIA_DESIGNACAO_DE_ASSISTENTE_TECNICO:
                $this->set('tituloLaudo', __('Laudo Designação de Assistente Técnico'));
                $layout = 'laudo_designacao';
                break;
            case TIPOLOGIA_SINDICANCIA_INQUERITO_PAD:
                $this->set('idTipo', $idTipo);
                $tipo = $this->Tipologia->getTipo($idTipo);
                $this->set('tituloLaudo', __('Laudo de '.$tipo));
                $layout = 'laudo_sindicancia_inquerito_pad';
                break;
            case TIPOLOGIA_COMUNICACAO_DE_ACIDENTE_DE_TRABALHO:
                $this->set('tituloLaudo', __('atendimento_laudo_cat'));
                $layout = 'laudo_cat';
                break;
            case TIPOLOGIA_APOSENTADORIA_ESPECIAL:
                $this->set('tituloLaudo', 'Laudo Aposentadoria Especial');
                $layout = 'laudo_aposentadoria_especial';
                break;

            case TIPOLOGIA_INSPECAO:
                $this->set('tituloLaudo', 'Laudo Inspeção');
                $layout = 'laudo_inspecao';
                break;

            default :
                $this->set('tituloLaudo', __('Default'));
                $layout = 'laudo_with_header';
                break;
        }
        return $layout;
    }



    public function historicoMedico($id) {
        $this->loadModel('Requerimentos');

        $listUsuarioLaudoParecer = $this->Requerimentos->carregaUsuarioLaudoParecer($id);
        $this->set("dadosUsuarioLaudoParecer", $listUsuarioLaudoParecer);

        $listDadosCid = $this->Requerimentos->carregaCid($id);
        $this->set("listDadosCid", $listDadosCid);

        $listDadosExigencia = $this->Requerimentos->carregaExigencia($id);
        $this->set("listDadosExigencia", $listDadosExigencia);
        $this->set("referer", ($this->request->referer()));
    }

    private function listarProcessosPublicacao($data_ini, $data_fim, $nome_usuario = false,$cpf_usuario = false, $orgaoOrigem = false, $publicado = false, $ids = null){
        $this->loadModel('TipoSituacaoParecerTecnico');
        $filtroB = new BSFilter();

        $joins = array();
        $joins[] = array(
            'table' => 'agendamento',
            'alias' => 'AgendamentoServidor',
            'type' => 'inner',
            'conditions' => array('AgendamentoServidor.id = Atendimento.agendamento_id')
        );
        $joins[] = array(
            'table' => 'tipologia',
            'alias' => 'Tipologia',
            'type' => 'inner',
            'conditions' => array('Tipologia.id = AgendamentoServidor.tipologia_id')
        );
        $joins[] = array(
            'table' => 'tipologia',
            'alias' => 'TipologiaRecurso',
            'type' => 'left',
            'conditions' => array('TipologiaRecurso.id = AgendamentoServidor.recurso_tipologia_id')
        );

        $joins[] = array(
            'table' => 'usuario',
            'alias' => 'Usuario',
            'type' => 'left',
            'conditions' => array('AgendamentoServidor.usuario_servidor_id = Usuario.id')
        );

        $joins[] = array(
            'table' => 'vinculo',
            'alias' => 'Vinculo',
            'type' => 'left',
            'conditions' => array('Vinculo.usuario_id = Usuario.id')
        );



        $joins[] = array(
            'table' => 'sit_parecer_tec',
            'alias' => 'ParecerSituacao',
            'type' => 'left',
            'conditions' => array('ParecerSituacao.id = Atendimento.situacao_id')
        );


        $joins[] = array(
            'table' => 'orgao_origem',
            'alias' => 'OrgaoOrigem',
            'type' => 'left',
            'conditions' => array('OrgaoOrigem.id = Vinculo.orgao_origem_id')
        );

        $joins[] = array(
            'table' => 'publicacao_atendimento',
            'alias' => 'PA',
            'type' => 'left',
            'conditions' => array('PA.atendimento_id = Atendimento.id')
        );

        $filtroB->setJoins($joins);

        $condicoes = array();
        $condicoes['Atendimento.status_atendimento'] ='Finalizado';

        if (!empty($data_ini)) {
            $condicoes['cast(Atendimento.data_inclusao as date) >= '] = $data_ini;
        }

        if (!empty($data_fim)) {
            $condicoes['cast(Atendimento.data_inclusao as date) <= '] = $data_fim;
        }

        if(!empty($nome_usuario)){
           $condicoes['Usuario.nome ILIKE '] = "%".$nome_usuario."%";
        }

        if(!empty($cpf_usuario)){
           $condicoes['Usuario.cpf'] = $cpf_usuario;
        }

        if (!empty($orgaoOrigem)) {
            $condicoes['Vinculo.orgao_origem_id'] = $orgaoOrigem;
        }

        $arrTipologias = array( TIPOLOGIA_LICENCA_MATERNIDADE, TIPOLOGIA_LICENCA_MATERNIDADE_ABORTO,
            TIPOLOGIA_LICENCA_NATIMORTO,TIPOLOGIA_LICENCA_ACOMPANHAMENTO_FAMILIAR,TIPOLOGIA_LICENCA_MEDICA_TRATAMENTO_SAUDE );
        $condicoes[] = array(
            'or'=>
                array(
                    'AgendamentoServidor.tipologia_id in'=> $arrTipologias,
                    array(
                        'AgendamentoServidor.tipologia_id'=> TIPOLOGIA_RECURSO_ADMINISTRATIVO,
                        'AgendamentoServidor.recurso_tipologia_id in'=>$arrTipologias
                    )
            )
        );

        if(!$publicado){
            $condicoes['(PA.publicacao_id is null) ='] = 'true';
        }

        if(!empty($ids) && count($ids) > 0){
            $ids[] = 0;
            $condicoes['Atendimento.id in'] = $ids;
        }

        $sitParTecnico[] =TipoSituacaoParecerTecnico::INDEFERIDO;
        $sitParTecnico[] =TipoSituacaoParecerTecnico::DEFERIDO;

        $condicoes['ParecerSituacao.id in'] = $sitParTecnico;

        $filtroB->setCondicoes($condicoes);
        $filtroB->setTipo('all');
        //$filtroB->setCamposOrdenados(array('OrgaoOrigem.orsgao_origem'=>'asc','ParecerSituacao.nome'=>'asc', 'Tipologia.id'=>'asc','Usuario.nome'=>'asc'));

        $filtroB->setCamposAgrupados(array('Usuario.id'));
        //$filtro->setLimiteConsulta($limitConsulta);
        $filtroB->setCamposRetornados(['Usuario.id']);
        $usuarios =  $this->Atendimento->listar($filtroB);
        $vin_ids = array();
        $db = $this->Atendimento->getDataSource();
        foreach($usuarios as $usuario){
            $usu_id =  $usuario['Usuario']['id'];
            $sql = "select v.id from vinculo v
            inner join orgao_origem oo on oo.id = v.orgao_origem_id
            where v.usuario_id = $usu_id
            limit 1";

            $arr = $db->fetchAll($sql);
            $vin_ids[] = (isset($arr[0][0]['id'])) ? $arr[0][0]['id'] : "";

        }
       
        //Só para garantir mais de 1 elemento no array
        $vin_ids[] = 0;
        $vin_ids[] = 0;

        $condicoes['Vinculo.id in '] = $vin_ids;

        $filtroB->setCondicoes($condicoes);
        $filtroB->setTipo('all');
        //$filtroB->setCamposOrdenados(array('OrgaoOrigem.orsgao_origem'=>'asc','ParecerSituacao.nome'=>'asc', 'Tipologia.id'=>'asc','Usuario.nome'=>'asc'));

        $filtroB->setCamposAgrupados(array('OrgaoOrigem.orgao_origem', 'Tipologia.nome', 'TipologiaRecurso.nome', 'Atendimento.modo', 'ParecerSituacao.nome','OrgaoOrigem.id', 'ParecerSituacao.id', 'Tipologia.id',
            'TipologiaRecurso.id', 'Usuario.id','Usuario.nome', 'Vinculo.matricula','Atendimento.data_parecer', 'Atendimento.id','AgendamentoServidor.duracao', 'AgendamentoServidor.data_a_partir', 'AgendamentoServidor.numero_processo'));
        //$filtro->setLimiteConsulta($limitConsulta);
        $filtroB->setCamposOrdenados(array('OrgaoOrigem.orgao_origem'=>'asc','ParecerSituacao.nome'=>'asc', 'Tipologia.id'=>'asc', 'Atendimento.modo'=>'asc','Usuario.nome'=>'asc'));
        $filtroB->setCamposRetornados(
            [
                'OrgaoOrigem.orgao_origem',
                'ParecerSituacao.nome',
                'Tipologia.nome',
                'TipologiaRecurso.nome',
                'TipologiaRecurso.id',
                'Atendimento.modo',
                'Usuario.nome',
                'Vinculo.matricula',
                'Atendimento.data_parecer',
                'Atendimento.id',
                'ParecerSituacao.id',
                'OrgaoOrigem.id',
                'Tipologia.id',
                'AgendamentoServidor.duracao',
                'AgendamentoServidor.data_a_partir',
                'AgendamentoServidor.numero_processo'
            ]
        );
        $lista = $this->Atendimento->listar($filtroB);
        $this->loadModel("Agendamento");
        foreach ($lista as &$item){
            if(!empty($item['AgendamentoServidor']['numero_processo'])){
                $agOrig = $this->Agendamento->getAgendamentoOriginal($item['AgendamentoServidor']['numero_processo']);
                $item['AgendamentoServidor']['duracao'] =  $agOrig['duracao'];
                $atendOrig = $this->Atendimento->getAtendimentoByAgendamento($agOrig['id']);
                $item['Atendimento']['idOrig'] = $atendOrig['id'];
            }
        }
        return $lista;
    }


    public function indexPublicacao(){
        $this->carregarListasOrgaoOrigem();
    }

    public function consultarPublicacoes(){

        if ($this->request->is('GET')) {    
            // die('aq');
            $limitConsulta = $this->request->query['limitConsulta'];
            $dataIni = $this->request->query['data_inicial'];
            $dataFim = $this->request->query['data_final'];
            $nomeUsuario = $this->request->query['nome'];
            $cpfUsuario = Util::limpaDocumentos($this->request->query['cpf']);
            $orgaoOrigem = $this->request->query['orgaoOrigem'];

            $this->loadModel('Publicacao');
            // $this->Publicacao->unbindModel(
            //     array('belongsTo' => array('Usuario'))
            // );
  

            $joins = array();
            
            $joins[] = array(
                'table' => 'publicacao_atendimento',
                'alias' => 'PA',
                'type' => 'left',
                'conditions' => array('PA.publicacao_id = Publicacao.id')
            );

            $joins[] = array(
                'table' => 'atendimento',
                'alias' => 'Atendimento',
                'type' => 'left',
                'conditions' => array('Atendimento.id = PA.atendimento_id')
            );

            $joins[] = array(
                'table' => 'usuario',
                'alias' => 'Usuario',
                'type' => 'left',
                'conditions' => array('Atendimento.usuario_id = Usuario.id')
            );
            
            $joins[] = array(
                'table' => 'vinculo',
                'alias' => 'Vinculo',
                'type' => 'left',
                'conditions' => array('Vinculo.usuario_id = Usuario.id')
            );

            $joins[] = array(
                'table' => 'orgao_origem',
                'alias' => 'OrgaoOrigem',
                'type' => 'left',
                'conditions' => array('OrgaoOrigem.id = Vinculo.orgao_origem_id')
            );

            $options['joins'] = array(
                array(
                    'table' => 'publicacao_atendimento',
                    'alias' => 'PA',
                    'type' => 'left',
                    'conditions' => array('PA.publicacao_id = Publicacao.id')
                ),
                array(
                    'table' => 'atendimento',
                    'alias' => 'Atendimento',
                    'type' => 'left',
                    'conditions' => array('Atendimento.id = PA.atendimento_id')
                ),
                array(
                    'table' => 'usuario',
                    'alias' => 'UsuarioAtendimento',
                    'type' => 'left',
                    'conditions' => array('Atendimento.usuario_id = UsuarioAtendimento.id')
                ),
                array(
                    'table' => 'vinculo',
                    'alias' => 'Vinculo',
                    'type' => 'left',
                    'conditions' => array('Vinculo.usuario_id = UsuarioAtendimento.id')
                )
            );

            $options['conditions'] = array();
            $condicao = array();            

            if(!empty($dataIni)){
                $condicao['(Publicacao.data_publicacao) >= '] = Util::toDBData($dataIni). ' 00:00:00';
                $options['conditions']['(Publicacao.data_publicacao) >= '] = Util::toDBData($dataIni). ' 00:00:00';
            }

            if(!empty($dataFim)){
                $options['conditions']['(Publicacao.data_publicacao) <= '] = Util::toDBData($dataFim). ' 23:59:00';
                $condicao['(Publicacao.data_publicacao) <= '] = Util::toDBData($dataFim). ' 23:59:00';
            }

            if(!empty($nomeUsuario)){
                $options['conditions']['UsuarioAtendimento.nome ILIKE '] = "%".$nomeUsuario."%";
                $condicao['UsuarioAtendimento.nome ILIKE '] = "%".$nomeUsuario."%";
            }

            if(!empty($cpfUsuario)){
               $options['conditions']['Usuario.cpf'] = $cpfUsuario;
               $condicao['UsuarioAtendimento.cpf'] = $cpfUsuario;
            }

            if (!empty($orgaoOrigem)) {
                $options['conditions']['Vinculo.orgao_origem_id'] = $orgaoOrigem;
                $condicao['Vinculo.orgao_origem_id'] = $orgaoOrigem;
            }



            $filtro = new BSFilter();
            $filtro->setTipo('first');
            $filtro->setCamposOrdenadosString(array('Publicacao.data_publicacao'=>'desc'));
            $filtro->setLimiteConsulta($limitConsulta);
            $filtro->setJoins($joins);
            $filtro->setCondicoes($condicao);
            $filtro->setCamposAgrupadosString('Publicacao.id', 'UsuarioVersao.id');


            // $tesPub = $this->Publicacao->find('all', $options);
            // pr($tesPub);die;

            // $publ = $this->Publicacao->listar($filtro);
            // $log = $this->Publicacao->getDataSource()->getLog(false, false);
            // pr($log);die;
            // die('ate aqui');
            // pr($publ);die;

            $this->set('limiteConsultaSelecionado', $limitConsulta);
            $this->set('data_inicial', Util::inverteData($dataIni));
            $this->set('data_final', Util::inverteData($dataFim));
             
            $currentFunction = $this->request->params['action']; //function corrente
            $currentController = $this->name; //Controller corrente
            $this->saveAuditLog(null,$currentController,'C',$currentFunction);
            
            $this->set('publicacoes', $this->paginar($filtro, 'Publicacao'));
            // $this->set('publicacoes', $this->paginate($arr));

            

        }
    }

    // public function consultarPublicacoes(){
    //     if ($this->request->is('GET')) {
    //         $limitConsulta = $this->request->query['limitConsulta'];
    //         $dataIni = $this->request->query['data_inicial'];
    //         $dataFim = $this->request->query['data_final'];
    //         $condicao = array();
    //         if(!empty($dataIni)){
    //             $condicao['(Publicacao.data_publicacao) >= '] = Util::toDBData($dataIni). ' 00:00:00';
    //         }

    //         if(!empty($dataFim)){
    //             $condicao['(Publicacao.data_publicacao) <= '] = Util::toDBData($dataFim). ' 23:59:00';
    //         }

    //         $this->loadModel('Publicacao');
    //         // pr($this->Publicacao);die;
    //         $this->Publicacao->unbindModel(
    //             array('belongsTo' => array('Usuario'))
    //         );
    //         pr($this->Publicacao);die;

    //         $filtro = new BSFilter();
    //         $filtro->setTipo('first');
    //         $filtro->setCamposOrdenadosString(array('Publicacao.data_publicacao'=>'desc'));
    //         $filtro->setLimiteConsulta($limitConsulta);
    //         $filtro->setCondicoes($condicao);

    //         $this->set('limiteConsultaSelecionado', $limitConsulta);
    //         $this->set('data_inicial', Util::inverteData($dataIni));
    //         $this->set('data_final', Util::inverteData($dataFim));
             
    //         $currentFunction = $this->request->params['action']; //function corrente
    //         $currentController = $this->name; //Controller corrente
    //         $this->saveAuditLog(null,$currentController,'C',$currentFunction);
            
    //         $this->set('publicacoes', $this->paginar($filtro, 'Publicacao'));

    //     }
    // }

    public function visualizarProcessosPublicacao($id){
        $this->set('modoVisualizar', true);
        if ($this->request->is('GET')) {
            $this->loadModel('Publicacao');
            $filtro = new BSFilter();
            $filtro->setTipo('all');
            $condicoes = array();
            $condicoes[] = array('Publicacao.id' => $id);
            $filtro->setCondicoes($condicoes);

            $resultado = $this->Publicacao->listar($filtro);
            if(count($resultado) > 0){
                $resultado = $resultado[0];
                $this->set('publicacaoId', $id);
                $dataIni = $resultado['Publicacao']['data_inicial'];
                $this->set('data_inicial',Util::inverteData( $dataIni ));
                $dataFim = $resultado['Publicacao']['data_final'];
                $this->set('data_final', Util::inverteData($dataFim));

                $atendimentos = $resultado['Atendimento'];
                $ids = array();
                foreach($atendimentos as $atendimento){
                    $ids[] = $atendimento['id'];
                }

                $publicacoes = $this->listarProcessosPublicacao($dataIni , $dataFim, true, $ids);
                $this->set('publicacoes', $publicacoes);

                $this->set('diretor_presidente', $resultado['Publicacao']['diretor_presidente']);
                
                
                 
                $currentFunction = $this->request->params['action']; //function corrente
                $currentController = $this->name; //Controller corrente
                $this->saveAuditLog($id,$currentController,'V',$currentFunction);
                
                
            }else{

            }
        }
    }
    public function indexExcluirAtendimento(){
        // $this->loadModel('Usuario');
        // $perfis = $this->Usuario->findById($this->Auth->user()['id'])['Perfil'];
        // $tipoUsuario = $this->Usuario->findById($this->Auth->user()['id'])['TipoUsuario'];
       
        // $administrador = false;
        // foreach ($perfis as $perfil) {
        //     if($perfil['id'] == PERFIL_ADMINISTADOR && $tipoUsuario['id'] == USUARIO_INTERNO){
        //         $administrador = true;
        //         break;
        //     }
        // }
        // if (CakeSession::read('Auth.User.cpf') != '00000000000'){
        // if (CakeSession::read('Auth.User.cpf') != '00000000000' && $administrador == false){
        if (CakeSession::read('Auth.User.admin') != 'true'){
            throw  new Exception("Usuário não possui acesso");
        }
        $this->carregarListaTipologia();
        $situacoes = array('Pendente'=>'Pendente', 'Salvo'=>'Salvo', 'Finalizado'=>'Finalizado');
        $this->set('situacoes', $situacoes);
    }

    public function consultarExcluirAtendimento(){
        $limitConsulta = $this->request->query['limitConsulta'];
        $tipologia = $this->request->query['tipologia_id'];
        $status = $this->request->query['tipo_situacao'];
        $data_inicial = $this->request->query['data_inicial'];
        $data_final = $this->request->query['data_final'];
        $condicoes = null;
        if (!empty($tipologia)) {
            $condicoes['Agendamento.tipologia_id'] = $tipologia;
        }
        if (!empty($status)) {
            $condicoes['Atendimento.status_atendimento'] = $status;
        }

        if (!empty($data_inicial)) {
            $condicoes['cast(Atendimento.data_inclusao as date) >= '] = Util::toDBDataHora($data_inicial);
        }

        if (!empty($data_final)) {
            $condicoes['cast(Atendimento.data_inclusao as date) <= '] = Util::toDBDataHora($data_final);
        }

        $joins = array();
        $joins[] = array(
            'table' => 'tipologia',
            'alias' => 'Tipologia',
            'type' => 'left',
            'conditions' => array('Tipologia.id = Agendamento.tipologia_id')
        );


        $filtro = new BSFilter();
        $filtro->setTipo('all');
        $filtro->setLimiteConsulta($limitConsulta);
        $filtro->setJoins($joins);
        $filtro->setCondicoes($condicoes);
        $filtro->setCamposRetornados(array(
            "Atendimento.id",
            "Atendimento.data_inclusao",
            "Tipologia.nome",
            "Atendimento.status_atendimento",
            "Servidor.nome",
            "TipoSituacaoParecerTecnico.nome"
        ));

        $this->set('limiteConsultaSelecionado', $limitConsulta);
        $processos = $this->paginar($filtro, "Atendimento");

        //pr($processos);
        $this->set(compact("processos"));
    }


    public function getUsuarioCpf() {

        $this->layout = 'ajax';
        if ($this->request->is('post')) {
            $this->loadModel('Usuario');
            $cpf = Util::limpaDocumentos($this->request->data['cpf']);
            $joins = array();
            $conditions = ['Usuario.cpf' => $cpf];

            $arrUsuario = $this->Usuario->obterUsuario($conditions , 'all', $joins);
            if (!empty($arrUsuario[0])) {
                echo json_encode($arrUsuario[0]['Usuario']);
                die;
            } else {
                echo json_encode(['status' => 'danger', 'msg' => __('validacao_busca_servidor_cpf')]);
                die;
            }
        }
        echo json_encode(['status' => 'danger', 'msg3' => 'Problemas ao efetuar requisição']);
        die;
    }

    private function arrCidAgendamento($id){

        $this->loadModel("Cid");
        $arr = ($this->Cid->listarCidAgendamento($id));
        
        $retorno = array();
        foreach ($arr as $item){
            $retorno[] = $item['Cid']['id'];
        }
        return $retorno;
    }



    public function buscarServidorSim(){
        $this->layout = 'ajax';
        if ($this->request->is('get')) {
            $data = $this->request->query;
            $data['nascimento'] = (isset($data['nascimento']) && $data['nascimento'])?Util::toDBData($data['nascimento']):"";
            $data['cpf'] = isset($data['cpf'])?preg_replace("/\W/","",$data['cpf']):"";
            $this->loadModel('Servidores');
            $list = $this->Servidores->listarServidores($data);
            foreach ($list as &$item){
                $item['Pessoas']['nascimento'] = (!empty($item['Pessoas']['nascimento']))?Util::toBrData($item['Pessoas']['nascimento']):"";
                $item['Pessoas']['cpf'] = (!empty($item['Pessoas']['cpf']))?$item['Pessoas']['cpf']:"";
            }
            echo json_encode($list);
        }
        die;
    }


    public function ajaxListaHistorico($id){
        $this->layout = 'ajax';
        if ($this->request->is('get')) {
            $this->Session->write("usuarioHistoricoAjax", $id);
            $this->loadModel('Requerimentos');
            $listHistoricoMedico = $this->Requerimentos->carregaHistoricoMedicoPessoa($id);
            $this->set("listHistoricoMedico",$listHistoricoMedico);
        }
    }

}