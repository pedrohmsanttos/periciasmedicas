<?php

class BSFilter {

    private $tipo = "list";
    private $camposOrdenados = array();
    private $camposRetornados = array();
    private $camposAgrupados = array();
    private $condicoes = array();
    private $contain = array();
    private $joins = array();
    private $limiteConsulta = 10;
    private $limiteListagem = "";
    private $limitarItensAtivos = true;
    private $modelConsulta;
    private $recursive;

    public function getRecursive() {
        return $this->recursive;
    }

    public function setRecursive($recursive) {
        $this->recursive = $recursive;
    }

    public function getModelConsulta() {
        return $this->modelConsulta;
    }

    public function setModelConsulta($modelConsulta) {
        $this->modelConsulta = $modelConsulta;
    }

    public function getTipo() {
        return $this->tipo;
    }

    public function getLimiteListagem() {
        return $this->limiteListagem;
    }
    
    public function getLimiteConsulta() {
        return $this->limiteConsulta;
    }

    public function setLimiteListagem($limite) {
        if ($limite == "") {
            $limite = $this->limiteListagem;
        }
        $this->limiteListagem = $limite;
    }
    
    public function setLimiteConsulta($limite) {
        if ($limite == "") {
            $limite = $this->limiteConsulta;
        }
        $this->limiteConsulta = $limite;
    }

    public function getCamposOrdenados() {
        return $this->camposOrdenados;
    }

    public function getCamposRetornados() {
        return $this->camposRetornados;
    }

    public function getCamposAgrupados() {
        return $this->camposAgrupados;
    }

    public function getLimitarItensAtivos() {
        return $this->limitarItensAtivos;
    }

    public function getJoins() {
        return $this->joins;
    }

    public function getContain() {
        return $this->contain;
    }

    public function getCondicoes() {
        return $this->condicoes;
    }

    public function setTipo($tipo) {
        $this->tipo = $tipo;
    }

    public function setCamposOrdenados($camposOrdenados) {
        $this->camposOrdenados = $camposOrdenados;
    }

    /**
     * Função responsavel por receber os dados de ordenação passados como string, 
     * e separados por paramentros, e setando em camposOrdenados
     */
    public function setCamposOrdenadosString() {
        $this->camposOrdenados = func_get_args();
    }

    public function setCamposRetornados($camposRetornados = array()) {
        $this->camposRetornados = $camposRetornados;
    }

    public function setCamposAgrupados($camposAgrupados = array()) {
        $this->camposAgrupados = $camposAgrupados;
    }

    public function setCamposAgrupadosString($camposAgrupadosStrings) {
        $args = func_get_args();

        foreach ($args as $arg) {
            $arrayRetornados[] = $arg;
        }

        $this->camposAgrupados = $arrayRetornados;
    }

    public function setCamposRetornadosString($camposRetornadosStrings) {
        $args = func_get_args();

        foreach ($args as $arg) {
            $arrayRetornados[] = $arg;
        }

        $this->camposRetornados = $arrayRetornados;
    }

    public function getScope() {
        $condicoes['conditions'] = $this->getCondicoes();
        if ($this->getLimitarItensAtivos()) {
            if (!isset($condicoes[$this->getModelConsulta() . '.ativo'])) {
                $condicoes['conditions'][$this->getModelConsulta() . '.ativo'] = true;
            }
        }
        if (!empty($this->getCamposOrdenados())) {
            $condicoes["order"] = $this->getCamposOrdenados();
        }

        if (!empty($this->getCamposRetornados())) {
            $condicoes["fields"] = $this->getCamposRetornados();
        }

        if (!empty($this->getContain())) {
            $condicoes["contain"] = $this->getContain();
        }

        if (!empty($this->getJoins())) {
            $condicoes["joins"] = $this->getJoins();
        }
        if (!empty($this->getCamposAgrupados())) {
            $condicoes["group"] = $this->getCamposAgrupados();
        }
        if (!empty($this->getRecursive())) {
            $condicoes["recursive"] = $this->getRecursive();
        }
        if(!empty($this->getLimiteListagem())){
            $condicoes['limit'] = $this->getLimiteListagem();
        }
        return $condicoes;
    }

    public function setLimitarItensAtivos($limitarItensAtivos) {
        $this->limitarItensAtivos = $limitarItensAtivos;
    }

    public function setContain($contain) {
        if (is_array($contain) && !empty($contain)) {
            $this->contain = $contain;
        }
    }

    /**
     * Seta as condições em formato de array;
     * @param array $condicoes
     */
    public function setCondicoes($condicoes) {
        if (is_array($condicoes) && !empty($condicoes)) {
            $this->condicoes = $condicoes;
        }
    }

    /**
     * Seta os joins também em forma de array;
     * @param array $joins
     */
    public function setJoins($joins) {
        if (is_array($joins) && !empty($joins)) {
            $this->joins = $joins;
        }
    }

}
