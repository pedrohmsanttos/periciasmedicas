<?php
App::uses('BSFilter', 'Model');
App::uses('AuditableConfig', 'Auditable.Lib');

App::import("Plugin/Web/Lib", "Util");

App::import("Plugin/Web/Controller", "BSController");

class WebserviceController extends BSController {

    /*
     * mf = main field :: field to where
     * fk = foreign key :: field pointing to another table id
     * n_m = relation n x m
     */

    private $CAMPOS_USUARIO = array(
        'mf' => array( 13 => 'cpf'),
        'fk' => array('endereco' => 'endereco_id'),
        'dates' => array('data_nascimento', 'data_admissao_pericia', 'data_obito'),
        'fields' => array(
            0 => 'nome',
            6 => 'data_nascimento',
            7 => 'estado_civil_id',
            8 => 'sexo_id',
            9 => 'data_admissao_pericia',
            10 => 'telefone',
            11 => 'telefone_trabalho',
            12 => 'telefone_celular',
            13 => 'cpf',
            14 => 'rg',
            15 => 'orgao_expedidor',
            16 => 'email',
            26 => 'data_obito',
            27 => 'aposentado'
        )
    ) ;

    //unique by user
    private $CAMPOS_ENDERECO = array(
        'fields' => array(
            19 => 'logradouro',
            20 => 'complemento',
            21 => 'numero',
            22 => 'bairro',
            23 => 'estado_id', // find(sigla)
            24 => 'municipio_id', //find(nome)
            25 => 'cep'
        )
    );

    //find orgao_origem(orga_origem) ? get_id   : insert=>get_id
    private $CAMPOS_ORGAO_ORIGEM = array(
        'mf' => array(1=> 'orgao_origem'),
        'fields' => array(
            1 => 'orgao_origem'
        )
    );

    //find funcao(*) ? update=>get_id   : insert=>get_id
    private $CAMPOS_FUNCAO = array(
        'fields' => array(
            4 => 'codigo_funcao_sad',
            5 => 'nome'  //UPPER
        )
    );

    //find cargo(*) ? update=>get_id : insert => get_id
    private $CAMPOS_CARGO = array(
        'fields' => array(
            2 => 'codigo_cargo_sad',
            3 => 'nome' //UPPER
        )
    );

    //find vinculo(data_admissao_servidor, matricula, fk)
    private $CAMPOS_VINCULO = array(
        'dates'=> array('data_admissao_servidor'),
        'fk' => array(
            'usuario' => 'usuario_id',
            'orgao'=> 'orgao_origem_id',
            'cargo' => 'cargo_id'),
        'n_m' => array('vinculo_funcao' => 'funcao'),
        'fields' => array(
            9 => 'data_admissao_servidor',
            17 => 'matricula'
        ),
        'extra' => array(
            0 => 'nome'
        )
    );

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow();
    }

    public function index() {
        $this->layout = 'ajax';
        die;
    }

    public function importSAD(){
        echo "<pre>";
        $this->layout = 'ajax';
        $arrFile = glob("../../sad_files/*.{txt,csv}",GLOB_BRACE);

        foreach($arrFile as $file){
            $handler = fopen($file, 'r');
            fgets($handler); // descartar a primeira linha
            while($line = fgets($handler)){
                if(!empty($line))$this->importLineSAD($line);
            }
            $pathInfo = pathinfo($file);
            $realDir = realpath($pathInfo['dirname']);
            $realFile = realpath($file);
            fclose($handler);
            rename($realFile, $realDir.'\\ok\\'.$pathInfo['filename'].'-'.uniqid().'.ok');
        }
        echo "</pre>";
        die;
    }

    private function importLineSAD($line){

        $arrItens = explode(";",$line);
        $arrItens = array_map("trim", $arrItens);

        $usuarioId = $this->importUsuario($arrItens);
        $orgaoOrigemId = $this->importOrgaoOrigem($arrItens);
        $funcaoId = $this->importFuncao($arrItens);
        $cargoId = $this->importCargo($arrItens);
        $fks = array('usuario_id'=>$usuarioId, 'orgao_origem_id' => $orgaoOrigemId, 'cargo_id' => $cargoId);
        $vinculoId = $this->importVinculo($arrItens, $fks);

        $isOk = $this->importVinculoFuncao($vinculoId, $funcaoId);

        echo "$usuarioId, $orgaoOrigemId, $funcaoId, $cargoId, $vinculoId, $isOk
";
        ob_flush();flush();
    }

    private function importUsuario($arrItens){
        $this->loadModel('Usuario');
        $this->loadModel('EstadoCivil');
        $this->loadModel('EnderecoSimples');

        $arrToInsUp = array();
        $arrFields = $this->CAMPOS_USUARIO['fields'];
        $arrMF = $this->CAMPOS_USUARIO['mf'];

        foreach($arrFields as  $key => $field){
            $value = $arrItens[$key];
            if(in_array($field, $this->CAMPOS_USUARIO['dates'])){
                $value = $this->formatDate($value);
            }else {
                switch($field) {
                    case 'aposentado':
                        $value = !!intval($value);  //if has value them return 1
                        break;
                    case 'sexo_id':
                        $value = $this->getSexoID($value);
                        break;
                    case 'estado_civil_id':
                        $value = $this->EstadoCivil->getEstadoCivilId($value);
                        break;
                }

            }
            $arrToInsUp[$field] = $value;
        }
        reset($arrMF);
        $user = $this->Usuario->getUsuario($arrItens[key($arrMF)]);
        if(!$user){ //novo usuario
            $user = $arrToInsUp;
        }else{
            $user = array_merge($user, $arrToInsUp);
        }
        $endereco_id = '';
        if(isset($user['endereco_id']) && intval($user['endereco_id']) > 0){
            $endereco_id = $user['endereco_id'];
        }
        $endereco_id = $this->importEndereco($arrItens, $endereco_id);
        $user['endereco_id'] = $endereco_id;
        $this->Usuario->save($user);
        $id = $this->Usuario->id;
        $this->Usuario->clear();
        return $id;
    }
    private function importEndereco($arrItens, $id = ''){
        $this->loadModel('EnderecoSimples');
        $this->loadModel('Estado');
        $this->loadModel('Municipio');

        $endereco = array();
        if($id){
            $endereco = $this->EnderecoSimples->getEndereco($id);
        }
        $arrToInsUp = array();
        $arrFields = $this->CAMPOS_ENDERECO['fields'];
        foreach($arrFields as  $key => $field) {
            $value = $arrItens[$key];
            switch($field){
                case 'estado_id':
                    $value =  $this->Estado->getEstadoId($value);
                    break;
                case 'municipio_id':
                    $value = $this->Municipio->getMunicipioId($value);
                    break;
            }
            $arrToInsUp[$field] = $value;
        }
        $endereco = array_merge($endereco, $arrToInsUp);
        $this->EnderecoSimples->save($endereco);
        $id = $this->EnderecoSimples->id;
        $this->EnderecoSimples->clear();
        return $id;
    }

    private function importOrgaoOrigem($arrItens){
        $this->loadModel('OrgaoOrigemSimples');
        $arrToInsUp = array();
        $arrFields = $this->CAMPOS_ORGAO_ORIGEM['fields'];
        $value = reset($arrFields);
        $key = key($arrFields);

        $orgaoOrigem = array();
        if($value){
            $orgaoOrigem = $this->OrgaoOrigemSimples->getOrgaoOrigem($arrItens[$key]);
        }
        $orgaoOrigem[$value] = $arrItens[$key];

        $this->OrgaoOrigemSimples->save($orgaoOrigem);
        $id = $this->OrgaoOrigemSimples->id;
        $this->OrgaoOrigemSimples->clear();
        return $id;
    }

    private function importFuncao($arrItens){ //format:  indice x new_value

        $this->loadModel('FuncaoSimples');
        $arrFields = $this->CAMPOS_FUNCAO['fields'];  //format: indice x field_name

        $arrFieldVal = array();
        $arrFieldPos = array_flip($arrFields); // format: field_name x indice
        $hasVal = false;
        foreach($arrFieldPos as $key => $value){
            if (!empty($arrItens[$value]))$hasVal = true;
            $arrFieldVal[$key] = $arrItens[$value];   //format: field_name x new_value
        }
        $funcao = array();//format: field_name x old_value
        if($hasVal){
            $funcao = $this->FuncaoSimples->getFuncao($arrFieldVal);
        }
        foreach ($arrFields as $key => $value){
            $funcao[$value] = $arrItens[$key];  //format: field_name x new_value
        }

        $this->FuncaoSimples->save($funcao);
        $id = $this->FuncaoSimples->id;
        $this->FuncaoSimples->clear();
        return $id;
    }

    private function importCargo($arrItens){ //format:  indice x new_value
        $this->loadModel('CargoSimples');
        $arrFields = $this->CAMPOS_CARGO['fields'];  //format: indice x field_name

        $arrFieldVal = array();
        $arrFieldPos = array_flip($arrFields); // format: field_name x indice
        $hasVal = false;
        foreach($arrFieldPos as $key => $value){
            if (!empty($arrItens[$value]))$hasVal = true;
            $arrFieldVal[$key] = $arrItens[$value];   //format: field_name x new_value
        }
        $cargo = array();//format: field_name x old_value
        if($hasVal){
            $cargo = $this->CargoSimples->getCargo($arrFieldVal);
        }
        foreach ($arrFields as $key => $value){
            $cargo[$value] = $arrItens[$key];  //format: field_name x new_value
        }

        $this->CargoSimples->save($cargo);
        $id = $this->CargoSimples->id;
        $this->CargoSimples->clear();
        return $id;
    }

    private function importVinculo($arrItens, $fks){//format:  indice x new_value
        $this->loadModel('VinculoSimples');

        $arrFields = $this->CAMPOS_VINCULO['fields'];  //format: indice x field_name

        $arrFieldVal = array();
        $arrFieldPos = array_flip($arrFields); // format: field_name x indice

        foreach($arrFieldPos as $key => $value){ // format: field_name x indice
            $val = $arrItens[$value];
            if(in_array($key, $this->CAMPOS_VINCULO['dates'])){
                $val  = $this->formatDate($val);
            }
            $arrFieldVal[$key] = $val;   //format: field_name x new_value
        }
        $arrFieldVal = array_merge($arrFieldVal, $fks);
        $vinculo = $this->VinculoSimples->getVinculo($arrFieldVal);

        foreach ($arrFields as $indice => $field){ //format: indice x field_name
            $val = $arrItens[$indice];
            if(in_array($field, $this->CAMPOS_VINCULO['dates'])){
                $val  = $this->formatDate($val);
            }
            $vinculo[$field] = $val;  //format: field_name x new_value
        }

        foreach($this->CAMPOS_VINCULO['extra'] as $indice => $field){
            $vinculo[$field] = $arrItens[$indice];
        }
        $this->VinculoSimples->save($vinculo);
        $id = $this->VinculoSimples->id;
        $this->VinculoSimples->clear();
        return $id;
    }

    private function importVinculoFuncao($vinculoId, $funcaoId){
        $this->loadModel('VinculoFuncaoSimples');
        return $this->VinculoFuncaoSimples->insertSimples($vinculoId, $funcaoId);
    }

    private function getSexoID($str){
        return (strtoupper($str) == 'M')?SEXO_MASCULINO:SEXO_FEMININO;
    }
    private function formatDate($date){
        if(intval($date) == 0)return null;
        $format = 'Ymd';
        $date = DateTime::createFromFormat($format, $date);
        return $date->format('Y-m-d');
    }

    public function test ($val = 1){
        //pr($this->Session->read('permissoes'));
        //pr($this->Session->read('permissoesPerfil'));
        die;
    }
}
