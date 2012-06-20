<?php
	/**
	 * @brief Config model is for saving and editing site configurations.
	 *
	 * @copyright Copyright (c) 2009 Carl Sutton ( dogmatic69 )
	 * @link http://infinitas-cms.org
	 * @package Infinitas.Configs.models
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.5a
	 *
	 * @author dogmatic69
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */

	class Config extends ConfigsAppModel {
		public $useTable = 'configs';
		/**
		 * the display field
		 *
		 * @var string
		 * @access public
		 */
		public $displayField = 'key';

		/**
		 * The default ordering for configs
		 *
		 * @var array
		 * @access public
		 */
		public $order = array();

		/**
		 * @copydoc AppModel::__construct()
		 */
		public function __construct($id = false, $table = null, $ds = null) {
			parent::__construct($id, $table, $ds);

			$this->_configTypes = array(
				0          => __d('configs', 'Please Select'),
				'string'   => __d('configs', 'String'),
				'integer'  => __d('configs', 'Integer'),
				'dropdown' => __d('configs', 'Dropdown'),
				'bool'     => __d('configs', 'Bool'),
				'array'    => __d('configs', 'Array')
			);

			$ruleCheck = $this->_configTypes;
			unset($ruleCheck[0]);

			$this->order = array(
				$this->alias . '.key' => 'ASC'
			);

			$this->validate = array(
				'key' => array(
					'notEmpty' => array(
						'rule' => 'notEmpty',
						'message' => __d('configs', 'Please enter the name of this config')
					),
					'validKeyName' => array(
						'rule' => '/^[A-Z][A-Za-z]*\.[a-z_\.]+$/',
						'message' => __d('configs', 'The key must be in the format "Plugin.config_name"'),
						'on' => 'create'
					)
				),
				'value' => array(
					'notEmpty' => array(
						'rule' => 'notEmpty',
						'message' => __d('configs', 'Please enter the value')
					)
				),
				'type' => array(
					'allowedChoice' => array(
						'rule' => array('inList', array_keys($ruleCheck)),
						'message' => __d('configs', 'Please select the type')
					)
				),
				'options' => array(
					'customOptionCheck' => array(
						'rule' => 'customOptionCheck',
						'message' => __d('configs', 'Please enter some valid options')
					)
				)
			);

		}

		/**
		 * @brief delete config cache
		 *
		 * after saving something delete the main core_configs cache so that
		 * the changes will take effect
		 *
		 * @param bool $created is it new or not
		 * @access public
		 *
		 * @return parent method
		 */
		public function afterSave($created) {
			return parent::afterSave($created);
		}

		/**
		 * @brief delete config cache
		 *
		 * after saving something delete the main core_configs cache so that
		 * the changes will take effect
		 *
		 * @access public
		 *
		 * @return parent method
		 */
		public function afterDelete() {
			return parent::afterDelete();
		}

		/**
		 * @brief validate the options based on what type is set for the row
		 *
		 * @todo this should be renamed to validateCustomOptionCheck
		 *
		 * @param array $data the field being validated
		 * @access public
		 *
		 * @return bool is it valid or not
		 */
		public function customOptionCheck($data){
			if (!isset($this->data[$this->alias]['type']) || empty($this->data[$this->alias]['type'])) {
				return true;
			}

			switch($this->data[$this->alias]['type']){
				case 'string':
					return true;
					break;

				case 'integer':
					if (!empty($data['options']) && is_int($data['options'])) {
						return true;
					}
					break;

				case 'dropdown':
					if (empty($data['options']) || is_int($data['options'])) {
						return false;
					}
					//@todo needs a bit more work
					return is_string($data['options']);

				case 'bool':
					if ($data['options'] === 'true,false' || $data['options'] === 'false,true') {
						return true;
					}
					break;

				case 'array':
					return $this->getJson($this->data[$this->alias]['value'], array(), false);
					break;
			} // switch

			return false;
		}

		/**
		 * @brief Get configuration for the app.
		 *
		 * This gets and formats an array of config values for the app to use. it goes
		 * through the list and formats the values to match the type that was passed.
		 *
		 * @param bool $format should it be formated for configure::write or not.
		 * @access public
		 *
		 * @return array all the config options set to the correct type
		 */
		public function getConfig($format = false) {
			$configs = Cache::read('global_configs');
			if ($configs !== false) {
				if ($format) {
					return $this->__formatConfigs($configs);
				}
				return $configs;
			}

			$configs = $this->find(
				'all',
				array(
					'fields' => array(
						$this->alias . '.key',
						$this->alias . '.value',
						$this->alias . '.type'
					),
					'conditions' => array(
						$this->alias . '.key !=' => -1
					)
				)
			);

			foreach($configs as $k => $config) {
				switch($configs[$k][$this->alias]['type']) {
					case 'bool':
						$configs[$k][$this->alias]['value'] = ($configs[$k][$this->alias]['value'] == 'true') ? true : false;
						break;

					case 'string':
						$configs[$k][$this->alias]['value'] = (string)$configs[$k][$this->alias]['value'];
						break;

					case 'integer':
						$configs[$k][$this->alias]['value'] = (int)$configs[$k][$this->alias]['value'];
						break;

					case 'array':
						$configs[$k][$this->alias]['value'] = $this->getJson($configs[$k][$this->alias]['value']);
						break;
				} // switch
			}

			Cache::write('global_configs', $configs);

			if ($format) {
				return $this->__formatConfigs($configs);
			}

			return $configs;
		}

		/**
		 * @brief format the data into a key value array to be used in Configure::write
		 *
		 * @param array $configs the unformatted configs
		 * @access private
		 *
		 * @return array the data that has been formatted
		 */
		private function __formatConfigs($configs = array(), $json = false){
			if (empty($configs)) {
				return false;
			}

			$format = array();
			foreach($configs as $k => $config) {
				$format[$configs[$k][$this->alias]['key']] = ($json) ? json_encode($configs[$k][$this->alias]['value']) : $configs[$k][$this->alias]['value'];
			}

			return $format;
		}

		/**
		 * @brief Installer setup.
		 *
		 * This gets some config values that are used in the installer for the site
		 * setup.
		 *
		 * @access public
		 *
		 * @return array of all the configs that are needed/able to be done in the installer
		 */
		public function getInstallSetupConfigs(){
			return $this->find(
				'all',
				array(
					'conditions' => array(
						$this->alias . '.key LIKE ' => 'Website.%'
					)
				)
			);
		}

		/**
		 * @brief build up the code to see what is being used or to avoid typing
		 *
		 * Just for generating code for the configs, can be used in the dev installer
		 * plugin stuff to avoid typing out stuff.
		 *
		 * @internal
		 * @access public
		 */
		public function generateCode($config){
			if(isset($config[0])){
				foreach($config as $_config){
					$return[] = self::generateCode($_config);
				}
			}

			if(is_bool($config[$this->alias]['value'])){
				if($config[$this->alias]['value']){
					$_config = 'true';
				}

				else{
					$_config = 'false';
				}
			}

			else if(is_array($config[$this->alias]['value'])){
				$_config = 'array(';
				foreach($config[$this->alias]['value'] as $k => $v){
					$_config .= !is_int($k) ? '\''.$k.'\'' : $k;
					$_config .= '=> \''.$v.'\',';
				}
				$_config .= ')';
			}

			else if(is_string($config[$this->alias]['value'])){
				$_config = '\''.$config[$this->alias]['value'].'\'';
			}

			else{
				$_config = $config[$this->alias]['value'];
			}

			return 'Configure::write(\''.$config[$this->alias]['key'].'\', '.$_config.');';
		}

		/**
		 * @brief Get a list of config options that can be overloaded by an admin user.
		 *
		 * This gets a list of possible values that can be changed in the backend
		 * unsetting a few options first that should not be fiddled with.
		 *
		 * @return array the config options to be overloaded.
		 */
		public function availableConfigs(){
			$configs = Configure::read();
			unset($configs['CORE']['current_route']);
			$configs = Set::flatten($configs);
			ksort($configs);

			return $configs;
		}
	}