<?php
/**
* Comment Template.
*
* @todo Implement .this needs to be sorted out.
*
* Copyright (c) 2009 Carl Sutton ( dogmatic69 )
*
* Licensed under The MIT License
* Redistributions of files must retain the above copyright notice.
* @filesource
* @copyright Copyright (c) 2009 Carl Sutton ( dogmatic69 )
* @link http://infinitas-cms.org
* @package sort
* @subpackage sort.comments
* @license http://www.opensource.org/licenses/mit-license.php The MIT License
* @since 0.5a
*/

class Config extends ManagementAppModel {
	var $name = 'Config';

	var $tablePrefix = 'core_';

	var $order = array(
		'Config.core' => 'DESC',
		'Config.key' => 'ASC'
		);

	var $configuration = array(
		'Revision' => array(
			'Core.Revision' => array(
				'active' => 'Config.Revision.active',
				'limit' => 'Config.Revision.limit',
				'auto' => 'Config.Revision.auto',
				'ignore' => 'Config.Revision.ignore',
				'useDbConfig' => 'Config.Revision.useDbConfig',
				'model' => 'Config.Revision.model'
			)
		)
	);

	function getConfig() {
		$configs = Cache::read('core_configs');
		if ($configs !== false) {
			return $configs;
		}

		$configs = $this->find(
			'all',
			array(
				'fields' => array(
					'Config.key',
					'Config.value',
					'Config.type'
					)
				)
			);

		foreach($configs as $k => $config) {
			switch($configs[$k]['Config']['type']) {
				case 'bool':
					switch($configs[$k]['Config']['value']) {
						case 'true':
							$configs[$k]['Config']['value'] = true;
							break;

						case 'false':
							$configs[$k]['Config']['value'] = false;
							break;
					} // switch
					break;

				case 'string':
					$configs[$k]['Config']['value'] = (string)$configs[$k]['Config']['value'];
					break;

				case 'integer':
					$configs[$k]['Config']['value'] = (int)$configs[$k]['Config']['value'];
					break;
			} // switch
		}

		Cache::write('core_configs', $configs, 'core');

		return $configs;
	}

	function beforeFind($queryData) {
		parent::beforeFind($queryData);
		return true;
	}

	function afterSave($created) {
		parent::afterSave($created);

		$this->__clearCache();
		return true;
	}

	function afterDelete() {
		parent::afterDelete();

		$this->__clearCache();
		return true;
	}

	function __clearCache() {
		if (is_file(CACHE . 'core' . DS . 'core_configs')) {
			unlink(CACHE . 'core' . DS . 'core_configs');
		}

		return true;
	}
}

?>