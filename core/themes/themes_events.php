<?php
	final class ThemesEvents extends AppEvents{		
		public function onSetupConfig(){
			return array();
		}

		public function onSetupCache(){
			return array(
				'name' => 'themes',
				'config' => array(
					'prefix' => 'core.themes.',
				)
			);
		}

		public function onAdminMenu(&$event){
			$menu['main'] = array(
				'Themes' => array('controller' => false, 'action' => false),
				'Default Theme' => array('controller' => 'themes', 'action' => 'index', 'Theme.active' => 1)
			);

			return $menu;
		}
	}