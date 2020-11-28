<?php

App::import("Plugin/Web/Controller", "BSController");

class AgendaSistemaController extends BSController {

    private $sessionAgendaSistemaItem = 'agendaSistemaItem';

    public function __construct($request = null, $response = null)
    {
        parent::__construct($request, $response);
        $this->uses = array_merge($this->uses, array('AgendaSistemaItem'));
    }

    private function flashMsg($msg = ""){
        $this->Session->setFlash($msg, false, array(), 'msgAlert');
    }

    public function index() {

    }

    private function carregarSessoes($dataAgenda = array()) {
        if (empty($dataAgenda)) {
            //Carrega as Agendas de Atendimento
            if (!empty($this->Session->read($this->sessionAgendaSistemaItem))) {
                $this->set('agendaSistemaItem', $this->Session->read($this->sessionAgendaSistemaItem));
            }
        } else {

            $agendaSistemaItem = $this->montarListAgendaSistemaItem($dataAgenda['agendaSistemaItem']);
            // pr($dataUser['AgendaAtendimento']);die;
            $this->Session->write($this->sessionAgendaAtendimentoItem, $agendaSistemaItem);
            $this->set('agendaSistemaItem', $agendaSistemaItem);
        }
    }

    private function montarListAgendaSistemaItem($arrAgendas) {
        $retorno = [];
        if (!empty($arrAgendas)) {
            foreach ($arrAgendas as $agenda) {
                $arrHoraInicial = explode(':', $agenda['AgendaSistemaItem']['hora_inicial']);
                $arrHoraFinal = explode(':', $agenda['AgendaSistemaItem']['hora_final']);

                $strHoraInicial = $arrHoraInicial[0] . ':' . $arrHoraInicial[1];
                $strHoraFinal = $arrHoraFinal[0] . ':' . $arrHoraFinal[1];
                $nomes_tipologias = "";
                $ids_tipologias = [];
                if (isset($agenda['Tipologia'])) {
                    foreach ($agenda['Tipologia'] as $key => $tipologia) {
                        if ($key > 0) {
                            $nomes_tipologias = $nomes_tipologias . ", ";
                        }
                        $nomes_tipologias = $nomes_tipologias . $tipologia['nome'];
                        $ids_tipologias[] = $tipologia['id'];
                    }
                }
                $retorno[$agenda['AgendaSistemaItem']['id']]['AgendaSistemaItem'] = [
                    'id' => $agenda['AgendaSistemaItem']['id'],
                    'dia_semana' => $agenda['AgendaSistemaItem']['dia_semana'],
                    'hora_inicial' => $strHoraInicial,
                    'hora_final' => $strHoraFinal,
                    'nome_unidade_atendimento' => $agenda['UnidadeAtendimento']['nome'],
                    'nome_tipologia' => $nomes_tipologias,
                    'unidade_atendimento_id' => ($agenda['AgendaSistemaItem']['unidade_atendimento_id']) ? $agenda['AgendaSistemaItem']['unidade_atendimento_id'] : "",
                    'validado' => $agenda['AgendaSistemaItem']['validado']
                ];
                $retorno[$agenda['AgendaSistemaItem']['id']]['Tipologia'] = $ids_tipologias;
            }
        }
        return $retorno;
    }


    public function consultar() {

        $this->layout = 'ajax';

        if ($this->request->is('GET')){
            $descricao = $this->request->query['data']['AgendaSistema']['descricao'];
            $ini_data_inicio = $this->request->query['data']['AgendaSistema']['ini_data_inicial'];
            $fim_data_inicio = $this->request->query['data']['AgendaSistema']['ini_data_final'];
            $ini_data_final = $this->request->query['data']['AgendaSistema']['fim_data_inicial'];
            $fim_data_final = $this->request->query['data']['AgendaSistema']['fim_data_final'];
            $limitConsulta = $this->request->query['data']['AgendaSistema']['limitConsulta'];

            $condicoes = null;
            if (!empty($descricao)) {
                $condicoes['AgendaSistema.descricao ILIKE'] = "%$descricao%";
            }
            if (!empty($ini_data_inicio)) {
                $condicoes['AgendaSistema.prazo_inicial >= '] = Util::toDBData($ini_data_inicio).' 00:00:00';
            }
            if (!empty($fim_data_inicio)) {
                $condicoes['AgendaSistema.prazo_inicial <= '] = Util::toDBData($fim_data_inicio).' 23:59:59';
            }
            if (!empty($ini_data_final)) {
                $condicoes['AgendaSistema.prazo_final <='] = Util::toDBData($ini_data_final).' 00:00:00';
            }
            if (!empty($fim_data_final)) {
                $condicoes['AgendaSistema.prazo_final <='] = Util::toDBData($fim_data_final).' 23:59:59';
            }
            $condicoes['AgendaSistema.ativo'] = true;

            $currentFunction = $this->request->params['action']; //function corrente
            $currentController = $this->name; //Controller corrente
            $this->saveAuditLog(null,$currentController,'C',$currentFunction);

            $filtro = new BSFilter();

            $filtro->setCondicoes($condicoes);
            $filtro->setLimitarItensAtivos(false);
            $filtro->setLimiteConsulta($limitConsulta);
            //$filtro->setCamposOrdenados(['AgendaSistema.data_cadastro' => 'desc']);

            $this->set('agendaSistema', $this->paginar($filtro));
            $this->set('limiteConsultaSelecionado', $limitConsulta);
        }
    }

    public function adicionar($new = 0){

        if ($this->request->is('post')){

            /*
             * [descricao] => XXXX
               [prazo_inicial] => 01/03/2017
               [prazo_final] =>
             */
            $agendaSistema =$this->request->data['AgendaSistema'];
            if(isset($agendaSistema['id']))unset($agendaSistema['id']);

            $agendaSistemaItem =  $this->Session->read($this->sessionAgendaSistemaItem);


            $data = array(
                'AgendaSistema' => $agendaSistema,
                'AgendaSistemaItem'=> $agendaSistemaItem,
                'Tipologia' => $this->request->data['Tipologia']['Tipologia']);



            if(count($agendaSistemaItem) <= 0){
                echo json_encode(["status"=>"danger","message"=>["erros"=>["descricao"=>["É preciso incluir ao menos um item de agenda"]]]]);
                die;
            }

            if($this->AgendaSistema->saveAll($data)){
                $id = $this->AgendaSistema->getInsertID();

                $currentFunction = $this->request->params['action']; //function corrente
                $currentController = $this->name; //Controller corrente
                $this->saveAuditLog($id,$currentController,'I',$currentFunction);

                $agendaSistema = $this->AgendaSistema->findById($id);

                $agendaValidada = $this->validarPeritoAS($agendaSistema['AgendaSistemaItem']);
                $agendaSistema['AgendaSistema']['validada'] = intval($agendaValidada);
                $data = array(
                    'AgendaSistema' => $agendaSistema['AgendaSistema'],
                    'AgendaSistemaItem'=> $agendaSistema['AgendaSistemaItem'],
                    'Tipologia' => $this->request->data['Tipologia']['Tipologia']);

                $this->AgendaSistema->saveAll($data);

                if(!$agendaValidada){
                    $this->flashMsg('A agenda não foi validada pois, existem horários que não estão disponíveis na agenda dos peritos. Favor, verificar a agenda dos peritos.');
                }else{
                    $this->flashMsg('A agenda foi salva e validada.');
                }

                if($new == 1){
                    echo json_encode(['status'=>'redirect', 'url'=>Router::url(array('controller' => 'AgendaSistema', 'action' => 'adicionar'), true), 'data'=>$data]);
                }else{
                    echo json_encode(['status'=>'redirect', 'url'=>Router::url(array('controller' => 'AgendaSistema', 'action' => 'index'), true), 'data'=>$data]);
                }
                die;
            }else{
                $arrError = ['status'=> 'danger', 'message'=>['erros'=>$this->AgendaSistema->validationErrors]];
                echo json_encode($arrError);
                die;
            }
        }else{
            $this->limparSessoes();
            $this->carregarListasDiasSemana();
            $this->carregarListasUnidadeAtendimento();
            $this->carregarListasTipologia();
        }

        $this->render('edit');
    }

    public function editar($id = null, $duplicar = 0) {
        if (!$id){
            throw new NotFoundException(__('Agenda do Sistema inválida'));
        }
        $this->set('agendaSistemaId', $id);
        if ($this->request->is(array('post', 'put'))) {
            $dataPost =$this->request->data;
            $agendaSistema = $dataPost['AgendaSistema'];
            $agendaSistema['id'] = $id;


            $agendaSistemaItem =  $this->Session->read($this->sessionAgendaSistemaItem);
            $this->loadModel('Tipologia');
            if($duplicar){
                unset($agendaSistema['id']);
                foreach ($agendaSistemaItem as &$innerItem){
                    unset($innerItem['AgendaSistemaItem']['id']);
                }
            }

            $data = array(
                'AgendaSistema' => $agendaSistema,
                'AgendaSistemaItem'=> $agendaSistemaItem,
                'Tipologia' => $dataPost['Tipologia']);


            $oldData = $this->AgendaSistema->findById($id);

            if($this->AgendaSistema->saveAll($data, array('deep'=>true))){



                if ($duplicar){

                    $currentFunction = $this->request->params['action']; //function corrente
                    $currentController = $this->name; //Controller corrente
                    $this->saveAuditLog($id,$currentController,'I',$currentFunction);

                    $this->flashMsg('A agenda foi duplicada.');
                    echo json_encode(['status'=>'redirect', 'url'=>Router::url(array('controller' => 'AgendaSistema', 'action' => 'index'), true), 'data'=>$data]);
                    die;
                }

                $oldAgendaSistemaItem = $oldData['AgendaSistemaItem'];
                $arrOldIds = [];
                foreach ($oldAgendaSistemaItem as $item){
                    $arrOldIds[] = $item['AgendaSistemaItem']['id'];
                }
                $arrNewIds = [];
                foreach ($agendaSistemaItem as $item){
                    if(isset($item['AgendaSistemaItem']['id']))$arrNewIds[] = $item['AgendaSistemaItem']['id'];
                }
                foreach ($arrOldIds as $idItem){
                    if (!in_array($idItem, $arrNewIds)){
                        $this->AgendaSistemaItem->delete($idItem);
                    }
                }
                $agendaSistema = $this->AgendaSistema->findById($id);

                $agendaValidada = $this->validarPeritoAS($agendaSistema['AgendaSistemaItem']);

                $agendaSistema['AgendaSistema']['validada'] = intval($agendaValidada);
                $data = array(
                    'AgendaSistema' => $agendaSistema['AgendaSistema'],
                    'AgendaSistemaItem'=> $agendaSistema['AgendaSistemaItem'],
                    'Tipologia' => $dataPost['Tipologia']);

                $this->AgendaSistema->saveAll($data);

                $currentFunction = $this->request->params['action']; //function corrente
                $currentController = $this->name; //Controller corrente
                $this->saveAuditLog($id,$currentController,'A',$currentFunction);

                echo json_encode($agendaSistema); die;
            }else{
                $arrError = ['status'=> 'danger', 'message'=>['erros'=>$this->AgendaSistema->validationErrors]];
                echo json_encode($arrError);
                die;
            }
        } else {

            $agendaSistema = $this->AgendaSistema->findById($id);

            //pr($agendaSistema); die;
            $agendaSistema['AgendaSistema']['prazo_inicial'] = Util::toBrData($agendaSistema['AgendaSistema']['prazo_inicial']);
            $agendaSistema['AgendaSistema']['prazo_final'] = Util::toBrData($agendaSistema['AgendaSistema']['prazo_final']);

            $agendaSistemaItem = $this->montarListAgendaSistemaItem($agendaSistema['AgendaSistemaItem']);
            // pr($dataUser['AgendaAtendimento']);die;
            $this->Session->write($this->sessionAgendaSistemaItem, $agendaSistemaItem);
            $this->set('AgendaSistemaItem', $agendaSistemaItem);

            $this->carregarListasDiasSemana();
            $this->carregarListasUnidadeAtendimento();
            $this->carregarListasTipologia();

            if (!$this->request->data) {
                $this->request->data = $agendaSistema;
            }

        }
        $this->render('edit');
    }

    /**
     * Método utilizado para visualizar um Perfil
     * @param string $id identificador do Perfil
     */
    public function visualizar($id = null) {
        if (!$id){
            throw new NotFoundException(__('Agenda do Sistema inválida'));
        }

        $this->set('agendaSistemaId', $id);
        $agendaSistema = $this->AgendaSistema->findById($id);

        //pr($agendaSistema); die;
        $agendaSistema['AgendaSistema']['prazo_inicial'] = Util::toBrData($agendaSistema['AgendaSistema']['prazo_inicial']);
        $agendaSistema['AgendaSistema']['prazo_final'] = Util::toBrData($agendaSistema['AgendaSistema']['prazo_final']);

        $agendaSistemaItem = $this->montarListAgendaSistemaItem($agendaSistema['AgendaSistemaItem']);
        // pr($dataUser['AgendaAtendimento']);die;
        $this->Session->write($this->sessionAgendaSistemaItem, $agendaSistemaItem);
        $this->set('AgendaSistemaItem', $agendaSistemaItem);

        $this->carregarListasDiasSemana();
        $this->carregarListasUnidadeAtendimento();
        $this->carregarListasTipologia();

        if (!$this->request->data) {
            $this->request->data = $agendaSistema;
        }

        $currentFunction = $this->request->params['action']; //function corrente
        $currentController = $this->name; //Controller corrente
        $this->saveAuditLog($id,$currentController,'V',$currentFunction);

        $this->set('formDisabled2', 1);
        $this->render('edit');
    }


    private function carregarListasDiasSemana() {
        $diasSemana = ['Domingo' => 'Domingo',
            'Segunda-feira' => 'Segunda-feira',
            'Terça-feira' => 'Terça-feira',
            'Quarta-feira' => 'Quarta-feira',
            'Quinta-feira' => 'Quinta-feira',
            'Sexta-feira' => 'Sexta-feira',
            'Sábado' => 'Sábado'];
        $this->set(compact('diasSemana'));
    }

    private function carregarListasUnidadeAtendimento() {
        $this->loadModel('UnidadeAtendimento');
        $filtro = new BSFilter();
        $filtro->setTipo('list');
        $filtro->setCamposOrdenadosString('UnidadeAtendimento.nome');
        $unidadeAtendimento = $this->UnidadeAtendimento->listar($filtro);

        $condicoes = array('UnidadeAtendimento.atendimento_domicilio' => true);
        $filtro->setCondicoes($condicoes);
        $unidadeAtendimentoDomicilio = $this->UnidadeAtendimento->listar($filtro);

        // pr($unidadeAtendimentoDomicilio);die;

        $this->set(compact('unidadeAtendimento', 'unidadeAtendimentoDomicilio'));
    }

    private function carregarListasTipologia() {
        $this->loadModel('Tipologia');
        $filtro = new BSFilter();
        $filtro->setTipo('list');
        $filtro->setCamposOrdenadosString('Tipologia.nome');
        $tipologia = $this->Tipologia->listar($filtro);
        $this->set(compact('tipologia'));
    }




    public function verificaRemocaoTipologia() {
        $this->layout = 'ajax';
        $retorno = false;
        if ($this->request->is('POST')) {
            $arrId = $this->request->data;
            $arraySessionAgenda = $this->Session->read($this->sessionAgendaSistemaItem);
            if ($arraySessionAgenda) {
                foreach ($arraySessionAgenda as $line) {
                    if (in_array($arrId['id'][0], $line['Tipologia'])) {
                        $retorno = true;
                        break;
                    }
                }
            }
        }
        echo json_encode($retorno);
        die;
    }


    /**
     * Adicionar Agenda de atendimento
     */
    public function adicionarAgendaSistemaItem() {
        $this->validateAgendaSistemaItem();

        $agendaAtendimento = $this->Session->read($this->sessionAgendaSistemaItem);

        if (is_null($agendaAtendimento)) {
            $agendaAtendimento = array();
        }

        $diaSemana = $this->request->data['dia_semana'];
        $horaInicial = $this->request->data['hora_inicial'];
        $horaFinal = $this->request->data['hora_final'];
        $unidadeAtendimentoId = $this->request->data['unidade_atendimento_id'];
        $nomeUnidadeAtendimento = $this->request->data['nome_unidade_atendimento'];
        $tipologia = $this->request->data['Tipologia'];
        $nomeTipologia = $this->request->data['nome_tipologia'];
        $agendaAtendimentoTemp['AgendaSistemaItem'] = [
            'dia_semana' => $diaSemana,
            'hora_inicial' => $horaInicial,
            'hora_final' => $horaFinal,
            'unidade_atendimento_id' => $unidadeAtendimentoId,
            'nome_unidade_atendimento' => $nomeUnidadeAtendimento,
            'nome_tipologia' => $nomeTipologia,
            'validado' => false
        ];
        $agendaAtendimentoTemp['Tipologia'] = $tipologia;
        $agendaAtendimento[] = $agendaAtendimentoTemp;
        $this->Session->write($this->sessionAgendaSistemaItem, $agendaAtendimento);
        echo json_encode($agendaAtendimento);
        die;
    }

    /**
     * Valida os Agenda de atendimentos.
     */
    public function validateAgendaSistemaItem(){
        $this->loadModel('AgendaSistemaItem');

        if ($this->request->is('post')) {
            $this->AgendaSistemaItem->set($this->request->data);
            $validacoes = array();

            $listAgendaAtendimento = $this->Session->read($this->sessionAgendaSistemaItem);

            if (!$this->AgendaSistemaItem->validates()) {
                $validacoesAgendaAtendimento = $this->AgendaSistemaItem->validationErrors;
                array_push($validacoes, $validacoesAgendaAtendimento);
                $this->tratarValidacoes($validacoes);
            }

            if (!is_null($listAgendaAtendimento) && !empty($listAgendaAtendimento)) {
                if ($this->AgendaSistemaItem->verificaIntercessaoHorario($listAgendaAtendimento, $this->request->data)) {
                    array_push($validacoes, ['hora_inicial' => __('validacao_horario_intercessao')]);
                    $this->tratarValidacoes($validacoes);
                }
            }
        }
    }

    public function tratarValidacoes($validacoces) {
        if (!empty($validacoces)) {
            $this->layout = null;
            $data = array();
            foreach ($validacoces as $key => $field) {
                foreach ($field as $key => $erro) {
                    $erros[] = $erro;
                }
            }

            if (!empty($validacoces)) {
                $data = Array(
                    "status" => "danger",
                    "message" => compact('message', 'erros')
                );
            }

            $this->set('data', $data);
            $this->render('/General/SerializeJson/');
            $this->renderView();
        }
    }

    private function limparSessoes() {
        $this->Session->delete($this->sessionAgendaSistemaItem);
    }

    public function obterAgendaSistemaItem() {
        $this->layout = 'ajax';
        $retorno = false;
        if (($this->request->is('POST')) && (!empty($this->Session->read($this->sessionAgendaSistemaItem)))) {
            $id = $this->request->data['id'];
            $arraySessionAgenda = $this->Session->read($this->sessionAgendaSistemaItem);
            $retorno = $arraySessionAgenda[$id];


            $currentFunction = $this->request->params['action']; //function corrente
            $currentController = $this->name; //Controller corrente
            $this->saveAuditLog($id,$currentController,'C',$currentFunction);

        }
        echo json_encode($retorno);
        die;
    }

    public function atualizarAgendaSistemaItem() {
        $this->validateAgendaSistemaItem();
        $agendaSistemaItem = (is_null($this->Session->read($this->sessionAgendaSistemaItem))) ? [] :
            $this->Session->read($this->sessionAgendaSistemaItem);

        $id = $this->request->data['id'];
        $diaSemana = $this->request->data['dia_semana'];
        $horaInicial = $this->request->data['hora_inicial'];
        $horaFinal = $this->request->data['hora_final'];
        $unidadeAtendimentoId = $this->request->data['unidade_atendimento_id'];
        $nomeUnidadeAtendimento = $this->request->data['nome_unidade_atendimento'];
        $tipologia = $this->request->data['Tipologia'];
        $nomeTipologia = $this->request->data['nome_tipologia'];
        $agendaAtendimentoTemp['AgendaSistemaItem'] = [
            'id' => $id,
            'dia_semana' => $diaSemana,
            'hora_inicial' => $horaInicial,
            'hora_final' => $horaFinal,
            'unidade_atendimento_id' => $unidadeAtendimentoId,
            'nome_unidade_atendimento' => $nomeUnidadeAtendimento,
            'nome_tipologia' => $nomeTipologia];
        $agendaAtendimentoTemp['Tipologia'] = $tipologia;

        $agendaSistemaItem[$id] = $agendaAtendimentoTemp;

        $this->Session->write($this->sessionAgendaSistemaItem, $agendaSistemaItem);
        echo json_encode($agendaSistemaItem);
        die;
    }

    public function deletarAgendaSistemaItem() {
        $retorno = false;
        $id = $this->request->data['key'];
        $agendaSistemaItem = $this->Session->read($this->sessionAgendaSistemaItem);
        if (!is_null($agendaSistemaItem)) {
            unset($agendaSistemaItem[$id]);
            $this->Session->write($this->sessionAgendaSistemaItem, $agendaSistemaItem);
            $retorno = true;
        }
        echo json_encode($retorno);
        die;
    }

    private function validarPeritoAS(&$agendaSistemaItem){
        $agendaValidada = true;
        foreach ($agendaSistemaItem as &$item){
            $arrTipologiaIds = [];
            $arrTipologia = $item['Tipologia'];
            $validado = true;
            foreach ($arrTipologia as $tipologia){
                $tipologia = is_array($tipologia)?$tipologia['id']:$tipologia;
                $validado = $validado && $this->validarPeritoASI($item['AgendaSistemaItem'], $tipologia);
                $arrTipologiaIds[] = $tipologia;
            }
            
            $item['AgendaSistemaItem']['validado'] = intval($validado);
            unset($item['UnidadeAtendimento']);
            $item['Tipologia'] = $arrTipologiaIds;
            $agendaValidada = $agendaValidada && $validado;
        }
        return $agendaValidada;
    }

    public function show($id){

        echo json_encode($listAgendaAtendimento = $this->Session->read($this->sessionAgendaSistemaItem));
        pr($listAgendaAtendimento);
        die;
    }

    public function validarPeritoASI($item, $tipologia){
        $db = $this->AgendaSistemaItem->getDataSource();

        $sql = "select count(*) as \"exist\", aat.tipologia_id, aa.dia_semana, aa.unidade_atendimento_id, aa.hora_final
            from agen_aten_tip aat
            INNER JOIN agenda_atendimento aa on aa.id = aat.agen_aten_id
        where aat.tipologia_id = $tipologia and 
            aa.dia_semana = '{$item['dia_semana']}' and  aa.hora_final = '{$item['hora_final']}' and 
            aa.unidade_atendimento_id = '{$item['unidade_atendimento_id']}' and aa.ativo = true
        GROUP by aat.tipologia_id, aa.dia_semana, aa.unidade_atendimento_id, aa.hora_final 
            ";

        $sql_domicilio = "select count(*) as \"exist\", aat.tipologia_id, aa.dia_semana, aa.unidade_atendimento_id, aa.hora_final
            from agen_aten_domic_tip aat
            INNER JOIN agenda_atendimento_domicilio aa ON aa. ID = aat.agend_atendi_domic_id
        where aat.tipologia_id = $tipologia and 
            aa.dia_semana = '{$item['dia_semana']}' and  aa.hora_final = '{$item['hora_final']}' and 
            aa.unidade_atendimento_id = '{$item['unidade_atendimento_id']}' and aa.ativo = true
        GROUP by aat.tipologia_id, aa.dia_semana, aa.unidade_atendimento_id, aa.hora_final 
            ";

        $arr = $db->fetchAll($sql);
        $arr_domicilio = $db->fetchAll($sql_domicilio);
        if(count($arr)>0 && $arr[0][0]['exist']>0 || count($arr_domicilio)>0 && $arr_domicilio[0][0]['exist']>0){
            return true;
        }
        return false;
    }

    public function deletar($id){
        if ($this->request->is('get')) {
            $this->AgendaSistema->delete($id);

            $currentFunction = $this->request->params['action']; //function corrente
            $currentController = $this->name; //Controller corrente
            $this->saveAuditLog($id,$currentController,'E',$currentFunction);
            die;
        }
    }

    public function  test(){
        $this->loadModel('AgendaSistemaItem');

        var_dump($this->AgendaSistemaItem->consultarAgendaSistemaItem(9, 3, 'Segunda-feira', '10:00'));
        die;
    }
}
