<?php

App::import("Plugin/Web/Controller", "BSController");

class TempoConsultaAtendimentoController extends BSController {

    public function index() {
        $tipologias = $this->carregarTipologias();
        $this->set('tipologias', $tipologias);
    }


    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('testeTempoConsulta');
    }

    private function carregarTipologias() {
        $this->loadModel('Tipologia');
        return $this->Tipologia->listar();
    }    

    /**
     * Método utilizado para visualizar um Cid
     * @param string $id identificador do Cid
     */
    public function visualizar($id = null) {
        $tipologias = $this->carregarTipologias();
        $this->set('tipologias', $tipologias);

        $this->set('formDisabled', true);

        $tempoConsulta = $this->_verificaId($id);
        $this->request->data = $tempoConsulta;
		 
        $currentFunction = $this->request->params['action']; //function corrente
        $currentController = $this->name; //Controller corrente
        $this->saveAuditLog($id,$currentController,'V',$currentFunction);
		
        $this->set('selected', Util::criarListaIds($this->request->data['Tipologia']));
        //render view edit
        $this->render('edit');
    }

    public function testeTempoConsulta($idTipologia){
        $this->loadModel("TempoConsultaAtendimento");
        $tempoConsulta = $this->TempoConsultaAtendimento->buscarTempoConsultaAtendimento($idTipologia);
        
        if(is_null($tempoConsulta)){
            echo "NULO";
        }else{
            echo $tempoConsulta;
        }


        die;
    }

    /**
     * Método utilizado para cadastrar um novo Tempo de Consulta no sistema
     */
    public function adicionar() {
        
        $this->loadModel('TempoConsultaAtendimento');

        $tipologias = $this->carregarTipologias();
        $this->set('tipologias', $tipologias);

        $this->set('formDisabled', true);

        if ($this->request->is('post')) {
           
            $this->TempoConsultaAtendimento->create();

            // pr($this->request->data);die;

            if(empty($this->request->data['Tipologia']['Tipologia'])){
                $this->Session->setFlash("Você tem que selecionar pelo menos uma tipologia!", 'flash_alert'); 
                return $this->redirect(array('action' => 'adicionar'));
            }
            if ($this->TempoConsultaAtendimento->saveAll($this->request->data)) {
                
                $id = $this->TempoConsultaAtendimento->id;
                $currentFunction = $this->request->params['action']; //function corrente
                $currentController = $this->name; //Controller corrente
                $this->saveAuditLog($id,$currentController,'I',$currentFunction);


                $this->Session->setFlash(__('objeto_salvo_sucesso', __('Tempo de Consulta do Atendimento')), 'flash_success');
                return $this->redirect ( array ('controller' => 'ParametroGeral', 'action' => 'editar', 1));
            }

            
            if (!empty($this->request->data['Tipologia']['Tipologia'])) {
                $this->set('selected', $this->request->data['Tipologia']['Tipologia']);
            }
        }
        //render view edit
        $this->render('edit');
    }


    /**
     * @param string $id identificador do Tempo de Consulta que vai ser editado
     * @throws NotFoundException
     */
    public function editar($id = null) {
         
        $this->loadModel('TempoConsultaAtendimento');

        $tipologias = $this->carregarTipologias();
        $this->set('tipologias', $tipologias);

        $this->set('formDisabled', true);   

        $tempoConsulta = $this->_verificaId($id);
        if ($this->request->is(array('post', 'put'))) {
            $this->TempoConsultaAtendimento->id = $id;
            if ($this->TempoConsultaAtendimento->saveAll($this->request->data)) {


                $currentFunction = $this->request->params['action']; //function corrente
                $currentController = $this->name; //Controller corrente
                $this->saveAuditLog($id,$currentController,'A',$currentFunction);


                $this->Session->setFlash(__('objeto_salvo_sucesso', __('Tempo de Consulta do Atendimento')), 'flash_success');
                return $this->redirect ( array ('controller' => 'ParametroGeral', 'action' => 'editar', 1));

            }
        }

        if (!$this->request->data) {
            $this->request->data = $tempoConsulta;
            $this->set('selected', Util::criarListaIds($this->request->data['Tipologia']));
        } else {
            if (!empty($this->request->data['Tipologia']['Tipologia'])) {
               $this->set('selected', $this->request->data['Tipologia']['Tipologia']);
            } 

            else {
                $this->set('selected', []);
                $this->set('selectedUnidades', []);
            }
        }
        //render view edit
        $this->render('edit');
    }

    /**
     * Método para excluir um Tempo de Consulta do sistema
     */
    public function deletar($id = null) {
        if ($this->request->is('get')) {
            $tempoConsulta = $this->_verificaId($id);
            $this->request->data = $tempoConsulta;

            $tipologias = $this->carregarTipologias();
            $this->set('tipologias', $tipologias);

            $this->set('formDisabled', true);

            $this->set('selected', Util::criarListaIds($this->request->data['Tipologia']));
            //render view edit
            $this->render('edit');
        } else {
            if ($this->TempoConsultaAtendimento->delete($id)) {


                $currentFunction = $this->request->params['action']; //function corrente
                $currentController = $this->name; //Controller corrente
                $this->saveAuditLog($id,$currentController,'E',$currentFunction);


                $this->Session->setFlash(__('objeto_excluir_sucesso', __('Tempo de Consulta do Atendimento')), 'flash_success');
               return $this->redirect ( array ('controller' => 'ParametroGeral', 'action' => 'editar', 1));
            }
        }
    }

    private function _verificaId($id = null) {
        if (!$id) {
            throw new NotFoundException(__('objeto_invalido', __('Tempo de Consulta do Atendimento')));
        }
        $tempoConsulta = $this->TempoConsultaAtendimento->findById($id);
        if (!$tempoConsulta) {
            throw new NotFoundException(__('objeto_invalido', __('Tempo de Consulta do Atendimento')));
        }
        return $tempoConsulta;
    }

}
