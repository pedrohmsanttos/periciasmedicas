<?php
App::uses('AuditableConfig', 'Auditable.Lib');
App::uses('AppModel', 'Model');
/**
 * Modelo de Exemplo para persistir os logs em algum meio.
 * Neste caso o modelo utiliza a conexão padrão com o Banco de Dados.
 *
 * PHP version > 5.3.1
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Radig - Soluções em TI, www.radig.com.br
 * @link http://www.radig.com.br
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 *
 * @package radig.Auditable
 * @subpackage Model
 */
class Logger extends AppModel
{
	public $primaryKey = 'id';

	public $name = 'Logger';

	public $useTable = 'logs';

	public $displayField = 'id';

	public $actsAs = array('Containable');

	public $belongsTo = array(
		'LogDetail' => array('className' => 'Auditable.LogDetail')
	);

	public $validates = array(
		'model_id' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => array('ID do item de referência é obrigatório')
			)
		),
		'model_alias' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => array('Alias do item de referência é obrigatório')
			)
		)
	);

	/**
	 * Recupera um registro do log e seus detalhes (opcionalmente)
	 *
	 * @param int $id
	 * @param  bool $loadResource
	 * @return array
	 */
	public function get($id, $loadResource = true)
	{
		$contain = array('LogDetail');

		if (!empty(AuditableConfig::$responsibleModel)) {
			$this->bindModel(array(
				'belongsTo' => array(
					'Responsible' => array(
						'className' => AuditableConfig::$responsibleModel,
						'foreignKey' => 'responsible_id'
						)
					)
				)
			);

			$contain[] = 'Responsible';
		}

		$data = $this->find('first', array(
			'conditions' => array('Logger.id' => $id),
			'contain' => $contain
			)
		);

		$linked = null;

		if ($loadResource) {
			$Resource = ClassRegistry::init($data[$this->alias]['model_alias']);

			$linked = $Resource->find('first', array(
				'conditions' => array('id' => $data[$this->alias]['model_id']),
				'recursive' => -1
				)
			);
		}

		if (!empty($linked)) {
			$data[$Resource->alias] = $linked[$Resource->alias];
		}

		if (array_search('Responsible', $contain) === false) {
			$data['Responsible'] = array();
		}

		if (isset($this->Responsible) && empty($data['Responsible'][$this->Responsible->primaryKey])) {
			$data['Responsible'] = array();
		}

		return $data;
	}
}
