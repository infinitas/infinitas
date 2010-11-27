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
		/**
		 * the name of the model
		 *
		 * @var string
		 * @access public
		 */
		public $name = 'Config';

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
		public $order = array(
			'Config.key' => 'ASC'
		);

		public function __construct($id = false, $table = null, $ds = null) {
			parent::__construct($id, $table, $ds);

			$this->_configTypes = array(
				0          => __('Please Select', true),
				'string'   => __('String', true),
				'integer'  => __('Integer', true),
				'dropdown' => __('Dropdown', true),
				'bool'     => __('Bool', true),
				'array'    => __('Array', true)
			);

			$ruleCheck = $this->_configTypes;
			unset($ruleCheck[0]);

			$this->validate = array(
				'key' => array(
					'notEmpty' => array(
						'rule' => 'notEmpty',
						'message' => __('Please enter the name of this config', true)
					),
					'validKeyName' => array(
						'rule' => '/^[A-Z][A-Za-z]*\.[a-z_]+$/',
						'message' => __('The key must be in the format "Plugin.config_name"', true),
						'on' => 'create'
					)
				),
				'value' => array(
					'notEmpty' => array(
						'rule' => 'notEmpty',
						'message' => __('Please enter the value', true)
					)
				),
				'type' => array(
					'allowedChoice' => array(
						'rule' => array('inList', array_keys($ruleCheck)),
						'message' => __('Please select the type', true)
					)
				),
				'options' => array(
					'customOptionCheck' => array(
						'rule' => 'customOptionCheck',
						'message' => __('Please enter some valid options', true)
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
		public function afterSave($created){
			Cache::delete('global_configs');
			return parent::afterSave($created);
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
			if (!isset($this->data['Config']['type']) || empty($this->data['Config']['type'])) {
				return true;
			}

			switch($this->data['Config']['type']){
				case 'string':
					return true;
					break;

				case 'integer':
					if (!empty($data['options']) && is_int($data['options'])) {
						return true;
					}
					break;

				case 'dropdown':
					if (is_int($data['options'])) {
						return false;
					}
					//@todo needs a bit more work
					return preg_match('/[0-9A-Za-z*\,]+$/', $data['options']);
					break;

				case 'bool':
					if ($data['options'] === 'true,false' || $data['options'] === 'false,true') {
						return true;
					}
					break;

				case 'array':
					return $this->getJson($this->data['Config']['value'], array(), false);
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
			$configs = Cache::read('configs', 'configs');
			if (!empty($configs)) {
				if ($format) {
					return $this->__formatConfigs($configs);
				}
				return $configs;
			}

			$configs = $this->find(
				'all',
				array(
					'fields' => array(
						'Config.key',
						'Config.value',
						'Config.type'
					),
					'conditions' => array(
						'Config.key !=' => -1
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

			Cache::write('configs', $configs, 'configs');

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
		private function __formatConfigs($configs = array()){
			if (empty($configs)) {
				return false;
			}

			$format = array();
			foreach($configs as $k => $config) {
				$format[$configs[$k]['Config']['key']] = $configs[$k]['Config']['value'];
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
						'Config.key LIKE ' => 'Website.%'
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
		 * @access private
		 */
		private function __generateCode(){
			if(is_bool($config['Config']['value'])){
				if($config['Config']['value']){
					$_config = 'true';
				}
				else{
					$_config = 'false';
				}
			}

			else if(is_array($config['Config']['value'])){
				$_config = 'array(';
					foreach($config['Config']['value'] as $k => $v){
						$_config .= !is_int($k) ? '\''.$k.'\'' : $k;
						$_config .= '=> \''.$v.'\',';
					}
				$_config .= ')';
			}
			else if(is_string($config['Config']['value'])){
				$_config = '\''.$config['Config']['value'].'\'';
			}
			else{
				$_config = $config['Config']['value'];
			}

			pr('Configure::write(\''.$config['Config']['key'].'\', '.$_config.');');
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
			$configs = Configure::getInstance();

			unset(
				$configs->CORE['current_route']
			);
			$configs = Set::flatten($configs);
			ksort($configs);

			return $configs;
		}
	}