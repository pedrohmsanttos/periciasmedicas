<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AtendimentoController
 *
 * @author user
 */
// App::uses('BSController', 'Admin.Controller');
App::uses('BSController', 'Web.Controller');

class GerenciamentoSalaController extends BSController {

    /**
     * Retorna as salas cadastradas hoje
     */
    public function consultar() {
        $this->layout = 'ajax';
        if ($this->request->is('GET')) {
            if(isset($this->request->query['limitConsulta'])){
                $limitConsulta = $this->request->query['limitConsulta'];
            }else{
                $limitConsulta = 10;
            }
            
            $unidade = $this->request->query['unidade'];

            $list = array();
            if ($unidade) {
                $filtro = new BSFilter();
                $filtro->setLimiteConsulta($limitConsulta);

                $condicoes = array();
                $condicoes['GerenciamentoSala.unidade_atendimento_id'] = $unidade;
                $filtro->setCondicoes($condicoes);

                $filtro->setCamposOrdenados(['GerenciamentoSala.sala' => 'asc']);
                $list = $this->paginar($filtro);

                $this->set('gerenciarSalas', $list);
            }
			
			
			
			 
                $currentFunction = $this->request->params['action']; //function corrente
                $currentController = $this->name; //Controller corrente
                $this->saveAuditLog(null,$currentController,'C',$currentFunction);
			

            $this->set('limiteConsultaSelecionado', $limitConsulta);
        }
    }

    /**
     * Metodo responsavel para listar a consulta
     */
    public function adicionar() {
        $this->layout = 'ajax';
        $retorno = false;
        if ($this->request->is('post')) {
            $this->validateGerenciarSalas();
            if ($this->GerenciamentoSala->save($this->request->data)) {

                $id = $this->GerenciamentoSala->id;
                $currentFunction = $this->request->params['action']; //function corrente
                $currentController = $this->name; //Controller corrente
                $this->saveAuditLog($id,$currentController,'I',$currentFunction);



                $retorno = true;
            } else {
                $retorno = false;
            }
        }
        echo json_encode($retorno);
        exit();
    }

    /**
     * Valida Gerenciar Salas
     */
    public function validateGerenciarSalas() {
        if ($this->request->is('post')) {
            $this->GerenciamentoSala->set($this->request->data);
            $validacoes = array();
            if (!$this->GerenciamentoSala->validates()) {
                $validacoesGerencarSalas = $this->GerenciamentoSala->validationErrors;
                array_push($validacoes, $validacoesGerencarSalas);
                $this->tratarValidacoes($validacoes);
            }
        }
    }

    /**
     * Método para tratar as mensagens de validação do cadastro de usuário
     * @param unknown $validacoces
     */
    public function tratarValidacoes($validacoces) {
        if (!empty($validacoces)) {
            $this->layout = null;
            $data = array();
            foreach ($validacoces as $key => $field) {
                foreach ($field as $key => $erro) {
                    $erros[] = $erro;
                }
            }

            if (!empty($validacoces)) {
                $data = Array(
                    "status" => "danger",
                    "message" => compact('message', 'erros')
                );
            }

            $this->set('data', $data);
            $this->render('/General/SerializeJson/');
            $this->renderView();
        }
    }

    /**
     * Get CPF do Servidor
     */
    public function getCpfServidor() {
        $this->layout = 'ajax';
        if ($this->request->is(['get', 'post'])) {
            $cpf = Util::limpaDocumentos($this->request->query['term']);
            $unidadeAtendimento = $this->request->query['unidade'];

            $this->loadModel('Usuario');
            $filtro = new BSFilter();
            $filtro->setTipo('all');
            $filtro->setCamposRetornados(['Usuario.id', 'Usuario.nome', 'Usuario.cpf', 'Usuario.numero_registro']);
            $filtro->setCamposOrdenadosString('Usuario.nome');

            $joins[] = array(
                'table' => 'agenda_atendimento',
                'alias' => 'AgendaAtendimento',
                'type' => 'left',
                'conditions' => array('AgendaAtendimento.usuario_id = Usuario.id')
            );

            $filtro->setJoins($joins);

            $condicoes['Usuario.cpf ILIKE '] = '%' . $cpf . '%';
            $condicoes['Usuario.tipo_usuario_id'] = [USUARIO_PERITO_CREDENCIADO, USUARIO_PERITO_SERVIDOR, USUARIO_SERVIDOR];
            $condicoes['AgendaAtendimento.unidade_atendimento_id'] = $unidadeAtendimento;

            $filtro->setCondicoes($condicoes);
            $filtro->setCamposAgrupadosString('"Usuario"."id", "Usuario"."nome", "Usuario"."cpf", "Usuario"."numero_registro"');

            $arrUsuario = $this->Usuario->listar($filtro);
            $arrayRetorno = array();
            foreach ($arrUsuario as $key => $line) {
                $cpfTmp = Util::mask($line['Usuario']['cpf'], '###.###.###-##');
                $objTmp = new stdClass();
                $objTmp->id = $line['Usuario']['id'];
                $objTmp->nome = $line['Usuario']['nome'];
                $objTmp->numeroRegistro = $line['Usuario']['numero_registro'];
                $objTmp->label = $cpfTmp;
                $objTmp->value = $cpfTmp;
                $arrayRetorno[] = $objTmp;
            }

            echo json_encode($arrayRetorno);
            exit;
        }
    }

    public function editar($id = null) {
        $this->layout = 'ajax';
        if ($this->request->is(array('post', 'put'))) {
            $id = $this->request->data['usuario_perito_id'];
            if ($id) {
                $gerenciarSala = array_values($this->GerenciamentoSala->get(['GerenciamentoSala.usuario_perito_id' => $id]));
                $idSala = $gerenciarSala[0];
                $this->GerenciamentoSala->id = $idSala;
                $this->request->data['id'] = $idSala;
                if ($this->GerenciamentoSala->save($this->request->data, ['validate' => false])) {

                    $currentFunction = $this->request->params['action']; //function corrente
                    $currentController = $this->name; //Controller corrente
                    $this->saveAuditLog($id,$currentController,'A',$currentFunction);


                    echo json_encode(true);
                    exit();
                }
            }
        }
        echo json_encode(false);
        exit();
    }

    /**
     * Get Número de Registro do Servidor
     */
    public function getNumRegistroServidor() {
        $this->layout = 'ajax';
        if ($this->request->is(['get', 'post'])) {
            $numRegistro = Util::limpaDocumentos($this->request->query['term']);
            $unidadeAtendimento = $this->request->query['unidade'];

            $this->loadModel('Usuario');
            $filtro = new BSFilter();
            $filtro->setTipo('all');
            $filtro->setCamposRetornados(['Usuario.id', 'Usuario.nome', 'Usuario.cpf', 'Usuario.numero_registro']);
            $filtro->setCamposOrdenadosString('Usuario.nome');

            $joins[] = array(
                'table' => 'agenda_atendimento',
                'alias' => 'AgendaAtendimento',
                'type' => 'left',
                'conditions' => array('AgendaAtendimento.usuario_id = Usuario.id')
            );

            $filtro->setJoins($joins);

            $condicoes['Usuario.numero_registro ILIKE '] = '%' . $numRegistro . '%';
            $condicoes['Usuario.tipo_usuario_id'] = [USUARIO_PERITO_CREDENCIADO, USUARIO_PERITO_SERVIDOR];
            $condicoes['AgendaAtendimento.unidade_atendimento_id'] = $unidadeAtendimento;

            $filtro->setCondicoes($condicoes);
            $filtro->setCamposAgrupadosString('"Usuario"."id", "Usuario"."nome", "Usuario"."cpf", "Usuario"."numero_registro"');

            $arrUsuario = $this->Usuario->listar($filtro);
            $arrayRetorno = array();
            foreach ($arrUsuario as $key => $line) {
                $cpfTmp = Util::mask($line['Usuario']['cpf'], '###.###.###-##');
                $objTmp = new stdClass();
                $objTmp->id = $line['Usuario']['id'];
                $objTmp->nome = $line['Usuario']['nome'];
                $objTmp->cpf = $cpfTmp;
                $objTmp->label = $line['Usuario']['numero_registro'];
                $objTmp->value = $line['Usuario']['numero_registro'];
                $arrayRetorno[] = $objTmp;
            }

            echo json_encode($arrayRetorno);
            exit;
        }
    }

    /**
     * Get Nome do Servidor
     */
    public function getNomeServidor() {
        $this->layout = 'ajax';
        if ($this->request->is(['get', 'post'])) {
            $nome = $this->request->query['term'];
            $unidadeAtendimento = $this->request->query['unidade'];

            $this->loadModel('Usuario');
            $filtro = new BSFilter();
            $filtro->setTipo('all');
            $filtro->setCamposRetornados(['Usuario.id', 'Usuario.nome', 'Usuario.cpf', 'Usuario.numero_registro']);
            $filtro->setCamposOrdenadosString('Usuario.nome');

            $joins[] = array(
                'table' => 'agenda_atendimento',
                'alias' => 'AgendaAtendimento',
                'type' => 'left',
                'conditions' => array('AgendaAtendimento.usuario_id = Usuario.id')
            );

            $filtro->setJoins($joins);

            $condicoes['Usuario.nome ILIKE '] = '%' . $nome . '%';
            $condicoes['Usuario.tipo_usuario_id'] = [USUARIO_PERITO_CREDENCIADO, USUARIO_PERITO_SERVIDOR, USUARIO_SERVIDOR];
            $condicoes['AgendaAtendimento.unidade_atendimento_id'] = $unidadeAtendimento;

            $filtro->setCondicoes($condicoes);
            $filtro->setCamposAgrupadosString('"Usuario"."id", "Usuario"."nome", "Usuario"."cpf", "Usuario"."numero_registro"');

            $arrUsuario = $this->Usuario->listar($filtro);
            $arrayRetorno = array();
            foreach ($arrUsuario as $key => $line) {
                $objTmp = new stdClass();
                $objTmp->id = $line['Usuario']['id'];
                $objTmp->cpf = $line['Usuario']['cpf'];
                $objTmp->numeroRegistro = $line['Usuario']['numero_registro'];
                $objTmp->label = $line['Usuario']['nome'];
                $objTmp->value = $line['Usuario']['nome'];
                $arrayRetorno[] = $objTmp;
            }

            echo json_encode($arrayRetorno);
            exit;
        }
    }

    public function getTipologias() {
        $this->layout = 'ajax';
        $arrayReturn = array();
        if ($this->request->is(['get', 'post'])) {
            $idUsuario = $this->request->data['perito'];

            $this->loadModel('Tipologia');
            $filtro = new BSFilter();

            $joins = array();
            $joins[] = array(
                'table' => 'usuario_tipologia',
                'alias' => 'UsuarioTipologia',
                'type' => 'inner',
                'conditions' => array('UsuarioTipologia.tipologia_id = Tipologia.id')
            );

            $joins[] = array(
                'table' => 'usuario',
                'alias' => 'Usuario',
                'type' => 'inner',
                'conditions' => array('Usuario.id = UsuarioTipologia.usuario_id')
            );
            $filtro->setJoins($joins);

            $condicoes = array();
            $condicoes['Usuario.id'] = $idUsuario;
            $filtro->setCondicoes($condicoes);

            $filtro->setCamposRetornadosString('Tipologia.id', 'Tipologia.nome');
            $filtro->setCamposOrdenadosString('Tipologia.nome');
            $tipologias = $this->Tipologia->listar($filtro);
            $arrayReturn = $tipologias;
        }
        echo json_encode($arrayReturn);
        exit;
    }

    /**
     * Deletando Sala.
     */
    public function validarDisponibilidadeSala() {
        if ($this->request->is('post')) {
            $this->loadModel('GerenciamentoSala');
            $filtro = new BSFilter();
            $filtro->setTipo('count');
            $condicoes = array();
            $condicoes['GerenciamentoSala.sala'] = $this->request->data['sala'];
            $filtro->setCondicoes($condicoes);
            $gerenciamentoSala = $this->GerenciamentoSala->listar($filtro);
        }
        echo json_encode($gerenciamentoSala > 0);
        exit();
    }

    /**
     * Deletação de salas antigas de peritos alocados a novas salas
     */
    private function deletarSalasAntigasPerito() {
        if (isset($this->request->data['usuario_perito_id'])) {
            $filtro = new BSFilter();
            $filtro->setTipo('all');
            $condicoes = array();
            $condicoes['GerenciamentoSala.usuario_perito_id'] = $this->request->data['usuario_perito_id'];
            $condicoes['GerenciamentoSala.sala !='] = $this->request->data['sala'];
            $filtro->setCondicoes($condicoes);
            $gerenciamentoSala = ($this->GerenciamentoSala->listar($filtro)) ? $this->GerenciamentoSala->listar($filtro)[0] : [];
            if (isset($gerenciamentoSala['GerenciamentoSala']['id'])) {
                $this->GerenciamentoSala->delete($gerenciamentoSala['GerenciamentoSala']['id']);

                $id = $gerenciamentoSala['GerenciamentoSala']['id'];
                $currentFunction = $this->request->params['action']; //function corrente
                $currentController = $this->name; //Controller corrente
                $this->saveAuditLog($id,$currentController,'E',$currentFunction);

            }
        }
    }

    /**
     * Realocar Sala
     */
    public function realocarEmSala() {
        if ($this->request->is('post')) {

            //Verifica se o Perito já está alocado a outra sala e o exclui de outra sala.
            $this->deletarSalasAntigasPerito();

            $this->loadModel('GerenciamentoSala');
            $filtro = new BSFilter();
            $filtro->setTipo('all');
            $condicoes = array();
            $condicoes['GerenciamentoSala.sala'] = $this->request->data['sala'];
            $filtro->setCondicoes($condicoes);
            $gerenciamentoSala = ($this->GerenciamentoSala->listar($filtro)) ? $this->GerenciamentoSala->listar($filtro)[0] : [];
            if ($gerenciamentoSala) {
                $idSala = $gerenciamentoSala['GerenciamentoSala']['id'];
                $this->GerenciamentoSala->id = $idSala;
                $this->request->data['id'] = $idSala;
                if ($this->GerenciamentoSala->save($this->request->data, ['validate' => false])) {


                    $id = $gerenciamentoSala['GerenciamentoSala']['id'];
                    $currentFunction = $this->request->params['action']; //function corrente
                    $currentController = $this->name; //Controller corrente
                    $this->saveAuditLog($id,$currentController,'A',$currentFunction);

                    echo json_encode(true);
                    exit();
                }
            }
        }
    }

    public function validarDisponibilidadePerito() {
        if ($this->request->is('post')) {
            $this->loadModel('GerenciamentoSala');
            $filtro = new BSFilter();
            $filtro->setTipo('count');
            $condicoes = array();
            $condicoes['GerenciamentoSala.usuario_perito_id'] = $this->request->data['usuario_perito_id'];
            $filtro->setCondicoes($condicoes);
            $gerenciamentoSala = $this->GerenciamentoSala->listar($filtro);
        }
        echo json_encode($gerenciamentoSala > 0);
        exit();
    }

    public function realocarPeritoEmSala() {
        if ($this->request->is('post')) {

            $this->loadModel('GerenciamentoSala');
            $filtro = new BSFilter();
            $filtro->setTipo('all');
            $condicoes = array();
            $condicoes['GerenciamentoSala.usuario_perito_id'] = $this->request->data['usuario_perito_id'];
            $filtro->setCondicoes($condicoes);
            $gerenciamentoSala = ($this->GerenciamentoSala->listar($filtro)) ? $this->GerenciamentoSala->listar($filtro)[0] : [];
            if ($gerenciamentoSala) {
                $idSala = $gerenciamentoSala['GerenciamentoSala']['id'];
                $this->GerenciamentoSala->id = $idSala;
                $this->request->data['id'] = $idSala;
                if ($this->GerenciamentoSala->save($this->request->data, ['validate' => false])) {

                    $id = $gerenciamentoSala['GerenciamentoSala']['id'];
                    $currentFunction = $this->request->params['action']; //function corrente
                    $currentController = $this->name; //Controller corrente
                    $this->saveAuditLog($id,$currentController,'A',$currentFunction);


                    echo json_encode(true);
                    exit();
                }
            }
        }
    }

    /**
     * Método para excluir uma Sala
     */
    public function deletar($id) {
        $this->layout = 'ajax';
        if ($this->request->is('get')) {
            if (!$id) {
                echo json_encode(false);
                exit();
            }

            $gerenciamentoSala = $this->GerenciamentoSala->findById($id);
            if (!$gerenciamentoSala) {
                echo json_encode(false);
                exit();
            }

            if ($this->GerenciamentoSala->delete($id)) {

                $currentFunction = $this->request->params['action']; //function corrente
                $currentController = $this->name; //Controller corrente
                $this->saveAuditLog($id,$currentController,'A',$currentFunction);



                echo json_encode(true);
                exit();
            }
        }
    }

}
