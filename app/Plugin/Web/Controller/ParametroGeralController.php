<?php

// App::uses('BSController', 'Admin.Controller');
App::uses('BSController', 'Web.Controller');

class ParametroGeralController extends BSController {

    public $helpers = array('Html', 'Form');

    public function index() {
        
    }

    /**
     * Método utilizado para exibir a listagem inicial de lotacaos cadastradas
     */
    public function consultar() {
        
    }

    /**
     * Método utilizado para editar os parametros gerais
     * @param string $id identificado do paramentro geral que vai ser editado
     * @throws NotFoundException
     */
    public function editar($id = null) {
        $this->loadModel('Tipologia');
        $this->loadModel('TempoConsultaAtendimento');
        if (!$id) {
            throw new NotFoundException(__('entidade_invalido', __('ParametroGeral')));
        }

        $parametroGeral = $this->ParametroGeral->findById($id);
        
        if (!$parametroGeral) {
            throw new NotFoundException(__('entidade_invalido', __('ParametroGeral')));
        }

        if ($this->request->is(array('post', 'put'))) {
            $this->ParametroGeral->id = $id;
            if ($this->ParametroGeral->save($this->request->data)) {


                $currentFunction = $this->request->params['action']; //function corrente
                $currentController = $this->name; //Controller corrente
                $this->saveAuditLog($id,$currentController,'A',$currentFunction);

                $this->Session->setFlash(__('entidade_salva_sucesso', __('ParametroGeral')), 'flash_success');
                return $this->redirect(array('action' => 'editar', $id));
            }
        }

        if (!$this->request->data) {
            $this->request->data = $parametroGeral;
            $this->set('tipologias', $this->Tipologia->listar());
            $this->set('consultas', $this->TempoConsultaAtendimento->find('all', array('conditions' => array('TempoConsultaAtendimento.ativo' => true))));
        }

        //render view edit
        $this->render('edit');
    }


}
