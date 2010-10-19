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
				'type' => $this->Config->_configTypes,
				'core' => Configure::read('CORE.core_options')
			);

			$this->set(compact('configs', 'filterOptions'));
		}

		public function admin_add() {
			parent::admin_add();

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