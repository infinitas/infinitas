<?php
	final class ThemesEvents extends AppEvents{
		public function onPluginRollCall(){
			return array(
				'name' => 'Themes',
				'description' => 'Theme your site',
				'icon' => '/themes/img/icon.png',
				'author' => 'Infinitas'
			);
		}
		
		public function onSetupConfig(){
			return array();
		}

		public function onSetupCache(){
		}

		public function onAdminMenu(&$event){
			$menu['main'] = array(
				'Themes' => array('controller' => false, 'action' => false),
				'Default Theme' => array('controller' => 'themes', 'action' => 'index', 'Theme.active' => 1)
			);

			return $menu;
		}
	}