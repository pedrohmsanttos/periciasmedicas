<?php

// App::import("Plugin/Admin/Controller", "BSController");
App::import("Plugin/Web/Controller", "BSController");

class CidController extends BSController {

    /**
     * Método utilizado para exibir a listagem inicial de Cid cadastrados
     */
    public function index() {
        
    }

    /**
     * Método responsável por realizar a filtragem de cids do sistema
     */
    public function consultar() {
        $this->layout = 'ajax';
        if ($this->request->is('GET')) {
            $limitConsulta = $this->request->query['data']['Cid']['limitConsulta'];
            $nome = $this->request->query['data']['Cid']['nome'];
            $nomeDoenca = $this->request->query['data']['Cid']['nome_doenca'];
            $condicoes = null;
            if (!empty($nome)) {
                $condicoes['Cid.nome ILIKE '] = "%$nome%";
            }
            if (!empty($nomeDoenca)) {
                $condicoes['Cid.nome_doenca ILIKE '] = "%$nomeDoenca%";
            }

            $joins[] = array(
                'table' => 'cid_especialidade',
                'alias' => 'CidEspecialidade',
                'type' => 'left',
                'conditions' => array('CidEspecialidade.cid_id = Cid.id')
            );
            $joins[] = array(
                'table' => 'especialidade',
                'alias' => 'Especialidade',
                'type' => 'left',
                'conditions' => array('Especialidade.id = CidEspecialidade.especialidade_id')
            );
			
			
			
			 
                $currentFunction = $this->request->params['action']; //function corrente
                $currentController = $this->name; //Controller corrente
                $this->saveAuditLog(null,$currentController,'C',$currentFunction);
			

            $filtro = new BSFilter();
            $filtro->setCondicoes($condicoes);
            $filtro->setJoins($joins);
            $filtro->setLimiteConsulta($limitConsulta);
            $filtro->setCamposRetornadosString("id", "nome", "nome_doenca", "array_to_string(array_agg(distinct(Especialidade.nome)), ', ') as Especialidade_nome ");
            $filtro->setCamposOrdenados(['Cid.nome' => 'asc']);
            $filtro->setCamposAgrupados(['Cid.id', 'Cid.nome', "nome_doenca"]);

            $this->set('cids', $this->paginar($filtro));
            $this->set('limiteConsultaSelecionado', $limitConsulta);
        }
    }

    /**
     * Método utilizado para visualizar um Cid
     * @param string $id identificador do Cid
     */
    public function visualizar($id = null) {
        $this->carregarListaEspecialidades();
         $this->set('unidades', $this->carregarListaUnidades());
        $cid = $this->_verificaId($id);
        $this->request->data = $cid;
		
		 
                $currentFunction = $this->request->params['action']; //function corrente
                $currentController = $this->name; //Controller corrente
                $this->saveAuditLog($id,$currentController,'V',$currentFunction);
		
        $this->set('selected', Util::criarListaIds($this->request->data['Especialidade']));
        $this->set('selectedUnidades', Util::criarListaIds($this->request->data['UnidadeAtendimento']));
        //render view edit
        $this->render('edit');
    }

    /**
     * Método para carregar a lista de especialidades
     */
    private function carregarListaEspecialidades() {
        $this->loadModel('Especialidade');
        $this->set('especialidades', $this->Especialidade->listarEspecialidades());
    }

    /**
     * Método utilizado para cadastrar um novo Cid no sistema
     */
    public function adicionar() {
        $this->set('unidades', $this->carregarListaUnidades());
        $this->carregarListaEspecialidades();
        $this->set('selected', []);
        $this->set('selectedUnidades', []);

        // pr($this->Cid);die;
        if ($this->request->is('post')) {
            $this->Cid->create();
            // pr($this->request->data);die;
            if ($this->Cid->saveAll($this->request->data)) {

                $id = $this->Cid->id;
                $currentFunction = $this->request->params['action']; //function corrente
                $currentController = $this->name; //Controller corrente
                $this->saveAuditLog($id,$currentController,'I',$currentFunction);


                $this->Session->setFlash(__('objeto_salvo_sucesso', __('Cid')), 'flash_success');
                return $this->tratarAcaoSalvar();
            }
            if (!empty($this->request->data['Especialidade']['Especialidade'])) {
                $this->set('selected', $this->request->data['Especialidade']['Especialidade']);
            }
            if (!empty($this->request->data['UnidadeAtendimento']['UnidadeAtendimento'])) {
                $this->set('selectedUnidades', $this->request->data['UnidadeAtendimento']['UnidadeAtendimento']);
            }
        }
        //render view edit
        $this->render('edit');
    }

    private function carregarListaCid() {
        $this->loadModel('Cid');
        return $this->Cid->listarCids();
        // $this->set('cids', $this->Cid->listarCids());
    }

    private function carregarListaUnidades() {
        $this->loadModel('UnidadeAtendimento');
        return $this->UnidadeAtendimento->listarUnidades();
    }

    /**
     * Método utilizado para editar um Cid previamente cadastrado no sistema
     * @param string $id identificador do Usuário que vai ser editado
     * @throws NotFoundException
     */
    public function editar($id = null) {
        $this->set('unidades', $this->carregarListaUnidades());
        $this->carregarListaEspecialidades();
        $cid = $this->_verificaId($id);
        if ($this->request->is(array('post', 'put'))) {
            $this->Cid->id = $id;
            if ($this->Cid->saveAll($this->request->data)) {


                $currentFunction = $this->request->params['action']; //function corrente
                $currentController = $this->name; //Controller corrente
                $this->saveAuditLog($id,$currentController,'A',$currentFunction);


                $this->Session->setFlash(__('objeto_salvo_sucesso', __('Cid')), 'flash_success');
                return $this->tratarAcaoSalvar();
            }
        }

        if (!$this->request->data) {
            $this->request->data = $cid;
            $this->set('selected', Util::criarListaIds($this->request->data['Especialidade']));
            $this->set('selectedUnidades', Util::criarListaIds($this->request->data['UnidadeAtendimento']));
        } else {
            if (!empty($this->request->data['Especialidade']['Especialidade'])) {
               $this->set('selected', $this->request->data['Especialidade']['Especialidade']);
            } 

            if (!empty($this->request->data['UnidadeAtendimento']['UnidadeAtendimento'])) {
                $this->set('selectedUnidades', $this->request->data['UnidadeAtendimento']['UnidadeAtendimento']);
            }

            else {
                $this->set('selected', []);
                $this->set('selectedUnidades', []);
            }
        }
        //render view edit
        $this->render('edit');
    }

    /**
     * Método para excluir um Cid do sistema
     */
    public function deletar($id = null) {
        if ($this->request->is('get')) {
            $cid = $this->_verificaId($id);
            $this->request->data = $cid;
            $this->carregarListaEspecialidades();
            $this->set('unidades', $this->carregarListaUnidades());
            $this->set('selected', Util::criarListaIds($this->request->data['Especialidade']));
            $this->set('selectedUnidades', Util::criarListaIds($this->request->data['UnidadeAtendimento']));
            //render view edit
            $this->render('edit');
        } else {
            if ($this->Cid->validarExclusao($id)) {
                $this->Session->setFlash(__('validacao_exclusao_cid'), 'flash_alert');
                return $this->redirect(array('action' => 'deletar', $id));
            }
            if ($this->Cid->delete($id)) {


                $currentFunction = $this->request->params['action']; //function corrente
                $currentController = $this->name; //Controller corrente
                $this->saveAuditLog($id,$currentController,'E',$currentFunction);


                $this->Session->setFlash(__('objeto_excluir_sucesso', __('Cid')), 'flash_success');
                return $this->redirect(array('action' => 'index'));
            }
        }
    }

    private function _verificaId($id = null) {
        if (!$id) {
            throw new NotFoundException(__('objeto_invalido', __('Cid')));
        }
        $cid = $this->Cid->findById($id);
        if (!$cid) {
            throw new NotFoundException(__('objeto_invalido', __('Cid')));
        }
        return $cid;
    }

    public function getCidId() {
        $arrayRetorno = array();
        $cid = $this->request->query['term'];
        if ($cid) {
            $this->loadModel('Cid');

            $filtro = new BSFilter();
            $filtro->setTipo('all');
            $filtro->setCamposRetornadosString('Cid.id', 'Cid.nome', '"Cid"."nome"|| \' - \' ||"Cid"."nome_doenca" AS "Cid__descricao"');
            $filtro->setCondicoes(['Cid.nome ILIKE ' => '%' . $cid . '%']);
            $filtro->setCamposOrdenadosString('Cid.nome');
            $arrCids = $this->Cid->listar($filtro);

            foreach ($arrCids as $line) {
                $objTmp = new stdClass();
                $nome = $line['Cid']['nome'];
                $objTmp->id = $line['Cid']['id'];
                $objTmp->descricao = $line['Cid']['descricao'];
                $objTmp->label = $nome;
                $objTmp->value = $nome;
                $arrayRetorno[] = $objTmp;
            }
        }
        echo json_encode($arrayRetorno);
        die;
    }

}
