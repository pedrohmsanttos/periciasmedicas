<?php

// App::uses('BSController', 'Admin.Controller');
App::uses('BSController', 'Web.Controller');

class MunicipioController extends BSController {

    public function listarMunicipiosEstado() {
        $estado = $this->request->query['estado_id'];

        $filtro = new BSFilter();
        $filtro->setCamposRetornadosString('id', 'nome');
        $condicoes['Municipio.estado_id'] = $estado;
        $filtro->setCondicoes($condicoes);
        $municipios = $this->Municipio->listar($filtro);

        header('Content-Type: application/json');
        echo json_encode($municipios);
        die();
    }

}
