<?php
// App::uses('BSController', 'Admin.Controller');
App::uses('BSController', 'Web.Controller');
class CargoController extends BSController {

    public $helpers = array('Html', 'Form');

    public function index() {
        
    }

    /**
     * Método utilizado para exibir a listagem inicial de Cargos cadastrados
     */
    public function consultar() {
       $this->layout = 'ajax';

        if ($this->request->is('GET')) {
            $limitConsulta = $this->request->query['data']['Cargo']['limitConsulta'];
            $nome = $this->request->query['data']['Cargo']['nome'];
            $sigla = $this->request->query['data']['Cargo']['sigla'];
            $condicoes = null;
            if (!empty($nome)) {
                $condicoes['Cargo.nome ILIKE '] = "%$nome%";
            }
            if (!empty($sigla)) {
                $condicoes['Cargo.sigla ILIKE '] = "%$sigla%";
            }
            $filtro = new BSFilter();
            $filtro->setCondicoes($condicoes);
            $filtro->setLimiteConsulta($limitConsulta);
            $filtro->setCamposRetornadosString("id", "nome", "sigla");
            $filtro->setCamposOrdenados(['Cargo.nome'=>'asc']);
			
			
			 
                $currentFunction = $this->request->params['action']; //function corrente
                $currentController = $this->name; //Controller corrente
                $this->saveAuditLog(null,$currentController,'C',$currentFunction);
			

            $this->set('cargos', $this->paginar($filtro));
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
		
		 $id = $this->Perfil->id;
                $currentFunction = $this->request->params['action']; //function corrente
                $currentController = $this->name; //Controller corrente
                $this->saveAuditLog($id,$currentController,'V',$currentFunction);

        if (!$cargo) {
            throw new NotFoundException(__('objeto_invalido', __('Cargo')));
        }

        $this->request->data = $cargo;
        
        //render view edit
        $this->render('edit');
    }

    /**
     * Método utilizado para cadastrar um novo Cargo no sistema
     */
    public function adicionar() {
        if ($this->request->is('post')) {
            $this->Cargo->create();
            if ($this->Cargo->save($this->request->data)) {



                $id = $this->Cargo->id;
                $currentFunction = $this->request->params['action']; //function corrente
                $currentController = $this->name; //Controller corrente
                $this->saveAuditLog($id,$currentController,'I',$currentFunction);


                $this->Session->setFlash(__('objeto_salvo_sucesso', __('Cargo')), 'flash_success');
                return $this->tratarAcaoSalvar();
            }
        }
        
        //render view edit
        $this->render('edit');
    }

    /**
     * Método utilizado para editar um Cargo previamente cadastrado no sistema
     * @param string $id identificado do Cargo que vai ser editado
     * @throws NotFoundException
     */
    public function editar($id = null) {
        if (!$id) {
            throw new NotFoundException(__('objeto_invalido', __('Cargo')));
        }

        $cargo = $this->Cargo->findById($id);
        
        if (!$cargo) {
            throw new NotFoundException(__('objeto_invalido', __('Cargo')));
        }

        if ($this->request->is(array('post', 'put'))) {
            $this->Cargo->id = $id;
            
            if ($this->Cargo->save($this->request->data)) {



                $currentFunction = $this->request->params['action']; //function corrente
                $currentController = $this->name; //Controller corrente
                $this->saveAuditLog($id,$currentController,'A',$currentFunction);



                $this->Session->setFlash(__('objeto_salvo_sucesso', __('Cargo')), 'flash_success');
                return $this->tratarAcaoSalvar();
            }
        }

        if (!$this->request->data) {
            $this->request->data = $cargo;
        }
        
        //render view edit
        $this->render('edit');
    }

    /**
     * Método para excluir um Cargo do sistema
     */
    public function deletar($id) {
        if ($this->request->is('get')) {
            if (!$id) {
                throw new NotFoundException(__('objeto_invalido', __('Cargo')));
            }

            $cargo = $this->Cargo->findById($id);

            if (!$cargo) {
                throw new NotFoundException(__('objeto_invalido', __('Cargo')));
            }

            $this->request->data = $cargo;
            
            //render view edit
            $this->render('edit');
        } else {
            if ($this->Cargo->validarExclusao($id)) {
                $this->Session->setFlash(__('validacao_exclusao_cargo'), 'flash_alert');
                return $this->redirect(array('action' => 'deletar', $id));
            }
            if ($this->Cargo->delete($id)) {


                $currentFunction = $this->request->params['action']; //function corrente
                $currentController = $this->name; //Controller corrente
                $this->saveAuditLog($id,$currentController,'E',$currentFunction);



                $this->Session->setFlash(
                        __('objeto_excluir_sucesso', __('Cargo')), 'flash_success'
                );
                return $this->redirect(array('action' => 'index'));
            }
        }
    }

}
