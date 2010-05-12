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

	class ConfigsController extends ManagementAppController {
		var $name = 'Configs';

		var $helpers = array(
			'Text'
		);

		var $configOptions = array();

		function beforeFilter() {
			parent::beforeFilter();
		}

		function admin_index() {
			$this->Config->recursive = -1;
			//$this->Filter->filter = array('Config.key LIKE \'Blog%\'');
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

		function admin_add() {
			if (!empty($this->data)) {
				$this->Config->create();
				if ($this->Config->saveAll($this->data)) {
					$this->Session->setFlash('Your config setting has been saved.');
					$this->redirect(array('action' => 'index'));
				}
			}

			$this->set('types', $this->Config->_configTypes);
		}

		function admin_edit($id = null) {
			if (!$id) {
				$this->Session->setFlash(__('That config could not be found', true), true);
				$this->redirect($this->referer());
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
					$this->Session->setFlash(__('Your config has been saved.', true));
					$this->redirect(array('action' => 'index'));
				}

				$this->Session->setFlash(__('Your config could not be saved.', true));
			}

			if ($id && empty($this->data)) {
				$this->data = $this->Config->read(null, $id);
			}
		}
	}