<?php

App::import("Plugin/Web/Controller", "BSController");

class AuditoriaController extends BSController {


    public function index() {        
        $this->carregarOperacao();
        $this->carregarAreaSistema();
    }


    public function consultar() {
        $this->layout = 'ajax';

        if ($this->request->is('GET')) {
          //  pr($this->request->query['data']);

            $nome = $this->request->query['data']['Auditoria']['nome'];
            $tipo_operacao= $this->request->query['data']['Auditoria']['tipo_operacao'];
            $area_sistema = $this->request->query['data']['Auditoria']['area_sistema'];
            $data_inicio = $this->request->query['data']['Auditoria']['data_inicial'];
            $data_fim = $this->request->query['data']['Auditoria']['data_final'];
            $pk_log = $this->request->query['data']['Auditoria']['pk_log'];
            $limitConsulta = $this->request->query['data']['Auditoria']['limitConsulta'];

            $condicoes = null;
            if (!empty($nome)) {
                $condicoes['UsuarioAlteracao.nome ILIKE'] = "%$nome%";
            }
            if (!empty($pk_log)) {
                $condicoes['Auditoria.pk_log'] = "$pk_log";
            }
            if (!empty($tipo_operacao)) {
                $condicoes['Auditoria.operacao'] = "$tipo_operacao";
            }
            if (!empty($area_sistema)) {
                $condicoes['Auditoria.area_sistema'] = "$area_sistema";
            }
            if (!empty($data_inicio)) {
                $condicoes['Auditoria.data_inclusao >= '] = Util::inverteData($data_inicio).' 00:00:00';
            }
            if (!empty($data_fim)) {
                $condicoes['Auditoria.data_inclusao <='] = Util::inverteData($data_fim).' 23:59:59';
            }
            
            $labelAtendimento = "";
            if( $area_sistema == "ATENDIMENTO" || empty( $area_sistema ) ){
                $labelAtendimento = "atendimento";
            }

            $labelAgendamento = "";
            if( $area_sistema == "AGENDAMENTO" || empty( $area_sistema ) ){
                $labelAgendamento = "agendamento";
            }

            // pr($labelAgendamento);
            // pr($labelAtendimento);

            $this->set('labelAtendimento', $labelAtendimento);
            $this->set('labelAgendamento', $labelAgendamento);
            $this->set('tipo_operacao', $tipo_operacao);
            
            $joins[] = array(
                'table' => 'atendimento',
                'alias' => 'Atendimento',
                'type' => 'LEFT',
                'conditions' => array("Atendimento.id = Auditoria.pk_log AND Auditoria.area_sistema = 'ATENDIMENTO' ")
            );

            $joins[] = array(
                'table' => 'usuario',
                'alias' => 'UsuarioExclusao',
                'type' => 'LEFT',
                'conditions' => array("UsuarioExclusao.id = Atendimento.solicitante_exclusao AND Auditoria.area_sistema = 'ATENDIMENTO' ")
            );


            $filtro = new BSFilter();

            $filtro->setCondicoes($condicoes);
            $filtro->setLimitarItensAtivos(false);
            $filtro->setLimiteConsulta($limitConsulta);
            $filtro->setJoins($joins);
            $filtro->setCamposOrdenados(['Auditoria.data_cadastro' => 'desc']);

            $filtro->setCamposRetornados([
                'Auditoria.id',
                'Auditoria.usuario_versao_id',
                'Auditoria.area_sistema',
                'Auditoria.operacao',
                'Auditoria.ip',
                'Auditoria.data_inclusao',
                'Auditoria.nome_funcao',
                'Auditoria.pk_log',
                'UsuarioAlteracao.nome',
                'Atendimento.motivo_exclusao',
                'UsuarioExclusao.nome'
            ]);


            $this->set('auditoria', $this->paginar($filtro));
            $this->set('limiteConsultaSelecionado', $limitConsulta);
        }
    }

    /**
     * Método utilizado para visualizar um Perfil
     * @param string $id identificador do Perfil
     */
    public function visualizar($id = null) {
        if (!$id) {
            throw new NotFoundException(__('objeto_invalido', __('Perfil')));
        }

        $perfil = $this->Perfil->findById($id);

        if (!$perfil) {
            throw new NotFoundException(__('objeto_invalido', __('Perfil')));
        }

        $this->request->data = $perfil;
        $this->carregarListaFuncionalidades();
        //render view edit
        $this->render('edit');
    }

    private function carregarOperacao() {
        $tipo_operacao = array(
            'A' => 'Alteração',
            'I' => 'Inserção',
            'E'=> 'Exclusão',
            'C' =>'Consulta',
			'V' => 'Visualização'
        );
        $this->set('tipo_operacao', $tipo_operacao);
    }

     private function carregarAreaSistema() {


        $db = $this->Auditoria->getDataSource();
        $sql = "select distinct Auditoria.area_sistema from desen.auditoria group by Auditoria.area_sistema";
        $arr = $db->fetchAll($sql);


        $retorno = array();
        foreach ($arr as $array) {
           foreach ($array as $area) {
            $retorno[$area['area_sistema']] = $area['area_sistema'];
           }
        }
         $this->set('area_sistema',$retorno);
        
        // $filtro = new BSFilter();
        // $filtro->setTipo('list');


        // $filtro->setCamposRetornados(['Auditoria.area_sistema']);
        // $filtro->setLimitarItensAtivos(false);
        // $return = $this->Auditoria->listar($filtro);
        // pr($return);die;
        // $return = array_unique($return);

        // foreach($return as $k => $v){
        //     $area_sistema[$v] =$v;
        // }


        // $this->set('area_sistema',$area_sistema);
    }




}
