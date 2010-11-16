<?php
	class ModuleTemplate{
		protected $_data = array(
		);

		public function __get($key) {
			return isset($this->_data[$key])
				? $this->_data[$key]
				: null;
		}

		public function __isset($key) {
			if($this->__isPosition($key)){
				App::import('Helper', 'Modules.ModuleLoader');
				$ModuleLoader = new ModuleLoaderHelper();
				$this->_data[$key] = $ModuleLoader->load($key);
				return true;
			}
		}

		private function __isPosition($position){
			return ClassRegistry::init('Modules.ModulePosition')->isPosition($position);
		}
	}
	
	final class ModulesEvents extends AppEvents{		
		public function onSetupConfig(){
		}

		public function onSetupCache(){
			return array(
				'name' => 'modules',
				'config' => array(
					'prefix' => 'core.modules.'
				)
			);
		}

		public function onAdminMenu(&$event){
			$menu['main'] = array(
				'All' => array('plugin' => 'modules', 'controller' => 'modules', 'action' => 'index'),
				'Frontend' => array('plugin' => 'modules', 'controller' => 'modules', 'action' => 'index', 'Module.admin' => 0),
				'Backend' => array('plugin' => 'modules', 'controller' => 'modules', 'action' => 'index', 'Module.admin' => 1),
				'Module Positions' => array('controller' => 'module_positions', 'action' => 'index')
			);

			return $menu;
		}

		public function onRequireHelpersToLoad(&$event){
			return array(
				'Modules.ModuleLoader'
			);
		}

		public function onRequireGlobalTemplates(&$event){
			return array(
				'ModuleLoader' => new ModuleTemplate()
			);
		}
	}