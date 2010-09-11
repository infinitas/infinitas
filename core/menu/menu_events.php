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
				'name' => 'menu',
				'config' => array(
					'prefix' => 'core.menu.'
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

		public function onRequireHelpersToLoad(&$event){
			return array(
				'Menu.Menu'
			);
		}
	}