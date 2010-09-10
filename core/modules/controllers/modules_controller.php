<?php
	/**
	 *
	 *
	 */
	class ModulesController extends ModulesAppController{
		var $name = 'Modules';

		function beforeFilter() {
			parent::beforeFilter();
		}

		function admin_index() {
			$this->Module->recursive = 1;
			$modules = $this->paginate(null, $this->Filter->filter);

			$filterOptions = $this->Filter->filterOptions;
			$filterOptions['fields'] = array(
				'name',
				'plugin' => $this->Module->getPlugins(),
				'theme_id' => array(null => __('All', true)) + $this->Module->Theme->find('list'),
				'position_id' => array(null => __('All', true)) + $this->Module->Position->find('list'),
				'author',
				'licence',
				'group_id' => array(null => __('Public', true)) + $this->Module->Group->find('list'),
				'core' => Configure::read('CORE.core_options'),
				'active' => Configure::read('CORE.active_options')
			);

			$this->set(compact('modules', 'filterOptions'));
		}

		function admin_add() {
			if (!empty($this->data)) {
				$this->Module->create();
				if ($this->Module->saveAll($this->data)) {
					$this->Session->setFlash('Your moodule has been saved.');
					$this->redirect(array('action' => 'index'));
				}
			}

			$positions = $this->Module->Position->find('list');
			$groups = $this->Module->Group->find('list');
			$routes = array(0 => __('All Pages', true)) + $this->Module->Route->find('list');
			$themes = array(0 => __('All Themes', true)) + $this->Module->Theme->find('list');
			$plugins = $this->Module->getPlugins();
			$modules = $this->Module->getModuleList();
			$this->set(compact('positions', 'groups', 'routes', 'themes', 'plugins', 'modules'));
		}

		function admin_edit($id = null) {
			if (!$id) {
				$this->Session->setFlash(__('That module could not be found', true), true);
				$this->redirect($this->referer());
			}

			if (!empty($this->data)) {
				if ($this->Module->save($this->data)) {
					$this->Session->setFlash(__('Your module has been saved.', true));
					$this->redirect(array('action' => 'index'));
				}

				$this->Session->setFlash(__('Your module could not be saved.', true));
			}

			if ($id && empty($this->data)) {
				$this->data = $this->Module->read(null, $id);
			}

			$positions = $this->Module->Position->find('list');
			$groups = $this->Module->Group->find('list');
			$routes = array(0 => __('All Pages', true)) + $this->Module->Route->find('list');
			$themes = array(0 => __('All Themes', true)) + $this->Module->Theme->find('list');
			$plugins = $this->Module->getPlugins();
			$modules = $this->Module->getModuleList($this->data['Module']['plugin']);
			$this->set(compact('positions', 'groups', 'routes', 'themes', 'plugins', 'modules'));
		}

		/**
		* get a list of modules that can be used.
		*/
		function admin_getModules(){
			if (!isset($this->params['named']['plugin'])) {
				$this->set('json', array('error'));
				return;
			}

			$this->set('json', $this->{$this->modelClass}->getModuleList($this->params['named']['plugin']));
		}
	}