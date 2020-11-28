<?php

App::uses('AppModel', 'Model');
App::import("Model", "BSFilter");

/**
 * BSModel.
 */
class BSModel extends AppModel {

    public $belongsTo = array('UsuarioAlteracao' => array('className' => 'Usuario', 'foreignKey' => 'usuario_versao_id'));

    public function __construct($id = false, $table = null, $ds = null) {
        if (get_class($this) !== 'Logger' && empty(AuditableConfig::$Logger)) {
            // Caso deseje usar o modelo padrão, utilize como abaixo, caso contrário você pode usar qualquer modelo
//            AuditableConfig::$Logger = ClassRegistry::init('Auditable.Logger', true);
        }

        parent::__construct($id, $table, $ds);
    }

    /**
     * Método para adicionar as informações relacionadas a versão do objeto
     * @param $bean Objeto a ser preenchido
     * @return Objeto com os dados de versão preenchidos
     */
    protected function adicionarInformacoesVersao($bean = array()) {
        $dataAtual = date('d/m/Y H:i:s');
        if ((!isset($bean[$this->alias]['data_inclusao'])) || ($bean[$this->alias]['data_inclusao'] == "NULL")) {
            if ((!isset($bean[$this->alias]['id'])) || ($bean[$this->alias]['id'] == "NULL")) {
                $bean[$this->alias]['data_inclusao'] = $dataAtual;
                $bean[$this->alias]['ativo'] = true;
            }
        }
        $bean[$this->alias]['data_alteracao'] = $dataAtual;
        $bean[$this->alias]['usuario_versao_id'] = CakeSession::read("Auth.User.id");

        return $bean;
    }

    public function delete($id = null, $cascade = true) {
        $result = parent::delete($id, $cascade);
        if ($result === false) {
            $dataAtual = date('d/m/Y H:i:s');
            $data = array('ativo' => 0, 'data_exclusao' => "$dataAtual", 'id' => $id);
            $entidade = new $this->alias();
            $retorno = $entidade->save($data, false);
            $entidade->resolverDependenciasExclusao($id);
            return $retorno;
        }
        return $result;
    }

    public function resolverDependenciasExclusao() {
        
    }

    public function beforeDelete($cascade = true) {
        return false;
    }

    public function listar(BSFilter $filtro = null) {
        if ($filtro == null) {
            $filtro = new BSFilter();
        }
        $filtro->setModelConsulta($this->alias);
        return $this->find($filtro->getTipo(), $filtro->getScope());
    }

    private function gerarLogs($bean = array()) {
        
    }

    public function beforeSave($options = array()) {
        $this->data = $this->adicionarInformacoesVersao($this->data);
        $this->retirarEspacos();
    }

    public function findById($id) {
        if( strtoupper($this->alias) == "ENDERECO" ){
            return $this->find('first', array(
                'conditions' => array(
                    $this->alias . '.id' => $id
                )
            ));

        }else{
            return $this->find('first', array(
                'conditions' => array(
                    $this->alias . '.ativo' => true,
                    $this->alias . '.id' => $id
                )
            ));
        }
        
    }

    function getLastQuery() {
        $dbo = $this->getDatasource();
        $logs = $dbo->getLog();
        $lastLog = end($logs['log']);
        return $lastLog['query'];
    }

    public function retirarEspacos() {
        foreach ($this->data[$this->alias] as $key => $data) {
            if (is_string($this->data[$this->alias][$key])) {
                $this->data[$this->alias][$key] = trim(Util::removerEspacosExtras($data));
            }
        }
    }


    public function updateFields($mapFields = array(), $mapWhere= array()){
        $table = $this->useTable;
        if(empty($mapFields) || empty($mapWhere))return;

        $arrDateFields = array('DATA_HORA', 'DATA_INCLUSAO', 'DATA_EXCLUSAO', 'DATA_ALTERACAO');

        $q = "update $table set "; $sep = '';
        $arrVals = array();
        foreach ($mapFields as $field => $value){
            if(in_array(strtoupper($field), $arrDateFields) && empty($value)) continue;
            $q .= "$sep $field = ?";$sep = ',';
            $arrVals[] = $value;
        }
        $q .= " where ";
        $sep = '';
        foreach ($mapWhere as $field => $value){
            $q .= " $sep $field = ?";$sep = 'and';
            $arrVals[] = $value;
        }
        $this->query($q, $arrVals);
    }

    public function removeAllValidation(){
        $validator = $this->validator();
        $arr = $validator->getField();
        $keys = array_keys($arr);
        foreach ($keys as $k){
            $validator->offsetUnset($k);
        }
    }
}
