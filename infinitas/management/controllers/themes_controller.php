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

	class ThemesController extends ManagementAppController{
		var $name = 'Themes';

		function beforeFilter() {
			parent::beforeFilter();
		}

		function admin_index() {
			$this->Theme->recursive = 1;
			$themes = $this->paginate(null, $this->Filter->filter);

			$filterOptions = $this->Filter->filterOptions;
			$filterOptions['fields'] = array(
				'name',
				'licence',
				'author',
				'core' => Configure::read('CORE.core_options'),
				'active' => Configure::read('CORE.active_options')
			);

			$this->set(compact('themes', 'filterOptions'));
		}

		function admin_add() {
			if (!empty($this->data)) {
				if ($this->data['Theme']['active']) {
					$this->Theme->_deactivateAll();
				}

				$this->Theme->create();
				if ($this->Theme->saveAll($this->data)) {
					$this->Session->setFlash('Your theme has been saved.');
					$this->redirect(array('action' => 'index'));
				}
			}
		}

		function admin_edit($id = null) {
			if (!$id) {
				$this->Session->setFlash(__('That theme could not be found', true), true);
				$this->redirect($this->referer());
			}

			if (!empty($this->data)) {
				if ($this->data['Theme']['active']) {
					$this->Theme->_deactivateAll();
				}

				if ($this->Theme->save($this->data)) {
					$this->Session->setFlash(__('Your theme has been saved.', true));
					$this->redirect(array('action' => 'index'));
				}

				$this->Session->setFlash(__('Your theme could not be saved.', true));
			}

			if ($id && empty($this->data)) {
				$this->data = $this->Theme->read(null, $id);
			}
		}


		/**
		* Mass toggle action.
		*
		* This overwrites the default toggle action so that other themes can
		* be deactivated first as you should only have one active at a time.
		*/
		function __massActionToggle($ids) {
			if (count($ids) > 1) {
				$this->Session->setFlash(__('Please select only one theme to be active', true));
				$this->redirect($this->referer());
			}

			if ($this->Theme->_deactivateAll()) {
				return $this->MassAction->toggle($ids);
			}

			$this->Session->setFlash(__('There was a problem deactivating the other theme', true));
			$this->redirect($this->referer());
		}
	}
?>