<?php

// App::import("Plugin/Admin/Controller", "BSController");
App::import("Plugin/Web/Controller", "BSController");

class EspecialidadeController extends BSController {

    /**
     * Método utilizado para exibir a listagem inicial de Especialidade cadastradas
     */
    public function index() {
        
    }

    /**
     * Método responsável por realizar a filtragem de perfis do sistema
     */
    public function consultar() {
        $this->layout = 'ajax';

        if ($this->request->is('GET')) {
            $limitConsulta = $this->request->query['data']['Especialidade']['limitConsulta'];
            $nome = $this->request->query['data']['Especialidade']['nome'];

            $condicoes = null;
            if (!empty($nome)) {
                $condicoes['Especialidade.nome ILIKE '] = "%$nome%";
            }
            $filtro = new BSFilter();
            $filtro->setCondicoes($condicoes);
            $filtro->setLimiteConsulta($limitConsulta);
            $filtro->setCamposRetornadosString("id", "nome");
            $filtro->setCamposOrdenados(['Especialidade.nome' => 'asc']);
			
			 ;
                $currentFunction = $this->request->params['action']; //function corrente
                $currentController = $this->name; //Controller corrente
                $this->saveAuditLog(null,$currentController,'C',$currentFunction);

            $this->set('especialidade', $this->paginar($filtro));
            $this->set('limiteConsultaSelecionado', $limitConsulta);
        }
    }

    /**
     * Método utilizado para visualizar um Especialidade
     * @param string $id identificador do Especialidade
     */
    public function visualizar($id = null) {
        if (!$id) {
            throw new NotFoundException(__('objeto_invalido', __('Especialidade')));
        }

        $especialidade = $this->Especialidade->findById($id);

        if (!$especialidade) {
            throw new NotFoundException(__('objeto_invalido', __('Especialidade')));
        }
        $this->request->data = $especialidade;
		
		
		 
                $currentFunction = $this->request->params['action']; //function corrente
                $currentController = $this->name; //Controller corrente
                $this->saveAuditLog($id,$currentController,'V',$currentFunction);

        //render view edit
        $this->render('edit');
    }

    /**
     * Método utilizado para cadastrar um novo Especialidade no sistema
     */
    public function adicionar() {
        if ($this->request->is('post')) {
            $this->Especialidade->create();
            if ($this->Especialidade->save($this->request->data)) {
                $id = $this->Especialidade->id;
                $currentFunction = $this->request->params['action']; //function corrente
                $currentController = $this->name; //Controller corrente
                $this->saveAuditLog($id,$currentController,'I',$currentFunction);

                $this->Session->setFlash(__('entidade_salva_sucesso', __('Especialidade')), 'flash_success');
                return $this->tratarAcaoSalvar();
            }
        }
        //render view edit
        $this->render('edit');
    }

    /**
     * Método utilizado para editar um Especialidade previamente cadastrado no sistema
     * @param string $id identificador do Usuário que vai ser editado
     * @throws NotFoundException
     */
    public function editar($id = null) {
        if (!$id) {
            throw new NotFoundException(__('objeto_invalido', __('Especialidade')));
        }

        $especialidade = $this->Especialidade->findById($id);

        if (!$especialidade) {
            throw new NotFoundException(__('objeto_invalido', __('Especialidade')));
        }

        if ($this->request->is(array('post', 'put'))) {
            $this->Especialidade->id = $id;

            if ($this->Especialidade->save($this->request->data)) {

                $currentFunction = $this->request->params['action']; //function corrente
                $currentController = $this->name; //Controller corrente
                $this->saveAuditLog($id,$currentController,'A',$currentFunction);

                $this->Session->setFlash(__('entidade_salva_sucesso', __('Especialidade')), 'flash_success');
                return $this->tratarAcaoSalvar();
            }
        }

        if (!$this->request->data) {
            $this->request->data = $especialidade;
        }

        //render view edit
        $this->render('edit');
    }

    /**
     * Método para excluir um Especialidade do sistema
     */
    public function deletar($id = null) {
        if (!$id) {
            throw new NotFoundException(__('objeto_invalido', __('Especialidade')));
        }

        if ($this->request->is('get')) {
            $especialidade = $this->Especialidade->findById($id);
            if (!$especialidade) {
                throw new NotFoundException(__('objeto_invalido', __('Especialidade')));
            }
            $this->request->data = $especialidade;
            //render view edit
            $this->render('edit');
        } else {
            if ($this->Especialidade->validarExclusao($id)) {
                $this->Session->setFlash(__('validacao_exclusao_especialidade'), 'flash_alert');
                return $this->redirect(array('action' => 'deletar', $id));
            }
            if ($this->Especialidade->delete($id)) {

                $currentFunction = $this->request->params['action']; //function corrente
                $currentController = $this->name; //Controller corrente
                $this->saveAuditLog($id,$currentController,'E',$currentFunction);

                $this->Session->setFlash(__('entidade_excluir_sucesso', __('Especialidade')), 'flash_success');
                return $this->redirect(array('action' => 'index'));
            }
        }
    }

}
