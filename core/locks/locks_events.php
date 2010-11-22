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

		public function onAdminMenu($event){
			$menu['main'] = array(
				'Locks' => array('plugin' => 'locks', 'controller' => 'locks', 'action' => 'index')
			);

			return $menu;
		}

		public function onRequireComponentsToLoad($event){
			return array(
				'Locks.Locker'
			);
		}

		public function onRequireHelpersToLoad($event){
			return array(
				'Locks.Locked'
			);
		}

		public function onAttachBehaviors($event = null) {
			if(is_subclass_of($event->Handler, 'Model') && isset($event->Handler->_schema) && is_array($event->Handler->_schema)) {
				if (isset($event->Handler->lockable) && $event->Handler->lockable && !$event->Handler->Behaviors->enabled('Locks.Lockable')) {
					$event->Handler->Behaviors->attach('Locks.Lockable');
				}
			}
		}

		public function onSetupRoutes(){
			Router::connect('/admin/content-locked', array('plugin' => 'locks', 'controller' => 'locks', 'action' => 'locked', 'admin' => true, 'prefix' => 'admin'));
		}
	}