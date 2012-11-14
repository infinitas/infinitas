<?php
/**
 * LockedHelper
 *
 * @package Infinitas.Locks.Lib
 */

App::uses('InfinitasHelper', 'Libs.View/Helper');

/**
 * LockedHelper
 *
 * Helper for generating markup displaying what is locked and by who
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Locks.Lib
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.8a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */
class LocksEvents extends AppEvents {
/**
 * Locks cache config
 *
 * @return array
 */
	public function onSetupCache() {
		return array(
			'name' => 'locks',
			'config' => array(
				'prefix' => 'locks.'
			)
		);
	}

/**
 * Locks admin nav bar
 *
 * @param Event $Event
 *
 * @return array
 */
	public function onAdminMenu(Event $Event) {
		$menu['main'] = array(
			'Dashboard' => array('plugin' => 'management', 'controller' => 'management', 'action' => 'site'),
			'Locks' => array('plugin' => 'locks', 'controller' => 'locks', 'action' => 'index')
		);

		return $menu;
	}

/**
 * Load locks components
 *
 * @param Event $Event
 *
 * @return array
 */
	public function onRequireComponentsToLoad(Event $Event) {
		return array(
			'Locks.Locker'
		);
	}

/**
 * Load locks helpers
 *
 * @param Event $Event
 *
 * @return array
 */
	public function onRequireHelpersToLoad(Event $Event) {
		return array(
			'Locks.Locked'
		);
	}

/**
 * Attatch the lockable behavior to all models with lockable property
 *
 * @param Event $Event
 *
 * @return void
 */
	public function onAttachBehaviors(Event $Event) {
		if($Event->Handler->shouldAutoAttachBehavior()) {
			if (isset($Event->Handler->lockable) && $Event->Handler->lockable && !$Event->Handler->Behaviors->enabled('Locks.Lockable')) {
				$Event->Handler->Behaviors->attach('Locks.Lockable');
			}
		}
	}

/**
 * Configure routes for the plugin
 *
 * @return void
 */
	public function onSetupRoutes() {
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

/**
 * Edit canceled event
 *
 * @param Event $Event
 * @param string $id
 *
 * @return boolean
 */
	public function onEditCanceled(Event $Event, $id = null) {
		if(!$id) {
			return false;
		}

		if(is_callable(array($Event->Handler->{$event->Handler->modelClass}, 'unlock'))) {
			try {
				$Event->Handler->{$event->Handler->modelClass}->unlock($id);
			}

			catch(Exception $e) {
				return false;
			}
		}
	}

}