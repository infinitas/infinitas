<?php
	class PluginsController extends InstallerAppController{
		public $name = 'Plugins';

		public $helpers = array(
			'Filter.Filter'
		);

		public function admin_index(){
			$plugins = $this->paginate(null, $this->Filter->filter);

			$filterOptions = $this->Filter->filterOptions;

			$filterOptions['fields'] = array(
				'name',
				'author',
				'version',
				'core' => Configure::read('CORE.core_options'),
				'active' => Configure::read('CORE.active_options')
			);

			$this->set(compact('plugins','filterOptions'));
		}

		public function admin_add(){
			$this->notice(
				__('Nothing to see, move along', true),
				array(
					'level' => 'warning',
					'redirect' => true
				)
			);
		}

		public function admin_edit(){
			self::admin_add();
		}
	}