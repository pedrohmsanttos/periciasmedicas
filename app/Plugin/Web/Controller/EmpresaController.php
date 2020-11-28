<?php

// App::uses('BSController', 'Admin.Controller');
App::uses('BSController', 'Web.Controller');

class EmpresaController extends BSController {

    public $helpers = array('Html', 'Form');

    public function index() {
        $this->carregarListasEstados();
    }

    /**
     * Função que retorna as empresas via json
     */
    public function getFirm() {
        $this->layout = 'ajax';
        $empresas = false;
        if ($this->request->query['term']) {
            $nome = $this->request->query['term'];
            $this->loadModel('Empresa');
            $filtro = new BSFilter();
            $filtro->setTipo('list');
            $condicoes['OR']= ['LOWER(Empresa.nome) LIKE '=> mb_strtolower($nome) . "%", 'Empresa.cnpj LIKE'=>"%".Util::limpaDocumentos($nome)."%"] ;
            
            $filtro->setCondicoes($condicoes);
            
            $empresas = $this->Empresa->listar($filtro);
            $arrayRetorno = array();
            foreach ($empresas as $key=>$line){
                $objTmp = new stdClass();
                $objTmp->id = $key;
                $objTmp->label = $line;
                $objTmp->value = $line;
                $arrayRetorno[] = $objTmp;
            }
        }
        echo json_encode($arrayRetorno);
        die;
    }

    /**
     * Método utilizado para exibir a listagem inicial de empresas cadastradas
     */
    public function consultar() {
        $this->layout = 'ajax';

        if ($this->request->is('GET')) {
            $limitConsulta = $this->request->query['data']['Empresa']['limitConsulta'];
            $nome = $this->request->query['data']['Empresa']['nome'];
            $cnpj = $this->request->query['data']['Empresa']['cnpj'];
            $municipio = $this->request->query['data']['Endereco']['municipio_id'];
            $estado = $this->request->query['data']['Endereco']['estado_id'];
            $nome_responsavel = $this->request->query['data']['Empresa']['nome_responsavel'];
            $condicoes = null;
            if (!empty($nome)) {
                $condicoes['Empresa.nome ILIKE '] = "%$nome%";
            }
            if (!empty($cnpj)) {
                $condicoes['Empresa.cnpj ='] = Util::limpaDocumentos($cnpj);
            }
            if (!empty($estado)) {
                $condicoes['endereco.estado_id ='] = $estado;
            }
            if (!empty($municipio) && $municipio != 'null') {
                $condicoes['endereco.municipio_id ='] = $municipio;
            }
            if (!empty($nome_responsavel)) {
                $condicoes['Empresa.nome_responsavel ILIKE'] = "%$nome_responsavel%";
            }

            $joins[] = array(
                'table' => 'endereco',
                'alias' => 'endereco',
                'type' => 'left',
                'conditions' => array('endereco.id = endereco_id')
            );
			
			
			
			
			 
                $currentFunction = $this->request->params['action']; //function corrente
                $currentController = $this->name; //Controller corrente
                $this->saveAuditLog(null,$currentController,'C',$currentFunction);
			
			

            $filtro = new BSFilter();
            $filtro->setCondicoes($condicoes);
            $filtro->setJoins($joins);
            $filtro->setLimiteConsulta($limitConsulta);
            $filtro->setCamposRetornadosString("id", "nome");
            $filtro->setCamposOrdenados(['Empresa.nome' => 'asc']);
            $this->set('empresas', $this->paginar($filtro));
            $this->set('limiteConsultaSelecionado', $limitConsulta);
        }
    }

    private function carregarListasMunicipios() {
        if (isset($this->request->data['Endereco'])) {
            $estado = $this->request->data['Endereco']['estado_id'];
            $this->loadModel('Municipio');

            $this->set('municipios', $this->Municipio->listarMunicipiosUF($estado));
        }
    }

    private function carregarListasEstados() {
        $this->loadModel('Estado');
        $this->set('estados', $this->Estado->listarEstados());
    }

    /**
     * Método utilizado para visualizar uma empresa
     * @param string $id
     */
    public function visualizar($id = null) {
        if (!$id) {
            throw new NotFoundException(__('entidade_invalido', __('Empresa')));
        }

		
		 
                $currentFunction = $this->request->params['action']; //function corrente
                $currentController = $this->name; //Controller corrente
                $this->saveAuditLog($id,$currentController,'V',$currentFunction);
		
		
        $empresa = $this->Empresa->findById($id);

        if (!$empresa) {
            throw new NotFoundException(__('entidade_invalido', __('Empresa')));
        }

        $this->request->data = $empresa;

        $this->carregarListasEstados();
        $this->carregarListasMunicipios();
        //render view edit
        $this->render('edit');
    }

    /**
     * Método utilizado para cadastrar uma nova empresa no sistema
     */
    public function adicionar() {
        if ($this->request->is('post')) {
            $this->Empresa->create();
            if ($this->Empresa->saveAll($this->request->data)) {

                $id = $this->Empresa->id;
                $currentFunction = $this->request->params['action']; //function corrente
                $currentController = $this->name; //Controller corrente
                $this->saveAuditLog($id,$currentController,'I',$currentFunction);

                $this->Session->setFlash(__('entidade_salva_sucesso', __('Empresa')), 'flash_success');
                return $this->tratarAcaoSalvar();
            }
        }
        $this->carregarListasEstados();
        $this->carregarListasMunicipios();
        //render view edit
        $this->render('edit');
    }

    /**
     * Método utilizado para editar uma empresa previamente cadastrado no sistema
     * @param string $id identificado da empresa que vai ser editado
     * @throws NotFoundException
     */
    public function editar($id = null) {
        if (!$id) {
            throw new NotFoundException(__('entidade_invalido', __('Empresa')));
        }

        $empresa = $this->Empresa->findById($id);

        if (!$empresa) {
            throw new NotFoundException(__('entidade_invalido', __('Empresa')));
        }

        if ($this->request->is(array('post', 'put'))) {
            $this->Empresa->id = $id;
            if ($this->Empresa->saveAll($this->request->data)) {



                $currentFunction = $this->request->params['action']; //function corrente
                $currentController = $this->name; //Controller corrente
                $this->saveAuditLog($id,$currentController,'A',$currentFunction);

                $this->Session->setFlash(__('entidade_salva_sucesso', __('Empresa')), 'flash_success');
                return $this->tratarAcaoSalvar();
            }
        }

        if (!$this->request->data) {
            $this->request->data = $empresa;
        }

        $this->carregarListasEstados();
        $this->carregarListasMunicipios();
        //render view edit
        $this->render('edit');
    }

    /**
     * Método para excluir uma empresa do sistema
     */
    public function deletar($id) {
        if ($this->request->is('get')) {
            if (!$id) {
                throw new NotFoundException(__('entidade_invalido', __('Empresa')));
            }

            $empresa = $this->Empresa->findById($id);

            if (!$empresa) {
                throw new NotFoundException(__('entidade_invalido', __('Empresa')));
            }

            $this->request->data = $empresa;

            $this->carregarListasEstados();
            $this->carregarListasMunicipios();
            //render view edit
            $this->render('edit');
        } else {
            if ($this->Empresa->delete($id)) {


                $currentFunction = $this->request->params['action']; //function corrente
                $currentController = $this->name; //Controller corrente
                $this->saveAuditLog($id,$currentController,'E',$currentFunction);

                $this->Session->setFlash(
                        __('entidade_excluir_sucesso', __('Empresa')), 'flash_success'
                );
                return $this->redirect(array('action' => 'index'));
            }
        }
    }

}
