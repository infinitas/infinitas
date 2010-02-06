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

	/**
	 * Construct for validation.
	 *
	 * This is used to make the validation messages run through __()
	 *
	 * @param mixed $id
	 * @param mixed $table
	 * @param mixed $ds
	 */
	function __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);

		$this->validate = array(
			'key' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => __('Please enter the name of this config', true)
				),
				'validKeyName' => array(
					'rule' => '/^[A-Z][A-Za-z]*\.[a-z_]+$/',
					'message' => __('The key must be in the format "Plugin.config_name"', true)
				)
			),
			'value' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => __('Please enter the value', true)
				)
			),
			'type' => array(
				'notEmpty' => array(
					'rule' => array('comparison', '>', 0),
					'message' => __('Please select the type', true)
				)
			),
			'options' => array(
				'customOptionCheck' => array(
					'rule' => 'customOptionCheck',
					'message' => __('Please enter some valid options', true)
				)
			),
			'description' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => __('Please enter a good description for this config', true)
				)
			)
		);

		$this->_configTypes = array(
			0          => __('Please Select', true),
			'string'   => __('String', true),
			'integer'  => __('Integer', true),
			'dropdown' => __('Dropdown', true),
			'bool'     => __('Bool', true),
			'array'    => __('Array', true)
		);
	}

	/**
	* customOptionCheck
	*
	* check the options based on what type is set.
	*/
	function customOptionCheck($data){
		if (!isset($this->data['Config']['type']) || empty($this->data['Config']['type'])) {
			return true;
		}

		switch($this->data['Config']['type']){
			case 'string':
				return true;
				break;

			case 'integer':
				return empty($data['options']) ? true : false;
				break;

			case 'dropdown':
				//@todo needs a bit more work
				return preg_match('/[0-9A-Za-z*\,]+$/', $data['options']);
				break;

			case 'bool':
				if ($data['options'] == 'true,false' || $data['options'] == 'false,true') {
					return true;
				}
				break;

			case 'array':
				return $this->getJson($data['options'], array(), false);
				break;
		} // switch

		return false;
	}

	/**
	 * Get configuration for the app.
	 *
	 * This gets and formats an array of config values for the app to use. it goes
	 * through the list and formats the values to match the type that was passed.
	 *
	 * @return array all the config options set to the correct type
	 */
	function getConfig() {
		$configs = Cache::read('core_configs', 'core');
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
					$configs[$k]['Config']['value'] = ($configs[$k]['Config']['value'] == 'true') ? true : false;
					break;

				case 'string':
					$configs[$k]['Config']['value'] = (string)$configs[$k]['Config']['value'];
					break;

				case 'integer':
					$configs[$k]['Config']['value'] = (int)$configs[$k]['Config']['value'];
					break;

				case 'array':
					$configs[$k]['Config']['value'] = $this->getJson($configs[$k]['Config']['value']);
					break;
			} // switch
		}

		Cache::write('core_configs', $configs, 'core');

		return $configs;
	}

	/**
	 * Installer setup.
	 *
	 * This gets some config values that are used in the installer for the site
	 * setup.
	 *
	 * @return array of all the configs that are needed/able to be done in the installer
	 */
	function getInstallSetupConfigs(){
		return $this->find(
			'all',
			array(
				'conditions' => array(
					'Config.key LIKE ' => 'Website.%'
				)
			)
		);
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

	/**
	 * delete cache.
	 *
	 * This deletes the cache after something is changed.
	 *
	 * @return
	 */
	function __clearCache() {
		if (is_file(CACHE . 'core' . DS . 'core_configs')) {
			unlink(CACHE . 'core' . DS . 'core_configs');
		}

		return true;
	}
}

?>