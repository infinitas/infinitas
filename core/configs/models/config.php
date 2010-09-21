<?php
	/**
	 * Configs model for saving and editing site configurations.
	 *
	 * Copyright (c) 2009 Carl Sutton ( dogmatic69 )
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 * @filesource
	 * @copyright Copyright (c) 2009 Carl Sutton ( dogmatic69 )
	 * @link http://infinitas-cms.org
	 * @package Infinitas.configs
	 * @subpackage Infinitas.configs.models
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.5a
	 */

	class Config extends ConfigsAppModel {
		public $name = 'Config';

		public $order = array(
			'Config.core' => 'DESC',
			'Config.key' => 'ASC'
		);

		public $configuration = array(
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
				),
				'description' => array(
					'notEmpty' => array(
						'rule' => 'notEmpty',
						'message' => __('Please enter a good description for this config', true)
					)
				)
			);

		}

		/**
		 * customOptionCheck
		 *
		 * check the options based on what type is set.
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
		 * Get configuration for the app.
		 *
		 * This gets and formats an array of config values for the app to use. it goes
		 * through the list and formats the values to match the type that was passed.
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
		 * Installer setup.
		 *
		 * This gets some config values that are used in the installer for the site
		 * setup.
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

		public function afterSave($created){
			Cache::delete('global_configs');
			return parent::afterSave($created);
		}

		/**
		 * Just for generating code for the configs, can be used in the dev installer
		 * plugin stuff to avoid typing out stuff.
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
	}