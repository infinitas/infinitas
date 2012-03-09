<?php
	class PluginsController extends InstallerAppController {
		public function admin_dashboard(){
			
		}

		public function admin_index(){
			$plugins = $this->Paginator->paginate(null, $this->Filter->filter);

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

		public function admin_install() {
			$this->notice['saved'] = array(
				'message' => 'Your %s was installed successfully',
				'redirect' => ''
			);
			if($this->request->data) {
				try{
					unset($this->request->data['action']);
					$this->Plugin->processInstall($this->request->data);
				}
				catch(Exception $e) {
					$this->notice(
						$e->getMessage(),
						array(
							'level' => 'warning'
						)
					);
				}
				
				$this->notice('saved');
			}
			
			$this->set('possibleThemes', ClassRegistry::init('Themes.Theme')->notInstalled());
		}

		public function admin_update_infinitas(){
			
		}
	}