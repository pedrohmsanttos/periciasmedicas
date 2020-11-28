<?php
/**
 * This file is loaded automatically by the app/webroot/index.php file after core.php
 *
 * This file should load/create any application wide configuration settings, such as
 * Caching, Logging, loading additional configuration files.
 *
 * You should also use this file to include any files that provide global functions/constants
 * that your application uses.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Config
 * @since         CakePHP(tm) v 0.10.8.2117
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

// Setup a 'default' cache configuration for use in the application.
Cache::config('default', array('engine' => 'File'));


set_time_limit(0);
ini_set('memory_limit', '-1');
ini_set('max_input_vars', 3000);
/**
 * The settings below can be used to set additional paths to models, views and controllers.
 *
 * App::build(array(
 *     'Model'                     => array('/path/to/models/', '/next/path/to/models/'),
 *     'Model/Behavior'            => array('/path/to/behaviors/', '/next/path/to/behaviors/'),
 *     'Model/Datasource'          => array('/path/to/datasources/', '/next/path/to/datasources/'),
 *     'Model/Datasource/Database' => array('/path/to/databases/', '/next/path/to/database/'),
 *     'Model/Datasource/Session'  => array('/path/to/sessions/', '/next/path/to/sessions/'),
 *     'Controller'                => array('/path/to/controllers/', '/next/path/to/controllers/'),
 *     'Controller/Component'      => array('/path/to/components/', '/next/path/to/components/'),
 *     'Controller/Component/Auth' => array('/path/to/auths/', '/next/path/to/auths/'),
 *     'Controller/Component/Acl'  => array('/path/to/acls/', '/next/path/to/acls/'),
 *     'View'                      => array('/path/to/views/', '/next/path/to/views/'),
 *     'View/Helper'               => array('/path/to/helpers/', '/next/path/to/helpers/'),
 *     'Console'                   => array('/path/to/consoles/', '/next/path/to/consoles/'),
 *     'Console/Command'           => array('/path/to/commands/', '/next/path/to/commands/'),
 *     'Console/Command/Task'      => array('/path/to/tasks/', '/next/path/to/tasks/'),
 *     'Lib'                       => array('/path/to/libs/', '/next/path/to/libs/'),
 *     'Locale'                    => array('/path/to/locales/', '/next/path/to/locales/'),
 *     'Vendor'                    => array('/path/to/vendors/', '/next/path/to/vendors/'),
 *     'Plugin'                    => array('/path/to/plugins/', '/next/path/to/plugins/'),
 * ));
 *
 */

/**
 * Custom Inflector rules can be set to correctly pluralize or singularize table, model, controller names or whatever other
 * string is passed to the inflection functions
 *
 * Inflector::rules('singular', array('rules' => array(), 'irregular' => array(), 'uninflected' => array()));
 * Inflector::rules('plural', array('rules' => array(), 'irregular' => array(), 'uninflected' => array()));
 *
 */

/**
 * Plugins need to be loaded manually, you can either load them one by one or all of them in a single call
 * Uncomment one of the lines below, as you need. Make sure you read the documentation on CakePlugin to use more
 * advanced ways of loading plugins
 *
 * CakePlugin::loadAll(); // Loads all plugins at once
 * CakePlugin::load('DebugKit'); //Loads a single plugin named DebugKit
 *
 */

// CakePlugin::load(array('Admin', 'Auditable'));
CakePlugin::load(array('Web', 'Auditable'));

/**
 * You can attach event listeners to the request lifecycle as Dispatcher Filter. By default CakePHP bundles two filters:
 *
 * - AssetDispatcher filter will serve your asset files (css, images, js, etc) from your themes and plugins
 * - CacheDispatcher filter will read the Cache.check configure variable and try to serve cached content generated from controllers
 *
 * Feel free to remove or add filters as you see fit for your application. A few examples:
 *
 * Configure::write('Dispatcher.filters', array(
 *		'MyCacheFilter', //  will use MyCacheFilter class from the Routing/Filter package in your app.
 *		'MyCacheFilter' => array('prefix' => 'my_cache_'), //  will use MyCacheFilter class from the Routing/Filter package in your app with settings array.
 *		'MyPlugin.MyFilter', // will use MyFilter class from the Routing/Filter package in MyPlugin plugin.
 *		array('callable' => $aFunction, 'on' => 'before', 'priority' => 9), // A valid PHP callback type to be called on beforeDispatch
 *		array('callable' => $anotherMethod, 'on' => 'after'), // A valid PHP callback type to be called on afterDispatch
 *
 * ));
 */
Configure::write('Dispatcher.filters', array(
	'AssetDispatcher',
	'CacheDispatcher'
));

/**
 * Configures default file logging options
 */
App::uses('CakeLog', 'Log');
CakeLog::config('debug', array(
	'engine' => 'File',
	'types' => array('notice', 'info', 'debug'),
	'file' => 'debug',
));
CakeLog::config('error', array(
	'engine' => 'File',
	'types' => array('warning', 'error', 'critical', 'alert', 'emergency'),
	'file' => 'error',
));

Configure::write(array(
'ACAO_INSERIR'=>'acaoInserir',
'ACAO_ALTERAR'=>'acaoAlterar',
'ACAO_VISUALIZAR'=>'acaoVisualizar',
'ACAO_EXCLUIR' => 'acaoExcluir',
'ACAO_HOMOLOGAR' => 'acaoHomologar'
));
date_default_timezone_set("America/Recife");

Configure::write('recatpch_settings', array(
    'public_key'=>'6Ld7pMESAAAAAHd1VihJkqPUXAJVwU3Cghc8fzrq',
    'private_key'=>'6Ld7pMESAAAAAMhr5WSk5bcRrff8Y08NtDi8Buoq'
));
// DEFINE('BS_PLUGIN_NAME','admin');
DEFINE('BS_PLUGIN_NAME','web');
DEFINE('BS_PLUGIN_CSS',"/".BS_PLUGIN_NAME."/css/");
DEFINE('BS_PLUGIN_AUDIO',"/".BS_PLUGIN_NAME."/audio/");
DEFINE('BS_PLUGIN_JS',"/".BS_PLUGIN_NAME."/js/");

/**
 * Tipo de usuários
 */
DEFINE('USUARIO_PERITO_CREDENCIADO', 1);
DEFINE('USUARIO_PERITO_SERVIDOR', 2);
DEFINE('USUARIO_INTERNO', 3);
DEFINE('USUARIO_SERVIDOR', 4);

/**
 * Perfis de usuário
 */
DEFINE('PERFIL_ADMINISTADOR', 1);
DEFINE('PERFIL_PERITO', 3);
DEFINE('PERFIL_ADMINISTRATIVO', 4);
DEFINE('PERFIL_SERVIDOR_GESTOR', 5);
DEFINE('PERFIL_PERITO_SERVIDOR', 6);


/**
 * Tipologias fixas
 */
DEFINE('TIPOLOGIA_LICENCA_MATERNIDADE', 1);
DEFINE('TIPOLOGIA_LICENCA_MATERNIDADE_ABORTO', 2);
DEFINE('TIPOLOGIA_LICENCA_NATIMORTO', 3);
DEFINE('TIPOLOGIA_APOSENTADORIA_INVALIDEZ', 4);
DEFINE('TIPOLOGIA_ISENCAO_CONTRIBUICAO_PREVIDENCIARIA', 5);
DEFINE('TIPOLOGIA_REVERSAO_APOSENTADORIA_INVALIDEZ', 6);
DEFINE('TIPOLOGIA_AVALIACAO_HABILITACAO_DEPENDENTES', 7);
DEFINE('TIPOLOGIA_PCD', 8);
DEFINE('TIPOLOGIA_ADMISSAO_PENSIONISTA_MAIOR_INVALIDO', 9);
DEFINE('TIPOLOGIA_INFORMACAO_SEGURO_COMPREENSIVO_HABITACIONAL', 10);
DEFINE('TIPOLOGIA_READAPTACAO_FUNCAO', 11);
DEFINE('TIPOLOGIA_REMANEJAMENTO_FUNCAO', 12);
DEFINE('TIPOLOGIA_RISCO_VIDA_INSALUBRIDADE', 13);
DEFINE('TIPOLOGIA_RECURSO_ADMINISTRATIVO', 17);
DEFINE('TIPOLOGIA_EXAME_PRE_ADMISSIONAL', 18);
DEFINE('TIPOLOGIA_LICENCA_ACOMPANHAMENTO_FAMILIAR', 19);
DEFINE('TIPOLOGIA_ATECIPACAO_LICENCA', 20);
DEFINE('TIPOLOGIA_REMOCAO', 21);
DEFINE('TIPOLOGIA_LICENCA_MEDICA_TRATAMENTO_SAUDE', 23);
DEFINE('TIPOLOGIA_DESIGNACAO_DE_ASSISTENTE_TECNICO', 28);
DEFINE('TIPOLOGIA_COMUNICACAO_DE_ACIDENTE_DE_TRABALHO', 29);
DEFINE('TIPOLOGIA_SINDICANCIA_INQUERITO_PAD',30);
DEFINE('TIPOLOGIA_APOSENTADORIA_ESPECIAL', 31);
DEFINE('TIPOLOGIA_INSPECAO', 32);


DEFINE('TIPO_SINDICANCIA', 1);
DEFINE('TIPO_INQUERITO', 2);
DEFINE('TIPO_PROCESSO_ADMINISTRATIVO', 3);
/**
 * Isenção tipos
 */
DEFINE('ISENCAO_TEMPORARIA', 1);
DEFINE('ISENCAO_DEFINITIVA', 2);

/**
 * Qualidade Outros
 */
DEFINE('QUALIDADE_OUTROS', 21);

/**
 * Vinculo
 */
DEFINE('ESTATUTARIO', 0);
DEFINE('CTD', 1);
DEFINE('CLT', 2);


DEFINE('RELATORIO_ATENDIMENTOS_TOTAIS',1);
DEFINE('RELATORIO_AGRUPADOS', 2);
DEFINE('RELATORIO_PROCESSOS_PUBLICADOS', 3);
DEFINE('RELATORIO_ATENDIMENTOS_POR_GENEREO', 4);
DEFINE('RELATORIO_ATENDIMENTOS_POR_PERITO', 5);
DEFINE('RELATORIO_DIAS_DE_LICENCA', 6);
DEFINE('RELATORIO_TOTAL_DEFERIDOS_INDEFERIDOS_EM_EXIGENCIA', 7);

DEFINE('RELATORIO_AGRUPADOS_POR_MUNICIPIO', 1);
DEFINE('RELATORIO_AGRUPADOS_POR_SECRETARIA', 2);
DEFINE('RELATORIO_AGRUPADOS_POR_CID', 3);


DEFINE('SEXO_MASCULINO', 1);
DEFINE('SEXO_FEMININO', 2);

DEFINE('TIPO_ISENCAO_SERVIDOR',1);
DEFINE('TIPO_ISENCAO_PENSIONISTA',2);

DEFINE('SITUACAO_EM_EXIGENCIA', 1);
DEFINE('SITUACAO_INDEFERIDO', 2);
DEFINE('SITUACAO_PROPORCIONAL', 3);
DEFINE('SITUACAO_INTEGRAL', 4);
DEFINE('SITUACAO_TEMPORARIO', 5);
DEFINE('SITUACAO_DEFINITIVO', 6);
DEFINE('SITUACAO_PROVISORIO', 7);
DEFINE('SITUACAO_DEFERIDO', 8);
DEFINE('SITUACAO_APTO', 9);
DEFINE('SITUACAO_NAO_APTO', 10);
DEFINE('SITUACAO_SE_ENQUADRA', 11);
DEFINE('SITUACAO_NAO_SE_ENQUADRA', 12);


Configure::write('DIAS_SEMANA', array('Domingo' => 0, 'Segunda-feira' => 1, 'Terça-feira' => 2, 'Quarta-feira' => 3, 'Quinta-feira' => 4, 'Sexta-feira' => 5, 'Sábado' => 6));
Configure::write('FORMATOS_UPLOAD', array('pdf'));

header('Content-Type: text/html; charset=utf-8');
header('Cache-Control: no-cache, no-store, must-revalidate'); //HTTP/1.1
header('Expires: Sun, 01 Jul 2005 00:00:00 GMT');
header('Pragma: no-cache');