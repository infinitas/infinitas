<?php
	final class LocksEvents extends AppEvents{
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

		public function onRequireHelpersToLoad(&$event){
			return array(
				'Locks.Locked'
			);
		}

		public function onSetupRoutes(){
			Router::connect('/admin/content-locked', array('plugin' => 'locks', 'controller' => 'locks', 'action' => 'locked', 'admin' => true, 'prefix' => 'admin'));
		}
	}