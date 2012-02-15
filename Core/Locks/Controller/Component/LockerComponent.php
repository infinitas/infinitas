<?php
	/*
	 * Short Description / title.
	 *
	 * Overview of what the file does. About a paragraph or two
	 *
	 * Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 *
	 * @filesource
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package {see_below}
	 * @subpackage {see_below}
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since {check_current_milestone_in_lighthouse}
	 *
	 * @author {your_name}
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */
	App::uses('InfinitasComponent', 'Libs/Component');

	class LockerComponent extends InfinitasComponent {
		public function initialize($Controller){
			$disable = !strstr($Controller->action, 'admin');

			if($disable && !empty($Controller->uses) && isset($Controller->{$Controller->modelClass}->Behaviors)){
				$Controller->{$Controller->modelClass}->Behaviors->detach('Locks.Lockable');
			}

			return true;
		}

		public function beforeRender($Controller){
			if(empty($Controller->params['admin']) || !$Controller->params['admin']) {
				return true;
			}

			if(isset($Controller->request->data['Lock']['user_id']) && $Controller->request->data['Lock']['user_id'] != $Controller->Session->read('Auth.User.id')){
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

		public function beforeRedirect($Controller){
			if(!empty($Controller->uses) && isset($Controller->{$Controller->modelClass}->lockable) && $Controller->{$Controller->modelClass}->lockable){
				if(isset($Controller->params['form']['action']) && $Controller->params['form']['action'] == 'cancel'){
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