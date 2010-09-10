<?php
	final class MenuEvents extends AppEvents{
		public function onPluginRollCall(){
			return array(
				'name' => 'Menus',
				'description' => 'Build menus for your site',
				'icon' => '/menu/img/icon.png',
				'author' => 'Infinitas'
			);
		}
		
		public function onSetupConfig(){
			return array();
		}

		public function onSetupCache(){
			return array(
				'name' => 'core',
				'config' => array(
					'duration' => 3600,
					'probability' => 100,
					'prefix' => 'core.',
					'lock' => false,
					'serialize' => true
				)
			);
		}

		public function onAdminMenu(&$event){
			$menu['main'] = array(
				'Menus' => array('controller' => 'menus', 'action' => 'index'),
				'Menu Items' => array('controller' => 'menu_items', 'action' => 'index')
			);

			return $menu;
		}
	}