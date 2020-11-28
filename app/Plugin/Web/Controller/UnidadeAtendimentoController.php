<?php

// App::import("Plugin/Admin/Controller", "BSController");
App::import("Plugin/Web/Controller", "BSController");

class UnidadeAtendimentoController extends BSController
{

    /**
     * Método utilizado para exibir a listagem inicial de UnidadeAtendimento cadastrados
     */
    public function index()
    {
        $this->carregarListasMunicipiosUnidade();
    }

    /**
     * Método responsável por realizar a filtragem de unidadeAtendimentos do sistema
     */
    public function consultar()
    {
        $this->layout = 'ajax';
        if ($this->request->is('GET')) {

            $currentFunction = $this->request->params['action']; //function corrente
            $currentController = $this->name; //Controller corrente
            $this->saveAuditLog(null, $currentController, 'C', $currentFunction);

            $limitConsulta = $this->request->query['data']['UnidadeAtendimento']['limitConsulta'];
            $nome = $this->request->query['data']['UnidadeAtendimento']['nome'];
            $cnpj = $this->request->query['data']['UnidadeAtendimento']['cnpj'];
            $responsavel = $this->request->query['data']['UnidadeAtendimento']['nome_responsavel'];
            $municipioId = $this->request->query['data']['Endereco']['municipio_id'];

            $condicoes = null;

            if (!empty($nome)) {
                $condicoes['UnidadeAtendimento.nome ILIKE '] = "%$nome%";
            }

            if (!empty($cnpj)) {
                $cnpj = Util::limpaDocumentos($cnpj);
                $condicoes['UnidadeAtendimento.cnpj'] = "$cnpj";
            }

            if (!empty($responsavel)) {
                $condicoes['usuario.nome'] = "$responsavel";
            }

            if (!empty($municipioId)) {
                $condicoes['Endereco.municipio_id'] = "$municipioId";
            }

            $joins = array();
            $joins[] = array(
                'table' => 'usuario',
                'alias' => 'usuario',
                'type' => 'left',
                'conditions' => array('usuario.id = UnidadeAtendimento.responsavel_id')
            );

            $filtro = new BSFilter();
            $filtro->setJoins($joins);
            $filtro->setCondicoes($condicoes);
            $filtro->setLimiteConsulta($limitConsulta);
            $filtro->setCamposRetornadosString("id", "nome");
            $filtro->setCamposOrdenados(['UnidadeAtendimento.nome' => 'asc']);

            $this->loadModel('UnidadeAtendimento');
            $this->UnidadeAtendimento->unbindModel(
                array('hasAndBelongsToMany' => array('Cid'))
            );

            $this->set('unidadeAtendimento', $this->paginar($filtro));
            $this->set('limiteConsultaSelecionado', $limitConsulta);

        }
    }

    public function carregarDadosResponsavelData($unidade)
    {
        if (isset($this->request->data['UnidadeAtendimento']['responsavel_id']) && $this->request->data['UnidadeAtendimento']['responsavel_id']) {
            $this->request->data['UnidadeAtendimento']['nome_responsavel'] = $unidade['Usuario']['nome'];
            $this->request->data['UnidadeAtendimento']['telefone_trabalho'] = $unidade['Usuario']['telefone_trabalho'];
        }
    }

    public function getNomeResponsavel()
    {
        $this->layout = 'ajax';
        if ($this->request->is(['get', 'post'])) {
            $nome = $this->request->query['term'];

            $this->loadModel('Usuario');
            $filtro = new BSFilter();
            $filtro->setTipo('all');
            $filtro->setCamposRetornados(['Usuario.id', 'Usuario.nome', 'Usuario.telefone_trabalho']);
            $filtro->setCamposOrdenadosString('Usuario.nome');

            $condicoes['Usuario.nome ILIKE '] = '%' . $nome . '%';

            $filtro->setCondicoes($condicoes);

            $arrUsuario = $this->Usuario->listar($filtro);
            $arrayRetorno = array();
            foreach ($arrUsuario as $key => $line) {
                $objTmp = new stdClass();
                $nome = $line['Usuario']['nome'];
                $objTmp->id = $line['Usuario']['id'];
                $objTmp->nome = $line['Usuario']['nome'];
                $objTmp->telefone = $line['Usuario']['telefone_trabalho'];
                $objTmp->label = $nome;
                $objTmp->value = $nome;
                $arrayRetorno[] = $objTmp;
            }

            echo json_encode($arrayRetorno);
            die;
        }
    }

    /**
     * Método utilizado para visualizar um UnidadeAtendimento
     * @param string $id identificador do UnidadeAtendimento
     */
    public function visualizar($id = null)
    {
        //$this->carregarListaCid();
        $this->carregarListasMunicipiosUnidade();
        $unidadeAtendimento = $this->_verificaId($id);
        $this->request->data = $unidadeAtendimento;
        $this->carregarDadosResponsavelData($unidadeAtendimento);
        $this->carregarListasEstados();
        $this->carregarListasMunicipios();

        $this->set('hasAllCids', intval($this->UnidadeAtendimento->hasAllCids($id)));


        $currentFunction = $this->request->params['action']; //function corrente
        $currentController = $this->name; //Controller corrente
        $this->saveAuditLog($id, $currentController, 'V', $currentFunction);

        /*
        $arrCidsSelecionados = ($unidadeAtendimento['Cid']);
        $list = array();
        foreach($arrCidsSelecionados as $key => $item){
            $list[]=array( $item['id'] => $item['nome']);
        }
        $this->set('cidsSelecionados', $list);
        */

        //render view edit
        $this->render('edit');
    }

    private function carregarListasMunicipiosUnidade()
    {
        $this->loadModel('Municipio');
        $this->set('municipiosUnidade', $this->Municipio->listarMunicipiosUF(17));
    }

    /**
     * Método para carregar a lista de especialidades
     */
    private function carregarListaCid()
    {
        $this->loadModel('Cid');
        $this->set('cids', $this->Cid->listarCids());
    }

    private function carregarListasMunicipios()
    {
        if (isset($this->request->data['Endereco'])) {
            $estado = $this->request->data['Endereco']['estado_id'];
            $this->loadModel('Municipio');

            $this->set('municipios', $this->Municipio->listarMunicipiosUF($estado));
        }
    }

    private function carregarListasEstados()
    {
        $this->loadModel('Estado');
        $this->set('estados', $this->Estado->listarEstados());
    }

    /**
     * Método utilizado para cadastrar um novo UnidadeAtendimento no sistema
     */
    public function adicionar()
    {
        // $this->carregarListaCid();
        $this->carregarListasMunicipiosUnidade();
        if ($this->request->is('post')) {
            $this->UnidadeAtendimento->create();
            //pr($this->request->data)
            if ($this->UnidadeAtendimento->saveAll($this->request->data)) {
                $id = $this->UnidadeAtendimento->id;
                if ($this->request->data['UnidadeAtendimento']['associar_cids']) {
                    $this->UnidadeAtendimento->updateUnidadeAllCids($id);
                }
                $currentFunction = $this->request->params['action']; //function corrente
                $currentController = $this->name; //Controller corrente
                $this->saveAuditLog($id, $currentController, 'I', $currentFunction);


                $this->Session->setFlash(__('entidade_salva_sucesso', __('UnidadeAtendimento')), 'flash_success');
                return $this->tratarAcaoSalvar();
            } else {


                //             $arrCidsSelecionados = $this->request->data['Cid']['Cid'];

                //           if(isset($arrCidsSelecionados) &&  count($arrCidsSelecionados) > 0){


                //             $list = array();
                //             $this->loadModel('Cid');
                //             $filtro = new BSFilter();
                //             $filtro->setTipo('all');

                //             $filtro->setCamposOrdenadosString('Cid.nome');
                // if( count($arrCidsSelecionados) == 1){
                // 	 $condicoes['Cid.id'] = $arrCidsSelecionados;
                // }else{
                // 	 $condicoes['Cid.id in '] = $arrCidsSelecionados;
                // }


                //             $filtro->setCondicoes($condicoes);
                //             $arrCid = $this->Cid->listar($filtro);

                //            foreach ($arrCid as $key => $line) {
                //                 $list[] = array($line['Cid']['id'] => $line['Cid']['nome']);
                //             }
                //             $this->set('cidsSelecionados', $list);

                //           }
            }
        }

        $this->carregarListasMunicipios();
        $this->carregarListasEstados();
        //render view edit
        $this->render('edit');
    }

    /**
     * Método utilizado para editar um UnidadeAtendimento previamente cadastrado no sistema
     * @param string $id identificador do Usuário que vai ser editado
     * @throws NotFoundException
     */
    public function editar($id = null)
    {
        // $this->carregarListaCid();
        $this->carregarListasMunicipiosUnidade();

        $this->set('hasAllCids', intval($this->UnidadeAtendimento->hasAllCids($id)));

        $unidadeAtendimento = $this->_verificaId($id);
        //var_dump($unidadeAtendimento['Cid']);
        if ($this->request->is(array('post', 'put'))) {
            $this->UnidadeAtendimento->id = $id;
            if ($this->UnidadeAtendimento->saveAll($this->request->data)) {
                if ($this->request->data['UnidadeAtendimento']['associar_cids']) {
                    $this->UnidadeAtendimento->updateUnidadeAllCids($id);
                }

                $id = $this->UnidadeAtendimento->id;
                $currentFunction = $this->request->params['action']; //function corrente
                $currentController = $this->name; //Controller corrente
                $this->saveAuditLog($id, $currentController, 'A', $currentFunction);

                $this->Session->setFlash(__('entidade_salva_sucesso', __('UnidadeAtendimento')), 'flash_success');
                return $this->tratarAcaoSalvar();
            }
        }

        if (!$this->request->data) {
            $this->request->data = $unidadeAtendimento;
            $this->carregarDadosResponsavelData($unidadeAtendimento);
        }

        $this->carregarListasMunicipios();
        $this->carregarListasEstados();
        //render view edit

        /*
        $arrCidsSelecionados = ($unidadeAtendimento['Cid']);
        $list = array();
        foreach($arrCidsSelecionados as $key => $item){
           $list[]=array( $item['id'] => $item['nome']);
        }
        $this->set('cidsSelecionados', $list);
        */


        $this->render('edit');
    }

    /**
     * Método para excluir um UnidadeAtendimento do sistema
     */
    public function deletar($id = null)
    {
        if ($this->request->is('get')) {
            // $this->carregarListaCid();
            $this->carregarListasMunicipiosUnidade();

            $unidadeAtendimento = $this->_verificaId($id);

            $this->request->data = $unidadeAtendimento;
            $this->carregarDadosResponsavelData($unidadeAtendimento);
            $this->carregarListasMunicipios();
            $this->carregarListasEstados();
            //render view edit
            $arrCidsSelecionados = ($unidadeAtendimento['Cid']);
            $list = array();
            foreach ($arrCidsSelecionados as $key => $item) {
                $list[] = array($item['id'] => $item['nome']);
            }
            $this->set('cidsSelecionados', $list);

            $this->render('edit');
        } else {
            if ($this->UnidadeAtendimento->validarExclusao($id)) {
                $this->Session->setFlash(__('validacao_exclusao_unidade_atendimento'), 'flash_alert');
                return $this->redirect(array('action' => 'deletar', $id));
            }
            if ($this->UnidadeAtendimento->delete($id)) {

                $currentFunction = $this->request->params['action']; //function corrente
                $currentController = $this->name; //Controller corrente
                $this->saveAuditLog($id, $currentController, 'E', $currentFunction);

                $this->Session->setFlash(__('entidade_excluir_sucesso', __('UnidadeAtendimento')), 'flash_success');
                return $this->redirect(array('action' => 'index'));
            }
        }
    }

    private function _verificaId($id = null)
    {
        if (!$id) {
            throw new NotFoundException(__('objeto_invalido', __('UnidadeAtendimento')));
        }
        $unidadeAtendimento = $this->UnidadeAtendimento->findById($id);

        if (!$unidadeAtendimento) {
            throw new NotFoundException(__('objeto_invalido', __('UnidadeAtendimento')));
        }
        return $unidadeAtendimento;
    }


    public function getCid()
    {
        $this->layout = 'ajax';

        if ($this->request->is(['get', 'post'])) {


            $this->loadModel('Cid');
            $filtro = new BSFilter();
            $filtro->setTipo('all');
            $filtro->setCamposOrdenadosString('Cid.nome');


            $arrCid = $this->Cid->listar($filtro);

            $arrayRetorno = array();
            $search = preg_quote(isset($this->request->query['search']) ? $this->request->query['search'] : '');
            $start = (isset($this->request->query['start']) ? $this->request->query['start'] : 1);

            foreach ($arrCid as $key => $line) {
                //  echo "cid::".$line['Cid']['nome']."<br>\n";
                if (preg_match('/' . ($start ? '^' : '') . $search . '/i', $line['Cid']['nome'])) {
                    $arrayRetorno[] = array('value' => $line['Cid']['id'], 'text' => $line['Cid']['nome']);
                }
            }
            echo json_encode($arrayRetorno);
            die;
        }

    }

}
