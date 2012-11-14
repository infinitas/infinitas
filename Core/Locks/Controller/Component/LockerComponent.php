<?php
/**
 * LockerComponent
 *
 * @package Infinitas.Locks.Controller.Component
 */

App::uses('InfinitasComponent', 'Libs.Controller/Component');

/**
 * LockedHelper
 *
 * Helper for generating markup displaying what is locked and by who
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Locks.Controller.Component
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.7a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

class LockerComponent extends InfinitasComponent {
/**
 * Component initialize
 *
 * @param Controller $Controller
 *
 * @return boolean
 */
	public function initialize(Controller $Controller) {
		$disable = !strstr($Controller->action, 'admin') &&
			!empty($Controller->uses) &&
			isset($Controller->{$Controller->modelClass}->Behaviors);

		$disable = $disable || (!empty($Controller->request->data['action']) && $Controller->request->data['action'] == 'copy');
		if($disable) {
			$Controller->{$Controller->modelClass}->Behaviors->detach('Lockable');
		}

		return true;
	}

/**
 * BeforeRender callback
 *
 * Check if the record is locked and redirect to error page it it has been
 *
 * @param Controller $Controller
 *
 * @return boolean
 */
	public function beforeRender(Controller $Controller) {
		if(empty($Controller->params['admin']) || !$Controller->params['admin']) {
			return true;
		}

		if(isset($Controller->request->data['Lock']['user_id']) && $Controller->request->data['Lock']['user_id'] != $Controller->Session->read('Auth.User.id')) {
			$Controller->notice(
				sprintf(
					__('The %s you requested has been locked by %s'),
					$Controller->prettyModelName,
					$Controller->request->data['Locker']['username']
				),
				array(
					'redirect' => array('plugin' => 'locks', 'controller' => 'locks', 'action' => 'locked')
				)
			);
		}

		return true;
	}

/**
 * BeforeRedirect callback
 *
 * When cancel has been clicked remove the locks associated with the record
 *
 * @param Controller $Controller
 *
 * @return void
 */
	public function beforeRedirect(Controller $Controller) {
		if(!empty($Controller->uses) && isset($Controller->{$Controller->modelClass}->lockable) && $Controller->{$Controller->modelClass}->lockable) {
			if(isset($Controller->params['form']['action']) && $Controller->params['form']['action'] == 'cancel') {
				ClassRegistry::init('Locks.Lock')->deleteAll(
					array(
						'Lock.class' => Inflector::camelize($Controller->plugin).'.'.$Controller->modelClass,
						'Lock.foreign_key' => $Controller->params['data'][$Controller->modelClass][$Controller->{$Controller->modelClass}->primaryKey]
					)
				);
			}
		}
	}

}