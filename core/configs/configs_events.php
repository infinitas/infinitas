<?php
	final class ConfigsEvents extends AppEvents{
		public function onPluginRollCall(){
			return array(
				'name' => 'Configs',
				'description' => 'Configure Your site',
				'icon' => '/configs/img/icon.png',
				'author' => 'Infinitas'
			);
		}
		
		public function onSetupConfig(){
		}

		public function onSetupCache(){
		}

		public function onAdminMenu(&$event){
			$menu['main'] = array(
				'Configuration' => array('controller' => false, 'action' => false)
			);

			return $menu;
		}
	}