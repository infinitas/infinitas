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

	class RoutesController extends ManagementAppController{
		var $name = 'Routes';

		var $listThemes = array(0 => 'Default');

		function beforeFilter() {
			parent::beforeFilter();

			$this->listThemes = array(
				0 => __('Default', true)
			) + $this->Route->Theme->find('list');
		}

		function admin_index() {
			$this->Route->recursive = 1;
			$routes = $this->paginate(null, $this->Filter->filter);

			$filterOptions = $this->Filter->filterOptions;
			$filterOptions['fields'] = array(
				'name',
				'core' => Configure::read('CORE.core_options'),
				'theme_id' => array(null => __('All', true)) + $this->Route->Theme->find('list'),
				'active' => Configure::read('CORE.active_options')
			);

			$this->set(compact('routes', 'filterOptions'));
		}

		function admin_add() {
			if (!empty($this->data)) {
				$this->Route->create();
				if ($this->Route->saveAll($this->data)) {
					$this->Session->setFlash('Your route has been saved.');
					$this->redirect(array('action' => 'index'));
				}
			}

			$this->set('plugins', $this->Route->getPlugins());
			$this->set('themes', $this->listThemes);
		}

		function admin_edit($id = null) {
			if (!$id) {
				$this->Session->setFlash(__('That route could not be found', true), true);
				$this->redirect($this->referer());
			}

			if (!empty($this->data)) {
				if ($this->Route->save($this->data)) {
					$this->Session->setFlash(__('Your route has been saved.', true));
					$this->redirect(array('action' => 'index'));
				}

				$this->Session->setFlash(__('Your route could not be saved.', true));
			}

			if ($id && empty($this->data)) {
				$this->data = $this->Route->read(null, $id);
			}

			$plugins = $this->Route->getPlugins();
			$controllers = $this->Route->getControllers($this->data['Route']['plugin']);
			$actions = $this->Route->getActions($this->data['Route']['plugin'], $this->data['Route']['controller']);
			$themes = $this->listThemes;

			$this->set(compact('plugins', 'controllers', 'actions', 'themes'));
		}
	}
?>