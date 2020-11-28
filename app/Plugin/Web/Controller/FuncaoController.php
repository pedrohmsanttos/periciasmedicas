<?php

// App::uses('BSController', 'Admin.Controller');
App::uses('BSController', 'Web.Controller');

class FuncaoController extends BSController {

    public $helpers = array('Html', 'Form');

    public function index() {
        
    }

    /**
     * Método utilizado para exibir a listagem inicial de funções cadastradas
     */
    public function consultar() {
        $this->layout = 'ajax';

        if ($this->request->is('GET')) {
            $limitConsulta = $this->request->query['data']['Funcao']['limitConsulta'];
            $nome = $this->request->query['data']['Funcao']['nome'];
            $sigla = $this->request->query['data']['Funcao']['sigla'];
            $condicoes = null;
            if (!empty($nome)) {
                $condicoes['Funcao.nome ILIKE '] = "%$nome%";
            }
            if (!empty($sigla)) {
                $condicoes['Funcao.sigla ILIKE '] = "%$sigla%";
            }
            $filtro = new BSFilter();
            $filtro->setCondicoes($condicoes);
            $filtro->setLimiteConsulta($limitConsulta);
            $filtro->setCamposRetornadosString("id", "nome", "sigla");
            $filtro->setCamposOrdenados(['Funcao.nome' => 'asc']);

			
			
			 
                $currentFunction = $this->request->params['action']; //function corrente
                $currentController = $this->name; //Controller corrente
                $this->saveAuditLog(null,$currentController,'C',$currentFunction);
			
			
            $this->set('funcoes', $this->paginar($filtro));
            $this->set('limiteConsultaSelecionado', $limitConsulta);
        }
    }

    /**
     * Método utilizado para visualizar uma função
     * @param string $id
     */
    public function visualizar($id = null) {
        if (!$id) {
            throw new NotFoundException(__('entidade_invalido', __('Funcao')));
        }

        $funcao = $this->Funcao->findById($id);

        if (!$funcao) {
            throw new NotFoundException(__('entidade_invalido', __('Funcao')));
        }

        $this->request->data = $funcao;
		
		 
                $currentFunction = $this->request->params['action']; //function corrente
                $currentController = $this->name; //Controller corrente
                $this->saveAuditLog($id,$currentController,'V',$currentFunction);

        //render view edit
        $this->render('edit');
    }

    /**
     * Método utilizado para cadastrar uma nova funcao no sistema
     */
    public function adicionar() {
        if ($this->request->is('post')) {
            $this->Funcao->create();
            if ($this->Funcao->save($this->request->data)) {


                $id = $this->Funcao->id;
                $currentFunction = $this->request->params['action']; //function corrente
                $currentController = $this->name; //Controller corrente
                $this->saveAuditLog($id,$currentController,'I',$currentFunction);

                $this->Session->setFlash(__('entidade_salva_sucesso', __('Funcao')), 'flash_success');
                return $this->tratarAcaoSalvar();
            }
        }

        //render view edit
        $this->render('edit');
    }

    /**
     * Método utilizado para editar uma função previamente cadastrado no sistema
     * @param string $id identificado da função que vai ser editado
     * @throws NotFoundException
     */
    public function editar($id = null) {
        if (!$id) {
            throw new NotFoundException(__('entidade_invalido', __('Funcao')));
        }

        $funcao = $this->Funcao->findById($id);

        if (!$funcao) {
            throw new NotFoundException(__('entidade_invalido', __('Funcao')));
        }

        if ($this->request->is(array('post', 'put'))) {
            $this->Funcao->id = $id;

            if ($this->Funcao->save($this->request->data)) {



                $currentFunction = $this->request->params['action']; //function corrente
                $currentController = $this->name; //Controller corrente
                $this->saveAuditLog($id,$currentController,'A',$currentFunction);

                $this->Session->setFlash(__('entidade_salva_sucesso', __('Funcao')), 'flash_success');
                return $this->tratarAcaoSalvar();
            }
        }

        if (!$this->request->data) {
            $this->request->data = $funcao;
        }

        //render view edit
        $this->render('edit');
    }

    /**
     * Método para excluir uma função do sistema
     */
    public function deletar($id) {
        if ($this->request->is('get')) {
            if (!$id) {
                throw new NotFoundException(__('entidade_invalido', __('Funcao')));
            }

            $funcao = $this->Funcao->findById($id);

            if (!$funcao) {
                throw new NotFoundException(__('entidade_invalido', __('Funcao')));
            }

            $this->request->data = $funcao;

            //render view edit
            $this->render('edit');
        } else {
            if ($this->Funcao->validarExclusao($id)) {
                $this->Session->setFlash(__('validacao_exclusao_funcao'), 'flash_alert');
                return $this->redirect(array('action' => 'deletar', $id));
            }
            if ($this->Funcao->delete($id)) {


                $currentFunction = $this->request->params['action']; //function corrente
                $currentController = $this->name; //Controller corrente
                $this->saveAuditLog($id,$currentController,'E',$currentFunction);

                $this->Session->setFlash(
                        __('entidade_excluir_sucesso', __('Funcao')), 'flash_success'
                );
                return $this->redirect(array('action' => 'index'));
            }
        }
    }

}
