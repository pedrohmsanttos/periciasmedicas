<?php

// App::uses('BSController', 'Admin.Controller');
App::uses('BSController', 'Web.Controller');

class OrgaoOrigemController extends BSController {

    public $helpers = array('Html', 'Form');

    public function index() {
        
    }

    /**
     * Método utilizado para exibir a listagem inicial de orgãos de origem cadastradas
     */
    public function consultar() {
        $this->layout = 'ajax';

        if ($this->request->is('GET')) {
            $limitConsulta = $this->request->query['data']['OrgaoOrigem']['limitConsulta'];
            $orgao_origem = $this->request->query['data']['OrgaoOrigem']['orgao_origem'];
            $sigla = $this->request->query['data']['OrgaoOrigem']['sigla'];
            $cnpj = $this->request->query['data']['OrgaoOrigem']['cnpj'];
            $condicoes = null;
            if (!empty($orgao_origem)) {
                $condicoes['OrgaoOrigem.orgao_origem ILIKE '] = "%$orgao_origem%";
            }
            if (!empty($sigla)) {
                $condicoes['OrgaoOrigem.sigla ILIKE '] = "%$sigla%";
            }
            if (!empty($cnpj)) {
                $condicoes['OrgaoOrigem.cnpj'] = Util::limpaDocumentos($cnpj);
            }
            $filtro = new BSFilter();
            $filtro->setCondicoes($condicoes);
            $filtro->setLimiteConsulta($limitConsulta);
            $filtro->setCamposRetornadosString("id", "orgao_origem", "sigla", "cnpj");
            $filtro->setCamposOrdenados(['OrgaoOrigem.orgao_origem' => 'asc']);

			
			 
                $currentFunction = $this->request->params['action']; //function corrente
                $currentController = $this->name; //Controller corrente
                $this->saveAuditLog(null,$currentController,'C',$currentFunction);
			
			
            $this->set('orgaosOrigem', $this->paginar($filtro));
            $this->set('limiteConsultaSelecionado', $limitConsulta);
			
        }
    }

    /**
     * Método utilizado para visualizar um orgão de origem
     * @param string $id
     */
    public function visualizar($id = null) {
        if (!$id) {
            throw new NotFoundException(__('objeto_invalido', __('OrgaoOrigem')));
        }
		
		 
                $currentFunction = $this->request->params['action']; //function corrente
                $currentController = $this->name; //Controller corrente
                $this->saveAuditLog($id,$currentController,'V',$currentFunction);
		
        $orgaoOrigem = $this->OrgaoOrigem->findById($id);

        if (!$orgaoOrigem) {
            throw new NotFoundException(__('objeto_invalido', __('OrgaoOrigem')));
        }

        $this->request->data = $orgaoOrigem;

        //render view edit
        $this->render('edit');
    }

    /**
     * Método utilizado para cadastrar um novo orgão de origem
     */
    public function adicionar() {
        if ($this->request->is('post')) {
            $this->OrgaoOrigem->create();
            if ($this->OrgaoOrigem->saveAll($this->request->data)) {

                $id = $this->OrgaoOrigem->id;
                $currentFunction = $this->request->params['action']; //function corrente
                $currentController = $this->name; //Controller corrente
                $this->saveAuditLog($id,$currentController,'I',$currentFunction);


                $this->Session->setFlash(__('objeto_salvo_sucesso', __('OrgaoOrigem')), 'flash_success');
                return $this->tratarAcaoSalvar();
            }
        }
        //render view edit
        $this->render('edit');
    }

    /**
     * Método utilizado para editar um orgão de origem previamente cadastrado no sistema
     * @param string $id identificado do orgão de origem que vai ser editado
     * @throws NotFoundException
     */
    public function editar($id = null) {
        if (!$id) {
            throw new NotFoundException(__('objeto_invalido', __('OrgaoOrigem')));
        }

        $orgaoOrigem = $this->OrgaoOrigem->findById($id);

        if (!$orgaoOrigem) {
            throw new NotFoundException(__('objeto_invalido', __('OrgaoOrigem')));
        }

        if ($this->request->is(array('post', 'put'))) {
            $this->OrgaoOrigem->id = $id;

            if ($this->OrgaoOrigem->saveAll($this->request->data)) {

                $id = $this->OrgaoOrigem->id;
                $currentFunction = $this->request->params['action']; //function corrente
                $currentController = $this->name; //Controller corrente
                $this->saveAuditLog($id,$currentController,'A',$currentFunction);


                $this->Session->setFlash(__('objeto_salvo_sucesso', __('OrgaoOrigem')), 'flash_success');
                return $this->tratarAcaoSalvar();
            }
        }

        if (!$this->request->data) {
            $this->request->data = $orgaoOrigem;
        }

        //render view edit
        $this->render('edit');
    }

    /**
     * Método para excluir um orgão de origem do sistema
     */
    public function deletar($id) {
        if ($this->request->is('get')) {
            if (!$id) {
                throw new NotFoundException(__('objeto_invalido', __('OrgaoOrigem')));
            }

            $orgaoOrigem = $this->OrgaoOrigem->findById($id);

            if (!$orgaoOrigem) {
                throw new NotFoundException(__('objeto_invalido', __('OrgaoOrigem')));
            }

            $this->request->data = $orgaoOrigem;

            //render view edit
            $this->render('edit');
        } else {
            if ($this->OrgaoOrigem->validarExclusao($id)) {

                 $log = $this->OrgaoOrigem->getDataSource()->getLog(false, false);
                pr($log);die;

                $this->Session->setFlash(__('validacao_exclusao_orgao_origem'), 'flash_alert');
                return $this->redirect(array('action' => 'deletar', $id));
            }
                $log = $this->OrgaoOrigem->getDataSource()->getLog(false, false);
                pr($log);die;

            if ($this->OrgaoOrigem->delete($id)) {


                $currentFunction = $this->request->params['action']; //function corrente
                $currentController = $this->name; //Controller corrente
                $this->saveAuditLog($id,$currentController,'E',$currentFunction);


                $this->Session->setFlash(
                        __('objeto_excluir_sucesso', __('OrgaoOrigem')), 'flash_success'
                );
                return $this->redirect(array('action' => 'index'));
            }
        }
    }

}
