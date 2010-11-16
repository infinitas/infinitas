<?php
	/**
	 * The configs controller for managing the site configs
	 *
	 * Copyright (c) 2009 Carl Sutton ( dogmatic69 )
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 * @filesource
	 * @copyright Copyright (c) 2009 Carl Sutton ( dogmatic69 )
 	 * @link http://infinitas-cms.org
	 * @package Infinitas.configs
	 * @subpackage Infinitas.configs.controllers
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.5a
	 */

	class ConfigsController extends ConfigsAppController {
		public $name = 'Configs';

		public $configOptions = array();

		public function admin_index() {
			$configs = $this->paginate(null, $this->Filter->filter);

			$filterOptions = $this->Filter->filterOptions;
			$filterOptions['fields'] = array(
				'key',
				'value',
				'type' => $this->Config->_configTypes
			);

			$this->set(compact('configs', 'filterOptions'));
		}

		public function admin_available(){			
			$this->set('configs', $this->Config->availableConfigs());
			$this->set('overloaded', $this->Config->find('list', array('fields' => array('Config.key', 'Config.value'))));
		}

		public function admin_add() {
			parent::admin_add();

			if(isset($this->params['named']['Config.key'])){
				$this->data['Config']['key'] = $this->params['named']['Config.key'];
				$value = Configure::read($this->params['named']['Config.key']);
				switch(true){
					case is_int($value):
							$this->data['Config']['type'] = 'integer';
						break;

					case is_bool($value):
						$this->data['Config']['type'] = 'bool';
						break;

					default:
						$array = explode(',', $value);
						if(count($array) > 1){
							$this->data['Config']['type'] = 'array';
							$this->data['Config']['value'] = $array[0];
							$this->data['Config']['options'] = $value;
						}
						else{
							$this->data['Config']['type'] = 'string';
						}
				}
				
				if(!isset($this->data['Config']['value'])){
					$this->data['Config']['value'] = $value;
				}
			}

			$this->set('types', $this->Config->_configTypes);
		}

		public function admin_edit($id = null) {
			if (!$id) {
				$this->Infinitas->noticeInvalidRecord();
			}

			if (!empty($this->data)) {
				switch($this->data['Config']['type']) {
					case 'bool':
						switch($this->data['Config']['value']) {
							case 1:
								$this->data['Config']['value'] = 'true';
								break;

							default:
								$this->data['Config']['value'] = 'false';
						} // switch
						break;
				} // switch
				if ($this->Config->save($this->data)) {
					$this->Infinitas->noticeSaved();
				}

				$this->Infinitas->noticeNotSaved();
			}

			if ($id && empty($this->data)) {
				$this->data = $this->Config->read(null, $id);
			}
		}
	}