<?php
	final class LocksEvents extends AppEvents{
		public function onPluginRollCall(){
			return array(
				'name' => 'Locks',
				'description' => 'Stop others editing things you are working on',
				'icon' => '/locks/img/icon.png',
				'author' => 'Infinitas'
			);
		}

		public function onSetupCache(){
			return array(
				'name' => 'locks',
				'config' => array(
					'prefix' => 'locks.'
				)
			);
		}
		
		public function onSetupConfig(){
			return Configure::load('locks.config');
		}

		public function onAdminMenu(&$event){
			return array();
		}

		public function onRequireComponentsToLoad(&$event){
			return array(
				'Locks.Locker'
			);
		}
	}