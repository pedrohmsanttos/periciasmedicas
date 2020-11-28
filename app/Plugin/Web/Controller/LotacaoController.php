<?php

// App::uses('BSController', 'Admin.Controller');
App::uses('BSController', 'Web.Controller');

class LotacaoController extends BSController {

    public $helpers = array('Html', 'Form');

    public function index() {
        $this->carregarListasMunicipios();
        $this->carregarListasOrgaos();
    }

    /**
     * Método utilizado para exibir a listagem inicial de lotacaos cadastradas
     */
    public function consultar() {
        $this->layout = 'ajax';
        if ($this->request->is('GET')) {
            $limitConsulta = $this->request->query['data']['Lotacao']['limitConsulta'];
            $nome = $this->request->query['data']['Lotacao']['nome'];
            $orgao = $this->request->query['data']['Lotacao']['orgao_origem_id'];
            $municipio = $this->request->query['data']['Endereco']['municipio_id'];

            $condicoes = null;
            if (!empty($nome)) {
                $condicoes['Lotacao.nome ILIKE '] = "%$nome%";
            }
            if (!empty($orgao)) {
                $condicoes['Lotacao.orgao_origem_id ='] = $orgao;
            }
            if (!empty($municipio)) {
                $condicoes['Lotacao.municipio_id ='] = $municipio;
            }
            $filtro = new BSFilter();
            $filtro->setCondicoes($condicoes);
            $filtro->setLimiteConsulta($limitConsulta);
            $filtro->setCamposRetornadosString("id", "nome", "OrgaoOrigem.orgao_origem", "Municipio.nome");

            $joins[] = array(
                'table' => 'municipio',
                'alias' => 'Municipio',
                'type' => 'left',
                'conditions' => array('Municipio.id = Endereco.municipio_id')
            );

            $filtro->setJoins($joins);
            $filtro->setCamposOrdenados(['Lotacao.nome' => 'asc']);
			
			
			
                $currentFunction = $this->request->params['action']; //function corrente
                $currentController = $this->name; //Controller corrente
                $this->saveAuditLog(null,$currentController,'C',$currentFunction);
			
			

            $this->set('lotacao', $this->paginar($filtro));
            $this->set('limiteConsultaSelecionado', $limitConsulta);
        }
    }

    private function carregarListasMunicipios() {
        
        if (isset($this->request->data['Endereco'])) {
            $this->loadModel('Municipio');
            $estado = $this->request->data['Endereco']['estado_id'];
            $this->set('municipios', $this->Municipio->listarMunicipiosUF($estado));
        }
        
    }

    private function carregarListasOrgaos() {
        $this->loadModel('OrgaoOrigem');
        $this->set('orgaos', $this->OrgaoOrigem->listarOrgaos());
    }

    /**
     * Método utilizado para visualizar uma lotacao
     * @param string $id
     */
    public function visualizar($id = null) {
        if (!$id) {
            throw new NotFoundException(__('entidade_invalido', __('Lotacao')));
        }
		
		 
                $currentFunction = $this->request->params['action']; //function corrente
                $currentController = $this->name; //Controller corrente
                $this->saveAuditLog($id,$currentController,'V',$currentFunction);

        $lotacao = $this->Lotacao->findById($id);

        if (!$lotacao) {
            throw new NotFoundException(__('entidade_invalido', __('Lotacao')));
        }

        $this->request->data = $lotacao;

        $this->carregarListasOrgaos();
        $this->carregarListasMunicipios();
        $this->carregarListasEstados();
        //render view edit
        $this->render('edit');
    }

    /**
     * Método utilizado para cadastrar uma nova lotacao no sistema
     */
    public function adicionar() {
        if ($this->request->is('post')) {
            $this->Lotacao->create();
            if ($this->Lotacao->saveAll($this->request->data)) {

                $id = $this->Lotacao->id;
                $currentFunction = $this->request->params['action']; //function corrente
                $currentController = $this->name; //Controller corrente
                $this->saveAuditLog($id,$currentController,'I',$currentFunction);

                $this->Session->setFlash(__('entidade_salva_sucesso', __('Lotacao')), 'flash_success');
                return $this->tratarAcaoSalvar();
            }
        }
        $this->carregarListasOrgaos();
        $this->carregarListasEstados();
        $this->carregarListasMunicipios();
        //render view edit
        $this->render('edit');
    }

    private function carregarListasEstados() {
        $this->loadModel('Estado');
        $this->set('estados', $this->Estado->listarEstados());
    }

    /**
     * Método utilizado para editar uma lotacao previamente cadastrado no sistema
     * @param string $id identificado da lotacao que vai ser editado
     * @throws NotFoundException
     */
    public function editar($id = null) {
        if (!$id) {
            throw new NotFoundException(__('entidade_invalido', __('Lotacao')));
        }

        $lotacao = $this->Lotacao->findById($id);

        if (!$lotacao) {
            throw new NotFoundException(__('entidade_invalido', __('Lotacao')));
        }

        if ($this->request->is(array('post', 'put'))) {
            $this->Lotacao->id = $id;
            if ($this->Lotacao->saveAll($this->request->data)) {


                $currentFunction = $this->request->params['action']; //function corrente
                $currentController = $this->name; //Controller corrente
                $this->saveAuditLog($id,$currentController,'A',$currentFunction);

                $this->Session->setFlash(__('entidade_salva_sucesso', __('Lotacao')), 'flash_success');
                return $this->tratarAcaoSalvar();
            }
        }

        if (!$this->request->data) {
            $this->request->data = $lotacao;
        }

        $this->carregarListasOrgaos();
        $this->carregarListasEstados();
        $this->carregarListasMunicipios();


        //render view edit
        $this->render('edit');
    }

    /**
     * Método para excluir uma lotacao do sistema
     */
    public function deletar($id) {
        if ($this->request->is('get')) {
            if (!$id) {
                throw new NotFoundException(__('entidade_invalido', __('Lotacao')));
            }

            $lotacao = $this->Lotacao->findById($id);

            if (!$lotacao) {
                throw new NotFoundException(__('entidade_invalido', __('Lotacao')));
            }

            $this->request->data = $lotacao;

            $this->carregarListasOrgaos();
            $this->carregarListasMunicipios();
            $this->carregarListasEstados();
            //render view edit
            $this->render('edit');
        } else {
            if ($this->Lotacao->validarExclusao($id)) {
                $this->Session->setFlash(__('validacao_exclusao_lotacao'), 'flash_alert');
                return $this->redirect(array('action' => 'deletar', $id));
            }

            if ($this->Lotacao->delete($id)) {


                $currentFunction = $this->request->params['action']; //function corrente
                $currentController = $this->name; //Controller corrente
                $this->saveAuditLog($id,$currentController,'E',$currentFunction);

                $this->Session->setFlash(
                        __('entidade_excluir_sucesso', __('Lotacao')), 'flash_success'
                );
                return $this->redirect(array('action' => 'index'));
            }
        }
    }

}
