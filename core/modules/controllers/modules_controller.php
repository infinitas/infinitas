<?php
	/**
	 *
	 *
	 */
	class ModulesController extends ModulesAppController{
		public $name = 'Modules';

		public function admin_index() {
			$this->paginate = array(
				'contain' => array(
					'Position',
					'Group'
				)
			);

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

		public function admin_add() {
			parent::admin_add();

			$positions = $this->Module->Position->find('list');
			$groups = $this->Module->Group->find('list');
			$routes = array(0 => __('All Pages', true)) + $this->Module->Route->find('list');
			$themes = array(0 => __('All Themes', true)) + $this->Module->Theme->find('list');
			$plugins = $this->Module->getPlugins();
			$modules = $this->Module->getModuleList();
			$this->set(compact('positions', 'groups', 'routes', 'themes', 'plugins', 'modules'));
		}

		public function admin_edit($id = null) {
			parent::admin_edit($id);
			
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
		 *
		 * Gets the plugin name from POST data to find the required modules.
		 */
		public function admin_getModules(){
			if (!isset($this->params['named']['plugin'])) {
				$this->set('json', array('error'));
				return;
			}

			$this->set('json', $this->{$this->modelClass}->getModuleList($this->params['named']['plugin']));
		}
	}