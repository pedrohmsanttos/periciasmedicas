<?php

// App::uses('BSController', 'Admin.Controller');
App::uses('BSController', 'Web.Controller');

class FeriadoController extends BSController {

    public $helpers = array('Html', 'Form');

    public function index() {
        
    }



        /**
     * Método utilizado para cadastrar um novo Feriado no sistema
     */
    public function adicionar() {
        if ($this->request->is('post')) {
            // pr($this->request->data);die;
            $this->Feriado->create();
            if ($this->Feriado->save($this->request->data)) {



                $id = $this->Feriado->id;
                $currentFunction = $this->request->params['action']; //function corrente
                $currentController = $this->name; //Controller corrente
                $this->saveAuditLog($id,$currentController,'I',$currentFunction);


                $this->Session->setFlash(__('objeto_salvo_sucesso', __('Feriado')), 'flash_success');
                return $this->tratarAcaoSalvar();
            }
        }
        
        //render view edit
        $this->render('edit');
    }

    /**
     * Método utilizado para editar um Feriado previamente cadastrado no sistema
     * @param string $id identificado do Feriado que vai ser editado
     * @throws NotFoundException
     */
    public function editar($id = null) {
        if (!$id) {
            throw new NotFoundException(__('objeto_invalido', __('Feriado')));
        }

        $feriado = $this->Feriado->findById($id);
        
        if (!$feriado) {
            throw new NotFoundException(__('objeto_invalido', __('Feriado')));
        }
        $feriado['Feriado']['data_feriado'] = Util::inverteData($feriado['Feriado']['data_feriado']);

        if ($this->request->is(array('post', 'put'))) {
            $this->Feriado->id = $id;
            
            if ($this->Feriado->save($this->request->data)) {



                $currentFunction = $this->request->params['action']; //function corrente
                $currentController = $this->name; //Controller corrente
                $this->saveAuditLog($id,$currentController,'A',$currentFunction);



                $this->Session->setFlash(__('objeto_salvo_sucesso', __('Feriado')), 'flash_success');
                return $this->tratarAcaoSalvar();
            }
        }

        if (!$this->request->data) {
            $this->request->data = $feriado;
        }
        
        //render view edit
        $this->render('edit');
    }


    /**
     * Método utilizado para visualizar um Feriado
     * @param string $id
     */
    public function visualizar($id = null) {
        if (!$id) {
            throw new NotFoundException(__('objeto_invalido', __('Feriado')));
        }

        $feriado = $this->Feriado->findById($id);
        $feriado['Feriado']['data_feriado'] = Util::inverteData($feriado['Feriado']['data_feriado']);
        

        $currentFunction = $this->request->params['action']; //function corrente
        $currentController = $this->name; //Controller corrente
        $this->saveAuditLog($id,$currentController,'V',$currentFunction);

        if (!$feriado) {
            throw new NotFoundException(__('objeto_invalido', __('Feriado')));
        }

        $this->request->data = $feriado;
        
        //render view edit
        $this->render('edit');
    }

    /**
     * Método utilizado para exibir a listagem inicial de Cargos cadastrados
     */
    public function consultar() {
       $this->layout = 'ajax';

        if ($this->request->is('GET')) {
            $limitConsulta = $this->request->query['data']['Feriado']['limitConsulta'];
            $nome = $this->request->query['data']['Feriado']['nome'];
            $data_feriado = $this->request->query['data']['Feriado']['data_feriado'];
            $condicoes = null;
            if (!empty($nome)) {
                $condicoes['Feriado.nome ILIKE '] = "%$nome%";
            }
            if (!empty($data_feriado)) {
                $condicoes['Feriado.data_feriado'] = Util::inverteData($data_feriado);
            }
            $filtro = new BSFilter();
            $filtro->setCondicoes($condicoes);
            $filtro->setLimiteConsulta($limitConsulta);
            $filtro->setCamposRetornadosString("id", "nome", "data_feriado", "feriado_recorrente");
            $filtro->setCamposOrdenados(['Feriado.nome'=>'asc']);

            $currentFunction = $this->request->params['action']; //function corrente
            $currentController = $this->name; //Controller corrente
            $this->saveAuditLog(null,$currentController,'C',$currentFunction);
            

            $this->set('feriados', $this->paginar($filtro));
            $this->set('limiteConsultaSelecionado', $limitConsulta);
        }
    }


    /**
     * Método para excluir um Feriado do sistema
     */
    public function deletar($id = null) {
        if ($this->request->is('get')) {
            $feriado = $this->Feriado->findById($id);
            $feriado['Feriado']['data_feriado'] = Util::inverteData($feriado['Feriado']['data_feriado']);
            $this->request->data = $feriado;
           
            $this->render('edit');
        } else {
            if ($this->Feriado->delete($id)) {


                $currentFunction = $this->request->params['action']; //function corrente
                $currentController = $this->name; //Controller corrente
                $this->saveAuditLog($id,$currentController,'E',$currentFunction);


                $this->Session->setFlash(__('objeto_excluir_sucesso', __('Feriado')), 'flash_success');
                return $this->redirect(array('action' => 'index'));
            }
        }
    }


}
