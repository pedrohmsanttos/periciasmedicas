<?php

App::import("Plugin/Web/Controller", "BSController");

class PerfilController extends BSController {

    /**
     * Método utilizado para exibir a listagem inicial de Perfil cadastrados
     */
    public function index() {

    }

    /**
     * Método responsável por realizar a filtragem de perfis do sistema
     */
    public function consultar() {
        $this->layout = 'ajax';

        if ($this->request->is('GET')) {
            $limitConsulta = $this->request->query['data']['Perfil']['limitConsulta'];
            $nome = $this->request->query['data']['Perfil']['nome'];
            $condicoes = null;
            if (!empty($nome)) {
                $condicoes['Perfil.nome ILIKE '] = "%$nome%";
            }
			
			
			
			
			 
                $currentFunction = $this->request->params['action']; //function corrente
                $currentController = $this->name; //Controller corrente
                $this->saveAuditLog(null,$currentController,'C',$currentFunction);
			
			
           
            $condicoes['Perfil.ativado'] =  $this->request->query['data']['Perfil']['ativado'];
            $filtro = new BSFilter();
            $filtro->setCondicoes($condicoes);
            $filtro->setLimiteConsulta($limitConsulta);
            $filtro->setCamposRetornadosString("id", "nome" , "ativado");
            $filtro->setCamposOrdenados(['Perfil.nome' => 'asc']);

            $this->set('perfis', $this->paginar($filtro));
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
		
		
		 
                $currentFunction = $this->request->params['action']; //function corrente
                $currentController = $this->name; //Controller corrente
                $this->saveAuditLog($id,$currentController,'V',$currentFunction);
		
		

        $this->request->data = $perfil;
        $this->carregarListaFuncionalidades();
        //render view edit
        $this->render('edit');
    }

    /**
     * Método para carregar a lista de funcionalidades
     */
    private function carregarListaFuncionalidades() {
        $funcionalidades = $this->Perfil->listarMenusFuncionalidades();
        $this->set(compact('funcionalidades'));
    }

    /**
     * Método utilizado para cadastrar um novo Perfil no sistema
     */
    public function adicionar() {
        if ($this->request->is('post')) {
            $this->Perfil->create();
            if ($this->Perfil->saveAll($this->request->data)) {

                $id = $this->Perfil->id;
                $currentFunction = $this->request->params['action']; //function corrente
                $currentController = $this->name; //Controller corrente
                $this->saveAuditLog($id,$currentController,'I',$currentFunction);


                $this->Session->setFlash(__('objeto_salvo_sucesso', __('Perfil')), 'flash_success');
                return $this->tratarAcaoSalvar();
            }
        } else {
            $this->request->data['Funcionalidade'] = array();
        }
        $this->carregarListaFuncionalidades();
        //render view edit
        $this->render('edit');
    }

    /**
     * Método utilizado para editar um Perfil previamente cadastrado no sistema
     * @param string $id identificador do Usuário que vai ser editado
     * @throws NotFoundException
     */
    public function editar($id = null) {


        if (!$id) {
            throw new NotFoundException(__('objeto_invalido', __('Perfil')));
        }

        $perfil = $this->Perfil->findById($id);

        if (!$perfil) {
            throw new NotFoundException(__('objeto_invalido', __('Perfil')));
        }

        if ($this->request->is(array('post', 'put'))) {
            $this->Perfil->id = $id;

            if ($this->Perfil->saveAll($this->request->data)) {
                $currentFunction = $this->request->params['action']; //function corrente
                $currentController = $this->name; //Controller corrente
                $this->saveAuditLog($id,$currentController,'A',$currentFunction);

                $this->Session->setFlash(__('objeto_salvo_sucesso', __('Perfil')), 'flash_success');
                return $this->tratarAcaoSalvar();
            }
        }

        if (!$this->request->data) {
            $this->request->data = $perfil;
        }

        $this->carregarListaFuncionalidades();
        //render view edit
        $this->render('edit');
    }

    /**
     * Método para excluir um Perfil do sistema
     */
    public function deletar($id = null) {
        if ($this->request->is('get')) {
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
        } else {
            if ($this->Perfil->validarExclusao($id)) {
                $this->Session->setFlash(
                        __('validacao_exclusao_perfil'), 'flash_alert'
                );
                return $this->redirect(array('action' => 'deletar', $id));
            }
            if ($this->Perfil->delete($id)) {


                $currentFunction = $this->request->params['action']; //function corrente
                $currentController = $this->name; //Controller corrente
                $this->saveAuditLog($id,$currentController,'E',$currentFunction);

                $this->Session->setFlash(
                        __('objeto_excluir_sucesso', __('Perfil')), 'flash_success'
                );
                return $this->redirect(array('action' => 'index'));
            }
        }
    }

}
