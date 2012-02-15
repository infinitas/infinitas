<?php
	class PluginsController extends InstallerAppController {
		public function admin_dashboard(){
			
		}

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

			$this->set(compact('plugins', 'filterOptions'));
		}

		public function admin_add(){
			$this->notice(
				__('Nothing to see, move along'),
				array(
					'level' => 'warning',
					'redirect' => true
				)
			);
		}

		public function admin_edit(){
			self::admin_add();
		}

		public function admin_install(){
			
		}

		public function admin_update_infinitas(){
			
		}
	}