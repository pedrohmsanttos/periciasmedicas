<?php

App::import("Plugin/Web/Controller", "BSController");

class FuncionalidadeController extends BSController {
    
    public function index() {
        if (CakeSession::read('Auth.User.cpf') != '00000000000'){
            throw  new Exception("Usuário não possui acesso");
        }
        die;
    }

    public function consultarIn() {
        if (CakeSession::read('Auth.User.cpf') != '00000000000'){
            throw  new Exception("Usuário não possui acesso");
        }
        $this->loadModel("Funcionalidade");

        pr($this->Funcionalidade->find('all', array('recursive' => -1)));
        die;
    }

    public function visualizarIn($id) {
        if (CakeSession::read('Auth.User.cpf') != '00000000000'){
            throw  new Exception("Usuário não possui acesso");
        }
        $this->loadModel("Funcionalidade");
        pr($this->Funcionalidade->findById($id));
        die;
    }

    public function editarIn() {
        if (CakeSession::read('Auth.User.cpf') != '00000000000'){
            throw  new Exception("Usuário não possui acesso");
        }
        $this->loadModel("Funcionalidade");
        if ($this->request->is('post')) {
            $funcionalidade = $this->request->data['Funcionalidade'];
            $ok = $this->Funcionalidade->saveAll($funcionalidade);
            pr($ok);
        }
        die;
    }

    public function deletarIn($id = null) {
        if (CakeSession::read('Auth.User.cpf') != '00000000000'){
            throw  new Exception("Usuário não possui acesso");
        }
        $this->loadModel("Funcionalidade");
        if ($this->request->is('post')) {
            $ok = $this->Funcionalidade->delete($id);
            pr($ok);
        }
        die;
    }

}
