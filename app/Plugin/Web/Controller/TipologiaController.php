<?php

// App::import("Plugin/Admin/Controller", "BSController");
App::import("Plugin/Web/Controller", "BSController");

class TipologiaController extends BSController {

    /**
     * Método utilizado para exibir a listagem inicial de Tipologia cadastradas
     */
    public function index() {
        
    }
    
    /**
     * Carregar lista de CIDs
     */
    private function carregarListasCid() {
        $this->loadModel('Cid');
        $filtro = new BSFilter();
        $filtro->setTipo('list');
        $filtro->setCamposOrdenadosString('Cid.nome');
        $cids = $this->Cid->listar($filtro);
        $this->set(compact('cids'));
    }

    /**
     * Método responsável por realizar a filtragem de perfis do sistema
     */
    public function consultar() {
        $this->layout = 'ajax';

        if ($this->request->is('GET')) {
            $limitConsulta = $this->request->query['data']['Tipologia']['limitConsulta'];
            $nome = $this->request->query['data']['Tipologia']['nome'];

            $condicoes = null;
            if (!empty($nome)) {
                $condicoes['Tipologia.nome ILIKE '] = "%$nome%";
            }
            $filtro = new BSFilter();
            $filtro->setCondicoes($condicoes);
            $filtro->setLimiteConsulta($limitConsulta);
            $filtro->setCamposRetornadosString("id", "nome");
            $filtro->setCamposOrdenados(['Tipologia.nome' => 'asc']);
            $this->set('tipologia', $this->paginar($filtro));
            $this->set('limiteConsultaSelecionado', $limitConsulta);
			
			
			
			 
                $currentFunction = $this->request->params['action']; //function corrente
                $currentController = $this->name; //Controller corrente
                $this->saveAuditLog(null,$currentController,'C',$currentFunction);
			
        }
    }

    /**
     * Método utilizado para visualizar um Tipologia
     * @param string $id identificador do Tipologia
     */
    public function visualizar($id = null) {
        if (!$id) {
            throw new NotFoundException(__('objeto_invalido', __('Tipologia')));
        }

        $tipologia = $this->Tipologia->findById($id);
		
		
		 
                $currentFunction = $this->request->params['action']; //function corrente
                $currentController = $this->name; //Controller corrente
                $this->saveAuditLog($id,$currentController,'V',$currentFunction);

        if (!$tipologia) {
            throw new NotFoundException(__('objeto_invalido', __('Tipologia')));
        }
        $this->carregarListasCid();
        $this->request->data = $tipologia;

        //render view edit
        $this->render('edit');
    }

    /**
     * Método utilizado para cadastrar um novo Tipologia no sistema
     */
    public function adicionar() {
        // die('hello');
        $this->carregarListasCid();
        if ($this->request->is('post')) {
            $this->Tipologia->create();
            if ($this->Tipologia->saveAll($this->request->data)) {

                $id = $this->Tipologia->id;
                $currentFunction = $this->request->params['action']; //function corrente
                $currentController = $this->name; //Controller corrente
                $this->saveAuditLog($id,$currentController,'I',$currentFunction);



                $this->Session->setFlash(__('entidade_salva_sucesso', __('Tipologia')), 'flash_success');
                return $this->tratarAcaoSalvar();
            }
        }
        //render view edit
        $this->render('edit');
    }

    /**
     * Método utilizado para editar um Tipologia previamente cadastrado no sistema
     * @param string $id identificador do Usuário que vai ser editado
     * @throws NotFoundException
     */
    public function editar($id = null) {
        if (!$id) {
            throw new NotFoundException(__('objeto_invalido', __('Tipologia')));
        }

        $tipologia = $this->Tipologia->findById($id);

        if (!$tipologia) {
            throw new NotFoundException(__('objeto_invalido', __('Tipologia')));
        }
        $this->carregarListasCid();
        if ($this->request->is(array('post', 'put'))) {
            $this->Tipologia->id = $id;

            if ($this->Tipologia->saveAll($this->request->data)) {

                $currentFunction = $this->request->params['action']; //function corrente
                $currentController = $this->name; //Controller corrente
                $this->saveAuditLog($id,$currentController,'A',$currentFunction);


                $this->Session->setFlash(__('entidade_salva_sucesso', __('Tipologia')), 'flash_success');
                return $this->tratarAcaoSalvar();
            }
        }

        if (!$this->request->data) {
            $this->request->data = $tipologia;
        }

        //render view edit
        $this->render('edit');
    }

    /**
     * Método para excluir um Tipologia do sistema
     */
    public function deletar($id = null) {
        if($this->Tipologia->isTipologiaDefault($id)){
           $this->Session->setFlash(__('validacao_exclusao_tipologia_travada'), 'flash_alert');
           return $this->redirect(array('action' => 'index'));
        }
        if (!$id) {
            throw new NotFoundException(__('objeto_invalido', __('Tipologia')));
        }

        $this->carregarListasCid();
        if ($this->request->is('get')) {
            $tipologia = $this->Tipologia->findById($id);
            if (!$tipologia) {
                throw new NotFoundException(__('objeto_invalido', __('Tipologia')));
            }
            $this->request->data = $tipologia;
            //render view edit
            $this->render('edit');
        } else {
            if ($this->Tipologia->validarExclusao($id)) {
                $this->Session->setFlash(__('validacao_exclusao_tipologia'), 'flash_alert');
                return $this->redirect(array('action' => 'deletar', $id));
            }
            if ($this->Tipologia->delete($id)) {


                $currentFunction = $this->request->params['action']; //function corrente
                $currentController = $this->name; //Controller corrente
                $this->saveAuditLog($id,$currentController,'E',$currentFunction);



                $this->Session->setFlash(__('entidade_excluir_sucesso', __('Tipologia')), 'flash_success');
                return $this->redirect(array('action' => 'index'));
            }
        }
    }

}
