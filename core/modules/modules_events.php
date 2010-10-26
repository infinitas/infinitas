<?php
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
	}