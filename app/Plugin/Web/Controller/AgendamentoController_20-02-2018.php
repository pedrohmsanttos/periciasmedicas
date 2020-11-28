<?php

// App::uses('BSController', 'Admin.Controller');
App::uses('BSController', 'Web.Controller');

class AgendamentoController extends BSController {


    public $uses = array('TipoUsuario');
    public $helpers = array('Html', 'Form', 'PForm');

    /** @var  Atendimento */
    public $Atendimento;

    private function diaDaSemana($strData){
        $arrData = explode('/',$strData);
        $diasDaSemana = array('Domingo', 'Segunda-feira', 'Terça-feira', 'Quarta-feira','Quinta-feira', 'Sexta-feira', 'Sábado');
        if(count($arrData) > 1){
            $numDiaSemana = date( "w", strtotime("{$arrData[2]}-{$arrData[1]}-{$arrData[0]}"));
            return $diasDaSemana[$numDiaSemana];
        }else{
            return '';
        }
    }


    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('checaFeriado');
        $this->Auth->allow('carregarHorariosAtendimentoTest');
        $this->Auth->allow('carregarHorariosAtendimento');
        $this->Auth->allow('getUnidadeCidMunicipio');
        $this->Auth->allow('carregarHorariosAtendimentoDomicilio');
        
    }

    public function checaFeriado(){
       
        if ($this->request->is(array('post', 'put'))) { 
            
            $data = $this->request->data['data_feriado'];
            $data = str_replace('/', '-', $data);
            $data = date('Y-m-d', strtotime($data));
           
            $this->loadModel("Feriado");
            $feriado = $this->Feriado->find('first', array(
                    'conditions' => array(
                        'EXTRACT(MONTH FROM data_feriado) =' => date('m', strtotime($data)),
                        'EXTRACT(DAY FROM data_feriado) =' => date('d', strtotime($data)),
                        'Feriado.ativo' => true
                        ),
                    'recursive' => -1
                ));

            $retorno = array();
            if(isset($feriado) && !empty($feriado)){
                if($data == $feriado['Feriado']['data_feriado']){
                    $retorno['tipo'] = 'true';
                    $retorno['feriado'] = $feriado['Feriado']['nome'];
                }else{
                   if($feriado['Feriado']['feriado_recorrente'] == '1'){
                        $retorno['tipo'] = 'true';
                        $retorno['feriado'] = $feriado['Feriado']['nome'];
                   }else{
                        $retorno['tipo'] = 'false';
                   }
                }
            }else{
                $retorno['tipo'] = 'false';
            }
             
             echo json_encode($retorno);
             die;
        }
    }

    public function carregarHorariosAtendimento() {
        if ($this->request->is(array('post', 'put'))) {

            $idTipologia = $this->request->data['tipologia_id'];
            $idUnidade = intval($this->request->data['unidade_id']);
            $diaSemana = $this->request->data['dia_semana'];
            $encaixe = null;
            if (isset($this->request->data['checkEncaixe'])) {
                $encaixe = $this->request->data['checkEncaixe'];
            }

            // pr($this->request->data);die();

            $this->loadModel("AgendaAtendimento");
            $this->loadModel("AgendaSistemaItem");
            $this->loadModel("AgendaSistema");
            $this->loadModel("TempoConsultaAtendimento");

            $arrAgendaSistema = ($this->AgendaSistema->findAgendaSistema($idTipologia, $idUnidade, $diaSemana));
             
            $horariosDisponiveis = [];
            
            foreach ($arrAgendaSistema as $agendaSistema){
                //Consulta todas as agendas dos peritos que atenda a tipologia, unidade e dia da semana selecionados
                $agendasAtendimentos = $this->AgendaAtendimento->consultarAgendasAtendimento($idTipologia, $idUnidade, $diaSemana, $agendaSistema);

                $this->loadModel("Agendamento");
                //Consulta todos os agendamentos para a unidade e dia da semana selecionado para remover os horários indisponíveis
                $agendamentos = $this->Agendamento->consultarAgendamentos($idUnidade, $diaSemana);

                $this->loadModel('ParametroGeral');
                $intervaloConsulta = null;

                $intervaloConsulta = $this->TempoConsultaAtendimento->buscarTempoConsultaAtendimento($idTipologia);

                if(is_null($intervaloConsulta)){
                    $intervaloConsulta = $this->ParametroGeral->buscarIntervaloConsulta();
                }


                // pr($agendaSistema);die;
                $agendasMedicas = $this->montarListaHorarios($intervaloConsulta, $agendasAtendimentos, $diaSemana, $agendaSistema['AgendaSistema']);

                // pr($agendasMedicas);die;

                //Caso não seja encaixe, é removido os horários que já possuem agendamentos
                if (is_null($encaixe) || $encaixe == "false") {
                    foreach ($agendamentos as $agendamento) {
                        foreach ($agendasMedicas as $key => $agendaMedica){
                            $dataAgendamento = Util::toDBDataHora($agendamento['Agendamento']['data_hora']);
                            $idTipologiaAgendamento = $agendamento['Agendamento']['tipologia_id'];
                           
                            //Caso exista um horário na lista com a mesma data e hora do agendamento
                            if (in_array($dataAgendamento, $agendaMedica['horarios'])) {
                                //É verificado se a tipologia que está sendo selecionada no agendamento faz parte da agenda que os horários fazem parte.
                                if (in_array($idTipologia, $agendaMedica['tipologias']) && $idTipologiaAgendamento == $idTipologia) {
                                    //Recupera a posição do horário na lista e em seguida remove o mesmo
                                    $posicaoHorarioOcupado = array_search($dataAgendamento, $agendaMedica['horarios']);
                                    unset($agendasMedicas[$key]['horarios'][$posicaoHorarioOcupado]);
                                    break;
                                }
                            }
                        }
                    }
                }

                //Adiciona todos os horários disponíveis na agenda em uma única lista
                foreach ($agendasMedicas as $agendaMedica) {
                    foreach ($agendaMedica['horarios'] as $horario) {
                        $horariosDisponiveis[] = $horario;
                    }
                }
                //Remove os horários repetidos
                $horariosDisponiveis = @array_unique($horariosDisponiveis);
            }

            //Ordena a lista de horários
            asort($horariosDisponiveis);
            $horariosDisponiveis = $this->converterDataPtBr($horariosDisponiveis);
            echo json_encode($horariosDisponiveis);
            die;
        }
    }

    public function carregarHorariosAtendimentoTest() {
        if ($this->request->is(array('post', 'put'))) {

            $idTipologia = $this->request->data['tipologia_id'];
            $idUnidade = intval($this->request->data['unidade_id']);
            $diaSemana = $this->request->data['dia_semana'];
            $encaixe = null;
            if (isset($this->request->data['checkEncaixe'])) {
                $encaixe = $this->request->data['checkEncaixe'];
            }

            pr("ID TIPOLOGIA ::: " . $idTipologia . " - LINHA :: " . __LINE__);
            pr("ID UNIDADE ::: " . $idUnidade . " - LINHA :: " . __LINE__);
            pr("DIA DA SEMANA ::: " . $diaSemana . " - LINHA :: " . __LINE__);
            pr("ENCAIXE ::: " . $encaixe . " - LINHA :: " . __LINE__);


            $this->loadModel("AgendaAtendimento");
            $this->loadModel("AgendaSistemaItem");
            $this->loadModel("AgendaSistema");
            $this->loadModel("TempoConsultaAtendimento");

            $arrAgendaSistema = ($this->AgendaSistema->findAgendaSistema($idTipologia, $idUnidade, $diaSemana));

            echo "------------- INICIO - arrAgendaSistema ----------------------- ";
            pr($arrAgendaSistema);
            echo "------------- FIM - arrAgendaSistema ----------------------- ";
             
            $horariosDisponiveis = [];
            
            foreach ($arrAgendaSistema as $agendaSistema){
                //Consulta todas as agendas dos peritos que atenda a tipologia, unidade e dia da semana selecionados
                $agendasAtendimentos = $this->AgendaAtendimento->consultarAgendasAtendimento($idTipologia, $idUnidade, $diaSemana, $agendaSistema);

                echo "<br><br>";
                echo "------------- INICIO - agendasAtendimentos ----------------------- ";
                pr($agendasAtendimentos);
                echo "------------- FIM - agendasAtendimentos ----------------------- ";

                $this->loadModel("Agendamento");
                //Consulta todos os agendamentos para a unidade e dia da semana selecionado para remover os horários indisponíveis
                $agendamentos = $this->Agendamento->consultarAgendamentos($idUnidade, $diaSemana);


                echo "<br><br>";
                echo "------------- INICIO - agendamentos ----------------------- ";
                pr($agendamentos);
                echo "------------- FIM - agendamentos ----------------------- ";

                $this->loadModel('ParametroGeral');
                $intervaloConsulta = null;

                $intervaloConsulta = $this->TempoConsultaAtendimento->buscarTempoConsultaAtendimento($idTipologia);

                if(is_null($intervaloConsulta)){
                    $intervaloConsulta = $this->ParametroGeral->buscarIntervaloConsulta();
                }

                echo "<br><br>";
                pr("INTERVALO DE CONSULTA ::: " . $intervaloConsulta . " - LINHA :: " . __LINE__);

                // pr($agendaSistema);die;
                $agendasMedicas = $this->montarListaHorarios($intervaloConsulta, $agendasAtendimentos, $diaSemana, $agendaSistema['AgendaSistema']);


                echo "<br><br>";
                echo "------------- INICIO - agendasMedicas ----------------------- ";
                pr($agendasMedicas);
                echo "------------- FIM - agendasMedicas ----------------------- ";

                // pr($agendasMedicas);die;

                echo "<br><br>";
                echo "------------- INICIO - is_null(encaixe) || encaixe == 'false' ----------------------- ";
                $i = 1;
                //Caso não seja encaixe, é removido os horários que já possuem agendamentos
                if (is_null($encaixe) || $encaixe == "false") {
                    foreach ($agendamentos as $agendamento) {
                        $j = 1;
                        echo "<br><br><br><br>";
                            echo "------------- INICIO ( $i ) - foreach (agendamentos as agendamento) ----------------------- ";
                        foreach ($agendasMedicas as $key => $agendaMedica){
                            echo "<br><br><br><br>";
                            echo "------ INICIO ( $i.$j ) - (agendasMedicas as key => agendaMedica)------------------- ";
                            $dataAgendamento = Util::toDBDataHora($agendamento['Agendamento']['data_hora']);
                             echo "<br><br><br><br>";
                              pr("DATA AGENDAMENTO ::: " . $dataAgendamento . " - LINHA :: " . __LINE__);
                              pr($agendaMedica['horarios']);
                              pr($agendaMedica['tipologias']);
                            //Caso exista um horário na lista com a mesma data e hora do agendamento
                            if (in_array($dataAgendamento, $agendaMedica['horarios'])) {
                                //É verificado se a tipologia que está sendo selecionada no agendamento faz parte da agenda que os horários fazem parte.
                                if (in_array($idTipologia, $agendaMedica['tipologias'])) {
                                    //Recupera a posição do horário na lista e em seguida remove o mesmo
                                    $posicaoHorarioOcupado = array_search($dataAgendamento, $agendaMedica['horarios']);
                                    unset($agendasMedicas[$key]['horarios'][$posicaoHorarioOcupado]);
                                    echo "BREEEEEEEEEEEAK";
                                    break;
                                }
                            }
                              echo "<br><br><br><br>";
                            echo "------ FIM ( $i.$j ) - (agendasMedicas as key => agendaMedica)------------------- ";
                            $j++;
                        }
                         echo "<br><br>";
                            echo "------------- FIM ( $i ) - foreach (agendamentos as agendamento ----------------------- ";
                        $i++;
                         echo "<br><br>";
                    }
                }

                //Adiciona todos os horários disponíveis na agenda em uma única lista
                foreach ($agendasMedicas as $agendaMedica) {
                    foreach ($agendaMedica['horarios'] as $horario) {
                        $horariosDisponiveis[] = $horario;
                    }
                }
                //Remove os horários repetidos

                echo "<br><br>";
                echo "------------- INICIO - @array_unique(horariosDisponiveis) ----------------------- ";
                pr($horariosDisponiveis);
                $horariosDisponiveis = @array_unique($horariosDisponiveis);
                pr($horariosDisponiveis);
                echo "------------- FIM - @array_unique(horariosDisponiveis) ----------------------- ";
                echo "<br><br>";
            }

            //Ordena a lista de horários
            asort($horariosDisponiveis);
            $horariosDisponiveis = $this->converterDataPtBr($horariosDisponiveis);
            echo json_encode($horariosDisponiveis);
            die;
        }
    }

    public function carregarHorariosAtendimentoMunicipio() {
        if ($this->request->is(array('post', 'put'))) {
            $idTipologia    = $this->request->data['tipologia_id'];
            $idMunicipio    = $this->request->data['municipio_id'];
            $idUnidade      = $this->request->data['unidade_id'];
            $diaSemana      = $this->request->data['dia_semana'];
            $encaixe        = null;
            if (isset($this->request->data['checkEncaixe'])) {
                $encaixe = $this->request->data['checkEncaixe'];
            }

            $this->loadModel("AgendaAtendimento");
           
            
            //Consulta todas as agendas dos peritos que atenda a tipologia, unidade e dia da semana selecionados
            $agendasAtendimentos = $this->AgendaAtendimento->consultarAgendasAtendimento($idTipologia, $idUnidade, $diaSemana);
            //$agendasAtendimentos = $this->AgendaSistemaItem->consultarAgendasSistemaItem($idTipologia, $idUnidade, $diaSemana);


            $this->loadModel("Agendamento");
            //Consulta todos os agendamentos para a unidade e dia da semana selecionado para remover os horários indisponíveis
            $agendamentos = $this->Agendamento->consultarAgendamentos($idUnidade, $diaSemana);

            $this->loadModel('ParametroGeral');
            $this->loadModel('TempoConsultaAtendimento');

            $intervaloConsulta = null;

            $intervaloConsulta = $this->TempoConsultaAtendimento->buscarTempoConsultaAtendimento($idTipologia);

            if(is_null($intervaloConsulta)){
                $intervaloConsulta = $this->ParametroGeral->buscarIntervaloConsulta();
            }

            $agendasMedicas = $this->montarListaHorarios($intervaloConsulta, $agendasAtendimentos, $diaSemana);

            //Caso não seja encaixe, é removido os horários que já possuem agendamentos
            if (is_null($encaixe) || $encaixe == "false") {
                
                foreach ($agendamentos as $agendamento) {
                    foreach ($agendasMedicas as $key => $agendaMedica) {
                        
                        //transforma o formato da data do agendamento para o mesmo formato que é armazenado no banco para poder fazer a comparação entre as datas
                        $dataAgendamentoFormatoOriginal = date('Y-m-d H:i', strtotime(Util::toDBDataHora($agendamento['Agendamento']['data_hora'])));
                        
                        //Caso exista um horário na lista com a mesma data e hora do agendamento
                        if (in_array($dataAgendamentoFormatoOriginal, $agendaMedica['horarios'])){
                            //É verificado se a tipologia que está sendo selecionada no agendamento faz parte da agenda que os horários fazem parte.
                            if (in_array($idTipologia, $agendaMedica['tipologias'])) {
                                
                                //Recupera a posição do horário na lista e em seguida remove o mesmo
                                $posicaoHorarioOcupado = array_search($dataAgendamentoFormatoOriginal, $agendaMedica['horarios']);
                                unset($agendasMedicas[$key]['horarios'][$posicaoHorarioOcupado]);
                                break;
                            }
                        }
                    }
                }
            }
            
            
            $horariosDisponiveis = [];
            
            //Adiciona todos os horários disponíveis na agenda em uma única lista
            foreach ($agendasMedicas as $agendaMedica) {
                foreach ($agendaMedica['horarios'] as $horario) {
                    $horariosDisponiveis[] = $horario;
                }
            }
            
            //Remove os horários repetidos
            $horariosDisponiveis = array_unique($horariosDisponiveis);

            //Ordena a lista de horários
            asort($horariosDisponiveis);
            $horariosDisponiveis = $this->converterDataPtBr($horariosDisponiveis);
            echo json_encode($horariosDisponiveis);
            die;
        }
    }

    public function carregarHorariosAtendimentoMunicipioEndereco() {
        if ($this->request->is(array('post', 'put'))) {

            $idTipologia    = $this->request->data['tipologia_id'];
            $idMunicipio    = $this->request->data['municipio_id'];
            // $idUnidade      = $this->request->data['unidade_id'];
            $diaSemana      = $this->request->data['dia_semana'];
            // $idCID = $this->request->data['cid_id'];;
            $cids = $this->request->data['cids'];

            $this->loadModel("AgendaSistema");
            $this->loadModel("AgendaAtendimentoDomicilio");
            $this->loadModel('UnidadeAtendimento');
            $this->loadModel("Agendamento");
            $this->loadModel('ParametroGeral');
            $this->loadModel('TempoConsultaAtendimento');
            
            $horariosDisponiveis = [];
            

            if (isset($this->request->data['checkEncaixe'])) {
                $encaixe = $this->request->data['checkEncaixe'];
            }

            


            $unidades = $this->UnidadeAtendimento->obterUnidadesCidMunicipio($cids, true, $idMunicipio, false);

            $ids = array_keys($unidades);
            
            foreach ($ids as $idUnidade) {

                $arrAgendaSistema = ($this->AgendaSistema->findAgendaSistema($idTipologia, $idUnidade, $diaSemana));

                foreach ($arrAgendaSistema as $agendaSistema){

                    //Consulta todas as agendas dos peritos que atenda a tipologia, unidade e dia da semana selecionados
                    $agendasAtendimentos = $this->AgendaAtendimentoDomicilio->consultarAgendasAtendimento($idTipologia, $idUnidade, $diaSemana);

                    pr($agendasAtendimentos);die;

                    //Consulta todos os agendamentos para a unidade e dia da semana selecionado para remover os horários indisponíveis
                    $agendamentos = $this->Agendamento->consultarAgendamentosDomiciliar($idUnidade, $diaSemana);                   
                    $intervaloConsulta = null;

                    $intervaloConsulta = $this->TempoConsultaAtendimento->buscarTempoConsultaAtendimento($idTipologia);

                    if(is_null($intervaloConsulta)){
                        $intervaloConsulta = $this->ParametroGeral->buscarIntervaloConsulta();
                    }

                    $agendasMedicas = $this->montarListaHorariosDomicilio($intervaloConsulta, $agendasAtendimentos, $diaSemana);

                    pr($agendamentos);die;
                    //Caso não seja encaixe, é removido os horários que já possuem agendamentos
                    if (is_null($encaixe) || $encaixe == "false") {
                        
                        foreach ($agendamentos as $agendamento) {
                            foreach ($agendasMedicas as $key => $agendaMedica) {
                                
                                //transforma o formato da data do agendamento para o mesmo formato que é armazenado no banco para poder fazer a comparação entre as datas
                                $dataAgendamentoFormatoOriginal = date('Y-m-d H:i', strtotime(Util::toDBDataHora($agendamento['Agendamento']['data_hora'])));
                                
                                //Caso exista um horário na lista com a mesma data e hora do agendamento
                                if (in_array($dataAgendamentoFormatoOriginal, $agendaMedica['horarios'])) {
                                    //É verificado se a tipologia que está sendo selecionada no agendamento faz parte da agenda que os horários fazem parte.
                                    if (in_array($idTipologia, $agendaMedica['tipologias'])) {
                                        
                                        //Recupera a posição do horário na lista e em seguida remove o mesmo
                                        $posicaoHorarioOcupado = array_search($dataAgendamentoFormatoOriginal, $agendaMedica['horarios']);
                                        unset($agendasMedicas[$key]['horarios'][$posicaoHorarioOcupado]);
                                        break;
                                    }
                                }
                            }
                        }
                    }
                    
                    // $horariosDisponiveis = [];
                    $horarioAux = [];
                    
                    //Adiciona todos os horários disponíveis na agenda em uma única lista
                    foreach ($agendasMedicas as $agendaMedica) {
                        foreach ($agendaMedica['horarios'] as $horario) {
                            $horarioAux[] = $horario;
                        }
                    }
                    
                    //Remove os horários repetidos
                    $horarioAux = array_unique($horarioAux);
                    
                    //Ordena a lista de horários
                    asort($horarioAux);
                    $horarioAux = $this->converterDataPtBr($horarioAux);

                    $horariosDisponiveis = array_merge($horariosDisponiveis, $horarioAux);

                }// end foreach ($arrAgendaSistema as $agendaSistema)
                
            }// End foreach ($ids as $idUnidade)

            echo json_encode($horariosDisponiveis);
            die;
        }
        die();
    }

    public function consultaEnderecoUsuario(){
        $this->layout = 'ajax';
        if ($this->request->is(array('post', 'put'))) { 
            $cpf = $this->request->data['cpfServidor'];
            if($cpf != null){
                $this->loadModel('Usuario');

                $endereco = $this->Usuario->buscaEndereco($cpf);

                echo json_encode($endereco);
                die;
            }
        }
    }

    /* Consulta os horarios de atendimento em domicilio */
    public function carregarHorariosAtendimentoDomicilio() {
        if ($this->request->is(array('post', 'put'))) {

            //  $idTipologia = '2';
            // $idUnidade = '5';
            // $diaSemana = 'Segunda-feira';
            $idTipologia = $this->request->data['tipologia_id'];
            $idUnidade = $this->request->data['unidade_id'];
            $diaSemana = $this->request->data['dia_semana'];
            $encaixe = null;
            if (isset($this->request->data['checkEncaixe'])) {
                $encaixe = $this->request->data['checkEncaixe'];
            }

            $this->loadModel("AgendaAtendimentoDomicilio");
            //Consulta todas as agendas dos peritos que atenda a tipologia, unidade e dia da semana selecionados
            $agendasAtendimentos = $this->AgendaAtendimentoDomicilio->consultarAgendasAtendimento($idTipologia, $idUnidade, $diaSemana);

            $this->loadModel("Agendamento");
            //Consulta todos os agendamentos para a unidade e dia da semana selecionado para remover os horários indisponíveis
            $agendamentos = $this->Agendamento->consultarAgendamentosDomiciliar($idUnidade, $diaSemana);

            $this->loadModel('ParametroGeral');
            $this->loadModel('TempoConsultaAtendimento');

            $intervaloConsulta = null;

            $intervaloConsulta = $this->TempoConsultaAtendimento->buscarTempoConsultaAtendimento($idTipologia);

            if(is_null($intervaloConsulta)){
                $intervaloConsulta = $this->ParametroGeral->buscarIntervaloConsulta();
            }

            $agendasMedicas = $this->montarListaHorariosDomicilio($intervaloConsulta, $agendasAtendimentos, $diaSemana);
            
            //Caso não seja encaixe, é removido os horários que já possuem agendamentos
            if (is_null($encaixe) || $encaixe == "false") {
                
                foreach ($agendamentos as $agendamento) {
                    foreach ($agendasMedicas as $key => $agendaMedica) {
                        
                        //transforma o formato da data do agendamento para o mesmo formato que é armazenado no banco para poder fazer a comparação entre as datas
                        $dataAgendamentoFormatoOriginal = date('Y-m-d H:i', strtotime(Util::toDBDataHora($agendamento['Agendamento']['data_hora'])));
                        
                        //Caso exista um horário na lista com a mesma data e hora do agendamento
                        if (in_array($dataAgendamentoFormatoOriginal, $agendaMedica['horarios'])) {
                            //É verificado se a tipologia que está sendo selecionada no agendamento faz parte da agenda que os horários fazem parte.
                            if (in_array($idTipologia, $agendaMedica['tipologias'])) {
                                
                                //Recupera a posição do horário na lista e em seguida remove o mesmo
                                $posicaoHorarioOcupado = array_search($dataAgendamentoFormatoOriginal, $agendaMedica['horarios']);
                                unset($agendasMedicas[$key]['horarios'][$posicaoHorarioOcupado]);
                                break;
                            }
                        }
                    }
                }
            }
            
            
            $horariosDisponiveis = [];
            
            //Adiciona todos os horários disponíveis na agenda em uma única lista
            foreach ($agendasMedicas as $agendaMedica) {
                foreach ($agendaMedica['horarios'] as $horario) {
                    $horariosDisponiveis[] = $horario;
                }
            }
            
            //Remove os horários repetidos
            $horariosDisponiveis = array_unique($horariosDisponiveis);
            
            //Ordena a lista de horários
            asort($horariosDisponiveis);
            $horariosDisponiveis = $this->converterDataPtBr($horariosDisponiveis);
            echo json_encode($horariosDisponiveis);
            die;
        }
    }

    private function converterDataPtBr($horariosDisponiveis) {
        $listaRetorno = array();
        foreach ($horariosDisponiveis as $horario) {
            $listaRetorno[] = date("d/m/Y H:i", @strtotime($horario));
        }
        return $listaRetorno;
    }

    private function removerHorariosIndisponiveis($agendamentos = array(), $horariosDisponiveis) {
        foreach ($agendamentos as $agendamento) {
            $dataAgendamento = date("Y-m-d H:i", strtotime(Util::toDBDataHora($agendamento['Agendamento']['data_hora'])));
            foreach ($horariosDisponiveis as $key => $horario) {
                if ($dataAgendamento == $horario) {
                    unset($horariosDisponiveis[$key]);
                    break;
                }
            }
        }
        return $horariosDisponiveis;
    }

    public function montarListaHorarios($intervaloConsulta, $agendasAtendimentos, $diaSemana, $agendaSistema) {
        $horarios = array();
        
        foreach ($agendasAtendimentos as $agendaAtendimento) {
            $peritoID = $agendaAtendimento['AgendaAtendimento']['usuario_id'];
            if (!isset($horarios[$peritoID])) {
                $horarios[$peritoID] = [];
            }
            $horariosMedico = [];
            $horas = $this->criarHorariosAgendaAtendimento($agendaAtendimento['AgendaAtendimento']['hora_inicial'], $agendaAtendimento['AgendaAtendimento']['hora_final'], $intervaloConsulta);
            foreach ($horas as $hora) {
                $horariosMedico = array_merge($horariosMedico, $this->formatarHorarioAtendimento($hora, $diaSemana, $agendaSistema));
            }

            if(!isset($horarios[$peritoID]['horarios']))$horarios[$peritoID]['horarios'] = [];
            $horarios[$peritoID]['horarios'] = array_merge($horarios[$peritoID]['horarios'], $horariosMedico);
            $horarios[$peritoID]['tipologias'] = Util::criarListaIds($agendaAtendimento['Tipologia']);
        }
        
        return $horarios;
    }

    public function montarListaHorariosDomicilio($intervaloConsulta, $agendasAtendimentos, $diaSemana) {
        // pr($intervaloConsulta);
        // pr($agendasAtendimentos);
        // pr($diaSemana);
       
        $horarios = array();
        foreach ($agendasAtendimentos as $agendaAtendimento) {
            $peritoID = $agendaAtendimento['AgendaAtendimentoDomicilio']['usuario_id'];
            if (!isset($horarios[$peritoID])) {
                $horarios[$peritoID] = [];
            }
            $horariosMedico = [];
            $horas = $this->criarHorariosAgendaAtendimento($agendaAtendimento['AgendaAtendimentoDomicilio']['hora_inicial'], $agendaAtendimento['AgendaAtendimentoDomicilio']['hora_final'], $intervaloConsulta);
            // pr($horas);
            foreach ($horas as $hora) {
                $horariosMedico = array_merge($horariosMedico, $this->formatarHorarioAtendimento($hora, $diaSemana, null, true));
                // pr($this->formatarHorarioAtendimento($hora, $diaSemana, null, true));
            }

            // pr($horariosMedico);
            if(!isset($horarios[$peritoID]['horarios']))$horarios[$peritoID]['horarios'] = [];
            $horarios[$peritoID]['horarios'] = array_merge($horarios[$peritoID]['horarios'], $horariosMedico);
            $horarios[$peritoID]['tipologias'] = Util::criarListaIds($agendaAtendimento['Tipologia']);
        }

         // die;

        // pr($horarios);die;
        return $horarios;
    }

    private function formatarHorarioAtendimento($hora, $diaSemana, $agendaSistema = null, $domicilio = false) {
        $dias_semana = Configure::read('DIAS_SEMANA');
        $diaInteiroSelecionado = $dias_semana[$diaSemana];
        $dataAtual = date('Y-m-d');
        $dataHoraAtual = date('Y-m-d H:i:s');
        $tsAgendaIni = strtotime($dataHoraAtual);

        if($domicilio == false){

            $encoding = mb_internal_encoding(); 
            $diaAgenda = mb_strtoupper($diaSemana, $encoding);
            $prazoInicial = $agendaSistema['prazo_inicial'];
            $prazoFinal = $agendaSistema['prazo_final'];
            $datasPorDia = Util::retornaDatasPorDia($diaAgenda, $prazoInicial, $prazoFinal);

            // echo "DIA AGENDA :: " . $diaAgenda . "<br>";
            // echo "prazoInicial :: " . $prazoInicial . "<br>";
            // echo "prazoFinal :: " . $prazoFinal . "<br>";
            // pr($diaAgenda);
            // pr($prazoInicial);
            // pr($prazoFinal);
            

            
            if($agendaSistema  != null && isset($agendaSistema['prazo_inicial']) && $agendaSistema['prazo_inicial'] > 0){
                $tsAgendaIni = strtotime($agendaSistema['prazo_inicial']." 00:00:00");
                $tsAtual = strtotime($dataHoraAtual);
                if($tsAgendaIni  > $tsAtual )$dataAtual = $agendaSistema['prazo_inicial'];
            }
           
           

            $retorno = array();

            foreach ($datasPorDia as $dataDia) {
                $dataFormatada = date("Y-m-d", strtotime($dataDia));
                $dataHoraFormatada = $dataFormatada . " " . $hora;
                $tsDHF = strtotime($dataHoraFormatada);
                if($tsDHF > strtotime($dataHoraAtual)){
                    if($agendaSistema != null){
                        $tsAgendaFinal = strtotime(date("Y-m-d"). "  + 1 years");
                        if(isset($agendaSistema['prazo_final']) && $agendaSistema['prazo_final'] > 0){
                            $tsAgendaFinal = strtotime($agendaSistema['prazo_final']. " 23:59:59");
                        }
                        if($tsDHF >= $tsAgendaIni && $tsDHF <= $tsAgendaFinal){
                            $retorno[] = $dataHoraFormatada;
                        }
                    }else{
                        $retorno[] = $dataHoraFormatada;
                    }
                }
            }

        }else{
            // Atendimento domiciliar sem ter relação com a Agenda do Sistema
            for ($i = 0; $i < 10; $i++){
                $diaInteiroDataAtual = date("w", strtotime($dataAtual));
                if ($diaInteiroDataAtual == $diaInteiroSelecionado) {
                    $dataFormatada = date("Y-m-d", strtotime($dataAtual));
                    $dataHoraFormatada = $dataFormatada . " " . $hora;
                    $tsDHF = strtotime($dataHoraFormatada);
                    if($tsDHF > strtotime($dataHoraAtual)){
                        if($agendaSistema != null){
                            $tsAgendaFinal = strtotime(date("Y-m-d"). "  + 1 years");
                            if(isset($agendaSistema['prazo_final']) && $agendaSistema['prazo_final'] > 0){
                                $tsAgendaFinal = strtotime($agendaSistema['prazo_final']. " 23:59:59");
                            }
                            if($tsDHF >= $tsAgendaIni && $tsDHF <= $tsAgendaFinal){
                                $retorno[] = $dataHoraFormatada;
                            }
                        }else{
                            $retorno[] = $dataHoraFormatada;
                        }
                    }
                }

                $dataAtual = date('Y-m-d', strtotime($dataAtual . ' + 1 days'));
            }
        }

        // for ($i = 0; $i < 10; $i++){
        //     $diaInteiroDataAtual = date("w", strtotime($dataAtual));
        //     if ($diaInteiroDataAtual == $diaInteiroSelecionado) {
        //         $dataFormatada = date("Y-m-d", strtotime($dataAtual));
        //         $dataHoraFormatada = $dataFormatada . " " . $hora;
        //         $tsDHF = strtotime($dataHoraFormatada);
        //         if($tsDHF > strtotime($dataHoraAtual)){
        //             if($agendaSistema != null){
        //                 $tsAgendaFinal = strtotime(date("Y-m-d"). "  + 1 years");
        //                 if(isset($agendaSistema['prazo_final']) && $agendaSistema['prazo_final'] > 0){
        //                     $tsAgendaFinal = strtotime($agendaSistema['prazo_final']. " 23:59:59");
        //                 }
        //                 if($tsDHF >= $tsAgendaIni && $tsDHF <= $tsAgendaFinal){
        //                     $retorno[] = $dataHoraFormatada;
        //                 }
        //             }else{
        //                 $retorno[] = $dataHoraFormatada;
        //             }
        //         }
        //     }

        //     $dataAtual = date('Y-m-d', strtotime($dataAtual . ' + 1 days'));
        // }
        
        return $retorno;
    }

    public function validarUnicidadeAgendamentoAjax() {
        $this->layout = 'ajax';
        $servidor = $this->request->query['servidor'];
        $unidade = $this->request->query['unidade'];
        $tipologia = $this->request->query['tipologia'];
        $data_hora = $this->request->query['data_hora'];
        $encaixe = $this->request->query['encaixe'];
        // pr($this->request->query);die;

        $id = null;
        if (isset($this->request->query['id'])) {
            $id = $this->request->query['id'];
        }
        $numero_processo = null;
        if(isset($this->request->query['numero_processo'])) {
            $numero_processo = $this->request->query['numero_processo'];
        }

        $agendamentos = $this->Agendamento->possuiAgendamentoVigente($unidade, $data_hora, $servidor, $tipologia, $id, $numero_processo);
        $arrayRetorno = array();
        if (count($agendamentos) > 0 &&  (is_null($encaixe) OR  $encaixe == "false")) {
            $arrayRetorno['status'] = "danger";
            $arrayRetorno['idAgendamentoVigente'] = $agendamentos[0]['Agendamento']['id'];
        } else {
            $arrayRetorno['status'] = "success";
        }
        echo json_encode($arrayRetorno);
        die;
    }

    private function criarHorariosAgendaAtendimento($horaInicial, $horaFinal, $intervalo) {
        $retorno = array();
        $hourTmp = true;
        $horaFinal = date("H:i", strtotime($horaFinal));
        for ($i = 0; $hourTmp; $i+=$intervalo) {
            $hora = date("H:i", strtotime($horaInicial) + ($i * 60));
            if ($hora < $horaFinal) {
                $retorno[] = $hora;
            }
            $hourTmp = $hora;
            if (($hourTmp == $horaFinal || $hourTmp > $horaFinal)) {
                return $retorno;
            }
        }
    }

    private function carregarListaTipologia($tipoUsuario = '') {
        $this->loadModel("AgendaSistema");
        $this->loadModel("Tipologia");
        $filtro = new BSFilter();
        $filtro->setTipo('list');
        $filtro->setCamposOrdenadosString('Tipologia.nome');
        if($tipoUsuario != ''){
            $arrEsconderTipologias = array(0, 0);
            switch($tipoUsuario){
                case USUARIO_SERVIDOR:
                    $arrEsconderTipologias =  array(TIPOLOGIA_DESIGNACAO_DE_ASSISTENTE_TECNICO, TIPOLOGIA_EXAME_PRE_ADMISSIONAL,
                        TIPOLOGIA_SINDICANCIA_INQUERITO_PAD, TIPOLOGIA_INSPECAO);
                    break;
                case USUARIO_INTERNO:
                    //VÊ TUDO!!
                    break;
                case USUARIO_PERITO_SERVIDOR:
                    $arrEsconderTipologias = array(0, TIPOLOGIA_DESIGNACAO_DE_ASSISTENTE_TECNICO);
                    break;
                case USUARIO_PERITO_CREDENCIADO:
                    $arrEsconderTipologias = array( TIPOLOGIA_DESIGNACAO_DE_ASSISTENTE_TECNICO, TIPOLOGIA_SINDICANCIA_INQUERITO_PAD,
                        TIPOLOGIA_INSPECAO);
                    break;
            }
            $condicoes['Tipologia.id NOT IN'] = $arrEsconderTipologias;
            $filtro->setCondicoes($condicoes);
        }
        $tipologias = $this->AgendaSistema->findTipologiasDisponiveis("t.id not in (".implode(",",$arrEsconderTipologias).")");
        $arrIdNome = [];
        foreach ($tipologias as $t){
            $arrIdNome[$t['Tipologia']['id']] = $t['Tipologia']['nome'];
        }
        $tipologias = $arrIdNome;
        //$tipologias = $this->Tipologia->listar($filtro);
        $this->set(compact('tipologias'));
        return $tipologias;
    }

    public function index() {

        $tipoUsuario = CakeSession::read('Auth.User.tipo_usuario_id');

        if ($tipoUsuario == USUARIO_SERVIDOR || $tipoUsuario == USUARIO_PERITO_SERVIDOR) {
            $condicoes = null;

			$condicoes = array('OR' =>
                array(
                    array('Agendamento.usuario_servidor_id' => CakeSession::read('Auth.User.id')),
                    array(
                        'OR' => array(
                            array('Agendamento.chefe_imediato_um_id =' => CakeSession::read('Auth.User.id')),
                            array('Agendamento.chefe_imediato_dois_id' => CakeSession::read('Auth.User.id')),
                            array('Agendamento.chefe_imediato_tres_id' => CakeSession::read('Auth.User.id'))
                        ),
                        array('Agendamento.tipologia_id IN' => array(TIPOLOGIA_RISCO_VIDA_INSALUBRIDADE, TIPOLOGIA_COMUNICACAO_DE_ACIDENTE_DE_TRABALHO)),
                        array('Agendamento.status_agendamento !=' => 'Atendido')
                    )
                )
            );

            $filtro = new BSFilter();


            $this->loadModel('Atendido');
            $this->Agendamento->bindModel(
                array(
                    'hasOne' => array(
                        'Atendido' => array(
                            'className' => 'Atendido',
                            'foreignKey' => 'agendamento_id'
                        )
                    )
                )
            );

            $filtro->setCondicoes($condicoes);
            $filtro->setTipo('all');
            $filtro->setCamposRetornados();
            $filtro->setCamposOrdenados(array('Agendamento.id' => 'desc'));
            $agendamentos = $this->Agendamento->listar($filtro);
            // pr($agendamentos);die;
            $this->set('agendamentos', $agendamentos);
        }
        $this->carregarListaUnidadeAtendimento();
        $this->carregarListaTipologia($tipoUsuario);
    }

    public function getServidorCpf($tipo = '', $idTipologia = '') {

        $this->layout = 'ajax';
        if ($this->request->is('post')) {
            $this->loadModel('Usuario');
            $cpf = Util::limpaDocumentos($this->request->data['cpf']);
            $joins = array();
            if($tipo == 'perito'){
                $conditions = ['Usuario.cpf' => $cpf, 'Usuario.tipo_usuario_id' => [0, USUARIO_PERITO_SERVIDOR]];
                if(!empty($idTipologia)){
                    $joins[] = array(
                        'table' => 'usuario_tipologia',
                        'alias' => 'UT',
                        'type' => 'INNER',
                        'conditions' => array("UT.usuario_id = Usuario.id", "UT.tipologia_id =".intval($idTipologia))
                    );
                }
            }else{
                $conditions = ['Usuario.cpf' => $cpf, 'Usuario.tipo_usuario_id' => [USUARIO_PERITO_SERVIDOR, USUARIO_SERVIDOR]];
            }

            $arrUsuario = $this->Usuario->obterUsuario($conditions , 'all', $joins);
            if (!empty($arrUsuario[0])) {
                echo json_encode($arrUsuario[0]['Usuario']);
                die;
            } else {
                echo json_encode(['status' => 'danger', 'msg' => __('validacao_busca_servidor_cpf')]);
                die;
            }
        }
        echo json_encode(['status' => 'danger', 'msg' => 'Problemas ao efetuar requisição']);
        die;
    }

    public function getServidorNome($tipo = '', $idTipologia = '') {

        $this->layout = 'ajax';
        if ($this->request->is('get')) {
            $this->loadModel('Usuario');
            $joins = array();
            $nome = $this->request->query['term'];
            if($tipo=='perito'){
                $conditions = ['Usuario.nome ILIKE ' => '%' . $nome . '%', 'Usuario.tipo_usuario_id' => [0, USUARIO_PERITO_SERVIDOR]];
                if(!empty($idTipologia)){
                    $joins[] = array(
                        'table' => 'usuario_tipologia',
                        'alias' => 'UT',
                        'type' => 'INNER',
                        'conditions' => array("UT.usuario_id = Usuario.id", "UT.tipologia_id =".intval($idTipologia))
                    );
                }
            }else{
                $conditions = ['Usuario.nome ILIKE ' => '%' . $nome . '%', 'Usuario.tipo_usuario_id' => [USUARIO_PERITO_SERVIDOR, USUARIO_SERVIDOR]];
            }

            $arrUsuario = $this->Usuario->obterUsuario($conditions, 'all', $joins);
            $arrayRetorno = array();
            foreach ($arrUsuario as $key => $line) {
                $objTmp = new stdClass();
                $objTmp->id = $line['Usuario']['id'];
                $objTmp->cpf = $line['Usuario']['cpf'];
                $objTmp->data_obito = $line['Usuario']['data_obito'];
                $objTmp->label = $line['Usuario']['nome'];
                $objTmp->value = $line['Usuario']['nome'];
                $arrayRetorno[] = $objTmp;
            }
            echo json_encode($arrayRetorno);
            die;
        }
    }

    /**
     * Função para obter o vinculo de acordo com aquela matricula.
     */
    public function getVinculoByMatricula() {
        if ($this->request->is('post')) {
            $matricula = (isset($this->request->data['matricula'])) ? $this->request->data['matricula'] : false;
            if ($matricula) {
                $this->loadModel('Vinculo');
                $arrVinculos = $this->Vinculo->obterVinculo(['Vinculo.matricula' => $matricula]);
                echo '<pre/>';
                var_dump($arrVinculos);
                exit();
            }
        }
    }

    /**
     * Método utilizado para exibir a listagem inicial de Cargos cadastrados
     */
    public function consultar() {
        $this->layout = 'ajax';
        if ($this->request->is('GET')) {
            // die('here')
            $this->loadModel("Atendimento");
            $this->loadModel("Tipologia");

            $limitConsulta = $this->request->query['data']['Agendamento']['limitConsulta'];

            $nomeServidor = $this->request->query['data']['Agendamento']['nome'];
            $cpfServidor = $this->request->query['data']['Agendamento']['cpf'];
            $dataInicial = $this->request->query['data']['Agendamento']['data_inicial'];
            $dataFinal = $this->request->query['data']['Agendamento']['data_final'];
            $tipologia = $this->request->query['data']['Agendamento']['Tipologia'];
            $unidadeAtendimento = $this->request->query['data']['Agendamento']['unidade_atendimento_id'];
            $condicoes = null;

            if (!empty($nomeServidor)) {
                $condicoes['UsuarioServidor.nome ILIKE '] = "%$nomeServidor%";
            }

            if (!empty($cpfServidor)) {
                $condicoes['UsuarioServidor.cpf'] = Util::limpaDocumentos(trim($cpfServidor));
            }

            if (!empty($tipologia)) {
                $condicoes['Agendamento.tipologia_id'] = $tipologia;
            }

            if (!empty($unidadeAtendimento)) {
                $condicoes['Agendamento.unidade_atendimento_id'] = $unidadeAtendimento;
            }

            if (!empty($dataInicial)) {
                $condicoes['cast(Agendamento.data_hora as date) >= '] = Util::toDBDataHora($dataInicial);
            }

            if (!empty($dataFinal)) {
                $condicoes['cast(Agendamento.data_hora as date) <= '] = Util::toDBDataHora($dataFinal);
            }

            $filtro = new BSFilter();
            $filtro->setCamposRetornados(array(
                'Agendamento.id', 'Agendamento.data_hora', 'Agendamento.status_agendamento',
                'Agendamento.chefe_imediato_um_id', 'Agendamento.chefe_imediato_dois_id', 'Agendamento.chefe_imediato_tres_id',
                'Tipologia.id', 'Atendido.id', 'UsuarioServidor.nome', 'UsuarioServidor.cpf',
                'Tipologia.nome', 'UnidadeAtendimento.nome', 'Agendamento.numero_processo'

                ));
            $filtro->setCamposOrdenados(array('Agendamento.protocolo'=> 'desc'));
            $filtro->setCondicoes($condicoes);
            $joins = array();
            $joins[] = array(
                'table' => 'atendimento',
                'alias' => 'Atendido',
                'type' => 'left',
                'conditions' => array('Agendamento.id = Atendido.agendamento_id')
            );
            $filtro->setJoins($joins);
            $filtro->setLimiteConsulta($limitConsulta);
            $agendamentos = $this->paginar($filtro);

            
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

    /**
     * Método utilizado para visualizar um Cargo
     * @param string $id
     */
    public function visualizar($id = null) {
        if (!$id) {
            throw new NotFoundException(__('objeto_invalido', __('Cargo')));
        }

        $cargo = $this->Cargo->findById($id);

        if (!$cargo) {
            throw new NotFoundException(__('objeto_invalido', __('Cargo')));
        }
		
		 
                $currentFunction = $this->request->params['action']; //function corrente
                $currentController = $this->name; //Controller corrente
                $this->saveAuditLog($id,$currentController,'V',$currentFunction);

        $this->request->data = $cargo;

        //render view edit
        $this->render('edit');
    }

    /*
        Método retorna as unidades de atendimento que atendam em domicílio em um determinado município
    */
    public function retornaUnidAtendimentoMunicipio($municipio = null){
        error_reporting(0);
        $this->loadModel('UnidadeAtendimento');
        $data = null;
        
        if($this->request->is('post')){
            $municipio = $this->request->data['municipio'];
            if(isset($municipio)){
                
                /* Fazendo uma consulta pelo endereco das unidades */
                $filtro = new BSFilter();
                $condicoes['UnidadeAtendimento.atendimento_domicilio'] = true;
                $condicoes['Endereco.municipio_id'] = $municipio;
                $joins[] = array(
                    'table' => 'endereco',
                    'alias' => 'Endereco',
                    'type' => 'INNER',
                    'conditions' => array("UnidadeAtendimento.endereco_id = Endereco.id")
                );
                $filtro->setCondicoes($condicoes);
                $filtro->setJoins($joins);
                $this->UnidadeAtendimento->unbindModel(
                    array('hasAndBelongsToMany' => array('Cid'))
                );
                $data =  $this->UnidadeAtendimento->listar($filtro);


                /* Fazendo ums consulta pelos municipios proximos que a unidade atende */
                $filtro2 = new BSFilter();
                $condicoes2['UnidadeAtendimento.atendimento_domicilio'] = true;
                $condicoes2['UnidadeAtendimentoMunicipio.municipio_id'] = $municipio;
                $joins2[] = array(
                    'table' => 'unidade_atendimento_municipio',
                    'alias' => 'UnidadeAtendimentoMunicipio',
                    'type' => 'INNER',
                    'conditions' => array("UnidadeAtendimento.id = UnidadeAtendimentoMunicipio.unidade_atendimento_id")
                );
                $filtro2->setCondicoes($condicoes2);
                $filtro2->setJoins($joins2);
                $this->UnidadeAtendimento->unbindModel(
                    array('hasAndBelongsToMany' => array('Cid'))
                );
                $data2 = $this->UnidadeAtendimento->listar($filtro2);

                if(isset($data) && !empty($data)){
                    array_merge($data, $data2);
                }else{
                    $data = $data2;
                }
            }

        } 

        echo json_encode($data);
        die;
    }

    /* 
        Retorna todos os municipios que possuem unidade que antendam tambem em domicilio 
    */
    public function retornaMunicipiosAtendimentoDomiciliar(){
        $this->loadModel("Municipio");
        $this->loadModel('UnidadeAtendimento');
        $this->UnidadeAtendimento->unbindModel(
            array('hasAndBelongsToMany' => array('Cid'))
        );
        $unidades = $this->UnidadeAtendimento->find('all', array(
                'conditions' => array(
                    'UnidadeAtendimento.atendimento_domicilio' => true,
                    'UnidadeAtendimento.ativo' => true),
            )
        );
        
        $municipios = array();
        foreach ($unidades as $unidade){
            $municipioUni = $this->Municipio->find("first", array(
                'conditions' => array(
                    'Municipio.id' => $unidade['Endereco']['municipio_id']
                ),
                'recursive' => 0
            ));
            $this->addTimeSession(__LINE__ . "-".__FUNCTION__);
            //pegando tambem o municipio em que a unidade esta cadastrada
            $municipios[$unidade['Endereco']['municipio_id']] = $municipioUni['Municipio']['nome'];
            //pegando todos os municipios proximos
            foreach ( $unidade['MunicipioProximo'] as $municipio ){
                $municipios[$municipio['id']] = $municipio['nome'];
            }
        }

        return $municipios;
    }
    /**
     * Método utilizado para cadastrar um novo Agendamento no sistema
     */
    public function adicionar(){
        $municipios = $this->retornaMunicipiosAtendimentoDomiciliar();
        $this->set('municipiosAtendimento', $municipios);
        
        $this->loadModel('Municipio');
        $municipiosAtendimento = $this->Municipio->listarMunicipiosUF(17);
        
        $this->set('municipiosUsuarios', $municipiosAtendimento);
        $this->loadModel('Estado');
        $this->set('estados', $this->Estado->listarEstados());
        

        if ($this->request->is('post')) {


            unset($this->request->data['ChefiaImediataUm']);
            unset($this->request->data['ChefiaImediataDois']);
            unset($this->request->data['ChefiaImediataTres']);
            unset($this->request->data['Usuario']);
            $this->Agendamento->create();
            $dataSource = $this->Agendamento->getDataSource();
            $dataSource->begin();

            if (!empty($this->request->data['Agendamento']['contrato_trabalho']['name'])) {
                $this->uploadArquivoAgendamento('ct', 'contrato_trabalho');
            } else{
                $this->request->data['Agendamento']['contrato_trabalho'] =null;
            }

            if(!empty($this->request->data['Agendamento']['edital_concurso']['name'])){
                $this->uploadArquivoAgendamento('ec', 'edital_concurso');
             }else{
                $this->request->data['Agendamento']['edital_concurso'] =null;
            }

            if(!empty($this->request->data['Agendamento']['curso_formacao_certificado']['name'])){
                $this->uploadArquivoAgendamento('fc', 'curso_formacao_certificado');
            }else{
                $this->request->data['Agendamento']['curso_formacao_certificado'] =null;
            }
            
            if(!empty($this->request->data['Agendamento']['oficio']['name'])){
                $this->uploadArquivoAgendamento('o', 'oficio');
            }else{
                $this->request->data['Agendamento']['oficio'] = null;
            }

            if(!empty($this->request->data['Agendamento']['ppp']['name'])){
                $this->uploadArquivoAgendamento('p', 'ppp');
            }else{
                $this->request->data['Agendamento']['ppp'] = null;
            }

            if(!empty($this->request->data['Agendamento']['declaracao_atribuicoes']['name'])){
                $this->uploadArquivoAgendamento('da', 'declaracao_atribuicoes');
            }else{
                $this->request->data['Agendamento']['declaracao_atribuicoes'] = null;
            }

            if(!empty($this->request->data['Agendamento']['ltcat']['name'])){
                $this->uploadArquivoAgendamento('l', 'ltcat');
            }else{
                $this->request->data['Agendamento']['ltcat'] = null;
            }

            if (!empty($this->request->data['AgendamentoCAT']['descricao_registro_policial_acidente_doenca']['name'])) {
                $this->uploadAnexoRegistroPolicial();
            } else{
                $this->request->data['AgendamentoCAT']['descricao_registro_policial_acidente_doenca'] =null;
            }


            if(empty($this->request->data['Agendamento']['unidade_atendimento_id'])){
                $this->request->data['Agendamento']['unidade_atendimento_id'] = null;
            }

            // pr($this->request->data['Agendamento']);die;
            if (isset($this->request->data['Agendamento']['cid_id']) && !empty($this->request->data['Agendamento']['cid_id'])) {
                if(count($this->request->data['Agendamento']['cid_id']) > 0){
                    $this->request->data['Agendamento']['cid_id'] =  $this->request->data['Agendamento']['cid_id']['0'];
                }
            }

            // pr($this->request->data);die;

            $this->loadModel("EnderecoSimples");

            if($this->request->data['Agendamento']['atend_domicilio_unidade_proxima'] == "0"){
                //Salvando endereço antes para pegar os ids dos dependentes
                $this->EnderecoSimples->save($this->request->data["EnderecoAtendimentoDomicilio"]);


                //Limpando o endereço do request
                unset($this->request->data["EnderecoAtendimentoDomicilio"]);

                //Recuperando o ID inserindo
                $idEndereco = $this->EnderecoSimples->id;

                //Setando o valor do endereço ID do usuário.
                $this->request->data["Agendamento"]["endereco_id_atend_domici"] = $idEndereco;
            }


            if(in_array($this->request->data['Agendamento']['tipologia_id'],
                    array(TIPOLOGIA_DESIGNACAO_DE_ASSISTENTE_TECNICO, TIPOLOGIA_INSPECAO, TIPOLOGIA_SINDICANCIA_INQUERITO_PAD))){
                $strData = $this->request->data['Agendamento']['data_livre'];
                $strHora = $this->request->data['Agendamento']['hora_livre'];

                $this->request->data['Agendamento']['dia_semana'] = $this->diaDaSemana($strData);
                $this->request->data['Agendamento']['data_hora'] = ($strData ." ".$strHora);
            }

            $tipologiaProcesso = $this->request->data['Agendamento']['tipologia_id'];
            if($this->request->data['Agendamento']['tipologia_id'] == TIPOLOGIA_RECURSO_ADMINISTRATIVO ){
                $this->loadModel('Atendimento');
                $tipologiaProcesso = $this->Atendimento->getTipologiaIdAtendimento($this->request->data['Agendamento']['numero_processo']);
                $this->request->data['Agendamento']['recurso_tipologia_id'] = $tipologiaProcesso;
            }

            //FLAG PARA AGENDAMENTO DIRETO
            $agendamentoDireto = false;
            if(isset($this->request->data['Agendamento']['atendimento_domiciliar']) && $this->request->data['Agendamento']['atendimento_domiciliar']){
                $agendamentoDireto = true;
            }
            //TIPOLOGIAS COM AGENDAMENTO DIRETO
            if(in_array($tipologiaProcesso,
                    array(TIPOLOGIA_COMUNICACAO_DE_ACIDENTE_DE_TRABALHO, TIPOLOGIA_INSPECAO,
                        TIPOLOGIA_APOSENTADORIA_ESPECIAL, TIPOLOGIA_RISCO_VIDA_INSALUBRIDADE,
                        TIPOLOGIA_DESIGNACAO_DE_ASSISTENTE_TECNICO, TIPOLOGIA_SINDICANCIA_INQUERITO_PAD))){
                $agendamentoDireto = true;
            }

            //PULA AS FASES DE CONFIRMAR PRESENCA E GERENCIAR SALA
            if($agendamentoDireto){
                $this->request->data['Agendamento']['agendamento_confirmado'] = true;
                $this->request->data['Agendamento']['status_agendamento'] = "Aguardando Atendimento";
            }

            $tipologiaSelecionada = $this->data['Agendamento']['tipologia_id'];

            //NAO POSSUI DIA
            if(in_array($tipologiaSelecionada,
                    array(TIPOLOGIA_RISCO_VIDA_INSALUBRIDADE, TIPOLOGIA_COMUNICACAO_DE_ACIDENTE_DE_TRABALHO,
                        TIPOLOGIA_APOSENTADORIA_ESPECIAL, ))){
                $this->request->data['Agendamento']['dia_semana'] = null;
            }
            
            if(in_array($tipologiaSelecionada, array(TIPOLOGIA_SINDICANCIA_INQUERITO_PAD))){
                $this->Agendamento->set($this->request->data);
                if($this->Agendamento->validates()){
                    $this->enviarEmailInformativoServidor();
                }
            }

            $dialogAlertMsg = '';
            if(in_array($this->request->data['Agendamento']['tipologia_id'] ,
                    array(TIPOLOGIA_RISCO_VIDA_INSALUBRIDADE, TIPOLOGIA_COMUNICACAO_DE_ACIDENTE_DE_TRABALHO))){
                $dialogAlertMsg = 'O agendamento só será concluído após a homologação de sua chefia imediata.';
            }

            if($this->request->data['Agendamento']['tipologia_id'] == TIPOLOGIA_RECURSO_ADMINISTRATIVO){
                $agendamentoOrig = $this->Agendamento->getAgendamentoOriginal($this->request->data['Agendamento']['numero_processo'], true);

                foreach($agendamentoOrig as $nome_model =>$model) {
                    if($nome_model == 'Agendamento' || $nome_model == 'AgendamentoCAT'){
                        foreach($model as $nome_param => $param){
                            //NÃO SOBRESCREVE OS DADOS DE AGENDAMENTO DESSE ARRAY
                            if(in_array($nome_param, $this->Agendamento->ignorarCamposAgendamentoRecurso())){
                                continue;
                            }else{
                                if($param === null || $param === ""){
                                    continue;
                                } else {
                                    $this->request->data[$nome_model][$nome_param] = $param;
                                }
                            }
                        }
                    }
                }
            }
            $okSave =  $this->Agendamento->saveAll($this->request->data);
            if($okSave){
                //$this->CidAgendamento->saveAll($this->request->data['CidAgendamento']);
                if(isset($this->Agendamento->id) && !empty($this->Agendamento->id))$id = $this->Agendamento->id;
                $currentFunction = $this->request->params['action']; //function corrente
                $currentController = $this->name; //Controller corrente
                $this->saveAuditLog($id,$currentController,'I',$currentFunction);


                if($this->request->data['Agendamento']['tipologia_id'] == TIPOLOGIA_COMUNICACAO_DE_ACIDENTE_DE_TRABALHO ||
                    (isset($this->request->data['Agendamento']['recurso_tipologia_id']) &&
                    $this->request->data['Agendamento']['recurso_tipologia_id'] == TIPOLOGIA_COMUNICACAO_DE_ACIDENTE_DE_TRABALHO)){ //CAT

                  //  unset($this->request->data["Agendamento"]);
                    if(isset($this->Agendamento->id) && !empty($this->Agendamento->id))$idAgendamento = $this->Agendamento->id;
                   // $this->loadModel("AgendamentoCAT");
                   // $this->request->data["AgendamentoCAT"]["agendamento_id"] = $idAgendamento;

                   //if($this->AgendamentoCAT->save($this->request->data["AgendamentoCAT"])){
                       $this->informarResponsaveis();
                       $dataSource->commit();
                       //$this->Session->setFlash(__('objeto_salvo_sucesso', __('Agendamento')), 'flash_success');
                       $this->Session->setFlash($dialogAlertMsg, false, array(), 'dialogAlertMsg');
                       return $this->redirect(array('action' => 'index'));
                   //}
                }
            }else{
                $this->loadModel("Cid");
                $this->request->data['Cids'] = $this->Cid->listarCidsIds($this->request->data['Cids']);
            }

            if ($okSave) {
                $this->informarResponsaveis();
                $dataSource->commit();
                if(!empty($dialogAlertMsg)){
                    $this->Session->setFlash($dialogAlertMsg, false, array(), 'dialogAlertMsg');
                }else{
                    $this->Session->setFlash(__('objeto_salvo_sucesso', __('Agendamento')), 'flash_success');
                }

                return $this->redirect(array('action' => 'index'));
            }
        }else{
            $this->request->data['Cids'][]= array('id'=>'', 'nome'=> '', 'nome_doenca'=> '');
        }
        $this->loadModel("Lotacao");
        if (isset($this->request->data['Agendamento']) && $this->request->data['Agendamento']['chefe_imediato_um_orgao_origem_id']) {
            $this->set('lotacoesUm', $this->Lotacao->consultarLotacoesOrgao($this->request->data['Agendamento']['chefe_imediato_um_orgao_origem_id']));
        } else {
            $this->set('lotacoesUm', []);
        }
        if (isset($this->request->data['Agendamento']) && $this->request->data['Agendamento']['chefe_imediato_dois_orgao_origem_id']) {
            $this->set('lotacoesDois', $this->Lotacao->consultarLotacoesOrgao($this->request->data['Agendamento']['chefe_imediato_dois_orgao_origem_id']));
        } else {
            $this->set('lotacoesDois', []);
        }
        if (isset($this->request->data['Agendamento']) && $this->request->data['Agendamento']['chefe_imediato_tres_orgao_origem_id']) {
            $this->set('lotacoesTres', $this->Lotacao->consultarLotacoesOrgao($this->request->data['Agendamento']['chefe_imediato_tres_orgao_origem_id']));
        } else {
            $this->set('lotacoesTres', []);
        }

        //Chamando os dados que são necessarios serem carregados.
        $this->carregarData();
        //render view edit
        $this->render('edit');
    }



    private function informarOrgaosSolicitacaoLicenca($vinculos, $tipologia = '') {
        foreach ($vinculos as $vinculo) {
            if (isset($vinculo['orgao_origem_id']) && $vinculo['orgao_origem_id']) {
                $this->loadModel('OrgaoOrigem');
                $orgao = $this->OrgaoOrigem->buscarEmailOrgao($vinculo['orgao_origem_id']);
                if (isset($orgao['OrgaoOrigem']['email']) && $orgao['OrgaoOrigem']['email']) {
                    $this->enviarEmailInformandoSolicitacaoLicenca($orgao['OrgaoOrigem']['email'], $this->request->data['Agendamento']['nome'], $this->request->data['Agendamento']['cpf'], $tipologia);
                }
            }
        }
    }

    private function informarResponsaveis() {

        $this->loadModel('Tipologia');


        $chefiaUm = $this->request->data['Agendamento']['chefe_imediato_um_id'];
        $chefiaDois = $this->request->data['Agendamento']['chefe_imediato_dois_id'];
        $chefiaTres = $this->request->data['Agendamento']['chefe_imediato_tres_id'];

        $cod_tipologia = $this->request->data['Agendamento']['tipologia_id'];

        if($this->request->data['Agendamento']['tipologia_id'] != TIPOLOGIA_SINDICANCIA_INQUERITO_PAD){
            $tipologia = $this->Tipologia->getNomeById($this->request->data['Agendamento']['tipologia_id']);
        }else{
            $tipologia = $this->Tipologia->getTipo($this->request->data['Agendamento']['tipo']);
        }

        $this->loadModel("Usuario");
        if ($chefiaUm) {
            $usuario1 = $this->Usuario->buscarUsuarioEmail($chefiaUm);
            
            if (isset($usuario1['Usuario']['email']) && !empty($usuario1['Usuario']['email'])) {
                $this->enviarEmailInformandoSolicitacaoLicenca($usuario1['Usuario']['email'], $this->request->data['Agendamento']['nome'], $this->request->data['Agendamento']['cpf'], $tipologia, $cod_tipologia);
            
            }
            if (isset($usuario1['Vinculo'])) {
                $this->informarOrgaosSolicitacaoLicenca($usuario1['Vinculo'], $tipologia);
            }
        }
        if ($chefiaDois) {
            $usuario2 = $this->Usuario->buscarUsuarioEmail($chefiaDois);
            if (isset($usuario2['Usuario']['email']) && !empty($usuario2['Usuario']['email'])) {
                $this->enviarEmailInformandoSolicitacaoLicenca($usuario2['Usuario']['email'], $this->request->data['Agendamento']['nome'], $this->request->data['Agendamento']['cpf'], $tipologia, $cod_tipologia);
            }
            if (isset($usuario2['Vinculo'])) {
                $this->informarOrgaosSolicitacaoLicenca($usuario2['Vinculo'], $tipologia);
            }
        }

        if ($chefiaTres) {
            $usuario3 = $this->Usuario->buscarUsuarioEmail($chefiaUm);
            if (isset($usuario3['Usuario']['email']) && !empty($usuario3['Usuario']['email'])) {
                $this->enviarEmailInformandoSolicitacaoLicenca($usuario3['Usuario']['email'], $this->request->data['Agendamento']['nome'], $this->request->data['Agendamento']['cpf'], $tipologia, $cod_tipologia);
            }
            if (isset($usuario3['Vinculo'])) {
                $this->informarOrgaosSolicitacaoLicenca($usuario3['Vinculo'], $tipologia);
            }
        }
    }

    /**
     * Obtem o cid de acordo com o CID
     */
    public function getCidAcompanhante() {
        $this->layout = 'ajax';
        if ($this->request->is('get')) {
            $this->loadModel('Cid');
            $cid = $this->request->query['term'];
            $conditions = ['Cid.nome ILIKE ' => '%' . $cid . '%'];
            $arrCid = $this->Cid->obterCid($conditions, 'Cid.id, Cid.nome, Cid.nome_doenca', 'all');
            $arrayRetorno = array();
            foreach ($arrCid as $key => $line) {
                $objTmp = new stdClass();
                $objTmp->id = $line['Cid']['id'];
                $objTmp->nome_doenca = $line['Cid']['nome_doenca'];
                $objTmp->label = $line['Cid']['nome'];
                $objTmp->value = $line['Cid']['nome'];
                $arrayRetorno[] = $objTmp;
            }
            echo json_encode($arrayRetorno);
            die;
        }
    }

    public function getUnidades(){
        $this->loadModel('UnidadeAtendimento');
        $resultado = $this->UnidadeAtendimento->obterUnidadesCidMunicipio('3', true, '3291', true);

        pr($resultado);die;
    }

    /**
     * Função para obter as Unidades de Atendimento
     * que atendam em domicilio, de acordo com um CID e um Município específico
     */
    public function getUnidadeCidMunicipio() {
        $this->loadModel('Agendamento');
        $this->layout = 'ajax';
        if ($this->request->is('post')) {

            $this->loadModel('UnidadeAtendimento');
            
            $arrayRetorno = array();

            $cids            = $this->request->data['cids'];
            $idTipologia = '';
            if(isset($this->request->data['idTipologia'])){
                $idTipologia            = $this->request->data['idTipologia'];
            }
            $municipio              = $this->request->data['municipio'];
            $atendimentoDomicilio   = false;
            $municipioProximo       = false;

            if($this->request->data['atendimento_domicilio'] == '1'){
                $atendimentoDomicilio = true;
            }

            if($this->request->data['municipio_proximo'] == '1'){
                $municipioProximo = true;
            }

            if (!empty($cids) ||
                in_array($idTipologia, Agendamento::$ARRAY_TIPOLOGIAS_SEM_CID) !== false) {
                $arrayRetorno = $this->UnidadeAtendimento->obterUnidadesCidMunicipio($cids, $atendimentoDomicilio, $municipio, $municipioProximo, $idTipologia);
            }
            echo json_encode($arrayRetorno);
            die;
        }
    }

    /**
     * Função para obter os Cids de acordo com a tipologia.
     */
    public function getUnidadeCid() {
        $this->loadModel('Agendamento');
        $this->layout = 'ajax';
        if ($this->request->is('post')) {
            $this->loadModel('UnidadeAtendimento');
            $arrayRetorno = array();
            $cids = $this->request->data['cids'];
            $idTipologia = $this->request->data['idTipologia'];
            if((!empty($cids) && count($cids)>0 && !empty($cids[0]))||
                //TIPOLOGIAS QUE NÃO REQUEREM OU NÃO POSSUEM CID
                in_array($idTipologia, Agendamento::$ARRAY_TIPOLOGIAS_SEM_CID) !== false){
                $cids = array_filter($cids, function($val){ return !empty($val); });
                $arrCids = $this->UnidadeAtendimento->obterUnidadesCid($cids, $idTipologia);
                foreach ($arrCids as $key => $line){
                    $objTmp = new stdClass();
                    $objTmp->id = $line['UnidadeAtendimento']['id'];
                    $objTmp->name = $line['UnidadeAtendimento']['nome'];
                    $isOk = true;
                    foreach ($arrayRetorno as $item){
                        if($objTmp->id  == $item->id)$isOk = false;
                    }
                    if($isOk)$arrayRetorno[] = $objTmp;
                }
            }
            echo json_encode($arrayRetorno);
            die;
        }
        die;
    }

    public function getUnidadeCidAtendimento() {
        $this->layout = 'ajax';
        if ($this->request->is('get')) {
            $this->loadModel('UnidadeAtendimento');
            $arrayRetorno = array();
            $idAtendimento = $this->request->query['idAtendimento'];
            if (!empty($idAtendimento)) {
                $arrUnidades = $this->UnidadeAtendimento->obterUnidadesCidAtendimento($idAtendimento);

                foreach ($arrUnidades as $key => $line) {
                    $objTmp = new stdClass();
                    $objTmp->id = $line['UnidadeAtendimento']['id'];
                    $objTmp->name = $line['UnidadeAtendimento']['nome'];
                    $objTmp->cid = $line['agendamento']['cid_id'];
                    $arrayRetorno[] = $objTmp;
                }
            }
            echo json_encode($arrayRetorno);
            die;
        }
    }

    /**
     * Obtem o cid de acordo com o CID
     */
    public function getCidNomeAcompanhante() {
        $this->layout = 'ajax';
        if ($this->request->is('get')) {
            $this->loadModel('Cid');
            $nomeDoenca = $this->request->query['term'];
            $conditions = ['Cid.nome_doenca ILIKE ' => '%' . $nomeDoenca . '%'];
            $arrCid = $this->Cid->obterCid($conditions, 'Cid.id, Cid.nome, Cid.nome_doenca', 'all');
            $arrayRetorno = array();
            foreach ($arrCid as $key => $line) {
                $objTmp = new stdClass();
                $objTmp->id = $line['Cid']['id'];
                $objTmp->nome = $line['Cid']['nome'];
                $objTmp->label = $line['Cid']['nome_doenca'];
                $objTmp->value = $line['Cid']['nome_doenca'];
                $arrayRetorno[] = $objTmp;
            }
            echo json_encode($arrayRetorno);
            die;
        }
    }

    public function carregarLicencasConcedidas() {
        $this->layout = 'ajax';
        if ($this->request->is(array('get'))) {
            $idServidor = $this->request->query['idServidor'];
            $tipologiaSelecionada = $this->request->query['tipologiaSelecionada'];

            $this->loadModel('Atendimento');
            $atendimentos = $this->Atendimento->listarAtendimentosVigentes($idServidor, $tipologiaSelecionada);
            echo json_encode($atendimentos);
            die;
        }
    }
    public function carregarDadosAcidentadoAgendamento() {
        $this->layout = 'ajax';
        $this->loadModel('Usuario');
        if ($this->request->is(array('post', 'put'))) {
            $idServidor = $this->request->data['idServidor'];
                    $filtro = new BSFilter();
                    $condicoes = array();
                    $condicoes['Usuario.id'] = $idServidor;
                    $condicoes['Vinculo.ativo'] = 't';
                    $filtro->setCondicoes($condicoes);
                    $filtro->setTipo('all');
                    $filtro->setCamposRetornadosString(
                        'Usuario.id',
                        'Usuario.data_nascimento',
                        'Usuario.nome',
                        'Usuario.cpf',
                        'Usuario.rg',
                        'Sexo.nome',
                        'EstadoCivil.nome',
                        'Funcao.nome',
                        'Lotacao.nome',
                        'Cargo.nome',
                        'Vinculo.matricula',
						'Escolaridade.nome'

                    );

                    $joins[] = array(
                        'table' => 'vinculo',
                        'alias' => 'Vinculo',
                        'type' => 'inner',
                        'conditions' => array('Vinculo.usuario_id = Usuario.id  ')
                    );

                    $joins[] = array(
                        'table' => 'vinculo_funcao',
                        'alias' => 'VinculoFuncao',
                        'type' => 'left',
                        'conditions' => array('VinculoFuncao.vinculo_id = Vinculo.id')
                    );


                    $joins[] = array(
                        'table' => 'funcao',
                        'alias' => 'Funcao',
                        'type' => 'left',
                        'conditions' => array('Funcao.id = VinculoFuncao.funcao_id')
                    );

                    $joins[] = array(
                        'table' => 'vinculo_lotacao',
                        'alias' => 'VinculoLotacao',
                        'type' => 'left',
                        'conditions' => array('VinculoLotacao.vinculo_id = Vinculo.id')
                    );

                    $joins[] = array(
                        'table' => 'lotacao',
                        'alias' => 'Lotacao',
                        'type' => 'left',
                        'conditions' => array('Lotacao.id = VinculoLotacao.lotacao_id')
                    );

                    $joins[] = array(
                        'table' => 'cargo',
                        'alias' => 'Cargo',
                        'type' => 'left',
                        'conditions' => array('Cargo.id = Vinculo.cargo_id')
                    );
					$joins[] = array(
                        'table' => 'escolaridade',
                        'alias' => 'Escolaridade',
                        'type' => 'left',
                        'conditions' => array('Escolaridade.id = Usuario.escolaridade_id')
                    );	
					
                    $filtro->setJoins($joins);


                    $dadosUsuario = $this->Usuario->listar($filtro);
                    echo json_encode($dadosUsuario);
                    die;
            }

    }


    public function carregarDadosOrgaoServidor() {
        $this->layout = 'ajax';
        $this->loadModel('Usuario');
        $this->loadModel('Endereco');
        $this->loadModel('Municipio');
        $this->loadModel('Lotacao');
        $this->loadModel('OrgaoOrigem');
        if ($this->request->is(array('post', 'put'))) {
            $idServidor = $this->request->data['idServidor'];
            $filtro = new BSFilter();
            $condicoes = array();
            $condicoes['Usuario.id'] = $idServidor;
            $condicoes['Vinculo.ativo'] = 't';
            $filtro->setCondicoes($condicoes);
            $filtro->setTipo('all');
            $filtro->setCamposRetornadosString(
                'lotacao.nome',
                'orgaoOrigem.orgao_origem',
                'orgaoOrigem.cnpj',
                'concat(endereco.logradouro, NULLIF(endereco.logradouro,\', Nº\' ||  endereco.numero), \' \', endereco.complemento) as logradouro',
                'endereco.bairro',
                'municipio.nome',
                'lotacao.telefone'
            );

            $joins[] = array(
                'table' => 'vinculo',
                'alias' => 'Vinculo',
                'type' => 'inner',
                'conditions' => array('Vinculo.usuario_id = Usuario.id  ')
            );

            $joins[] = array(
                'table' => 'orgao_origem',
                'alias' => 'orgaoOrigem',
                'type' => 'left',
                'conditions' => array('orgaoOrigem.id = Vinculo.orgao_origem_id')
            );


            $joins[] = array(
                'table' => 'lotacao',
                'alias' => 'lotacao',
                'type' => 'left',
                'conditions' => array('lotacao.orgao_origem_id = orgaoOrigem.id')
            );

            $joins[] = array(
                'table' => 'endereco',
                'alias' => 'endereco',
                'type' => 'left',
                'conditions' => array('endereco.id  = lotacao.endereco_id')
            );

            $joins[] = array(
                'table' => 'municipio',
                'alias' => 'municipio',
                'type' => 'left',
                'conditions' => array('municipio.id = endereco.municipio_id')
            );

            $filtro->setJoins($joins);


            $dadosUsuario = $this->Usuario->listar($filtro);
            echo json_encode($dadosUsuario);
            die;
        }

    }

    public function getDataFinalLicenca() {
        $this->layout = 'ajax';
        if ($this->request->is('get')) {
            $this->loadModel('Atendimento');
            $dataFinal = array();
            $idAtendimento = $this->request->query['idAtendimento'];
            if (!empty($idAtendimento)) {
                $arrAtendimento = $this->Atendimento->buscarAtendimentoDataParecer($idAtendimento);

                $duracao = $arrAtendimento['Atendimento']['duracao'];
                $data_parecer = $arrAtendimento['Atendimento']['data_parecer'];
                if (is_null($duracao)) {
                    $dataFinal = $data_parecer;
                } else {
                    $dataFinal = date('Y-m-d', strtotime($data_parecer . ' + ' . $duracao . ' days'));
                }
            }

            die(Util::toBrData($dataFinal));
        }
    }

    /**
     * Método que carrega o data para este crud
     */
    private function carregarData() {
        $tipoUsuario = CakeSession::read('Auth.User.tipo_usuario_id');
        
        $this->carregarListaDiasSemana();
        $this->carregarListaTipologia($tipoUsuario);
        $this->carregarListaCid();
        $this->carregarListaUnidadeAtendimento();
        $this->carregarListaOrgaoOrigem();
        $this->carregarListaQualidades();
        $this->carregarListasSexos();
        $this->carregarListasEstadoCivil();
        $this->carregarListasEscolaridade();

        $this->loadModel("Agendamento");
        $this->set('tipologiasSemCid', json_encode(Agendamento::$ARRAY_TIPOLOGIAS_SEM_CID));
    }

    private function carregarListasEstadoCivil() {
        $this->loadModel('EstadoCivil');
        $filtro = new BSFilter();
        $filtro->setTipo('list');
        $filtro->setCamposOrdenadosString('EstadoCivil.nome');
        $estadoCivil = $this->EstadoCivil->listar($filtro);
        $this->set(compact('estadoCivil'));
    }


    private function carregarListasEscolaridade() {
        $this->loadModel('Escolaridade');
        $filtro = new BSFilter();
        $filtro->setTipo('list');
        $filtro->setCamposOrdenadosString('Escolaridade.nome');
        $escolaridade = $this->Escolaridade->listar($filtro);
        $this->set(compact('escolaridade'));
     }

    /**
     * Carrega os dias da semana
     */
    private function carregarListaDiasSemana() {
        $diasSemana = ['Domingo' => 'Domingo',
            'Segunda-feira' => 'Segunda-feira',
            'Terça-feira' => 'Terça-feira',
            'Quarta-feira' => 'Quarta-feira',
            'Quinta-feira' => 'Quinta-feira',
            'Sexta-feira' => 'Sexta-feira',
            'Sábado' => 'Sábado'];
        $this->set(compact('diasSemana'));
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

    /**
     * Carregar lista de CIDs
     */
    private function carregarListaCid() {
        $this->loadModel('Cid');
        $filtro = new BSFilter();
        $filtro->setTipo('list');
        $filtro->setCamposOrdenadosString('Cid.nome');
        //$cids = $this->Cid->listar($filtro);
        $cids = array();
        $this->set(compact('cids'));
    }

    /**
     * Carrega lista de unidades de atendimentos
     */
    private function carregarListaUnidadeAtendimento() {
        $this->loadModel('UnidadeAtendimento');
        $filtro = new BSFilter();
        $filtro->setTipo('list');
        $filtro->setCamposOrdenadosString('UnidadeAtendimento.nome');
        $this->UnidadeAtendimento->unbindModel(
            array('hasAndBelongsToMany' => array('Cid'))
        );
        $unidadeAtendimento = $this->UnidadeAtendimento->listar($filtro);
        $acaoTela = $this->request->params["action"];
        $userUnidadeAtendimento = CakeSession::read('Auth.User');
        if ($acaoTela != "adicionar" && $acaoTela != "editar") {
            if (!isset($this->request->data['Agendamento']) || (isset($this->request->data['Agendamento']['unidade_atendimento_id']) && $this->request->data['Agendamento']['unidade_atendimento_id'] == "")) {
                if (isset($userUnidadeAtendimento['EnderecoUsuario'])) {
                    if ($userUnidadeAtendimento['EnderecoUsuario']['municipio_id'] !== NULL) {
                        $municipioUsuario = $userUnidadeAtendimento['EnderecoUsuario']['municipio_id'];
                        foreach ($unidadeAtendimento as $key => $strUnidade) {
                            $this->UnidadeAtendimento->unbindModel(
                                array('hasAndBelongsToMany' => array('Cid'))
                            );
                            $currentUnidade = $this->UnidadeAtendimento->findById($key);
                            if ($currentUnidade['Endereco']['municipio_id'] == $municipioUsuario) {
                                $this->request->data['Agendamento']['unidade_atendimento_id'] = $currentUnidade['UnidadeAtendimento']['id'];
                                break;
                            }
                            if (!isset($this->request->data['Agendamento']['unidade_atendimento_id'])) {
                                foreach ($currentUnidade['MunicipioProximo'] as $key => $arrMunicipioProximo) {
                                    if ($arrMunicipioProximo['id'] == $municipioUsuario) {
                                        $this->request->data['Agendamento']['unidade_atendimento_id'] = $currentUnidade['UnidadeAtendimento']['id'];
                                        break;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        $this->set(compact('unidadeAtendimento'));
    }

    public function beforeRender(){
        parent::beforeRender();
        $this->loadModel('TipoUsuario');
        if ($this->params['action'] == 'editar') {
            $this->set('title', __('agendamento_titulo_remarcar'));
        }
    }

    /**
     * Carrega lista de unidades de atendimentos
     */
    private function carregarListaOrgaoOrigem() {
        $this->loadModel('OrgaoOrigem');
        $filtro = new BSFilter();
        $filtro->setTipo('list');
        $filtro->setCamposOrdenadosString('OrgaoOrigem.orgao_origem');
        $orgaoOrigem = $this->OrgaoOrigem->listar($filtro);
        $this->set(compact('orgaoOrigem'));
    }

    private function carregarLotacoesOrgao($idOrgao = false) {
        $retorno = [];
        if ($idOrgao) {
            $this->loadModel('Lotacao');
            $filtro = new BSFilter();
            $filtro->setTipo('list');
            $filtro->setCamposOrdenadosString('Lotacao.nome');
            $condicoes = ['Lotacao.orgao_origem_id' => $idOrgao];
            $filtro->setCondicoes($condicoes);
            $retorno = $this->Lotacao->listar($filtro);
        }
        return $retorno;
    }

    public function getLotacoesOrgao() {
        if ($this->request->is(array('post', 'put'))) {
            $idOrgao = $this->request->data['id'];

            $this->loadModel('Lotacao');
            $filtro = new BSFilter();
            $filtro->setTipo('list');
            $filtro->setCamposOrdenadosString('Lotacao.nome');
            $condicoes = ['Lotacao.orgao_origem_id' => $idOrgao];
            $filtro->setCondicoes($condicoes);
            echo json_encode($this->Lotacao->listar($filtro));
            die;
        }
    }

    /**
     * Carrega lista de data e hora dos agendamentos
     */
    private function carregarListaDataHoraAgendamentos() {
        $this->loadModel('AgendaAtendimento');
        $dataHoraAgenda = [1 => 'teste', 2 => 'teste 2'];
        $this->set(compact('dataHoraAgenda'));
    }

    /**
     * Método utilizado para editar um Cargo previamente cadastrado no sistema
     * @param string $id identificado do Cargo que vai ser editado
     * @throws NotFoundException
     */


    private function carregarListasSexos() {
        $this->loadModel('Sexo');
        $filtro = new BSFilter();
        $filtro->setTipo('list');
        $filtro->setCamposOrdenadosString('Sexo.nome');
        $sexo = $this->Sexo->listar($filtro);
        $this->set(compact('sexo'));
    }

    public function editar($id = null, $retorno = false, $homologa = false) {
        if (!$id) {
            throw new NotFoundException(__('objeto_invalido', __('Cargo')));
        }

        $agendamento = $this->Agendamento->findById($id);

        $arrCidsSelecionados = ($agendamento['Cid']);
        $list = array();
        $list=array(

            $arrCidsSelecionados['id'] => $arrCidsSelecionados['nome'],
        );
        $this->set('cidsSelecionados', $list);

        $this->set('cidsSelecionados', $list);

        $this->loadModel('Atendimento');
        $this->loadModel('Tipologia');

        $this->loadModel('AgendamentoCAT');
        $tipoUsuario = CakeSession::read('Auth.User.tipo_usuario_id');


        $municipios = $this->retornaMunicipiosAtendimentoDomiciliar();
        $this->set('municipiosAtendimento', $municipios);

        $this->loadModel('Municipio');
        $municipiosAtendimento = $this->Municipio->listarMunicipiosUF(17);
        $this->set('municipiosUsuarios', $municipiosAtendimento);

        $this->loadModel('Estado');
        $this->set('estados', $this->Estado->listarEstados());

        if(!$homologa){
            if (!$agendamento || ($tipoUsuario != USUARIO_INTERNO && CakeSession::read('Auth.User.id') != $agendamento['Agendamento']['usuario_servidor_id'])) {
                 throw new NotFoundException(__('objeto_invalido', __('Cargo')));
            }

            if (!in_array($agendamento['Agendamento']['status_agendamento'], ['Agendado', 'Aguardando Atendimento'])) {
                $this->Session->setFlash(__('agendamento_nao_permitido_reagendar'), 'flash_alert');
                if ($retorno === false) {
                    return $this->redirect(array('action' => 'index'));
                } else {
                    return $this->redirect(array('controller' => $retorno, 'action' => 'index'));
                }
            }
        }

        if ($this->request->is(array('post', 'put'))) {

            $this->Agendamento->id = $id;
            unset($this->request->data['ChefiaImediataUm']);
            unset($this->request->data['ChefiaImediataDois']);
            unset($this->request->data['ChefiaImediataTres']);
            unset($this->request->data['Usuario']);

            if (!empty($this->request->data['Agendamento']['contrato_trabalho']['name'])) {
                $this->uploadArquivoAgendamento('ct','contrato_trabalho');
            } else{
                $this->request->data['Agendamento']['contrato_trabalho'] =null;
            }

            if(!empty($this->request->data['Agendamento']['edital_concurso']['name'])){
                $this->uploadArquivoAgendamento('ec','edital_concurso');
            }else{
                $this->request->data['Agendamento']['edital_concurso'] =null;
            }

            if(!empty($this->request->data['Agendamento']['curso_formacao_certificado']['name'])){
                $this->uploadArquivoAgendamento('fc','curso_formacao_certificado');
            }else{
                $this->request->data['Agendamento']['curso_formacao_certificado'] =null;
            }
            
            if(!empty($this->request->data['Agendamento']['oficio']['name'])){
                $this->uploadArquivoAgendamento('o','oficio');
            }else{
                $this->request->data['Agendamento']['oficio'] = null;
            }

            if(!empty($this->request->data['Agendamento']['ppp']['name'])){
                $this->uploadArquivoAgendamento('p', 'ppp');
            }else{
                $this->request->data['Agendamento']['ppp'] = null;
            }

            if(!empty($this->request->data['Agendamento']['ltcat']['name'])){
                $this->uploadArquivoAgendamento('l', 'ltcat');
            }else{
                $this->request->data['Agendamento']['ltcat'] = null;
            }

            if (!empty($this->request->data['AgendamentoCAT']['descricao_registro_policial_acidente_doenca']['name'])) {
                $this->uploadAnexoRegistroPolicial();
            } else{
                $this->request->data['AgendamentoCAT']['descricao_registro_policial_acidente_doenca'] =null;
            }

            if(in_array($this->request->data['Agendamento']['tipologia_id'],
                    array(TIPOLOGIA_RISCO_VIDA_INSALUBRIDADE, TIPOLOGIA_COMUNICACAO_DE_ACIDENTE_DE_TRABALHO,
                        TIPOLOGIA_APOSENTADORIA_ESPECIAL))){
                $this->request->data['Agendamento']['dia_semana'] = null;
            }

            if($this->request->data['Agendamento']['tipologia_id'] == TIPOLOGIA_COMUNICACAO_DE_ACIDENTE_DE_TRABALHO){
                $this->request->data['AgendamentoCAT']['agendamento_id'] = $id;
            }

            $dialogAlertMsg = '';
            if(in_array($this->request->data['Agendamento']['tipologia_id'],
                    array(TIPOLOGIA_RISCO_VIDA_INSALUBRIDADE, TIPOLOGIA_COMUNICACAO_DE_ACIDENTE_DE_TRABALHO))){
                $dialogAlertMsg = 'O agendamento só será concluído após a homologação de sua chefia imediata.';
            }

            if(in_array($this->request->data['Agendamento']['tipologia_id'],
                array(TIPOLOGIA_DESIGNACAO_DE_ASSISTENTE_TECNICO, TIPOLOGIA_INSPECAO, TIPOLOGIA_SINDICANCIA_INQUERITO_PAD))){
                $strData = $this->request->data['Agendamento']['data_livre'];
                $strHora = $this->request->data['Agendamento']['hora_livre'];

                $this->request->data['Agendamento']['dia_semana'] = $this->diaDaSemana($strData);
                $this->request->data['Agendamento']['data_hora'] = Util::toDBDataHora($strData ." ".$strHora);
            }

            if (isset($this->request->data['Agendamento']['cid_id']) && is_array($this->request->data['Agendamento']['cid_id'])) {
                $this->request->data['Agendamento']['cid_id'] =  $this->request->data['Agendamento']['cid_id'][0];
            }

            $valida = true;
            if($homologa){$valida = false;}
            try {
                if(isset($this->request->data['Agendamento']['data_hora']) && !empty($this->request->data['Agendamento']['data_hora'])){
                    $this->request->data['Agendamento']['data_hora'] = Util::toBrDataHora($this->request->data['Agendamento']['data_hora']);
                }
                if ($this->Agendamento->saveAll($this->request->data, array('validate' => $valida))) {

                    $currentFunction = $this->request->params['action']; //function corrente
                    $currentController = $this->name; //Controller corrente
                    $this->saveAuditLog($id, $currentController, 'A', $currentFunction);

                    if (!empty($dialogAlertMsg) && !$homologa) {
                        $this->Session->setFlash($dialogAlertMsg, false, array(), 'dialogAlertMsg');
                    } else {
                        $this->Session->setFlash(__('objeto_salvo_sucesso', __('Agendamento')), 'flash_success');
                    }
                    if ($retorno === false) {
                        return $this->redirect(array('action' => 'index'));
                    } else {
                        return $this->redirect(array('controller' => $retorno, 'action' => 'index'));
                    }
                }else{
                    $this->loadModel("Cid");
                    $this->request->data['Cids'] = $this->Cid->listarCidsIds($this->request->data['Cids']);
                }
            }catch (Exception $e){
                pr($e->getMessage()); die;
            }


        }
        $this->set('lotacoesUm', []);
        $this->set('lotacoesDois', []);
        $this->set('lotacoesTres', []);

        if (!$this->request->data) {
            if(in_array($agendamento['Agendamento']['tipologia_id'],
                    array(TIPOLOGIA_DESIGNACAO_DE_ASSISTENTE_TECNICO, TIPOLOGIA_INSPECAO, TIPOLOGIA_SINDICANCIA_INQUERITO_PAD))){
                $dataHora = Util::toDBDataHora($agendamento['Agendamento']['data_hora']);
                $data = substr($dataHora, 0, 10);
                $hora = substr($dataHora,11, 5);

                $agendamento['Agendamento']['data_livre'] = $data;
                $agendamento['Agendamento']['hora_livre'] = $hora;
            }

            if($agendamento['Agendamento']['tipologia_id'] == TIPOLOGIA_RECURSO_ADMINISTRATIVO) {
                $tipologia_id = $this->Atendimento->getTipologiaIdAtendimento($agendamento['Agendamento']['numero_processo']);
                $tipologia = $this->Tipologia->getNomeById($tipologia_id);
                $agendamento['Agendamento']['tipologia_processo'] = "Recurso Administrativo (".$tipologia.")";
            }

                $agendamento['Agendamento']['searchAcompnhadoCid'] = $agendamento['CidAcompanhante']['nome'];
            $agendamento['Agendamento']['searchAcompnhadoDoenca'] = $agendamento['CidAcompanhante']['nome_doenca'];

            $agendamento['Agendamento']['data_a_partir'] = Util::toBrDataHora($agendamento['Agendamento']['data_a_partir']);
            // pr($agendamento['Agendamento']['data_a_partir']);die;
            
            $this->request->data = $agendamento;
            $this->request->data['Agendamento']['nome'] = $agendamento['UsuarioServidor']['nome'];
            $this->request->data['Agendamento']['cpf'] = $agendamento['UsuarioServidor']['cpf'];
            if ($agendamento['Agendamento']['chefe_imediato_um_orgao_origem_id']) {
                $orgaoOrigemUm = $agendamento['Agendamento']['chefe_imediato_um_orgao_origem_id'];
                $lotacaoUm = $agendamento['Agendamento']['chefe_imediato_um_lotacao_id'];
                $idUsuarioUm = $agendamento['Agendamento']['chefe_imediato_um_id'];

                $this->request->data['Agendamento']['matriculaChefiaUm'] = $this->getMatriculaOrgaoLotacaoId($orgaoOrigemUm, $lotacaoUm, $idUsuarioUm);
                $this->request->data['Agendamento']['nomeChefiaUm'] = $this->request->data['ChefiaImediataUm']['nome'];

                $this->set('lotacoesUm', $this->carregarLotacoesOrgao($agendamento['Agendamento']['chefe_imediato_um_orgao_origem_id']));
            }
            if ($agendamento['Agendamento']['chefe_imediato_dois_orgao_origem_id']) {
                $orgaoOrigemDois = $agendamento['Agendamento']['chefe_imediato_dois_orgao_origem_id'];
                $lotacaoDois = $agendamento['Agendamento']['chefe_imediato_dois_lotacao_id'];
                $idUsuarioDois = $agendamento['Agendamento']['chefe_imediato_dois_id'];

                $this->request->data['Agendamento']['matriculaChefiaDois'] = $this->getMatriculaOrgaoLotacaoId($orgaoOrigemDois, $lotacaoDois, $idUsuarioDois);
                $this->request->data['Agendamento']['nomeChefiaDois'] = $this->request->data['ChefiaImediataDois']['nome'];

                $this->set('lotacoesDois', $this->carregarLotacoesOrgao($agendamento['Agendamento']['chefe_imediato_dois_orgao_origem_id']));
            }
            if ($agendamento['Agendamento']['chefe_imediato_tres_orgao_origem_id']) {
                $orgaoOrigemTres = $agendamento['Agendamento']['chefe_imediato_tres_orgao_origem_id'];
                $lotacaoTres = $agendamento['Agendamento']['chefe_imediato_tres_lotacao_id'];
                $idUsuarioTres = $agendamento['Agendamento']['chefe_imediato_tres_id'];

                $this->request->data['Agendamento']['matriculaChefiaTres'] = $this->getMatriculaOrgaoLotacaoId($orgaoOrigemTres, $lotacaoTres, $idUsuarioTres);
                $this->request->data['Agendamento']['nomeChefiaTres'] = $this->request->data['ChefiaImediataTres']['nome'];

                $this->set('lotacoesTres', $this->carregarLotacoesOrgao($this->request->data['Agendamento']['chefe_imediato_tres_orgao_origem_id']));
            }
        } else {
            if ($this->request->data['Agendamento']['chefe_imediato_um_orgao_origem_id']) {
                $orgaoOrigemUm = $this->request->data['Agendamento']['chefe_imediato_um_orgao_origem_id'];
                $lotacaoUm = $this->request->data['Agendamento']['chefe_imediato_um_lotacao_id'];
                $idUsuarioUm = $this->request->data['Agendamento']['chefe_imediato_um_id'];

                $this->request->data['Agendamento']['matriculaChefiaUm'] = $this->getMatriculaOrgaoLotacaoId($orgaoOrigemUm, $lotacaoUm, $idUsuarioUm);

                $this->set('lotacoesUm', $this->carregarLotacoesOrgao($this->request->data['Agendamento']['chefe_imediato_um_orgao_origem_id']));
            }
            if ($this->request->data['Agendamento']['chefe_imediato_dois_orgao_origem_id']) {
                $orgaoOrigemDois = $this->request->data['Agendamento']['chefe_imediato_dois_orgao_origem_id'];
                $lotacaoDois = $this->request->data['Agendamento']['chefe_imediato_dois_lotacao_id'];
                $idUsuarioDois = $this->request->data['Agendamento']['chefe_imediato_dois_id'];

                $this->request->data['Agendamento']['matriculaChefiaDois'] = $this->getMatriculaOrgaoLotacaoId($orgaoOrigemDois, $lotacaoDois, $idUsuarioDois);

                $this->set('lotacoesDois', $this->carregarLotacoesOrgao($this->request->data['Agendamento']['chefe_imediato_dois_orgao_origem_id']));
            }
            if ($this->request->data['Agendamento']['chefe_imediato_tres_orgao_origem_id']) {
                $orgaoOrigemTres = $this->request->data['Agendamento']['chefe_imediato_tres_orgao_origem_id'];
                $lotacaoTres = $this->request->data['Agendamento']['chefe_imediato_tres_lotacao_id'];
                $idUsuarioTres = $this->request->data['Agendamento']['chefe_imediato_tres_id'];

                $this->request->data['Agendamento']['matriculaChefiaTres'] = $this->getMatriculaOrgaoLotacaoId($orgaoOrigemTres, $lotacaoTres, $idUsuarioTres);

                $this->set('lotacoesTres', $this->carregarLotacoesOrgao($this->request->data['Agendamento']['chefe_imediato_tres_orgao_origem_id']));
            }
        }

        //Chamando os dados que são necessarios serem carregados.
        $this->carregarData();
        //render view edit
        $this->render('edit');
    }


    public function homologar($id, $retorno = null)
    {
        $idUsuario = CakeSession::read('Auth.User.id');
        $agendamento = $this->Agendamento->findById($id);
        $idUsuarioDois = $agendamento['Agendamento']['chefe_imediato_dois_id'];
        $idUsuarioUm = $agendamento['Agendamento']['chefe_imediato_um_id'];
        $idUsuarioTres = $agendamento['Agendamento']['chefe_imediato_tres_id'];

        if (in_array($idUsuario, array($idUsuarioUm, $idUsuarioDois, $idUsuarioTres))) {
            $this->editar($id, null, true);
            if (isset($this->Agendamento->id) && !empty($this->Agendamento->id)) $id = $this->Agendamento->id;

        } else {
            $this->Session->setFlash(__('Você não tem permissão para acessar essa homologação.'), 'flash_alert');
            return $this->redirect(array('controller' => 'Agendamento', 'action' => 'index'));
        }
    }


    public function deleteFile($id, $tipo){

        $tipo = "Agendamento.".$tipo;
        $path = $tipo."_path";
        $this->Agendamento->id = $id;
        $this->Agendamento->updateAll(
            array($tipo=>null, $path=>null),
        array('Agendamento.id' => $id));


        $currentFunction = $this->request->params['action']; //function corrente
        $currentController = $this->name; //Controller corrente
        $this->saveAuditLog($id,$currentController,'E',$currentFunction);


        $this->Session->setFlash(__('arquivo_excluir_sucesso', __('Agendamento')), 'flash_success');
        return $this->redirect(array('controller' => 'Agendamento', 'action' => 'editar',$id));

    }

    public function deleteFileCAT($id, $field){
        $this->loadModel('AgendamentoCAT');
        $field = "AgendamentoCAT.descricao_registro_policial_acidente_doenca_path";
        $field2 = "AgendamentoCAT.descricao_registro_policial_acidente_doenca";
        $this->AgendamentoCAT->id = $id;
       $ss =  $this->AgendamentoCAT->updateAll(
            array($field=>null),
           array($field2=>null),
            array('AgendamentoCAT.id' => $id));

        if(isset($this->Agendamento->id) && !empty($this->Agendamento->id))$id = $this->Agendamento->id;
        $currentFunction = $this->request->params['action']; //function corrente
        $currentController = $this->name; //Controller corrente
        $this->saveAuditLog($id,$currentController,'E',$currentFunction);


        $this->Session->setFlash(__('arquivo_excluir_sucesso', __('Agendamento')), 'flash_success');
        return $this->redirect(array('controller' => 'Agendamento', 'action' => 'editar',$id));

    }

    private function uploadContratoTrabalho() {
        $filepathPart = WWW_ROOT. '/' .  'contrato_trabalho';
        if(!is_dir($filepathPart)) {
            mkdir($filepathPart);
        }
        $fileCT =$this->request->data['Agendamento']['contrato_trabalho'];
		$temp = explode(".", $this->request->data['Agendamento']['contrato_trabalho']['name']);
        $filename = uniqid(). 'ctct.' . end($temp);
        $filePath= $filepathPart.'/'.$filename;
		$filePath = str_replace("\\","/",$filePath);
		
        $this->request->data['Agendamento']['contrato_trabalho'] = $filename;

        move_uploaded_file($fileCT['tmp_name'],$filePath);
        $this->request->data['Agendamento']['contrato_trabalho_path'] = '/' . 'contrato_trabalho'.'/'.$filename;
		
		
    }

    private function uploadEditalConcurso() {
        $filepathPart = WWW_ROOT. '/' . 'edital_concurso';
        if(!is_dir($filepathPart)) {
            mkdir($filepathPart);
        }

        $fileCEC = $this->request->data['Agendamento']['edital_concurso'];
        $temp = explode(".", $this->request->data['Agendamento']['edital_concurso']['name']);
        $filename = uniqid(). 'ecec.' . end($temp);
        $filePath= $filepathPart.'/'.$filename;
        $filePath = str_replace("\\","/",$filePath);
        $this->request->data['Agendamento']['edital_concurso'] = $filename;

        move_uploaded_file($fileCEC['tmp_name'],$filePath);
        $this->request->data['Agendamento']['edital_concurso_path'] = '/' . 'edital_concurso'.'/'.$filename;


    }


    private function uploadFormacaoCertificado() {
        $filepathPart = WWW_ROOT. '/' . 'curso_formacao_certificado';
        if(!is_dir($filepathPart)) {
            mkdir($filepathPart);
        }
        $fileCFC = $this->request->data['Agendamento']['curso_formacao_certificado'];
        $temp = explode(".", $this->request->data['Agendamento']['curso_formacao_certificado']['name']);
        $filename = uniqid(). 'fcfc.' . end($temp);
        $filePath= $filepathPart.'/'.$filename;
        $filePath = str_replace("\\","/",$filePath);
        $this->request->data['Agendamento']['curso_formacao_certificado'] = $filename;

        move_uploaded_file($fileCFC['tmp_name'],$filePath);
        $this->request->data['Agendamento']['curso_formacao_certificado_path'] = '/' . 'curso_formacao_certificado'.'/'.$filename;


    }
    

    private function getTamanhoMaxArquivoUpload(){
        
        $this->loadModel('ParametroGeral');
        $parametro = $this->ParametroGeral->findById('1');

        return $parametro['ParametroGeral']['limite_tam_arquivo_upload']; 
    }


    private function getTempoMaxArquivoUpload(){
        
        $this->loadModel('ParametroGeral');
        $parametro = $this->ParametroGeral->findById('1');

        return $parametro['ParametroGeral']['limite_tempo_arquivo_upload']; 
    }

    private function getQtdMaxArquivoUpload(){
        
        $this->loadModel('ParametroGeral');
        $parametro = $this->ParametroGeral->findById('1');

        return $parametro['ParametroGeral']['limite_qtd_arquivo_upload']; 
    }

    private function limparTabelaUploads($ids){
        $this->loadModel("TempUploadArquivo");

        foreach ($ids as $id) {
            $this->TempUploadArquivo->delete($id, false);    
        }
    }

    public function limparTabelaUpload(){

        $this->autoRender = false;
        $this->loadModel("TempUploadArquivo");

        $ids = array('15'=>'15', '14' => '14');

        foreach ($ids as $id) {
            $this->TempUploadArquivo->delete($id, false);
        }
       // $db = ConnectionManager::getDataSource('default');
       // $db->rawQuery("DELETE FROM TempUploadArquivo WHERE id=$id");  
        die;
    }

    public function opeHorasMinutos(){
        $dataAuxiliar = "";
        $dataAuxiliar = Util::opeHorasMinutos(date('Y-m-d H:i:s'), '00:30', false);
        die($dataAuxiliar);
    }

    private function uploadArquivoAgendamento($sigla, $name){
        try {
            $this->loadModel("TempUploadArquivo");


            $qtdMaxUpload = $this->getQtdMaxArquivoUpload();
            $tempoMaxUpload = $this->getTempoMaxArquivoUpload();

            $formatosValidos = Configure::read('FORMATOS_UPLOAD');
            $tmp = explode('.', $this->request->data['Agendamento'][$name]['name']);
            $extensao = end($tmp);
            $idUsuario = $this->Auth->user()['id'];
            
            // false - subtrai / true - soma
            $dataAuxiliar = Util::opeHorasMinutos(date('Y-m-d H:i:s'), $tempoMaxUpload, false);

            $tempUploadArquivo = $this->TempUploadArquivo->find('list', array(
                'conditions' => array(
                    'TempUploadArquivo.created <= '  => $dataAuxiliar,
                    'TempUploadArquivo.usuario_id'  => $idUsuario
                )
            ));

            // deletando os registros antigos
            $this->limparTabelaUploads($tempUploadArquivo);
            

            //consultando a quantidade de registros a partir da data-hora 
            $totalRegistrosUpload = $this->TempUploadArquivo->find('count', array(
                'conditions' => array(
                    'TempUploadArquivo.created >= '  => $dataAuxiliar,
                    'TempUploadArquivo.usuario_id'  => $idUsuario
                )
            ));

            // pr($tempUploadArquivo);die;

            if(intval($totalRegistrosUpload) < intval($qtdMaxUpload)){
                // continua o upload
                $dataTempUpload = array();
                $tamanhoMaxArquivo = Util::convertToBytes($this->getTamanhoMaxArquivoUpload());

                // verifica se o tamanho do arquivo é menor do que o tamanho máximo (Paramêtros Gerais)
                if($this->request->data['Agendamento'][$name]['size'] < $tamanhoMaxArquivo){

                    //verifica o formato do arquivo é um dos formatos válidos para upload
                    if (in_array($extensao, $formatosValidos)) {
                        
                        // executa o upload
                        $filepathPart = WWW_ROOT. '/' . $name;
                        if(!is_dir($filepathPart)) {
                            mkdir($filepathPart);
                        }
                        $file = $this->request->data['Agendamento'][$name];
                        $temp = explode(".", $this->request->data['Agendamento'][$name]['name']);
                        $filename = uniqid(). $sigla.$sigla.'.' . end($temp);
                        $filePath= $filepathPart.'/'.$filename;
                        $filePath = str_replace("\\","/",$filePath);
                        
                        $this->request->data['Agendamento'][$name] = $filename;
                        if(isset($this->request->data['Agendamento'][$name]) && !empty($this->request->data['Agendamento'][$name])){
                            $this->request->data['Agendamento'][$name] = $filename;
                        }

                        move_uploaded_file($file['tmp_name'],$filePath);
                        

                        $this->request->data['Agendamento'][$name.'_path'] = '/' . $name.'/'.$filename;

                        $dataTempUpload['usuario_id'] = $idUsuario;
                        $dataTempUpload['arquivo']    = '/' . $name.'/'.$filename;
                        $this->TempUploadArquivo->save($dataTempUpload);
                            
                        $this->TempUploadArquivo->id = null;
                        $dataTempUpload = null;
                        $this->TempUploadArquivo = null;
                       
                    }else{
                        // Formato inválido
                        $texto = "";
                        foreach ($formatosValidos as $key => $formato) {
                            $tmp = explode('.', $formato);
                            $formato = end($tmp);
                            
                            $texto .= $formato;

                            if(intval($key+1) < count($formatosValidos)){
                                $texto .= ", ";
                            }
                        }
                        throw new Exception("Somente o(s) formato(s): ". $texto . " é(são) aceito(s) para upload.");
                    }
                }else{
                    throw new Exception("O tamanho do arquivo é maior que o permitido (". $this->getTamanhoMaxArquivoUpload() ." mb)");
                }
            }else{
                //mesagem para o usuário de que ele passou do limite e tem que aguardar um tempo
                $dataHabilitarUpload = Util::opeHorasMinutos(date('Y-m-d H:i:s'), $tempoMaxUpload, true);
                throw new Exception("Você já efetuou " . $totalRegistrosUpload . " upload(s). Aguarde até ". date("d/m/Y H:i:s", strtotime($dataHabilitarUpload)) . " para efetuar nova(s)  submissão(ões) de arquivo(s)"); 
            }

            
            
        }catch(\Exception $e){
            if(isset($this->request->params['pass']['0']) && !empty(($this->request->params['pass']['0']))){
                $this->Session->setFlash($e->getMessage(), 'flash_alert');
                return $this->redirect(array('controller' => $this->request->controller, 'action' => $this->request->action, $this->request->params['pass']['0']));
            }else{
                $this->Session->setFlash($e->getMessage(), 'flash_alert');
                return $this->redirect(array('controller' => $this->request->controller, 'action' => $this->request->action));    
            }
        }
        

    }


    private function uploadArquivo($sigla, $name){
        $filepathPart = WWW_ROOT. '/' . $name;
        if(!is_dir($filepathPart)) {
            mkdir($filepathPart);
        }
        $file = $this->request->data['Agendamento'][$name];
        $temp = explode(".", $this->request->data['Agendamento'][$name]['name']);
        $filename = uniqid(). $sigla.$sigla.'.' . end($temp);
        $filePath= $filepathPart.'/'.$filename;
        $filePath = str_replace("\\","/",$filePath);
        $this->request->data['Agendamento'][$name] = $filename;

        move_uploaded_file($file['tmp_name'],$filePath);
        $this->request->data['Agendamento'][$name.'_path'] = '/' . $name.'/'.$filename;
    }

    private function uploadAnexoRegistroPolicial() {
        try {

            $this->loadModel("TempUploadArquivo");


            $qtdMaxUpload = $this->getQtdMaxArquivoUpload();
            $tempoMaxUpload = $this->getTempoMaxArquivoUpload();

            $formatosValidos = Configure::read('FORMATOS_UPLOAD');
            $tmp = explode('.', $this->request->data['AgendamentoCAT']['descricao_registro_policial_acidente_doenca']['name']);
            $extensao = end($tmp);
            $idUsuario = $this->Auth->user()['id'];
            
            // false - subtrai / true - soma
            $dataAuxiliar = Util::opeHorasMinutos(date('Y-m-d H:i:s'), $tempoMaxUpload, false);

            $tempUploadArquivo = $this->TempUploadArquivo->find('list', array(
                'conditions' => array(
                    'TempUploadArquivo.created <= '  => $dataAuxiliar,
                    'TempUploadArquivo.usuario_id'  => $idUsuario
                )
            ));

            // deletando os registros antigos
            $this->limparTabelaUploads($tempUploadArquivo);
            

            //consultando a quantidade de registros a partir da data-hora 
            $totalRegistrosUpload = $this->TempUploadArquivo->find('count', array(
                'conditions' => array(
                    'TempUploadArquivo.created >= '  => $dataAuxiliar,
                    'TempUploadArquivo.usuario_id'  => $idUsuario
                )
            ));

            // pr($tempUploadArquivo);die;

            if(intval($totalRegistrosUpload) < intval($qtdMaxUpload)){
                // continua o upload
                $dataTempUpload = array();
                $tamanhoMaxArquivo = Util::convertToBytes($this->getTamanhoMaxArquivoUpload()); 
                

                // verifica se o tamanho do arquivo é menor do que o tamanho máximo (Paramêtros Gerais)
                if($this->request->data['AgendamentoCAT']['descricao_registro_policial_acidente_doenca']['size'] < $tamanhoMaxArquivo){

                    //verifica o formato do arquivo é um dos formatos válidos para upload
                    if (in_array($extensao, $formatosValidos)) {
                        
                        // executa o upload
                        $filepathPart = WWW_ROOT. '/' . 'registro_policial';
                        if(!is_dir($filepathPart)) {
                            mkdir($filepathPart);
                        }
                        $file = $this->request->data['AgendamentoCAT']['descricao_registro_policial_acidente_doenca'];
                        $temp = explode(".", $this->request->data['AgendamentoCAT']['descricao_registro_policial_acidente_doenca']['name']);
                        $filename = uniqid(). 'arp.' . end($temp);
                        $filePath= $filepathPart.'/'.$filename;
                        $filePath = str_replace("\\","/",$filePath);

                        // pr($this->request->data);die;
                        // $this->request->data['AgendamentoCAT']['descricao_registro_policial_acidente_doenca']['name'] = $filename;
                        // if(isset($this->request->data['Agendamento'][$name]) && !empty($this->request->data['Agendamento'][$name])){
                        //     $this->request->data['Agendamento'][$name] = $filename;
                        // }

                        move_uploaded_file($file['tmp_name'],$filePath);
                        

                        $this->request->data['AgendamentoCAT']['descricao_registro_policial_acidente_doenca'] = $filename;

                        $dataTempUpload['usuario_id'] = $idUsuario;
                        $dataTempUpload['arquivo']    = $filename;
                        $this->TempUploadArquivo->save($dataTempUpload);
                            
                        $this->TempUploadArquivo->id = null;
                        $dataTempUpload = null;
                        $this->TempUploadArquivo = null;
                       
                    }else{
                        // Formato inválido
                        $texto = "";
                        foreach ($formatosValidos as $key => $formato) {
                            
                            $tmp = explode('.', $formato);
                            $formato = end($tmp);
                            
                            $texto .= $formato;

                            if(intval($key+1) < count($formatosValidos)){
                                $texto .= ", ";
                            }
                           
                        }
                        
                       throw new Exception("Somente o(s) formato(s): ". $texto . " é(são) aceito(s) para upload.");  
                    }
                }else{
                    throw new Exception("O tamanho do arquivo é maior que o permitido (". $this->getTamanhoMaxArquivoUpload() ." mb)");
                }
            }else{
                //mesagem para o usuário de que ele passou do limite e tem que aguardar um tempo
                $dataHabilitarUpload = Util::opeHorasMinutos(date('Y-m-d H:i:s'), $tempoMaxUpload, true);
                throw new Exception("Você já efetuou " . $totalRegistrosUpload . " upload(s). Aguarde até ". date("d/m/Y H:i:s", strtotime($dataHabilitarUpload)) . " para efetuar nova(s)  submissão(ões) de arquivo(s)"); 
            }

            
            
        }catch(\Exception $e){
            $this->Session->setFlash($e->getMessage(), 'flash_alert');
            return $this->redirect(array('controller' => $this->request->controller, 'action' => $this->request->action));
        }


    }
    
    // private function uploadAnexoRegistroPolicial() {
    //     $filepathPart = WWW_ROOT. '/' .  'registro_policial';
    //     if(!is_dir($filepathPart)) {
    //         mkdir($filepathPart);
    //     }
    //     $fileCT =$this->request->data['AgendamentoCAT']['descricao_registro_policial_acidente_doenca'];
    //     $temp = explode(".", $this->request->data['AgendamentoCAT']['descricao_registro_policial_acidente_doenca']['name']);
    //     $filename = uniqid(). 'arp.' . end($temp);
    //     $filePath= $filepathPart.'/'.$filename;
    //     $filePath = str_replace("\\","/",$filePath);

    //     $this->request->data['AgendamentoCAT']['descricao_registro_policial_acidente_doenca'] = $filename;

    //     move_uploaded_file($fileCT['tmp_name'],$filePath);
    //     $this->request->data['AgendamentoCAT']['contrato_trabalho_path'] = '/' . 'descricao_registro_policial_acidente_doenca'.'/'.$filename;


    // }



    public function validarUnicidadeAgendamento() {
        
    }
    
    private function enviarEmailInformativoServidor(){

        App::uses('CakeEmail', 'Network/Email');
        $email = new CakeEmail('default');
        $email->from(array(
            'olga.marinho@sad.pe.gov.br' => 'SPM'
        ));
        $email->subject('Solicitação de Perícia .:SPM:.');
        $email->emailFormat('html');

        $this->loadModel('Usuario');
        $emailServidor = $this->Usuario->getEmailById($this->data['Agendamento']['usuario_servidor_id']);

        $destinatario = $emailServidor;
        if(empty($destinatario)){
            $emailLogado = $this->Usuario->getEmailById($this->Auth->user()['id']);
            $destinatario = $emailLogado;
        }

        if(empty($destinatario)){
            return false;
        }
        $email->to($destinatario);

        $this->loadModel('Tipologia');
        $this->loadModel('UnidadeAtendmento');

        $agendamento = $this->data['Agendamento'];
        $nome = $agendamento['nome'];
        $tipo =  $this->Tipologia->getTipo($agendamento['tipo']);
        $this->UnidadeAtendimento->unbindModel(
            array('hasAndBelongsToMany' => array('Cid'))
        );
        $unidade = $this->UnidadeAtendimento->getNomeById($agendamento['unidade_atendimento_id']);

        $chefias = array();
        if(!empty($agendamento['chefe_imediato_um_id'])){
            $chefias[] = $this->Usuario->getNomeById($agendamento['chefe_imediato_um_id']);
        }
        if(!empty($agendamento['chefe_imediato_dois_id'])){
            $chefias[] = $this->Usuario->getNomeById($agendamento['chefe_imediato_dois_id']);
        }
        if(!empty($agendamento['chefe_imediato_tres_id'])){
            $chefias[] = $this->Usuario->getNomeById($agendamento['chefe_imediato_tres_id']);
        }
        $chefiasTxt = implode(", ", $chefias);

        $htmlMsg="<html>
                <head></head>
                <body>
                    <h1>Informativo de solicitação de Perícia Médica,</h1>
                    <br/>
                    Olá $nome,
                    <br/>
                    Um agendamento do tipo $tipo foi marcado para você. <br/>

                    Quando: {$agendamento['data_hora']} {$agendamento['dia_semana']}<br />
                    Local: $unidade <br/>
                    Chefias: $chefiasTxt <br/>
                </body>
            </html>";

        return ($email->send($htmlMsg)) ? true : false;
    }

    private function enviarEmailInformandoSolicitacaoLicenca($destinatario, $nomeServidor, $cpfServidor, $tipologia = '', $cod_tipologia = false) {


        $agendamento = $this->data['Agendamento'];
        
        $this->loadModel('UnidadeAtendimento');
        $unidadeDeAtendimento = false;
        if(isset($agendamento['unidade_atendimento_id']) && !empty($agendamento['unidade_atendimento_id'])){
            $this->UnidadeAtendimento->unbindModel(
                array('hasAndBelongsToMany' => array('Cid'))
            );
            $unidadeDeAtendimento = $this->UnidadeAtendimento->findById($agendamento['unidade_atendimento_id']);
        }


        // pr($unidadeDeAtendimento);die;
        
        App::uses('CakeEmail', 'Network/Email');
        $email = new CakeEmail('default');
        $email->from(array(
            'olga.marinho@sad.pe.gov.br' => 'SPM'
        ));
        $email->to($destinatario);
        $email->subject('Solicitação de Perícia .:SPM:.');
        $email->emailFormat('html');

        $htmlMsg = '<html>
                        <head></head>
                        <body>
                            <h1>Informativo de solicitação de Perícia Médica,</h1>
                            <br/>
                            Olá,
                            <br/>
                            O Servidor ' . $nomeServidor . ', CPF: ' . $cpfServidor . ', acaba de realizar um agendamento '. $tipologia . ' solicitando uma perícia médica';

        if(!empty($agendamento['data_hora']) && !empty($agendamento['dia_semana'])){
            $htmlMsg .= ' para o dia '.$agendamento['data_hora'].' ('.$agendamento['dia_semana'].')';
        }
        if($unidadeDeAtendimento != false){
            $htmlMsg.= ', na ' . $unidadeDeAtendimento['UnidadeAtendimento']['nome'];
        }
        if($cod_tipologia == TIPOLOGIA_RISCO_VIDA_INSALUBRIDADE || $cod_tipologia == TIPOLOGIA_COMUNICACAO_DE_ACIDENTE_DE_TRABALHO){
            $htmlMsg .= "<br/>Chefia imediata deve acessar o Sistema SPM e homologar devidamente o agendamento para o processo ser analisado e concluído por esta Perícia.";
        }
        $htmlMsg .='</body>
        </html>';
        
        return ($email->send($htmlMsg)) ? true : false;
        // return true;
    }


    public function testeEmail(){
        
        App::uses('CakeEmail', 'Network/Email');
        $email = new CakeEmail('default');
        $email->from(array(
            'olga.marinho@sad.pe.gov.br' => 'SPM'
        ));
       
        $email->subject('Solicitação de Perícia .:SPM:.'); 
        $email->to('phmsanttos@gmail.com');
        $email->emailFormat('html');

        $htmlMsg = '<html>
                        <head></head>
                        <body>
                            <h1>Informativo de solicitação de Perícia Médica,</h1>
                            <br/>
                            Olá,
                            <br/>
                            O Servidor PEDRO ALVES DA CRUZ GOUVEIA, CPF: 009.978.404-12, acaba de realizar um agendamento Licença Médica para Tratamento de Saúde solicitando uma perícia médica para o dia 30/12/2016 16:50 (Sexta-feira), na Unidade de Atendimento 01</body>
        </html>';




        // if(!empty($agendamento['data_hora']) && !empty($agendamento['dia_semana'])){
        //     $htmlMsg .= ' para o dia '.$agendamento['data_hora'].' ('.$agendamento['dia_semana'].')';
        // }
        // if($unidadeDeAtendimento != false){
        //     $htmlMsg.= ', na ' . $unidadeDeAtendimento['UnidadeAtendimento']['nome'];
        // }
        // $htmlMsg .='</body>
        // </html>';
        if($email->send($htmlMsg)){
            // pr($email);
            die('enviou email');
        }else{
           pr($this->Email->smtpError);
            die('erro ao enviar');
        }
        // return ($email->send($htmlMsg)) ? true : false;
    }

    /**
     * Método para excluir um Cargo do sistema
     */
    public function deletar($id, $retorno = false) {
        $this->set('retorno', $retorno);
        if ($this->request->is('get')) {
            if (!$id) {
                throw new NotFoundException(__('objeto_invalido', __('Agendamento')));
            }

            $agendamento = $this->Agendamento->findById($id);

            $tipoUsuario = CakeSession::read('Auth.User.tipo_usuario_id');

            if (!$agendamento || ($tipoUsuario != USUARIO_INTERNO && CakeSession::read('Auth.User.id') != $agendamento['Agendamento']['usuario_servidor_id'])) {
                throw new NotFoundException(__('objeto_invalido', __('Agendamento')));
            }

            $this->set('lotacoesUm', []);
            $this->set('lotacoesDois', []);
            $this->set('lotacoesTres', []);

            if (!$this->request->data) {
                $agendamento['Agendamento']['searchAcompnhadoCid'] = $agendamento['CidAcompanhante']['nome'];
                $agendamento['Agendamento']['searchAcompnhadoDoenca'] = $agendamento['CidAcompanhante']['nome_doenca'];

                $this->request->data = $agendamento;
                $this->request->data['Agendamento']['nome'] = $agendamento['UsuarioServidor']['nome'];
                $this->request->data['Agendamento']['cpf'] = $agendamento['UsuarioServidor']['cpf'];
                if ($agendamento['Agendamento']['chefe_imediato_um_orgao_origem_id']) {
                    $orgaoOrigemUm = $agendamento['Agendamento']['chefe_imediato_um_orgao_origem_id'];
                    $lotacaoUm = $agendamento['Agendamento']['chefe_imediato_um_lotacao_id'];
                    $idUsuarioUm = $agendamento['Agendamento']['chefe_imediato_um_id'];

                    $this->request->data['Agendamento']['matriculaChefiaUm'] = $this->getMatriculaOrgaoLotacaoId($orgaoOrigemUm, $lotacaoUm, $idUsuarioUm);
                    $this->request->data['Agendamento']['nomeChefiaUm'] = $this->request->data['ChefiaImediataUm']['nome'];

                    $this->set('lotacoesUm', $this->carregarLotacoesOrgao($agendamento['Agendamento']['chefe_imediato_um_orgao_origem_id']));
                }
                if ($agendamento['Agendamento']['chefe_imediato_dois_orgao_origem_id']) {
                    $orgaoOrigemDois = $agendamento['Agendamento']['chefe_imediato_dois_orgao_origem_id'];
                    $lotacaoDois = $agendamento['Agendamento']['chefe_imediato_dois_lotacao_id'];
                    $idUsuarioDois = $agendamento['Agendamento']['chefe_imediato_dois_id'];

                    $this->request->data['Agendamento']['matriculaChefiaDois'] = $this->getMatriculaOrgaoLotacaoId($orgaoOrigemDois, $lotacaoDois, $idUsuarioDois);
                    $this->request->data['Agendamento']['nomeChefiaDois'] = $this->request->data['ChefiaImediataDois']['nome'];

                    $this->set('lotacoesDois', $this->carregarLotacoesOrgao($agendamento['Agendamento']['chefe_imediato_dois_orgao_origem_id']));
                }
                if ($agendamento['Agendamento']['chefe_imediato_tres_orgao_origem_id']) {
                    $orgaoOrigemTres = $agendamento['Agendamento']['chefe_imediato_tres_orgao_origem_id'];
                    $lotacaoTres = $agendamento['Agendamento']['chefe_imediato_tres_lotacao_id'];
                    $idUsuarioTres = $agendamento['Agendamento']['chefe_imediato_tres_id'];

                    $this->request->data['Agendamento']['matriculaChefiaTres'] = $this->getMatriculaOrgaoLotacaoId($orgaoOrigemTres, $lotacaoTres, $idUsuarioTres);
                    $this->request->data['Agendamento']['nomeChefiaTres'] = $this->request->data['ChefiaImediataTres']['nome'];

                    $this->set('lotacoesTres', $this->carregarLotacoesOrgao($this->request->data['Agendamento']['chefe_imediato_tres_orgao_origem_id']));
                }
            } else {
                if ($this->request->data['Agendamento']['chefe_imediato_um_orgao_origem_id']) {
                    $orgaoOrigemUm = $this->request->data['Agendamento']['chefe_imediato_um_orgao_origem_id'];
                    $lotacaoUm = $this->request->data['Agendamento']['chefe_imediato_um_lotacao_id'];
                    $idUsuarioUm = $this->request->data['Agendamento']['chefe_imediato_um_id'];

                    $this->request->data['Agendamento']['matriculaChefiaUm'] = $this->getMatriculaOrgaoLotacaoId($orgaoOrigemUm, $lotacaoUm, $idUsuarioUm);

                    $this->set('lotacoesUm', $this->carregarLotacoesOrgao($this->request->data['Agendamento']['chefe_imediato_um_orgao_origem_id']));
                }
                if ($this->request->data['Agendamento']['chefe_imediato_dois_orgao_origem_id']) {
                    $orgaoOrigemDois = $this->request->data['Agendamento']['chefe_imediato_dois_orgao_origem_id'];
                    $lotacaoDois = $this->request->data['Agendamento']['chefe_imediato_dois_lotacao_id'];
                    $idUsuarioDois = $this->request->data['Agendamento']['chefe_imediato_dois_id'];

                    $this->request->data['Agendamento']['matriculaChefiaDois'] = $this->getMatriculaOrgaoLotacaoId($orgaoOrigemDois, $lotacaoDois, $idUsuarioDois);

                    $this->set('lotacoesDois', $this->carregarLotacoesOrgao($this->request->data['Agendamento']['chefe_imediato_dois_orgao_origem_id']));
                }
                if ($this->request->data['Agendamento']['chefe_imediato_tres_orgao_origem_id']) {
                    $orgaoOrigemTres = $this->request->data['Agendamento']['chefe_imediato_tres_orgao_origem_id'];
                    $lotacaoTres = $this->request->data['Agendamento']['chefe_imediato_tres_lotacao_id'];
                    $idUsuarioTres = $this->request->data['Agendamento']['chefe_imediato_tres_id'];

                    $this->request->data['Agendamento']['matriculaChefiaTres'] = $this->getMatriculaOrgaoLotacaoId($orgaoOrigemTres, $lotacaoTres, $idUsuarioTres);

                    $this->set('lotacoesTres', $this->carregarLotacoesOrgao($this->request->data['Agendamento']['chefe_imediato_tres_orgao_origem_id']));
                }
            }



            //Chamando os dados que são necessarios serem carregados.
            $this->carregarData();

            //render view edit
            $this->render('edit');
        } else {
            if ($this->Agendamento->delete($id)){
                $currentFunction = $this->request->params['action']; //function corrente
                $currentController = $this->name; //Controller corrente
                $this->saveAuditLog($id,$currentController,'E',$currentFunction);

                $this->Session->setFlash(
                        __('objeto_excluir_sucesso', __('Agendamento')), 'flash_success'
                );
                if ($retorno === false) {
                    return $this->redirect(array('action' => 'index'));
                } else {
                    return $this->redirect(array('controller' => $retorno, 'action' => 'index'));
                }
            }
        }
    }

    public function getServidorByNome() {
        $this->layout = 'ajax';
        if ($this->request->is(['get', 'post'])) {
            $orgao_id = (int) $this->request->query['orgao_origem_id'];
            $lotacao_id = (int) $this->request->query['lotacao_id'];
            $nome = $this->request->query['nome'];

            $this->loadModel('Usuario');
            $filtro = new BSFilter();
            $filtro->setTipo('all');
            $filtro->setCamposRetornados(['Usuario.id', 'Usuario.nome', 'Usuario.email', 'Usuario.cpf']);
            $this->Usuario->Behaviors->load('Containable');
            $filtro->setContain(array('TipoUsuario', 'Vinculo'));
            $filtro->setCamposOrdenadosString('Usuario.nome');

            $joins[] = array(
                'table' => 'vinculo',
                'alias' => 'Vinculo',
                'type' => 'left',
                'conditions' => array('Vinculo.usuario_id = Usuario.id', 'Vinculo.ativo = true')
            );
            $joins[] = array(
                'table' => 'vinculo_lotacao',
                'alias' => 'VinculoLotacao',
                'type' => 'left',
                'conditions' => array('VinculoLotacao.vinculo_id = Vinculo.id')
            );

            $filtro->setJoins($joins);

            $condicoes['Usuario.nome ILIKE '] = '%' . $nome . '%';
            $condicoes['Usuario.tipo_usuario_id !='] = USUARIO_INTERNO;
            $condicoes['Vinculo.orgao_origem_id'] = $orgao_id;
            $condicoes['VinculoLotacao.lotacao_id'] = $lotacao_id;
            $filtro->setCondicoes($condicoes);
            $filtro->setLimiteListagem(40);

            $arrUsuario = $this->Usuario->listar($filtro);
            $arrayRetorno = array();
            foreach ($arrUsuario as $key => $line) {

                $objTmp = new stdClass();
                $objTmp->id = $line['Usuario']['id'];
                foreach ($line['Vinculo'] as $vinculo) {
                    if ($vinculo['orgao_origem_id'] == $orgao_id) {
                        $objTmp->matricula = $vinculo['matricula'];
                        break;
                    }
                }

                $objTmp->label = $line['Usuario']['nome'];
                $objTmp->value = $line['Usuario']['nome'];
                $arrayRetorno[] = $objTmp;
            }
            echo json_encode($arrayRetorno);
            die;
        }
    }

    private function getMatriculaOrgaoLotacaoId($orgaoId, $lotacaoId, $usuarioId) {
        $this->loadModel('Vinculo');
        $filtro = new BSFilter();
        $filtro->setTipo('all');
        $filtro->setCamposOrdenadosString('Vinculo.matricula');

        $joins[] = array(
            'table' => 'vinculo_lotacao',
            'alias' => 'VinculoLotacao',
            'type' => 'left',
            'conditions' => array('VinculoLotacao.vinculo_id = Vinculo.id')
        );

        $filtro->setJoins($joins);

        $condicoes['Vinculo.usuario_id'] = $usuarioId;
        $condicoes['Usuario.tipo_usuario_id !='] = USUARIO_INTERNO;
        $condicoes['Vinculo.orgao_origem_id'] = $orgaoId;
        $condicoes['VinculoLotacao.lotacao_id'] = $lotacaoId;
        $filtro->setCondicoes($condicoes);
        $vinculo = $this->Vinculo->listar($filtro);
        $matricula = "";

        if (isset($vinculo[0])) {
            $vinculoMatricula = $vinculo[0];
            if (isset($vinculoMatricula['Vinculo']['matricula']))
                $matricula = $vinculoMatricula['Vinculo']['matricula'];
        }
        return $matricula;
    }

    public function getServidorByMatricula() {
        $this->layout = 'ajax';
        if ($this->request->is(['get', 'post'])) {
            $orgao_id = $this->request->query['orgao_origem_id'];
            $lotacao_id = $this->request->query['lotacao_id'];
            $matricula = $this->request->query['nome'];

            $this->loadModel('Vinculo');
            $filtro = new BSFilter();
            $filtro->setTipo('all');
            $filtro->setCamposOrdenadosString('Vinculo.matricula');
            $filtro->setLimiteListagem(40);

            $joins[] = array(
                'table' => 'vinculo_lotacao',
                'alias' => 'VinculoLotacao',
                'type' => 'left',
                'conditions' => array('VinculoLotacao.vinculo_id = Vinculo.id')
            );

            $filtro->setJoins($joins);

            $condicoes['Vinculo.matricula ILIKE '] = '%' . $matricula . '%';
            $condicoes['Usuario.tipo_usuario_id !='] = USUARIO_INTERNO;
            $condicoes['Vinculo.orgao_origem_id'] = $orgao_id;
            $condicoes['VinculoLotacao.lotacao_id'] = $lotacao_id;
            $condicoes['Vinculo.ativo'] = true;

            $filtro->setCondicoes($condicoes);

            $arrVinculo = $this->Vinculo->listar($filtro);

            $arrayRetorno = array();
            foreach ($arrVinculo as $key => $line) {
                $objTmp = new stdClass();
                $objTmp->id = $line['Usuario']['id'];
                $objTmp->nome = $line['Usuario']['nome'];
                $objTmp->label = $line['Vinculo']['matricula'];
                $objTmp->value = $line['Vinculo']['matricula'];
                $arrayRetorno[] = $objTmp;
            }
            echo json_encode($arrayRetorno);
            die;
        }
    }

    public function validarAcompanhado() {
        $this->layout = 'ajax';
        if ($this->request->is('get')) {
            $nome = Util::trataString($this->request->query['nome']);
            $dataNascimento = Util::toDBData($this->request->query['dataNascimento']);
            $filtro = new BSFilter();
            $condicoes = array();
            $condicoes['LOWER(Agendamento.nome_acompanhado_sem_abreviacao)'] = $nome;
            $condicoes['Agendamento.data_nascimento_acompanhado'] = $dataNascimento;

            $filtro->setTipo('list');
            $filtro->setCondicoes($condicoes);
            $listIds = $this->Agendamento->listar($filtro);
            //Verifica se existe agendamentos
            if (!empty($listIds)) {
                $this->loadModel('Atendimento');
                //varre todos agendamentos
                foreach ($listIds as $key => $idAgendamento) {
                    $filtroTmp = new BSFilter();
                    $condicoesTmp = array();
                    $condicoesTmp['Atendimento.agendamento_id'] = $idAgendamento;
                    $filtroTmp->setTipo('all');
                    $filtroTmp->setCamposRetornados();
                    $filtroTmp->setCondicoes($condicoesTmp);
                    $atendimentoTmp = $this->Atendimento->listar($filtroTmp);

                    //Verifica se o atendimento TMP está vazio
                    if (!empty($atendimentoTmp)) {
                        //Verifica se o status está finalizado
                        $status = $atendimentoTmp[0]['Atendimento']['status_atendimento'];
                        if ($status === "Finalizado") {
                            //Verifica se a situação do atendimento está deferido
                            if ($atendimentoTmp[0]['TipoSituacaoParecerTecnico']['id'] == TipoSituacaoParecerTecnico::DEFERIDO) {
                                $dataParecer = $atendimentoTmp[0]['Atendimento']['data_parecer'];
                                $duracao = $atendimentoTmp[0]['Atendimento']['duracao'];
                                //Verifica se existe data de Parecer e Duração
                                if ($dataParecer && $duracao) {
                                    $dataAtual = date('Y-m-d');
                                    $dataAgendamento = date('Y-m-d', strtotime($dataParecer . ' + ' . $duracao . ' day'));
                                    //Verifica se está dentro da data
                                    if (($dataAtual < $dataAgendamento) && ($dataAtual >= $dataParecer)) {
                                        echo json_encode(true);
                                        exit();
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        echo json_encode(false);
        exit();
    }

    public function getProcesso($idUsuario, $numProcesso){
        $this->layout = 'ajax';
        if ($this->request->is([ 'get', 'post'])) {

            $this->loadModel('Atendimento');
            $this->loadModel('Tipologia');
            $filtro = new BSFilter();
            $filtro->setTipo('all');
            $joins = array();
            $joins [] = array(
                'table' => 'tipologia',
                'alias' => 'Tipologia',
                'type' => 'inner',
                'conditions' => array('Agendamento.tipologia_id = Tipologia.id')
            );
            $filtro->setJoins($joins);

            $filtro->setCamposRetornados([
                'Atendimento.id',
                'Agendamento.cid_id',
                'Agendamento.numero_processo',
                'Tipologia.id',
                'Tipologia.nome']);

            $condicoes['Atendimento.usuario_id'] = $idUsuario;
            $condicoes['(cast ("Atendimento".id as VARCHAR)) ILIKE'] ='%'.$numProcesso. '%';
            $condicoes['Atendimento.status_atendimento'] = 'Finalizado';

            $filtro->setCondicoes($condicoes);

            $arrAtendimento = $this->Atendimento->listar($filtro);

            $arrayRetorno = array();
            foreach ($arrAtendimento as $key => $line) {
                $objTmp = new stdClass();
                $numero = $line['Atendimento']['id'];
                $tipologia = $line['Tipologia']['nome'];
                $tipologia_id = $line['Tipologia']['id'];
                $isRA = false;

                if($tipologia_id == TIPOLOGIA_RECURSO_ADMINISTRATIVO){
                    $isRA = true;
                    $tipologia_id_ra = $this->Atendimento->getTipologiaIdAtendimento($line['Agendamento']['numero_processo']);
                    $tipologia_ra = $this->Tipologia->getNomeById($tipologia_id_ra);
                }
                $objTmp->tipologia = $tipologia . ($isRA?" ($tipologia_ra)":"");
                $objTmp->cid_id = $line['Agendamento']['cid_id'];
                $objTmp->numero = $numero;
                $objTmp->label = "$numero - $tipologia" . ($isRA?" ($tipologia_ra)":"");
                $objTmp->value = $numero;
                $arrayRetorno[] = $objTmp;
            }

            echo json_encode($arrayRetorno);
            die;
        }
    }

    public function getPAD($idUsuario, $numPAD){
        $this->layout = 'ajax';
        if ($this->request->is([ 'get', 'post'])) {

            $this->loadModel('Atendimento');
            $this->loadModel('Tipologia');
            $filtro = new BSFilter();
            $filtro->setTipo('all');

            $filtro->setCamposRetornados(['Atendimento.id']);

            $condicoes['Agendamento.tipologia_id'] = TIPOLOGIA_SINDICANCIA_INQUERITO_PAD;
            $condicoes['Agendamento.tipo'] = TIPO_PROCESSO_ADMINISTRATIVO;
            $condicoes['Agendamento.usuario_servidor_id'] = $idUsuario;
            $condicoes['(cast ("Atendimento".id as VARCHAR)) ILIKE'] ='%'.$numPAD. '%';
            $condicoes['Atendimento.status_atendimento'] = 'Finalizado';

            $filtro->setCondicoes($condicoes);

            $arrAtendimento = $this->Atendimento->listar($filtro);

            $arrayRetorno = array();
            foreach ($arrAtendimento as $key => $line) {
                $objTmp = new stdClass();
                $numero = $line['Atendimento']['id'];

                $objTmp->numero = $numero;
                $objTmp->label = $numero;
                $objTmp->value = $numero;
                $arrayRetorno[] = $objTmp;
            }

            echo json_encode($arrayRetorno);
            die;
        }
    }



    public function jslistProcessosCAT($usuario_id){
        $this->layout = 'ajax';
        if ($this->request->is([ 'get', 'post'])) {
            $this->loadModel('Atendimento');
            $filtro = new BSFilter();
            $filtro->setTipo('all');
            $joins = array();
            $joins [] = array(
                'table' => 'agendamento',
                'alias' => 'AgendamentoTA',
                'type' => 'left',
                'conditions' => array('AgendamentoTA.tratamento_acidente_processo = Atendimento.id')
            );

            $filtro->setJoins($joins);

            $filtro->setCamposRetornados(array('Atendimento.id'));

            $condicoes['Atendimento.usuario_id'] = $usuario_id;
            $condicoes['Atendimento.status_atendimento'] = 'Finalizado';
            $condicoes['Agendamento.tipologia_id'] = TIPOLOGIA_COMUNICACAO_DE_ACIDENTE_DE_TRABALHO;
            $condicoes['AgendamentoTA.id'] = null;

            $filtro->setCondicoes($condicoes);
            $list = $this->Atendimento->listar($filtro);

            $arrItens = array();
            foreach ($list as $item){
                $arrItens[] = intval($item['Atendimento']['id']);
            }
            echo json_encode($arrItens); die;
        }
    }

    public function impressao($id){
        $this->layout = 'pdf';
        $agendamento = $this->Agendamento->findById($id);
        $this->set('agendamento', $agendamento);
    }


    public function getCid() {
        $this->layout = 'ajax';

        if ($this->request->is(['get', 'post'])) {
            $this->loadModel('Cid');
           
            // $filtro = new BSFilter();
            // $filtro->setTipo('all');
            // $filtro->setCamposOrdenadosString('Cid.nome');

            // $arrCid = $this->Cid->listar($filtro);
            $arrCid = $this->Cid->listarCids();

            $arrayRetorno = array();
            $search = preg_quote(isset( $this->request->query['search']) ?  $this->request->query['search'] : '');
            $start = (isset($this->request->query['start']) ?$this->request->query['start'] : 1);

            foreach ($arrCid as $key => $line) {
                //  echo "cid::".$line['Cid']['nome']."<br>\n";
                $valueLine  = isset($line['Cid']['nome']) ? $line['Cid']['nome'] : $line;
                $valueId    = isset($line['Cid']['id']) ? $line['Cid']['id'] : $key;

                // if(preg_match('/' . ($start ? '^' : '') . $search . '/i', $line['Cid']['nome'])){
                //     $arrayRetorno[] = array('value' => $line['Cid']['id'], 'text' => $line['Cid']['nome']);
                // }

                if(preg_match('/' . ($start ? '^' : '') . $search . '/i', $valueLine)){
                    $arrayRetorno[] = array('value' => $valueId, 'text' => $valueLine);
                }
            }
            echo json_encode($arrayRetorno);
            die;
        }
    }

    private function addTimeSession($line){
        $times = $this->Session->read('times');
        if(!is_array($times) || empty($times))$times = [];
        $times[] = ['line'=> $line, 'date'=>date('Y-m-d H:i:s')];
        $this->Session->write('times', $times);
    }

    public function agendaSistema(){
        pr($this->carregarListaTipologia(USUARIO_SERVIDOR)); die;
    }

    public function testAgendaLoad($id = 1){
        $this->loadModel('Agendamento');
        $filtro = new BSFilter();
        $condicoes = array('Agendamento.id' => $id);
        $filtro->setCondicoes($condicoes);
        $filtro->setTipo("first");
        $arr = $this->Agendamento->listar($filtro);

        pr($arr);
        die;
    }

    public function testCid(){
        $this->loadModel("Cid");
        $arr = ($this->Cid->listarCidsIds([7,400]));
    }
}

