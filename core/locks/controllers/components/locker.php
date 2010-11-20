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

	class LockerComponent extends InfinitasComponent{
		public function initialize($Controller){			
			if(!strstr($Controller->action, 'admin') && isset($Controller->{$Controller->modelClass}->Behaviors)){
				$Controller->{$Controller->modelClass}->Behaviors->disable('Locks.Lockable');
			}
		}

		public function beforeRender($Controller){
			if(isset($Controller->data['Lock']['user_id']) && $Controller->data['Lock']['user_id'] != $this->Session->read('Auth.User.id')){
				$Controller->Session->setFlash(sprintf(__('The %s you requested has been locked by %s', true), $Controller->prettyModelName, $Controller->data['Locker']['username']));
				$Controller->redirect(array('plugin' => 'locks', 'controller' => 'locks', 'action' => 'locked'));
			}
		}

		public function beforeRedirect($Controller){
			if(isset($Controller->{$Controller->modelClass}->lockable) && $Controller->{$Controller->modelClass}->lockable){
				if(isset($Controller->params['form']['action']) && $Controller->params['form']['action'] == 'cancel'){
					$Controller->{$Controller->modelClass}->Lock->deleteAll(
						array(
							'Lock.class' => Inflector::camelize($Controller->plugin).'.'.$Controller->modelClass,
							'Lock.foreign_key' => $Controller->params['data'][$Controller->modelClass][$Controller->{$Controller->modelClass}->primaryKey]
						)
					);
				}
			}
		}
	}