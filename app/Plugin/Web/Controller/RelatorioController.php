<?php

/**
 * Description of AtendimentoController
 *
 * @author BankSystem Software Build
 */
// App::uses('BSController', 'Admin.Controller');
App::uses('BSController', 'Web.Controller');
App::uses('Model', 'TipoSituacaoParecerTecnico');
App::uses('Model', 'ParametroGeral');


class RelatorioController extends BSController {
    public $helpers = array('PForm');

    private function getMes($id){
        $arrMeses = array(1=> 'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho',
            'Julho', 'Agosto', 'Setembro', 'Outubro','Novembro', 'Dezembro' );
        return $arrMeses[$id];
    }

    public function beforeRender() {
        parent::beforeRender();
    }

    /**
     *
     */
    public function index() {
        $this->carregarListaTipologia();
        $tipo_relatorios = array(
            RELATORIO_ATENDIMENTOS_TOTAIS => 'Número de atendimento totais',
            RELATORIO_AGRUPADOS => 'Atendimentos agrupados',
            RELATORIO_TOTAL_DEFERIDOS_INDEFERIDOS_EM_EXIGENCIA => 'Total deferidos / indeferidos / em exigência',
            RELATORIO_PROCESSOS_PUBLICADOS => 'Total de processos publicados',
            RELATORIO_ATENDIMENTOS_POR_GENEREO => 'Atendimentos mensal por gênero(M/F)',
            RELATORIO_ATENDIMENTOS_POR_PERITO =>  'Atendimentos mensal por Perito Agrupado por Tipologia',
            RELATORIO_DIAS_DE_LICENCA => 'Dias concedidos de licença'
        );

        $this->set('tipo_relatorios', $tipo_relatorios);

        $tipo_agrupadmento = array(
            RELATORIO_AGRUPADOS_POR_MUNICIPIO => 'Município',
            RELATORIO_AGRUPADOS_POR_SECRETARIA => 'Secretarias',
            RELATORIO_AGRUPADOS_POR_CID => 'CID'
        );
        $this->set('tipo_agrupamento', $tipo_agrupadmento);
    }

    public function impressaoRelatorioPersonalizadoExcel(){
        
        $dados = $this->request->data['Relatorio'];
        
        $retorno = $this->preConsultaPersonalizada($dados);
        $this->set('resultado_relatorio',$retorno);
        
        $this->render('impressao_relatorio_personalizado_excel');
    }

    public function preConsultaPersonalizada($dados){


            $tb_principal = "";
            $cdc_where = " WHERE 1=1 AND ";

            $tabelas = array("atendimento", "agendamento");

            $controle = strtolower( $dados['controle'] );

            $tituloRelatorio = $dados['titulo_relatorio'];

            if(in_array($controle, $tabelas)){
                $tb_principal = $controle;
            
            }

            if($controle != "todos"){
                $campos_relatorio = $tb_principal.".id,";
                $campos_relatorio .= "to_char(" .$tb_principal .".data_inclusao,'DD/MM/YYYY hh24:MI:SS') AS data_". $tb_principal .",";
                $campos_agrupamento = $tb_principal .".id,";
            }else{
                $campos_relatorio = "atendimento.id as id_atendimento, agendamento.id as id_agendamento,";
                $campos_relatorio .= "to_char(agendamento.data_inclusao,'DD/MM/YYYY hh24:MI:SS') AS data_agendamento,";
                $campos_relatorio .= "to_char(atendimento.data_inclusao,'DD/MM/YYYY hh24:MI:SS') AS data_atendimento,";
                $campos_agrupamento = "id_atendimento,id_agendamento,";
            }

            $ano_relatorio          = strtolower( $dados['ano_exercicio'] );
            $unidade_relatorio      = $dados['unidades'];
            $sexo_relatorio         = $dados['sexo'];
            $tipologia_relatorio    = $dados['tipologia'];
            $status_agendamento     = $dados['status_agendamento'];
            $status_atendimento     = $dados['status_atendimento'];
            $orgao_origem           = $dados['orgao_origem'];
            $tipo_usuario           = $dados['tipo_usuario'];
            $numero_laudo_opcao     = $dados['numero_laudo_opcao'];
            $numero_laudo           = $dados['numero_laudo'];
            $endereco_opcao         = $dados['endereco_opcao'];
            $endereco               = $dados['endereco'];
            $cid_opcao              = $dados['cid_opcao'];
            $cid                    = $dados['cid'];
            $agrupamento            = $dados['disponiveis'];
            $situacao_atendimento   = $dados['situacao_atendimento'];
            $cargo                  = $dados['cargo'];

            // pr($orgao_origem);die;

            $periodo_inicio = $periodo_inicio_opcoes = $periodo_inic_dt_inic = $periodo_fim_dt_fim = "";
            
            // $periodo_inicio         = $dados['periodo_inicio'];
            if(!empty($dados['periodo_inicio_opcoes'])){
                $periodo_inicio_opcoes  = $dados['periodo_inicio_opcoes'];
            }    
            
            if(!empty($dados['periodo_inic_dt_inic'])){
                $periodo_inic_dt_inic   = $dados['periodo_inic_dt_inic'];
            }
            
            if(!empty($dados['periodo_inic_dt_fim'])){
                $periodo_fim_dt_fim   = $dados['periodo_inic_dt_fim'];
            }

            if(!empty($dados['periodo_inicio'])){
                $periodo_inicio            = $dados['periodo_inicio'];
            }
            // $periodo_final_opcoes   = $dados['periodo_final_opcoes'];
            // $periodo_fim_dt_fim     = $dados['periodo_fim_dt_fim'];

            if(isset($dados['campos_exibicao']) && !empty($dados['campos_exibicao'])){
                $campos_exibicao        = $dados['campos_exibicao'];
            }

            $funcao                 = $dados['funcao'];
            $lotacao                = $dados['lotacao'];
            $lotacao                = $dados['lotacao'];
            $demais_campos          = $dados['demais_campos'];
            $demais_campos_opcao    = $dados['demais_campos_opcao'];
            $conteudo_demais_campos = $dados['conteudo'];
            $publicacao             = $dados['publicacao'];


            if(!empty($periodo_inic_dt_inic)){
                $periodo_inic_dt_inic = date('Y-m-d', strtotime( str_replace("/", "-", $periodo_inic_dt_inic)));
            }
            if(!empty($periodo_fim_dt_fim)){
                $periodo_fim_dt_fim = date('Y-m-d', strtotime( str_replace("/", "-", $periodo_fim_dt_fim)));
            }

            $consultaQtdTipo = $cdc_tipo = "";

            //Montando a consulta da subquery que será feita para a coluna de quantidade de tipologias, validando pelo tempo consultado

            if(!empty($tipologia_relatorio)){
                $cdc_tipo = " AND agendamento.tipologia_id IN (";
                foreach ($tipologia_relatorio as $tipologia) {
                    $cdc_tipo .= $tipologia  . ",";
                }
                $cdc_tipo = rtrim($cdc_tipo,",");
                $cdc_tipo .= ")";
            }

            $consultaQtdTipo = " (SELECT COUNT (atendimento.*) ";
            $consultaQtdTipo .= " FROM atendimento INNER JOIN agendamento ON atendimento.agendamento_id = agendamento.ID "; 
            $consultaQtdTipo .=" WHERE atendimento.usuario_id = usuario.ID ";
            if(trim($cdc_tipo) != ""){
                $consultaQtdTipo .= $cdc_tipo;
            }  
            $consultaQtdTipo.= " AND (atendimento.situacao_id = 8 OR atendimento.situacao_id = 2) ";  
             
            if($ano_relatorio == "todos"){

                if($controle != "todos"){

                    $consultaQtdTipo .= " AND EXTRACT(year FROM ".$tb_principal.".data_inclusao) IN (";
                    foreach ($ano_exercicio as $item) {
                        if(strtolower($item) != "todos"){
                            $consultaQtdTipo .= $item . ",";
                        }
                    }
                    $consultaQtdTipo = rtrim($consultaQtdTipo,",");
                    $consultaQtdTipo .= ")";
                
                }else{

                    $consultaQtdTipo .= " AND EXTRACT(year FROM agendamento.data_inclusao) IN (";
                    foreach ($ano_exercicio as $item) {
                        if(strtolower($item) != "todos"){
                            $consultaQtdTipo .= $item . ",";
                        }
                    }
                    $consultaQtdTipo = rtrim($consultaQtdTipo,",");
                    $consultaQtdTipo .= ") AND EXTRACT(year FROM atendimento.data_inclusao) IN (";
                    foreach ($ano_exercicio as $item) {
                        if(strtolower($item) != "todos"){
                            $consultaQtdTipo .= $item . ",";
                        }
                    }
                    $consultaQtdTipo = rtrim($consultaQtdTipo,",");
                    $consultaQtdTipo .= ") ";

                }
            }else{

                if($controle != "todos"){
                    $consultaQtdTipo .= " AND EXTRACT(year FROM ".$tb_principal.".data_inclusao) IN (".$ano_relatorio.")";
                }else{
                    $consultaQtdTipo .= " AND EXTRACT(year FROM atendimento.data_inclusao) IN (".$ano_relatorio.") AND";
                    $consultaQtdTipo .= " EXTRACT(year FROM agendamento.data_inclusao) IN (".$ano_relatorio.") ";
                }

            }


            if($periodo_inicio == "Início de Agendamento"){
                if($periodo_inicio_opcoes == "Período"){
                    $consultaQtdTipo .= " AND agendamento.data_inclusao >= '" . $periodo_inic_dt_inic . "' ";
                    $consultaQtdTipo .= " AND agendamento.data_inclusao <= '" . $periodo_fim_dt_fim . "' ";
                }

                else if($periodo_inicio_opcoes == "Nulo"){
                    $consultaQtdTipo .= " AND agendamento.data_inclusao = NULL ";
                }

                else if($periodo_inicio_opcoes == "Não Nulo"){
                    $consultaQtdTipo .= " AND agendamento.data_inclusao IS NOT NULL ";
                }
            }

            if($periodo_inicio == "Início de Atendimento"){
                if($periodo_inicio_opcoes == "Período"){
                    $consultaQtdTipo .= " AND atendimento.data_inclusao >= '" . $periodo_inic_dt_inic . "' ";
                    $consultaQtdTipo .= " AND atendimento.data_inclusao <= '" . $periodo_fim_dt_fim . "' ";
                }

                else if($periodo_inicio_opcoes == "Nulo"){
                    $consultaQtdTipo .= " AND atendimento.data_inclusao = NULL ";
                }

                else if($periodo_inicio_opcoes == "Não Nulo"){
                    $consultaQtdTipo .= " AND atendimento.data_inclusao IS NOT NULL ";
                }
            }


            $consultaQtdTipo.= " ) AS qtd_licencas";

            // FIM da montagem da subquery de consultas de quantidade de licenças atendidas no tempo escolhido

            $campos_exibicao_ref     = array(
                "NOME"              => "usuario.nome", 
                "NOME DA UNIDADE"   => "unidade_atendimento.nome as nome_und",
                "BAIRRO"            => "endereco.bairro",
                "CPF"               => "substr(usuario.cpf, 1, 3) || '.' || substr(usuario.cpf, 4, 3) || '.' || substr(usuario.cpf, 7, 3) || '-' || substr(usuario.cpf, 10) AS cpf_usuario", 
                "SEXO"              => "sexo.nome as sexo_desc", 
                "IDADE"             => "IDADE", 
                // "QTD TIPOLOGIAS"      => "(SELECT COUNT (atendimento.*) FROM atendimento INNER JOIN agendamento ON atendimento.agendamento_id = agendamento. ID WHERE atendimento.usuario_id = usuario. ID AND agendamento.tipologia_id IN (1, 3, 19, 23, 20, 24, 2) AND atendimento.situacao_id = 8 ) AS qtd_licencas", 
                "QTD TIPOLOGIAS"      => $consultaQtdTipo, 
                "FUNÇÃO"            => "funcao.nome as nome_funcao ", 
                "DATA DE ADMISSÃO"  => "to_char(vinculo.data_admissao_servidor,'DD/MM/YYYY') AS data_admissao_servidor",
                "PERÍODO DE LICENÇA"=> "atendimento.duracao",
                "NOME DO ORGÃO"         => "orgao_origem.orgao_origem"
            );

            $campos_agrupar     = array(
                "NOME"                  => "usuario.nome", 
                "NOME DA UNIDADE"       => "unidade_atendimento.nome",
                "BAIRRO"                => "endereco.bairro",
                "CPF"                   => "cpf_usuario", 
                "SEXO"                  => "sexo.nome", 
                "IDADE"                 => "IDADE", 
                "QTD TIPOLOGIAS"          => "usuario.id", 
                "FUNÇÃO"                => "funcao.nome", 
                "DATA DE ADMISSÃO"      => "vinculo.data_admissao_servidor",
                "PERÍODO DE LICENÇA"    => "atendimento.duracao",
                "NOME DO ORGÃO"         => "orgao_origem.orgao_origem"
            );

            $ano_exercicio = array();

            $this->loadModel("Atendimento");
            $db = $this->Atendimento->getDataSource();
            $sql_anos = "SELECT DISTINCT EXTRACT(year FROM atendimento.data_inclusao) FROM atendimento";
            $arr = $db->fetchAll($sql_anos);
            foreach ($arr as $item) {
                foreach ($item as $aux) {
                    $ano = array();
                    $ano_exercicio[$aux['date_part']] = $aux['date_part'];
                }
            }
            $ano_exercicio['Todos'] = "Todos";

            if(isset( $campos_exibicao ) && !empty($campos_exibicao) ){
                foreach ($campos_exibicao as $campo) {
                    $campos_relatorio .= $campos_exibicao_ref[$campo] . ",";
                    $campos_agrupamento .= $campos_agrupar[$campo] . ",";
                }
            }

            $campos_relatorio   = rtrim($campos_relatorio,",");
            $campos_agrupamento = rtrim($campos_agrupamento,",");

            $campos_relatorio .= ",array_agg(DISTINCT cid.descricao) as grupo_cid";
            $campos_relatorio .= ",array_agg(DISTINCT tipologia.nome) as grupo_tipologia";
            $campos_relatorio .= ",array_agg(DISTINCT sexo.nome) as grupo_sexo";
            $campos_relatorio .= ",array_agg(DISTINCT municipio.nome) AS grupo_municipio";
            $campos_relatorio .= ",array_agg(DISTINCT unidade_atendimento.nome) AS grupo_unidade";
            $campos_relatorio .= ",array_agg(DISTINCT ";
            $campos_relatorio .= "'Publicação' || ' ' || publicacao.id || ' - ' ||";
            $campos_relatorio .= "to_char(publicacao.data_publicacao,'DD/MM/YYYY hh24:MI:SS')) AS grupo_publicacao";
            $campos_relatorio .= ",array_agg (DISTINCT 'Perito(a) ' || usuario_perito.nome) AS grupo_perito";

            $sql_relatorio = "SELECT " . $campos_relatorio . " ";

            if ($tb_principal == "atendimento" || $controle == "todos") {
                $sql_relatorio .= "FROM
                                        atendimento
                                    LEFT JOIN agendamento ON atendimento.agendamento_id = agendamento.id
                                    LEFT JOIN publicacao_atendimento ON publicacao_atendimento.atendimento_id = atendimento.id
                                    LEFT JOIN publicacao ON publicacao_atendimento.publicacao_id = publicacao.id
                                    LEFT JOIN usuario ON atendimento.usuario_id = usuario.id
                                    LEFT JOIN usuario AS usuario_perito ON atendimento.usuario_versao_id = usuario_perito.id
                                    LEFT JOIN endereco ON usuario.endereco_id = endereco.id
                                    LEFT JOIN unidade_atendimento ON agendamento.unidade_atendimento_id = unidade_atendimento.id
                                    LEFT JOIN vinculo ON vinculo.usuario_id = usuario.id
                                    LEFT JOIN vinculo_funcao ON vinculo_funcao.vinculo_id = vinculo.id
                                    LEFT JOIN vinculo_lotacao ON vinculo_lotacao.vinculo_id = vinculo.id
                                    LEFT JOIN funcao ON vinculo_funcao.funcao_id = funcao.id
                                    LEFT JOIN orgao_origem ON vinculo.orgao_origem_id = orgao_origem.id
                                    LEFT JOIN atendimento_cid ON atendimento_cid.atendimento_id = atendimento.id
                                    LEFT JOIN cid ON cid.id = atendimento_cid.cid_id
                                    LEFT JOIN sexo ON usuario.sexo_id = sexo.id
                                    LEFT JOIN tipologia ON agendamento.tipologia_id = tipologia.ID
                                    LEFT JOIN municipio ON endereco.municipio_id = municipio.id";
            
            } else if ($tb_principal == "agendamento") {
                $sql_relatorio .= "FROM
                                        agendamento
                                    LEFT JOIN atendimento ON atendimento.agendamento_id = agendamento.id
                                    LEFT JOIN publicacao_atendimento ON publicacao_atendimento.atendimento_id = atendimento.id
                                    LEFT JOIN publicacao ON publicacao_atendimento.publicacao_id = publicacao.id
                                    LEFT JOIN usuario ON agendamento.usuario_servidor_id = usuario.id
                                    LEFT JOIN usuario AS usuario_perito ON agendamento.usuario_versao_id = usuario_perito.id
                                    LEFT JOIN endereco ON usuario.endereco_id = endereco.id
                                    LEFT JOIN unidade_atendimento ON agendamento.unidade_atendimento_id = unidade_atendimento.id
                                    LEFT JOIN vinculo ON vinculo.usuario_id = usuario.id
                                    LEFT JOIN vinculo_funcao ON vinculo_funcao.vinculo_id = vinculo.id
                                    LEFT JOIN vinculo_lotacao ON vinculo_lotacao.vinculo_id = vinculo.id
                                    LEFT JOIN funcao ON vinculo_funcao.funcao_id = funcao.id
                                    LEFT JOIN orgao_origem ON vinculo.orgao_origem_id = orgao_origem.id
                                    LEFT JOIN agendamento_cid ON agendamento_cid.agendamento_id = agendamento.id
                                    LEFT JOIN cid ON cid.id = agendamento_cid.cid_id
                                    LEFT JOIN sexo ON usuario.sexo_id = sexo.id
                                    LEFT JOIN tipologia ON agendamento.tipologia_id = tipologia.ID
                                    LEFT JOIN municipio ON endereco.municipio_id = municipio.id";
            }


            // echo $campos_relatorio;die;


            
            // Add o where de ANO na consulta
            if($ano_relatorio == "todos"){

                if($controle != "todos"){

                    $cdc_where .= " EXTRACT(year FROM ".$tb_principal.".data_inclusao) IN (";
                    foreach ($ano_exercicio as $item) {
                        if(strtolower($item) != "todos"){
                            $cdc_where .= $item . ",";
                        }
                    }
                    $cdc_where = rtrim($cdc_where,",");
                    $cdc_where .= ")";
                
                }else{

                    $cdc_where .= " EXTRACT(year FROM agendamento.data_inclusao) IN (";
                    foreach ($ano_exercicio as $item) {
                        if(strtolower($item) != "todos"){
                            $cdc_where .= $item . ",";
                        }
                    }
                    $cdc_where = rtrim($cdc_where,",");
                    $cdc_where .= ") AND EXTRACT(year FROM atendimento.data_inclusao) IN (";
                    foreach ($ano_exercicio as $item) {
                        if(strtolower($item) != "todos"){
                            $cdc_where .= $item . ",";
                        }
                    }
                    $cdc_where = rtrim($cdc_where,",");
                    $cdc_where .= ") ";

                }
            }else{

                if($controle != "todos"){
                    $cdc_where .= " EXTRACT(year FROM ".$tb_principal.".data_inclusao) IN (".$ano_relatorio.")";
                }else{
                    $cdc_where .= " EXTRACT(year FROM atendimento.data_inclusao) IN (".$ano_relatorio.") AND";
                    $cdc_where .= " EXTRACT(year FROM agendamento.data_inclusao) IN (".$ano_relatorio.") ";
                }

            }

            //Add o where de UNIDADES na consulta
            if(!empty($unidade_relatorio)){
                $cdc_where .= " AND agendamento.unidade_atendimento_id IN (";
                foreach ($unidade_relatorio as $unidade) {
                    $cdc_where .= $unidade . ",";
                }
                $cdc_where = rtrim($cdc_where,",");
                $cdc_where .= ")";
            }

            //Add o where de SEXO na consulta
            if(!empty($sexo_relatorio)){
                $cdc_where .= " AND usuario.sexo_id IN (";
                foreach ($sexo_relatorio as $sexo) {
                    $cdc_where .= $sexo . ",";
                }
                $cdc_where = rtrim($cdc_where,",");
                $cdc_where .= ")";

                // $cdc_where .= " AND usuario.sexo_id = " . $sexo_relatorio['0'];
            }

            //Add o where de TIPOLOGIA na consulta
            if(!empty($tipologia_relatorio)){
                $cdc_where .= " AND agendamento.tipologia_id IN (";
                foreach ($tipologia_relatorio as $tipologia) {
                    $cdc_where .= $tipologia  . ",";
                }
                $cdc_where = rtrim($cdc_where,",");
                $cdc_where .= ")";
            }

            //Add o where de STATUS DO AGENDAMENTO na consulta
            if(!empty($status_agendamento)){
                $cdc_where .= " AND agendamento.status_agendamento IN (";
                foreach ($status_agendamento as $status) {
                    $cdc_where .= "'". $status  . "',";
                }
                $cdc_where = rtrim($cdc_where,",");
                $cdc_where .= ")";
            }

            //Add o where de STATUS DO ATENDIMENTO na consulta
            if(!empty($status_atendimento)){
                $cdc_where .= " AND atendimento.status_atendimento IN (";
                foreach ($status_atendimento as $status) {
                    $cdc_where .= "'". $status  . "',";
                }
                $cdc_where = rtrim($cdc_where,",");
                $cdc_where .= ")";
            }

            //Add o where de SITUAÇÃO DO ATENDIMENTO na consulta
            if(!empty($situacao_atendimento)){
                $cdc_where .= " AND atendimento.situacao_id IN (";
                foreach ($situacao_atendimento as $status) {
                    $cdc_where .= "'". $status  . "',";
                }
                $cdc_where = rtrim($cdc_where,",");
                $cdc_where .= ")";
            }

            //Add o where de TIPO DE USUÁRIO na consulta
            if(!empty($tipo_usuario)){
                $cdc_where .= " AND usuario.tipo_usuario_id IN (";
                foreach ($tipo_usuario as $tipo) {
                    $cdc_where .= $tipo . ",";
                }
                $cdc_where = rtrim($cdc_where,",");
                $cdc_where .= ")";
            }

            $labelOrgao = "TODOS";
            $this->loadModel("OrgaoOrigem");
            //Add o where de ORGÃO DE ORIGEM na consulta
            if(!empty($orgao_origem)){
                $nomeOrgao = "";
                $cdc_where .= " AND vinculo.orgao_origem_id IN (";
                foreach ($orgao_origem as $orgao) {
                    $cdc_where .= $orgao . ",";

                    $nomeOrgao .= $this->OrgaoOrigem->findById($orgao)['OrgaoOrigem']['orgao_origem'] . ",";

                    
                }
                $cdc_where = rtrim($cdc_where,",");
                $cdc_where .= ")";

                $labelOrgao = rtrim($nomeOrgao, ",");

            }

            //Add o where de LOTAÇÃO na consulta
            if(!empty($lotacao)){
                $cdc_where .= " AND vinculo_lotacao.lotacao_id IN (";
                foreach ($lotacao as $lota) {
                    $cdc_where .= $lota . ",";
                }
                $cdc_where = rtrim($cdc_where,",");
                $cdc_where .= ")";
            }

             //Add o where de CARGO na consulta
             if(!empty($cargo)){
                $cdc_where .= " AND vinculo.cargo_id IN (";
                foreach ($cargo as $car) {
                    $cdc_where .= $car . ",";
                }
                $cdc_where = rtrim($cdc_where,",");
                $cdc_where .= ")";
            }

            

            //Add o where de FUNÇÃO na consulta
            if(!empty($funcao)){
                $cdc_where .= " AND vinculo_funcao.funcao_id IN (";
                foreach ($funcao as $fun) {
                    $cdc_where .= $fun . ",";
                }
                $cdc_where = rtrim($cdc_where,",");
                $cdc_where .= ")";
            }

            //Add o where de PUBLICAÇÃO na consulta
            if(!empty($publicacao)){
                $cdc_where .= " AND publicacao.id IN (";
                foreach ($publicacao as $pub) {
                    $cdc_where .= $pub . ",";
                }
                $cdc_where = rtrim($cdc_where,",");
                $cdc_where .= ")";
            }

            // Add o where de DEMAIS CAMPOS na consulta

            if(!empty($demais_campos)){
                if($demais_campos == "CPF do Servidor"){
                    if($demais_campos_opcao == "Iniciado por"){
                        $cdc_where .= " AND CAST( usuario.cpf AS TEXT) LIKE '" . trim($conteudo_demais_campos) . "%' ";
                    }
                    else if($demais_campos_opcao == "Igual a"){
                        $cdc_where .= " AND CAST( usuario.cpf AS TEXT) = '" . trim($conteudo_demais_campos) . "' ";
                    }
                    else if($demais_campos_opcao == "Contém"){
                        $cdc_where .= " AND CAST( usuario.cpf AS TEXT) LIKE '%" . trim($conteudo_demais_campos) . "%' ";
                    }
                }
                else if($demais_campos == "Nome do Servidor"){
                    if($demais_campos_opcao == "Iniciado por"){
                        $cdc_where .= " AND usuario.nome ILIKE '" . trim($conteudo_demais_campos) . "%' ";
                    }
                    else if($demais_campos_opcao == "Igual a"){
                        $cdc_where .= " AND usuario.nome = '" . trim($conteudo_demais_campos) . "' ";
                    }
                    else if($demais_campos_opcao == "Contém"){
                        $cdc_where .= " AND usuario.nome ILIKE '%" . trim($conteudo_demais_campos) . "%' ";
                    }
                }
                else if($demais_campos == "CPF do Perito"){
                    if($demais_campos_opcao == "Iniciado por"){
                        $cdc_where .= " AND CAST( usuario_perito.cpf AS TEXT) LIKE '" . trim($conteudo_demais_campos) . "%' ";
                    }
                    else if($demais_campos_opcao == "Igual a"){
                        $cdc_where .= " AND CAST( usuario_perito.cpf AS TEXT) = '" . trim($conteudo_demais_campos) . "' ";
                    }
                    else if($demais_campos_opcao == "Contém"){
                        $cdc_where .= " AND CAST( usuario_perito.cpf AS TEXT) LIKE '%" . trim($conteudo_demais_campos) . "%' ";
                    }
                }
                else if($demais_campos == "Nome do Perito"){
                    if($demais_campos_opcao == "Iniciado por"){
                        $cdc_where .= " AND usuario_perito.nome ILIKE '" . trim($conteudo_demais_campos) . "%' ";
                    }
                    else if($demais_campos_opcao == "Igual a"){
                        $cdc_where .= " AND usuario_perito.nome = '" . trim($conteudo_demais_campos) . "' ";
                    }
                    else if($demais_campos_opcao == "Contém"){
                        $cdc_where .= " AND usuario_perito.nome ILIKE '%" . trim($conteudo_demais_campos) . "%' ";
                    }
                }
                else if($demais_campos == "Número do Agendamento"){
                    if($demais_campos_opcao == "Iniciado por"){
                        $cdc_where .= " and CAST( agendamento.id AS TEXT) LIKE '" . trim($conteudo_demais_campos) . "%' ";
                    }
                    else if($demais_campos_opcao == "Igual a"){
                        $cdc_where .= " AND CAST( agendamento.id AS TEXT) = '" . trim($conteudo_demais_campos) . "' ";
                    }
                    else if($demais_campos_opcao == "Contém"){
                        $cdc_where .= " AND CAST( agendamento.id AS TEXT) '%" . trim($conteudo_demais_campos) . "%' ";
                    }
                }
            }

            //Add o where de N° DO LAUDO na consulta
            if(!empty($numero_laudo_opcao) && !empty($numero_laudo)){
                if($numero_laudo_opcao == "Iniciado por"){
                    $cdc_where .= " AND CAST(atendimento.id AS TEXT) LIKE '". trim($numero_laudo) ."%'";
                
                }else if($numero_laudo_opcao == "Igual a"){
                    $cdc_where .= " AND CAST(atendimento.id AS TEXT) = '". trim($numero_laudo) ."'";
                
                }else if($numero_laudo_opcao == "Contém"){
                    $cdc_where .= " AND CAST(atendimento.id AS TEXT) LIKE '%". trim($numero_laudo) ."%'";
                }
            }

            //Add o where de ENDEREÇO na consulta
            if(!empty($endereco_opcao) && !empty($endereco)){
                if($endereco_opcao == "Iniciado por"){
                    $cdc_where .= " AND endereco.logradouro ILIKE '". trim($endereco) ."%'";
                
                }else if($endereco_opcao == "Igual a"){
                    $cdc_where .= " AND endereco.logradouro = '". trim($endereco) ."'";
                
                }else if($endereco_opcao == "Contém"){
                    $cdc_where .= " AND endereco.logradouro ILIKE '%". trim($endereco) ."%'";
                }
            }

            //Add o where de CID na consulta
            if(!empty($cid_opcao) && !empty($cid)){
                if($cid_opcao == "Iniciado por"){
                    $cdc_where .= " AND cid.nome ILIKE '". trim($cid) ."%'";
                
                }else if($cid_opcao == "Igual a"){
                    $cdc_where .= " AND cid.nome = '". trim($cid) ."'";
                
                }else if($cid_opcao == "Contém"){
                    $cdc_where .= " AND cid.nome ILIKE '%". trim($cid) ."%'";
                }
            }

            //Add o where de PERIODO na consulta
            if($periodo_inicio == "Início de Agendamento"){
                if($periodo_inicio_opcoes == "Período"){
                    $cdc_where .= " AND agendamento.data_inclusao >= '" . $periodo_inic_dt_inic . "' ";
                    $cdc_where .= " AND agendamento.data_inclusao <= '" . $periodo_fim_dt_fim . "' ";
                }

                else if($periodo_inicio_opcoes == "Nulo"){
                    $cdc_where .= " AND agendamento.data_inclusao = NULL ";
                }

                else if($periodo_inicio_opcoes == "Não Nulo"){
                    $cdc_where .= " AND agendamento.data_inclusao IS NOT NULL ";
                }
            }

            if($periodo_inicio == "Início de Atendimento"){
                if($periodo_inicio_opcoes == "Período"){
                    $cdc_where .= " AND atendimento.data_inclusao >= '" . $periodo_inic_dt_inic . "' ";
                    $cdc_where .= " AND atendimento.data_inclusao <= '" . $periodo_fim_dt_fim . "' ";
                }

                else if($periodo_inicio_opcoes == "Nulo"){
                    $cdc_where .= " AND atendimento.data_inclusao = NULL ";
                }

                else if($periodo_inicio_opcoes == "Não Nulo"){
                    $cdc_where .= " AND atendimento.data_inclusao IS NOT NULL ";
                }
            }

            if($periodo_inicio == "Término de Agendamento"){
                if($periodo_inicio_opcoes == "Período"){
                    $cdc_where .= " AND agendamento.data_inclusao <= '" . $periodo_inic_dt_inic . "' ";
                }

                else if($periodo_inicio_opcoes == "Nulo"){
                    $cdc_where .= " AND agendamento.data_inclusao = NULL ";
                }

                else if($periodo_inicio_opcoes == "Não Nulo"){
                    $cdc_where .= " AND agendamento.data_inclusao IS NOT NULL ";
                }
            }

            // if($periodo_inicio == "Início de Atendimento"){
            //     if($periodo_inicio_opcoes == "Período"){
            //         $cdc_where .= " AND atendimento.data_inclusao >= '" . $periodo_inic_dt_inic . "' ";
            //     }

            //     else if($periodo_inicio_opcoes == "Nulo"){
            //         $cdc_where .= " AND atendimento.data_inclusao = NULL ";
            //     }

            //     else if($periodo_inicio_opcoes == "Não Nulo"){
            //         $cdc_where .= " AND atendimento.data_inclusao IS NOT NULL ";
            //     }
            // }



            $sql_relatorio .= $cdc_where;
            $sql_relatorio .= " GROUP BY " . $campos_agrupamento;
            // $sql_relatorio .= " LIMIT 20 OFFSET 5 ";

                // echo $sql_relatorio;die;

            $resultado_relatorio = $db->fetchAll($sql_relatorio);

            // pr($resultado_relatorio );die;

            $retorno = array();
            
            if(trim($tituloRelatorio) != ""){
                $retorno['titulo_relatorio'] = $tituloRelatorio;
            }

            if(trim($labelOrgao) != ""){
                $retorno['label_orgao'] = $labelOrgao;
            }

            if(isset($agrupamento) && !empty($agrupamento) && !empty($resultado_relatorio)){
                $retorno['agrupamento'] = $agrupamento;
                $retorno['resultado']   = $resultado_relatorio;
            }else{
                $retorno = $resultado_relatorio;
            }

            return $retorno;
            
            
    }

    public function consultaPersonalizada(){

        $this->layout = 'ajax';
        if($this->request->is('post')){

            $dados = $this->request->data['Relatorio'];
            $retorno = $this->preConsultaPersonalizada($dados);

            $this->set('resultado_relatorio',$retorno);
            
        }

    }

    public function impressaoRelatorioPersonalizado(){
        // die("oi");
        // pr($this->request->data);die;
        $dados = $this->request->data['Relatorio'];
        // $dados = $this->request->query['data']['Relatorio'];
        $retorno = $this->preConsultaPersonalizada($dados);
        $this->set('resultado_relatorio',$retorno);
        
        $this->render('impressao_relatorio_personalizado');
    }

    public function personalizado(){
        // die("Relatório Personalizado");

        $this->loadModel("Tipologia");
        $this->loadModel("OrgaoOrigem");
        $this->loadModel("UnidadeAtendimento");
        $this->loadModel("Lotacao");
        $this->loadModel("Funcao");
        $this->loadModel("Atendimento");
        $this->loadModel("Publicacao");
        $this->loadModel("Cargo");

        $ano_exercicio = array();

        $db = $this->Atendimento->getDataSource();
        $sql_anos = "SELECT DISTINCT EXTRACT(year FROM atendimento.data_inclusao) FROM atendimento";
        $arr = $db->fetchAll($sql_anos);
        foreach ($arr as $item) {
        	foreach ($item as $aux) {
        		$ano = array();
        		$ano_exercicio[$aux['date_part']] = $aux['date_part'];
        	}
        }
        $ano_exercicio['Todos'] = "Todos";

        


        $tipologias                 = $this->Tipologia->find('list', array('conditions' => array('ativo' => true)));
        $unidades_atendimento       = $this->UnidadeAtendimento->find('list', array('conditions' => array('ativo' => true)));
        $orgao_origem				= $this->OrgaoOrigem->find('list', array('conditions' => array('ativo' => true)));
        $lotacao					= $this->Lotacao->find('list', array('conditions' => array('ativo' => true)));
        $funcao						= $this->Funcao->find('list', array('conditions' => array('ativo' => true)));
        $cargos						= $this->Cargo->find('list', array('conditions' => array('ativo' => true)));
        $publicacoes                = $this->Publicacao->find(
                                            'all', 
                                            array('recursive' => -1), 
                                            array('conditions' => array('ativo' => true)
                                        ));


        // $opcoes = ;
        $ret_publicacoes = array();
        foreach ($publicacoes as $publicacao) {
            $aux = "Publicação " . $publicacao['Publicacao']['id'];
            $aux .= " (" . date('d/m/Y', strtotime( str_replace("-", "/", $publicacao['Publicacao']['data_publicacao'])) );  
            $aux .= ")";
            $ret_publicacoes[$publicacao['Publicacao']['id']] = $aux;
        }
        // pr($ret_publicacoes);die;

        // pr($orgao_origem);die;

        $tipo_usuario = array(
        	USUARIO_PERITO_CREDENCIADO => "Perito Credenciado",
        	USUARIO_PERITO_SERVIDOR    => "Perito Servidor",
        	USUARIO_INTERNO            => "Administrativo",
        	USUARIO_SERVIDOR           => "Servidor / Gestor",
        );
        
       
        $sexo = array(
            SEXO_MASCULINO  => "Masculino",
            SEXO_FEMININO   => "Feminino"
        );
        $status_agendamento = array(
        	"Agendado" 			=> "Agendado",
        	"Em Atendimento" 	=> "Em Atendimento",
        	"Atendido" 			=> "Atendido",
			"Agendado" 			=> "Agendado"
        );
        
        $status_atendimento = array(
        	"Pendente" 		=> "Pendente",
        	"Salvo"			=> "Salvo",
        	"Finalizado" 	=> "Finalizado"
		);

       
        $controle = array(
            "Agendamento"  => "Agendamentos", 
            "Atendimento"  => "Atendimentos", 
            // "Licenca"      => "Licenças", 
            "Todos"        => "Todos"
        );

        $agrupado = array(
            "Com agrupamento por CID"       => "Com agrupamento por CID", 
            "Com agrupamento por Perito"    => "Com agrupamento por Perito", 
            "Sem agrupamento"               => "Sem agrupamento"
        );

        $filtros_licenca = array(
            "Tipologia"                 => "Tipologia",
            "Sexo"                      => "Sexo",
            "Status Agendamento"        => "Status Agendamento",
            "Status Atendimento"        => "Status Atendimento",
            "Situação Atendimento"      => "Situação Atendimento",
            "Orgão"                     => "Orgão",
            "Cargo"                     => "Cargo",
            "Lotação"                   => "Lotação",
            "Função"                    => "Função",
            "Tipo de Usuário"           => "Tipo de Usuário",       
            // "Estado"                    => "Estado",       
            // "Município"                 => "Município",       
            "Unidade de Atendimento"    => "Unidade de Atendimento",       
            "Publicação"                => "Publicação" 
        );

        $conteudo = array(
            "Iniciado por"  =>  "Iniciado por", 
            "Igual a"       =>  "Igual a", 
            "Contém"        =>  "Contém"
        );

        $demais_campos = array(
            "CPF do Servidor"       => "CPF do Servidor", 
            "Nome do Servidor"      => "Nome do Servidor", 
            "CPF do Perito"         => "CPF do Perito",
            "Nome do Perito"        => "Nome do Perito",
            // "Número do Laudo"       => "Número do Laudo",
            "Número do Agendamento" => "Número do Agendamento"
        );

        $periodo_inicio = array(
            "Início de Agendamento" => "Início de Agendamento", 
            "Início de Atendimento" => "Início de Atendimento"
        );

        $periodo_fim = array(
            "Término de Agendamento" => "Término de Agendamento", 
            "Término de Atendimento" => "Término de Atendimento"
        );
        $opcoes_periodo = array(
            "Período"   => "Período", 
            "Nulo"      => "Nulo", 
            "Não Nulo"  => "Não Nulo"
        );

        $disponiveis = array(
            "grupo_cid"             => "CIDs", 
            "grupo_tipologia"       => "Tipologia", 
            "grupo_tipologia_cid"   => "Tipologia/CID", 
            "grupo_sexo"            => "Sexo", 
            "grupo_publicacao"      => "Publicação", 
            "grupo_municipio"       => "Município", 
            "grupo_perito"          => "Perito",
            "grupo_unidade"         => "Unidade de Atendimento"
        );

        $campos_exibicao = array(
            "NOME"                  => "NOME", 
            "NOME DA UNIDADE"       => "NOME DA UNIDADE",
            "BAIRRO"                => "BAIRRO",
            "CPF"                   => "CPF", 
            "SEXO"                  => "SEXO", 
            // "IDADE"              => "IDADE", 
            "QTD TIPOLOGIAS"          => "QTD TIPOLOGIAS", 
            "FUNÇÃO"                => "FUNÇÃO", 
            "DATA DE ADMISSÃO"      => "DATA DE ADMISSÃO",
            "PERÍODO DE LICENÇA"    => "PERÍODO DE LICENÇA",
            "NOME DO ORGÃO"         => "NOME DO ORGÃO"
        );

        $ordenacao = array("ASC" => "Crescente", "DESC" => "Descrescente");


        $situacao_atendimento = array(
            SITUACAO_EM_EXIGENCIA       => "Em exigência",
            SITUACAO_INDEFERIDO         => "Indeferido",
            SITUACAO_PROPORCIONAL       => "Proporcional",
            SITUACAO_INTEGRAL           => "Integral",
            SITUACAO_TEMPORARIO         => "Temporário",
            SITUACAO_DEFINITIVO         => "Definitivo",
            SITUACAO_PROVISORIO         => "Provisório",
            SITUACAO_DEFERIDO           => "Deferido",
            SITUACAO_APTO               => "Apto",
            SITUACAO_NAO_APTO           => "Não Apto",
            SITUACAO_SE_ENQUADRA        => "Se enquadra",
            SITUACAO_NAO_SE_ENQUADRA    => "Não se enquadra",
        );


        $this->set('tipologias', $tipologias);
        $this->set('unidades_atendimento', $unidades_atendimento);
        $this->set('orgao_origem', $orgao_origem);
        $this->set('cargo', $cargos);
        $this->set('lotacao', $lotacao);
        $this->set('funcao', $funcao);
        $this->set('tipo_usuario', $tipo_usuario);
        $this->set('sexo', $sexo);
        $this->set('status_agendamento', $status_agendamento);
        $this->set('status_atendimento', $status_atendimento);
        $this->set('filtros_licenca', $filtros_licenca);
        $this->set('disponiveis', $disponiveis);
        $this->set('ordenacao', $ordenacao);
        $this->set('campos_exibicao', $campos_exibicao);
        $this->set('opcoes_periodo', $opcoes_periodo);
        $this->set('demais_campos', $demais_campos);
        $this->set('periodo_inicio', $periodo_inicio);
        $this->set('periodo_fim', $periodo_fim);
        $this->set('controle', $controle);
        $this->set('conteudo', $conteudo);
        $this->set('ano_exercicio', $ano_exercicio);
        $this->set('agrupado', $agrupado);
        $this->set('publicacoes', $ret_publicacoes);
        $this->set('situacao_atendimento', $situacao_atendimento);

    }

    /**
     * Carrega as tipologias
     */
    private function carregarListaTipologia() {
        $this->loadModel("Tipologia");
        $filtro = new BSFilter();
        $filtro->setTipo('list');
        $filtro->setCamposOrdenadosString('Tipologia.nome');
        $tipologias = $this->Tipologia->listar($filtro);
        $this->set(compact('tipologias'));
    }

    public function impressao(){
        $this->layout = false;
        $this->request->query['data']['Relatorio']['impressao'] = '1';
        $dados = $this->request->query['data']['Relatorio'];
        $this->preRelatorio($dados);

    }

    public function preRelatorio($dados){

        $dados['data_inicial'] = Util::inverteData($dados['data_inicial']);
        $impressao = $dados['impressao'];
        if(isset($dados['data_final']) && !empty($dados['data_final'])){
            $dados['data_final'] = Util::inverteData($dados['data_final']);
        }else{
            $dados['data_final'] = date('Y-m-d');
        }

        $render = '';
        switch($dados['tipo_relatorio']){
            case RELATORIO_ATENDIMENTOS_TOTAIS:
                $this->set('titulo', 'Número de atendimentos totais');
                $render = ($impressao == "0") ? 'relatorio_atendimentos_totais' : 'relatorio_atendimentos_totais_pdf';
                $this->relatorioAtendimentoTotais($dados);
                break;
            case RELATORIO_AGRUPADOS:
                $render = ($impressao == "0") ? 'relatorio_agrupados' : 'relatorio_agrupados_pdf';
                $this->relatorioAtendimentosAgrupados($dados);
                break;
            case RELATORIO_PROCESSOS_PUBLICADOS:
                $this->set('titulo', 'Processos publicados');
                $this->relatorioPublicados($dados);
                //$render = 'relatorio_processos_publicados';
                $render = ($impressao == "0") ? 'relatorio_atendimentos_totais' : 'relatorio_atendimentos_totais_pdf';
                break;
            case RELATORIO_ATENDIMENTOS_POR_GENEREO:
                $this->set('titulo', 'Atendimentos mensal por gênero');
                $this->agrupadoPorGeneroMes($dados);
                $render = ($impressao == "0") ? 'relatorio_atendimentos_mes' : 'relatorio_atendimentos_mes_pdf';
                break;
            case RELATORIO_ATENDIMENTOS_POR_PERITO:
                $this->set('titulo', 'Atendimentos mensal por perito');
                $this->agrupadoPorPeritoMes($dados);
                $render = ($impressao == "0") ? 'relatorio_atendimentos_mes' : 'relatorio_atendimentos_mes_pdf';
                break;
            case RELATORIO_DIAS_DE_LICENCA:
                $this->set('titulo', 'Dias concedidos de licença');
                $this->diasDeLicenca($dados);
                $render = ($impressao == "0") ? 'relatorio_atendimentos_totais' : 'relatorio_atendimentos_totais_pdf';
                break;
            case RELATORIO_TOTAL_DEFERIDOS_INDEFERIDOS_EM_EXIGENCIA:
                $this->set('titulo', 'Total deferidos / indeferidos / em exigência');
                $this->agrupadoPorDeferidoIndeferidoEmExigencia($dados);
                $render = ($impressao == "0") ? 'relatorio_agrupados' : 'relatorio_agrupados_pdf';
                break;
        }


        $id = $dados['tipo_relatorio'];
        $currentFunction = $this->request->params['action']; //function corrente
        $currentController = $this->name; //Controller corrente
        $this->saveAuditLog($id,$currentController,'C',$currentFunction);



        $this->render($render);
    }

    public function relatorio(){

        // die("oi");

        $this->layout = 'ajax';
        $dados = $this->request->data['Relatorio'];

        $this->preRelatorio($dados);

    }

    public function relatorioTeste(){
        die("Teste relatorio personalizado");
    }

    private function relatorioAtendimentoTotais($dados){
        $data_inicial = trim($dados['data_inicial']);
        $data_final = trim($dados['data_final']);

        $this->loadModel('Atendimento');
        $filtro = new BSFilter();
        $filtro->setTipo('all');
        $filtro->setCamposRetornados(['count(Atendimento.id) as "Tipologia__qtd"', 'Tipologia.nome']);
        $filtro->setCamposAgrupados(array('Tipologia.id'));
        $filtro->setCamposOrdenadosString('count(Atendimento.id)');

        $joins = array();
        $joins[] = array(
            'table' => 'tipologia',
            'alias' => 'Tipologia',
            'type' => 'left',
            'conditions' => array('Tipologia.id = Agendamento.tipologia_id')
        );
        $filtro->setJoins($joins);

        $condicoes= array();
        $condicoes[] = array(
            'Atendimento.status_atendimento' => 'Finalizado',
            'Atendimento.data_inclusao >= ' => $data_inicial.' 00:00:00',
            'Atendimento.data_inclusao <= ' => $data_final.' 23:59:59'
        );
        if($dados['tipologia_id']){
            $condicoes[] = array('Agendamento.tipologia_id' => $dados['tipologia_id']);
        }
        $filtro->setCondicoes($condicoes);

        $resultado = $this->Atendimento->listar($filtro);
        $this->set('resultado', $resultado);
    }

    private function relatorioAtendimentosAgrupados($dados){
        $data_inicial = $dados['data_inicial'];
        $data_final = $dados['data_final'];

        switch($dados['tipo_agrupamento']){
            case RELATORIO_AGRUPADOS_POR_MUNICIPIO:
                $this->set('titulo', 'Atendimentos agrupados por município');
                $result = $this->agrupadoPorMunicipio($data_inicial, $data_final, $dados['tipologia_id']);
                break;
            case RELATORIO_AGRUPADOS_POR_SECRETARIA:
                $this->set('titulo', 'Atendimentos agrupados por secretaria');
                $result = $this->agrupadoPorSecretaria($data_inicial, $data_final, $dados['tipologia_id']);
                break;
            case RELATORIO_AGRUPADOS_POR_CID:
                $this->set('titulo', 'Atendimentos agrupados por CID');
                $result = $this->agrupadoPorCID($data_inicial, $data_final, $dados['tipologia_id']);
                break;
        }
        $this->set('resultado', $result);
    }


    private function agrupadoPorMunicipio($data_inicial, $data_final, $tipologia =''){
        $this->loadModel('Atendimento');
        $db = $this->Atendimento->getDataSource();

        $sql = 'select sum(qtd) as qtd, tipologia, cidade "agrupamento", municipio_id "agrupamento_id" from (

                SELECT count(ate.id) "qtd",
                tip.nome "tipologia",
                tip.id "tipologia_id",
                (mua.nome || \'/\' || eua.sigla) "cidade",
                endua.municipio_id
                from atendimento ate
                inner join agendamento age on age.id = ate.agendamento_id
                inner join tipologia tip on tip.id = age.tipologia_id
                left join unidade_atendimento ua on ua.id = age.unidade_atendimento_id
                left join endereco endua on endua.id = ua.endereco_id
                left join municipio mua on mua.id = endua.municipio_id
                left join estado eua on  eua.id = endua.estado_id
                where ate.data_inclusao >= :data_ini and ate.data_inclusao <=  :data_fim
                and ate.status_atendimento = \'Finalizado\'
                and age.endereco_id_atend_domici is null
                group by tip.id, cidade, municipio_id

                union

                SELECT count(ate.id) "qtd",
                tip.nome "tipologia",
                tip.id "tipologia_id",
                (mdom.nome || \'/\' || edom.sigla) "cidade",
                endd.municipio_id
                from atendimento ate
                inner join agendamento age on age.id = ate.agendamento_id
                inner join tipologia tip on tip.id = age.tipologia_id
                left join endereco endd on endd.id = age.endereco_id_atend_domici
                left join municipio mdom on mdom.id = endd.municipio_id
                left join estado edom on edom.id = endd.estado_id
                where ate.data_inclusao >= :data_ini and ate.data_inclusao <=  :data_fim
                and ate.status_atendimento = \'Finalizado\'
                and age.endereco_id_atend_domici is not null
                group by tip.id, cidade, municipio_id
        ) tbl ';
        $values = array('data_ini' => $data_inicial." 00:00", 'data_fim' =>$data_final." 23:59");
        if(!empty($tipologia)){
            $sql .= " where tipologia_id= :tipologia";
            $values['tipologia'] = $tipologia;
        }
        $sql .=' group by tipologia, agrupamento, agrupamento_id
        order by tipologia, cidade';

        // pr($sql);die;
        // pr($data_inicial);
        // pr($data_final);
        return $db->fetchAll($sql, $values);
    }

    private function agrupadoPorSecretaria($data_inicial, $data_final, $tipologia =''){
        $this->loadModel('Atendimento');
        $db = $this->Atendimento->getDataSource();

        $sql = 'SELECT count(ate.id) "qtd",
                tip.nome "tipologia",
                l.nome "agrupamento",
                l.id "agrupamento_id"
                from atendimento ate
                inner join agendamento age on age.id = ate.agendamento_id
                inner join tipologia tip on tip.id = age.tipologia_id
                inner join usuario usu on usu.id =  age.usuario_servidor_id
                inner join vinculo v on v.usuario_id = usu.id
                inner join vinculo_lotacao vl on vl.vinculo_id = v.id
                inner join lotacao l on l.id =  vl.lotacao_id
                where ate.data_inclusao >= :data_ini and ate.data_inclusao <=  :data_fim
                and ate.status_atendimento = \'Finalizado\' ';
        $values = array('data_ini' => $data_inicial." 00:00", 'data_fim' =>$data_final." 23:59");
        if(!empty($tipologia)){
            $sql .= " and tip.id= :tipologia";
            $values['tipologia'] = $tipologia;
        }
        $sql .=' group by tipologia, agrupamento, agrupamento_id
        order by tipologia, agrupamento';

        // pr($sql);
        // pr($data_inicial);
        // pr($data_final);
        return $db->fetchAll($sql, $values);
    }


    private function agrupadoPorCID($data_inicial, $data_final, $tipologia =''){
        $this->loadModel('Atendimento');
        $db = $this->Atendimento->getDataSource();

        $sql = 'SELECT count(ate.id) "qtd",
                tip.nome "tipologia",
                cid.nome "agrupamento",
                cid.id "agrupamento_id"
                from atendimento ate
                inner join agendamento age on age.id = ate.agendamento_id
                inner join tipologia tip on tip.id = age.tipologia_id
                left join cid on cid.id = age.cid_id
                where ate.data_parecer >= :data_ini and ate.data_parecer <=  :data_fim
                and ate.status_atendimento = \'Finalizado\' ';
        $values = array('data_ini' => $data_inicial." 00:00", 'data_fim' =>$data_final." 23:59");
        if(!empty($tipologia)){
            $sql .= " and tip.id= :tipologia";
            $values['tipologia'] = $tipologia;
        }
        $sql .=' group by tipologia, agrupamento, agrupamento_id
        order by tipologia, agrupamento';
        return $db->fetchAll($sql, $values);
    }

    private function agrupadoPorDeferidoIndeferidoEmExigencia($dados){

        $data_inicial = $dados['data_inicial'];
        $data_final = $dados['data_final'];

        $this->loadModel('Atendimento');
        $db = $this->Atendimento->getDataSource();

        $deferido = TipoSituacaoParecerTecnico::DEFERIDO;
        $indeferido = TipoSituacaoParecerTecnico::INDEFERIDO;
        $em_exigencia = TipoSituacaoParecerTecnico::EM_EXIGENCIA;

        $situacoes = "$deferido, $indeferido, $em_exigencia";

        $sql = 'SELECT count(ate.id) "qtd",
                tip.nome "tipologia",
                spt.nome "agrupamento",
                spt.id "agrupamento_id"
                from atendimento ate
                inner join agendamento age on age.id = ate.agendamento_id
                inner join tipologia tip on tip.id = age.tipologia_id
                inner join sit_parecer_tec spt on spt.id = ate.situacao_id
                where ate.data_inclusao >= :data_ini and ate.data_inclusao <=  :data_fim
                and ate.status_atendimento = \'Finalizado\'
                and ate.situacao_id in ('.$situacoes.')
                ';      
        $values = array('data_ini' => $data_inicial." 00:00", 'data_fim' =>$data_final." 23:59");

        if(isset($dados['tipologia_id']) &&!empty($dados['tipologia_id'])){
            $sql .= " and tip.id= :tipologia";
            $values['tipologia'] = $dados['tipologia_id'];
        }
        $sql .=' group by tipologia, agrupamento, agrupamento_id
        order by tipologia, agrupamento';

        $this->set('resultado', $db->fetchAll($sql, $values));
    }

    private function agrupadoPorGeneroMes($dados){
        $data_inicial = $dados['data_inicial'];
        $data_final = $dados['data_final'];
        $tipologia = $dados['tipologia_id'];

        $this->loadModel('Atendimento');
        $db = $this->Atendimento->getDataSource();

        $months = array();
        for($m=1; $m <= 12; $m++){
            $sql = 'SELECT count(ate.id) "qtd",
                tip.nome "tipologia",
                s.nome "agrupamento",
                s.id "agrupamento_id"
                from atendimento ate
                inner join agendamento age on age.id = ate.agendamento_id
                inner join tipologia tip on tip.id = age.tipologia_id
                inner join usuario usu on usu.id =  age.usuario_servidor_id
                inner join sexo s on s.id =  usu.sexo_id
                where ate.data_inclusao >= :data_ini and ate.data_inclusao <=  :data_fim
                and ate.status_atendimento = \'Finalizado\'
                and date_part(\'month\',  ate.data_inclusao) = '.$m;


            $values = array('data_ini' => $data_inicial." 00:00", 'data_fim' =>$data_final." 23:59");
            if(!empty($tipologia)){
                $sql .= " and tip.id= :tipologia";
                $values['tipologia'] = $tipologia;
            }
            $sql .=' group by tipologia, agrupamento, agrupamento_id
        order by tipologia, agrupamento';

                // pr($sql);
                // pr($data_inicial);
                // pr($data_final);

            $result =  $db->fetchAll($sql, $values);
            if($result && count($result) >0){
                $months[$m] = array('id'=> $m, 'nome' => $this->getMes($m), 'resultado' =>$result);
            }
        }
        $this->set('resultados', $months);
    }


    private function agrupadoPorPeritoMes($dados){
        $data_inicial = $dados['data_inicial'];
        $data_final = $dados['data_final'];
        $tipologia = $dados['tipologia_id'];

        $sqlTip = '';
        $values = array('data_ini' => $data_inicial." 00:00", 'data_fim' =>$data_final." 23:59");
        if(!empty($tipologia)){
            $sqlTip = " and tip.id= :tipologia";
            $values['tipologia'] = $tipologia;
        }

        $this->loadModel('Atendimento');
        $db = $this->Atendimento->getDataSource();

        $months = array();
        for($m=1; $m <= 12; $m++){
            $sql = 'SELECT count(ate.id) "qtd",
                tip.nome "tipologia",
                usu.nome "agrupamento",
                usu.id "agrupamento_id"
                from atendimento ate
                inner join agendamento age on age.id = ate.agendamento_id
                inner join tipologia tip on tip.id = age.tipologia_id
                inner join usuario usu on usu.id =  ate.usuario_versao_id
                where ate.data_inclusao >= :data_ini and ate.data_inclusao <=  :data_fim
                and ate.status_atendimento = \'Finalizado\'
                and date_part(\'month\',  ate.data_inclusao) = '.$m
                .$sqlTip.' group by tipologia, agrupamento, agrupamento_id
                order by tipologia, agrupamento';

                // pr($sql);
                // pr($data_inicial);
                // pr($data_final);
                // pr($sql);
            $result =  $db->fetchAll($sql, $values);
            if($result && count($result) >0){
                $months[$m] = array('id'=> $m, 'nome' => $this->getMes($m), 'resultado' =>$result);
            }
        }
        $this->set('resultados', $months);
    }

    private function diasDeLicenca($dados){
        $data_inicial = $dados['data_inicial'];
        $data_final = $dados['data_final'];

        $this->loadModel('Atendimento');
        $filtro = new BSFilter();
        $filtro->setTipo('all');
        $filtro->setCamposRetornados(['sum(Agendamento.duracao) as "Tipologia__qtd"', 'Tipologia.nome']);
        $filtro->setCamposAgrupados(array('Tipologia.id'));
        $filtro->setCamposOrdenadosString('Tipologia.nome');

        $joins = array();
        $joins[] = array(
            'table' => 'tipologia',
            'alias' => 'Tipologia',
            'type' => 'left',
            'conditions' => array('Tipologia.id = Agendamento.tipologia_id')
        );
        $filtro->setJoins($joins);
        // pr($data_inicial);
        $condicoes= array();
        $condicoes[] = array(
            'Atendimento.status_atendimento' => 'Finalizado',
            'Atendimento.data_parecer >= ' => Util::inverteData($data_inicial),
            'Atendimento.data_parecer <= ' => Util::inverteData($data_final),
            'Agendamento.tipologia_id in '=> array(
                TIPOLOGIA_LICENCA_ACOMPANHAMENTO_FAMILIAR, TIPOLOGIA_LICENCA_MATERNIDADE,
                TIPOLOGIA_LICENCA_MATERNIDADE_ABORTO, TIPOLOGIA_LICENCA_MEDICA_TRATAMENTO_SAUDE,
                TIPOLOGIA_LICENCA_NATIMORTO)
        );
        if($dados['tipologia_id']){
            $condicoes[] = array('Agendamento.tipologia_id' => $dados['tipologia_id']);
        }
        // pr($condicoes);
        $filtro->setCondicoes($condicoes);

        $resultado = $this->Atendimento->listar($filtro);
        $this->set('resultado', $resultado);
    }


    private function relatorioPublicados($dados){

        $data_inicial = trim($dados['data_inicial']);
        $data_final = trim($dados['data_final']);

        $this->loadModel('Atendimento');
        $filtro = new BSFilter();
        $filtro->setTipo('all');
        $filtro->setCamposRetornados(['count(Atendimento.id) as "Tipologia__qtd"', 'Tipologia.nome']);
        $filtro->setCamposAgrupados(array('Tipologia.id'));
        $filtro->setCamposOrdenadosString('Tipologia.nome');

        $joins = array();
        $joins[] = array(
            'table' => 'tipologia',
            'alias' => 'Tipologia',
            'type' => 'left',
            'conditions' => array('Tipologia.id = Agendamento.tipologia_id')
        );
        $joins[] = array(
            'table' => 'publicacao_atendimento',
            'alias' => 'PublicacaoAtendimento',
            'type' => 'inner',
            'conditions' => array('PublicacaoAtendimento.atendimento_id = Atendimento.id')
        );

        $joins[] = array(
            'table' => 'publicacao',
            'alias' => 'Publicacao',
            'type' => 'inner',
            'conditions' => array('Publicacao.id = PublicacaoAtendimento.publicacao_id')
        );

        $filtro->setJoins($joins);

        $condicoes= array();
        $condicoes[] = array(
            'Atendimento.status_atendimento' => 'Finalizado',
            'Publicacao.data_publicacao >= ' => $data_inicial.' 00:00:00',
            'Publicacao.data_publicacao <= ' => $data_final.' 23:59:59'
        );
        if($dados['tipologia_id']){
            $condicoes[] = array('Agendamento.tipologia_id' => $dados['tipologia_id']);
        }

        // pr($joins);
        // pr($condicoes);

        $filtro->setCondicoes($condicoes);

        $resultado = $this->Atendimento->listar($filtro);
        $this->set('resultado', $resultado);
    }

}

