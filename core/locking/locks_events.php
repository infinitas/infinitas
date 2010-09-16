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

		public function onAdminMenu(&$event){
			return array();
		}

		public function onAttachBehaviors(&$event) {
			if(is_subclass_of($event->Handler, 'Model') && isset($event->Handler->_schema) && is_array($event->Handler->_schema)) {
				if (!$event->Handler->Behaviors->enabled('Libs.Lockable')) {
					$event->Handler->Behaviors->attach('Libs.Lockable');
				}
			}
		}
	}