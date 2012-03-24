<?php
	final class LocksEvents extends AppEvents {
		public function onSetupCache(){
			return array(
				'name' => 'locks',
				'config' => array(
					'prefix' => 'locks.'
				)
			);
		}

		public function onAdminMenu($event){
			$menu['main'] = array(
				'Dashboard' => array('plugin' => 'management', 'controller' => 'management', 'action' => 'site'),
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
			if($event->Handler->shouldAutoAttachBehavior()) {
				if (isset($event->Handler->lockable) && $event->Handler->lockable && !$event->Handler->Behaviors->enabled('Locks.Lockable')) {
					$event->Handler->Behaviors->attach('Locks.Lockable');
				}
			}
		}

		public function onSetupRoutes(){
			InfinitasRouter::connect(
				'/admin/content-locked',
				array(
					'plugin' => 'locks',
					'controller' => 'locks',
					'action' => 'locked',
					'admin' => true,
					'prefix' => 'admin'
				)
			);
		}

		public function onEditCanceled($event, $id = null) {
			if(!$id) {
				return false;
			}
			
			if(is_callable(array($event->Handler->{$event->Handler->modelClass}, 'unlock'))) {
				$event->Handler->{$event->Handler->modelClass}->unlock($id);
			}
		}
	}