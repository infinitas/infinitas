<?php
	final class ConfigsEvents extends AppEvents{		
		public function onSetupConfig(){
		}

		public function onSetupCache(){
			return array(
				'name' => 'configs',
				'config' => array(
					'prefix' => 'core.configs.',
				)
			);
		}

		public function onAdminMenu($event){
			$menu['main'] = array(
				'Configuration' => array('controller' => 'configs', 'action' => 'index'),
				'Available' => array('controller' => 'configs', 'action' => 'available')
			);

			return $menu;
		}
	}