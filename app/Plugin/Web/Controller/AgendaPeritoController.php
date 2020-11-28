<?php

App::import("Plugin/Web/Controller", "BSController");

class AgendaPeritoController extends BSController {

    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('testeCarregamentoAgenda');
    }

    public function __construct($request = null, $response = null)
    {
        parent::__construct($request, $response);
    }

    private function flashMsg($msg = ""){
        $this->Session->setFlash($msg, false, array(), 'msgAlert');
    }

    public function index() {


        // pr(Util::getAllDatasEntrePeriodo('2019-10-01', '2019-10-20'));die;

        $tipologias = $this->carregarTipologias();
        $this->set('tipologias', $tipologias);

        $unidades = $this->carregarUnidades();
        $this->set('unidades', $unidades);
    }


    public function concatenaTipologias($arrayTipologias){   
        $todasTipologias = "";
        $aux = 0;
        foreach ($arrayTipologias as $tipologia) {
            $todasTipologias .= $tipologia['nome'];
            $aux++;
            if($aux != count($arrayTipologias)){
                $todasTipologias .= ", ";
            }

        }
        return $todasTipologias;
    }



    public function concatenaTipologiasIntersecao($arrayTipologias,$arrayTipologias2){  

        $auxArrayTipologias = array();

        foreach($arrayTipologias as $tipologiaDiaSemana) {
            foreach ($tipologiaDiaSemana as $tip) {
                
            array_push($auxArrayTipologias, $tip);
            
            }
           
        }

        $tipologiasInner = Util::array_intersect_recursive($auxArrayTipologias, $arrayTipologias2);

        $todasTipologias = "";
        $aux = 0;
        foreach ($tipologiasInner as $tipologia) {
            $todasTipologias .= $tipologia['nome'];
            $aux++;
            if($aux != count($tipologiasInner)){
                $todasTipologias .= ", ";
            }

        }

        return $todasTipologias;
    }

    public function testeCarregamentoAgenda(){
        
      
        $dataInicial = "";
        $dataFinal = "";
        $cpf = "";
        $tipologias = "";

        return  $this->carregaTodasAgendasExternaPerito($dataInicial, $dataFinal, $cpf, $tipologias);die;
    }

    public function tipologiaExisteNaAgenda($idAgenda, $idTipologia, $unidade = null){
        $this->loadModel('AgendaSistema');
        $this->loadModel('Tipologia');
        $hasUnidade = 1;

        if(empty($unidade)){
            $hasUnidade = 0;
            $unidade = array(0,0);
        }else if($unidade == array(0,0)){
            $hasUnidade = 0;
        }

        $agendasIds = $this->AgendaSistema->find('list', array(
            'fields' => array('AgendaSistema.id'),
            'joins' => array(
                    array('table' => 'agenda_sistema_item',
                        'alias' => 'AgendaSistemaItem',
                        'type' => 'INNER',
                        'conditions' => array(
                           'AgendaSistemaItem.agenda_sistema_id = AgendaSistema.id',
                            array('or'=> array(
                                'AgendaSistemaItem.unidade_atendimento_id in' => $unidade,
                                "(0=$hasUnidade)"
                            ))
                        )
                    ),
                     array('table' => 'agen_sist_item_tip',
                        'alias' => 'AgendaSistemaItemTipologia',
                        'type' => 'INNER',
                        'conditions' => array(
                           'AgendaSistemaItemTipologia.agenda_sistema_item_id = AgendaSistemaItem.id'
                        )
                    )
                ),
            'conditions' => array('AgendaSistema.id' => $idAgenda)
        ));

        return !empty($agendasIds);

    }


    public function retornaAgendaSomenteTipologia($itemAgenda, $idTipologias, $unidades = null){
        $this->loadModel('Tipologia');

        $tipologias = array(); 

        foreach ($idTipologias as $tipo) {
            if($this->tipologiaExisteNaAgenda($itemAgenda['AgendaSistema']['id'], $tipo, $unidades)){
                $tipologiaById = $this->Tipologia->findById($tipo);
                unset($tipologiaById['UsuarioAlteracao']);
                $tipologias['Tipologia'][] = $tipologiaById['Tipologia'];            
            }
        }    

        $tipologiasInner = Util::array_intersect_recursive($itemAgenda['Tipologia'] , $tipologias['Tipologia']);

        if(!empty($tipologiasInner)){
            $itemAgenda['Tipologia'] = $tipologiasInner;
        }else{
            $itemAgenda = array();
        }

        return $itemAgenda;
       
    }

    public function checaFeriado($data){
        $this->loadModel("Feriado");
        $feriado = $this->Feriado->find('first', array(
                'conditions' => array(
                    'EXTRACT(MONTH FROM data_feriado) =' => date('m', strtotime($data)),
                    'EXTRACT(DAY FROM data_feriado) =' => date('d', strtotime($data)),
                    'Feriado.ativo' => true
                    ),
                'recursive' => -1
            ));
        
        if(!empty($feriado)){
            return true;
        }else{
            return false;
        }
        
    }

    public function getAgendasIdsByData($data, $unidades, $tipologias){
        
        
        $this->loadModel('AgendaSistema');


        $hasUnidade = 1;
        if(empty($unidades)){
            $hasUnidade = 0;
            $unidades =array(0, 0);
        }else{
            $unidades[] =0;
        }

        $hasTipologia = 1;
        if(empty($tipologias)){
            $tipologias = array(0, 0);
            $hasTipologia = 0;
        }else{
            $tipologias[] = 0;
        }

        
        $conditionsAgendaSistema = array(
            'AgendaSistema.ativo ' => true,
            'AgendaSistema.habilitada ' => true,
            'AgendaSistema.validada ' => true,
            'AgendaSistema.prazo_inicial <= ' => $data,
            'AgendaSistema.prazo_final >= ' => $data,
            
        );
        
        $agendasIds = $this->AgendaSistema->find('list', array(
                'fields' => array('AgendaSistema.id'),
                'joins' => array(
                        array('table' => 'agenda_sistema_item',
                            'alias' => 'AgendaSistemaItem',
                            'type' => 'INNER',
                            'conditions' => array(
                                'AgendaSistemaItem.agenda_sistema_id = AgendaSistema.id',
                                array('or'=> array(
                                    'AgendaSistemaItem.unidade_atendimento_id in' => $unidades,
                                    "(0=$hasUnidade)"
                                )),
                                'AgendaSistemaItem.ativo' => true,
                                'AgendaSistemaItem.validado' => true
                            )
                        ),
                         array('table' => 'agen_sist_item_tip',
                            'alias' => 'AgendaSistemaItemTipologia',
                            'type' => 'INNER',
                            'conditions' => array(
                               'AgendaSistemaItemTipologia.agenda_sistema_item_id = AgendaSistemaItem.id',
                                array('or' =>  array(
                                    'AgendaSistemaItemTipologia.tipologia_id in' => $tipologias,
                                    "0 = $hasTipologia"
                                ))
                            )
                         )
                    ),
                'conditions' => $conditionsAgendaSistema,
                'group' => array('AgendaSistema.id')
            ));

        return $agendasIds;
    }
   
    public function carregaTodasAgendasExternaPerito($dataInicial, $dataFinal, $cpf, $tipologias, $unidades = null, $peritoAtende = null){

        $periodo = Util::getAllDatasEntrePeriodo($dataInicial, $dataFinal);
        $agendasIds = array();
        
        $agendasRetorno = array();


        $this->loadModel('AgendaAtendimento');
        $this->loadModel('AgendaSistema');
        $this->loadModel('AgendaSistemaItem');
        $this->loadModel('Tipologia');
        $this->loadModel('UnidadeAtendimento');

        $unidadesInicio   = $unidades;
        $tipologiasInicio = $tipologias;
        
        foreach($periodo as $diaPercorrido){

            if(!$this->checaFeriado($diaPercorrido)){
                $newAgendasIds = array();
                $newAgendasIds = $this->getAgendasIdsByData($diaPercorrido, $unidadesInicio, $tipologiasInicio);
                
                if(!empty($newAgendasIds)){
                    
                    $agendasIds = $newAgendasIds;

                    $this->loadModel('AgendaAtendimento');
                    $this->loadModel('AgendaSistema');
                    $this->loadModel('AgendaSistemaItem');
                    $this->loadModel('Tipologia');
                    $this->loadModel('UnidadeAtendimento');

                    $unidades = array();
                    $unidades = $unidadesInicio;
                    $hasUnidade = 1;
                    if(empty($unidades)){
                        $hasUnidade = 0;
                        $unidades =array(0, 0);
                    }else{
                        $unidades[] =0;
                    }

                    $tipologias = array();
                    $tipologias = $tipologiasInicio;
                    $hasTipologia = 1;
                    if(empty($tipologias)){
                        $tipologias = array(0, 0);
                        $hasTipologia = 0;
                    }else{
                        $tipologias[] = 0;
                    }
                    
                    //$agendasIds = array_unique($agendasIds);
                    if(count($agendasIds) == 1){
                        $agendasIds[] = 0;
                    }else if(empty($agendasIds)){
                        $agendasIds = array(0,0);
                    }

                    $diaDaSemana =  Util::getDiaDaSemanaByData($diaPercorrido);

                        $agendaSistemaItens = $this->AgendaSistemaItem->find('all',
                        array(
                            'conditions' => array(
                                'AgendaSistemaItem.agenda_sistema_id = AgendaSistema.id',
                                array('or'=> array(
                                    'AgendaSistemaItem.unidade_atendimento_id in' => $unidades,
                                    "(0=$hasUnidade)"
                                )),
                                'AgendaSistema.id in' => $agendasIds,
                                'AgendaSistemaItem.ativo' => true,
                                'AgendaSistemaItem.validado' => true,
                                'AgendaSistemaItem.dia_semana' => $diaDaSemana,
                            ),
                            'joins' => array(
                                array(
                                    'table' => 'agen_sist_item_tip',
                                    'alias' => 'AgendaSistemaItemTipologia',
                                    'type' => 'INNER',
                                    'conditions' => array(
                                        'AgendaSistemaItemTipologia.agenda_sistema_item_id = AgendaSistemaItem.id',
                                        array('or' =>  array(
                                            'AgendaSistemaItemTipologia.tipologia_id in' => $tipologias,
                                            "0 = $hasTipologia"
                                        ))
                                    )
                                )
                            )
                        )
                    );

                    
                    

                    $agendaAtendimento          = array();
                    $tipologiasAgendaSistema    = array();
                    $todasAgendas               = array();

                    if(!empty( $agendaSistemaItens)){
                        
                        $tipologiasAgendaItem = array();
    
                        foreach ($agendaSistemaItens as $agendaSistemaItem ) {
                            $item = array();
                            
                            if( !empty( $agendaSistemaItem['AgendaSistemaItem'] ) ) {
                                $item = $agendaSistemaItem['AgendaSistemaItem'];
    
                                if ($item['validado'] == true) {
    
                                    $encoding = mb_internal_encoding();
                                    $diaAgenda = mb_strtoupper($item['dia_semana'], $encoding);
    
                                    $horario = $item['hora_inicial'] . ' - ' . $item['hora_final'];
    
                                    $tipologiasAgendaItem[$diaAgenda][$horario][] = $agendaSistemaItem['Tipologia'];
    
                                    $itemTipologiaIds = array();
                                    foreach ($agendaSistemaItem['Tipologia'] as $itemTipologia){
                                        $itemTipologiaIds[] = $itemTipologia['id'];
                                    }
                                    if(count($itemTipologiaIds) == 1){
                                        $itemTipologiaIds[] = 0;
                                    }   
    
                                    $conditionPeritoAtende = array();
    
                                    if($peritoAtende == '1'){
                                        $conditionPeritoAtende = array('AgendaAtendimento.permitir_agendamento' => 'true');
                                    }else if($peritoAtende == '0'){
                                        $conditionPeritoAtende = array('AgendaAtendimento.permitir_agendamento' => 'false');
                                    }

                                    
    
                                    $conditions = array(
                                        'AgendaAtendimento.unidade_atendimento_id' => $item['unidade_atendimento_id'],
                                        'AgendaAtendimento.dia_semana' => $diaDaSemana,
                                        'AgendaAtendimento.hora_inicial' => $item['hora_inicial'],
                                        'AgendaAtendimento.hora_final' => $item['hora_final'],
                                        'AgendaAtendimento.ativo' => true,
                                        $conditionPeritoAtende
    
                                    );
                                    // pr($conditions);die;
                                    if ($cpf != "") {
                                        $c = array('Usuario.cpf' => trim($cpf));
                                        array_push($conditions, $c);
                                    }
    
                                    $agenAten = $this->AgendaAtendimento->find('all', array(
                                        'conditions' => $conditions,
                                        // 'recursive' => -1,
                                        'joins'=> array(
                                            array(
                                                'table' => 'agen_aten_tip',
                                                'alias' => 'AgenTip',
                                                'type' => 'INNER',
                                                'conditions' => array(
                                                    'AgenTip.agen_aten_id = AgendaAtendimento.id',
                                                    array('or'=>
                                                        array(
                                                            'AgenTip.tipologia_id in' => $tipologias,
                                                            "0=$hasTipologia"
                                                        )
                                                    ),
                                                    'AgenTip.tipologia_id in' => $itemTipologiaIds
                                                )
                                            )
                                        )
                                    ));
    
                                    // $log = $this->AgendaSistemaItem->getDataSource()->getLog(false, false);
                                    // pr($log);
                                    // die;
    
    
                                    
    
                                    if (!empty($agenAten)) {
                                        //pr($agenAten); die;
                                        $agenAten['AgendaSistema'] = $agendaSistemaItem['AgendaSistema'];
                                        array_push($agendaAtendimento, $agenAten);
                                    }
                                }
                            }
                        }
                        
                        // pr($agendaAtendimento);die;
    
                        foreach ($agendaAtendimento as $kI => $ag) {
                            foreach ($ag as $k=>$agen) {
                                
                                if($k === 'AgendaSistema')continue;
    
                                $diaAgenda = $agen['AgendaAtendimento']['dia_semana'];
                                
                                $datasPorDia = array();
    
                                $encoding = mb_internal_encoding();
                                // mb_strtoupper por causa do Ç da TERÇA
                                $diaAgenda = mb_strtoupper($diaAgenda, $encoding);
                                
                                $dataIni = $ag['AgendaSistema']['prazo_inicial'];
                                $dataFim = $ag['AgendaSistema']['prazo_final'];
                                $datasPorDia = Util::retornaDatasPorDia($diaAgenda, $dataIni, $dataFim);
    
                                $data = $diaPercorrido;
                                //foreach ($datasPorDia as $data) {
                                    $horario = $agen['AgendaAtendimento']['hora_inicial'] . " - " . $agen['AgendaAtendimento']['hora_final'];
    
                                    $tipologiasAgendaItem[$diaAgenda][$horario] = array_map("unserialize", array_unique(array_map("serialize", $tipologiasAgendaItem[$diaAgenda][$horario])));
    
                                    $tipologiasConcatenadas = $this->concatenaTipologiasIntersecao($tipologiasAgendaItem[$diaAgenda][$horario] ,$agen['Tipologia']);
                                    if(empty($tipologiasConcatenadas) OR $tipologiasConcatenadas == ""){
                                        continue;
    
                                    }else{
                                        $horario = $agen['AgendaAtendimento']['hora_inicial'] . " - " . $agen['AgendaAtendimento']['hora_final'];
    
                                        $new = array(
                                            'UnidadeAtendimento' =>  $agen['UnidadeAtendimento']['nome'],
                                            'Perito' => $agen['Usuario']['nome'],
                                            'Tipologias' => $tipologiasConcatenadas
                                        );
                                        
                                        // pr($data);
                                        $todasAgendas[$data][$horario][] = $new;
                                        
                                        $todasAgendas[$data][$horario] = array_map("unserialize", array_unique(array_map("serialize", $todasAgendas[$data][$horario])));
                                        
                                        ksort($todasAgendas[$data]);
                                        $agendasRetorno =  array_merge($agendasRetorno, $todasAgendas);
                                        // pr($todasAgendas);
    
                                    }
                                //}
                            } // END foreach ($ag as $agen)

                        } // END  foreach ($agendaAtendimento as $ag)
    
                    } // END - !empty( $agendaSistemaItens)


                } // END - !empty($agendasIds)

            } // END - checaFeriado($dia)

        }// END - foreach($periodo as $dia)

        // $agendaSistema['AgendaSistema'] = array_map("unserialize", array_unique(array_map("serialize", $agendaSistema['AgendaSistema'])));

        // die;
        
        // pr($agendasRetorno);die;
        ksort($agendasRetorno); 
        return $agendasRetorno;
        
    }

    public function carregarTodasAgendasPeritos($dataInicial, $dataFinal, $cpf, $tipologias){
        $this->loadModel('AgendaAtendimento');
        $this->loadModel('AgendaSistema');
        
        $conditions = array('AgendaAtendimento.ativo' => true);

        if($dataInicial == ""){ 
            $dataInicial = "01/" . date("m") . "/" . date("Y"); 
           
        }
        if($dataFinal == ""){
            $ultimo_dia = date("t", mktime(0,0,0,date("m"),'01',date("Y"))); 
            $dataFinal = $ultimo_dia . "/" . date("m") . "/" . date("Y"); 
        }

        if($cpf != ""){
            $c = array('Usuario.cpf' => trim($cpf));
            array_push($conditions, $c);
        }

        
        if(empty($tipologias)){
            $agendas = $this->AgendaAtendimento->find('all', array('conditions' => $conditions ));
        }else{

            $c = array('AgendaAtendimentoTipologia.tipologia_id' => $tipologias);
            array_push($conditions, $c);


            // AgendasIDS captura os IDS das agendas com tipologias especificadas 
            // Ps: Feito isso por conta do DISTINCT que se dá apenas por cada campo especificado    
            $agendasIds = $this->AgendaAtendimento->find('list', array(
                    'fields' => array('AgendaAtendimento.id'),
                    'joins' => array(
                             array('table' => 'agen_aten_tip',
                                'alias' => 'AgendaAtendimentoTipologia',
                                'type' => 'INNER',
                                'conditions' => array(
                                   'AgendaAtendimentoTipologia.agen_aten_id = AgendaAtendimento.id'
                                )
                            )
                        ),
                    'conditions' => $conditions
                ));

            $c = array('AgendaAtendimento.id' => $agendasIds);
            $agendas = $this->AgendaAtendimento->find('all', array('conditions' => $c ));

        }


        // Util::returnLogSQL($this->AgendaAtendimento);

    
        $todasAgendas = array();

        if(!is_null($agendas) && !empty($agendas)){ 
            foreach ($agendas as $agen) {
                $diaAgenda = $agen['AgendaAtendimento']['dia_semana'];
                $datasPorDia = array();
                
                $encoding = mb_internal_encoding(); 
                // mb_strtoupper por causa do Ç da TERÇA
                $diaAgenda = mb_strtoupper($diaAgenda, $encoding);
                $datasPorDia = Util::retornaDatasPorDia($diaAgenda, $dataInicial, $dataFinal);
                
                foreach ($datasPorDia as $data) {
                    
                    $horario = $agen['AgendaAtendimento']['hora_inicial'] . " - " . $agen['AgendaAtendimento']['hora_final'];
                
                    $new = array(
                        'UnidadeAtendimento' =>  $agen['UnidadeAtendimento']['nome'], 
                        'Perito' => $agen['Usuario']['nome'],
                        'Tipologias' => $this->concatenaTipologias($agen['Tipologia'])
                    );
                    $todasAgendas[$data][$horario][] = $new;
                }
            }
        }
        
        // pr($todasAgendas);die;
                ksort($todasAgendas);
                // $this->set('todasAgendas', $todasAgendas);
                return $todasAgendas;
    }

    public function todosEventos($data_inicial, $data_final){
        $this->loadModel('AgendaSistema');
        $this->loadModel('AgendaSistemaItem');

        $dias = array(
            0 => 'DOMINGO',
            1 => 'SEGUNDA-FEIRA',
            2 => 'TERÇA-FEIRA',
            3 => 'QUARTA-FEIRA',
            4 => 'QUINTA-FEIRA',
            5 => 'SEXTA-FEIRA',
            6 => 'SÁBADO'
        );

        $eventos = array();



        $start = $data_inicial;
        $end = $data_final;

        $conditions = array('AgendaSistema.habilitada' => true);

       
        if(!is_null($start)){
            $c = array('AgendaSistema.prazo_inicial >=' => $start);
            array_push($conditions, $c);
        } 

        if(!is_null($end)){

            $c = array('AgendaSistema.prazo_final <=' => $end);
            array_push($conditions, $c);
        }


        $agendasSistema = $this->AgendaSistema->find('all', array(
            'conditions' => $conditions,
            'recursive' => 2
        ));

        if(!is_null($agendasSistema) && !empty($agendasSistema)){
            
            foreach ($agendasSistema as $agenda) {
                $prazo_inicial = $agenda['AgendaSistema']['prazo_inicial'];
                $prazo_final = $agenda['AgendaSistema']['prazo_final'];

                $date_ini = $prazo_inicial; //Data inicial
                $date_fim = $prazo_final; //Data final
                while (strtotime($date_ini) <= strtotime($date_fim)) {
                    
                    foreach ($agenda['AgendaSistemaItem'] as $agendaItem) {
                        if( $dias[date("w", strtotime($date_ini))] == strtoupper($agendaItem['dia_semana']) ){
       
                            $hora_inicial = $agendaItem['hora_inicial'];
                            $hora_final = $agendaItem['hora_final'];
                            foreach ($agendaItem['Tipologia'] as $tipologiaItem) {
                                $arrayEvento = array(
                                    'title' => $tipologiaItem['nome'],
                                    'start' => $date_ini . "T" . $hora_inicial,
                                    'end' => $date_ini . "T" . $hora_final
                                );

                                array_push($eventos, $arrayEvento);
                            }

                        }
                    }



                    $date_ini = date ("Y-m-d", strtotime("+1 day", strtotime($date_ini)));
                }
            }

        }


        return $eventos;

    }

    public function carregarTodasAgendas(){
        

        $start = $this->request->query['start'];
        $end = $this->request->query['end'];
        
        echo json_encode($this->todosEventos($start,$end));

        die;
    }

    public function carregarTipologias() {
        $this->loadModel('Tipologia');
        // pr($this->Tipologia->listar());die;
        return $this->Tipologia->listar();
    }
    public function carregarUnidades() {
        $this->loadModel('UnidadeAtendimento');
        // pr($this->Tipologia->listar());die;
        return $this->UnidadeAtendimento->listar();
    }

    public function impressao(){
        if($this->request->is('GET')){
            $this->layout = 'pdf';

            $dataInicial    = $this->request->query['data']['AgendaSistema']['ini_data_inicial'];
            $dataFinal      = $this->request->query['data']['AgendaSistema']['ini_data_final'];
            $cpf            = Util::limpaDocumentos( $this->request->query['data']['AgendaSistema']['cpf'] );
            $tipologias     = $this->request->query['data']['Tipologia']['Tipologia'];
            $unidades       = $this->request->query['data']['AgendaSistema']['Unidade'];

            $this->set('todasAgendas',$this->carregaTodasAgendasExternaPerito($dataInicial, $dataFinal, $cpf, $tipologias, $unidades));
        }
    }

    public function consultar() {

        $this->layout = 'ajax';

        if ($this->request->is('POST')){

            // pr($this->request->data);die;
            
            $dataInicial    = $this->request->data['AgendaSistema']['ini_data_inicial'];
            $dataFinal      = $this->request->data['AgendaSistema']['ini_data_final'];
            $peritoAtende   = $this->request->data['AgendaSistema']['perito_atende'];
            $cpf            = Util::limpaDocumentos( $this->request->data['AgendaSistema']['cpf'] );
            $tipologias     = $this->request->data['Tipologia']['Tipologia'];
            $unidades       = $this->request->data['AgendaSistema']['Unidade'];

            $data = date('Y-m-d');

            if(empty($dataInicial)){
                $dataInicial = date('Y-m-01', strtotime($data ));
            }

            if(empty($dataFinal)){
                $dataFinal = date('Y-m-t', strtotime($data));
            }

            $agendas = $this->carregaTodasAgendasExternaPerito($dataInicial, $dataFinal, $cpf, $tipologias, $unidades, $peritoAtende);

            $this->set('todasAgendas',$agendas);

        }
    }
}
