<?php
	/**
	 * The ConfigsController is for managing the site configs from the backend
	 *
	 * @copyright Copyright (c) 2009 Carl Sutton ( dogmatic69 )
 	 * @link http://infinitas-cms.org
	 * @package Infinitas.Configs.controllers
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.5a
	 *
	 * @author dogmatic69
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */

	class ConfigsController extends ConfigsAppController {
		/**
		 * dont see where this is used
		 *
		 * @deprecated
		 */
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
				$this->request->data['Config']['key'] = $this->params['named']['Config.key'];
				$value = Configure::read($this->params['named']['Config.key']);
				switch(true){
					case is_int($value):
							$this->request->data['Config']['type'] = 'integer';
						break;

					case is_bool($value):
						$this->request->data['Config']['type'] = 'bool';
						break;

					default:
						$array = explode(',', $value);
						if(count($array) > 1){
							$this->request->data['Config']['type'] = 'array';
							$this->request->data['Config']['value'] = $array[0];
							$this->request->data['Config']['options'] = $value;
						}
						else{
							$this->request->data['Config']['type'] = 'string';
						}
				}

				if(!isset($this->request->data['Config']['value'])){
					$this->request->data['Config']['value'] = $value;
				}
			}

			$this->set('types', $this->Config->_configTypes);
		}

		public function admin_edit($id = null) {
			if (!$id) {
				$this->notice('invalid');
			}

			if (!empty($this->request->data)) {
				switch($this->request->data['Config']['type']) {
					case 'bool':
						switch($this->request->data['Config']['value']) {
							case 1:
								$this->request->data['Config']['value'] = 'true';
								break;

							default:
								$this->request->data['Config']['value'] = 'false';
						} // switch
						break;
				} // switch
				if ($this->Config->save($this->request->data)) {
					$this->notice('saved');
				}

				$this->notice('not_saved');
			}

			if ($id && empty($this->request->data)) {
				$this->request->data = $this->Config->read(null, $id);
			}
		}
	}