<?php
	/**
	 *
	 *
	 */
	class ModulesController extends ModulesAppController {
		public function admin_index() {
			$this->Paginator->settings = array(
				'fields' => array(
					'Module.*',
					'Position.*',
					'Group.*',
				),
				'joins' => array(
					array(
						'table' => 'core_module_positions',
						'alias' => 'Position',
						'type' => 'LEFT',
						'conditions' => array(
							'Position.id = Module.position_id'
						)
					),
					array(
						'table' => 'core_groups',
						'alias' => 'Group',
						'type' => 'LEFT',
						'conditions' => array(
							'Group.id = Module.group_id'
						)
					)
				)
			);

			$modules = $this->Paginator->paginate(null, $this->Filter->filter);

			$filterOptions = $this->Filter->filterOptions;
			$filterOptions['fields'] = array(
				'name',
				'plugin' => $this->Module->getPlugins(),
				'theme_id' => array(null => __d('modules', 'All')) + $this->Module->Theme->find('list'),
				'position_id' => array(null => __d('modules', 'All')) + $this->Module->Position->find('list'),
				'author',
				'licence',
				'group_id' => array(null => __d('modules', 'Public')) + $this->Module->Group->find('list'),
				'core' => Configure::read('CORE.core_options'),
				'active' => Configure::read('CORE.active_options')
			);

			$this->set(compact('modules', 'filterOptions'));
		}

		public function admin_add() {
			parent::admin_add();

			$positions = $this->Module->Position->find('list');
			$groups = $this->Module->Group->find('list');
			$routes = array(0 => __d('modules', 'All Pages')) + $this->Module->Route->find('list');
			$themes = array(0 => __d('modules', 'All Themes')) + $this->Module->Theme->find('list');
			$plugins = $this->Module->getPlugins();
			$modules = $this->Module->getModuleList();
			$this->set(compact('positions', 'groups', 'routes', 'themes', 'plugins', 'modules'));
		}

		public function admin_edit($id = null) {
			parent::admin_edit($id, array('fields' => array('Module.*')));

			$positions = $this->Module->Position->find('list');
			$groups = $this->Module->Group->find('list');
			$routes = array(0 => __d('modules', 'All Pages')) + $this->Module->Route->find('list');
			$themes = array(0 => __d('modules', 'All Themes')) + $this->Module->Theme->find('list');
			$plugins = $this->Module->getPlugins();

			$modules = $this->Module->getModuleList($this->request->data['Module']['plugin']);
			$this->set(compact('positions', 'groups', 'routes', 'themes', 'plugins', 'modules'));
		}

		/**
		 * get a list of modules that can be used.
		 *
		 * Gets the plugin name from POST data to find the required modules.
		 */
		public function admin_getModules() {
			if (!isset($this->request->data[$this->modelClass]['plugin'])) {
				$this->set('json', array('error'));
				return;
			}

			$this->set('json', $this->{$this->modelClass}->getModuleList($this->request->data[$this->modelClass]['plugin']));
		}
	}