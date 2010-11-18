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
			$this->Controller = $Controller;
			
			if(!strstr($this->Controller->action, 'admin')){
				$Controller->{$Controller->modelClass}->Behaviors->detach('Locks.Lockable');
			}
		}

		public function beforeRender(){
			if(isset($this->Controller->data['Lock']['user_id']) && $this->Controller->data['Lock']['user_id'] != $this->Session->read('Auth.User.id')){
				$this->Controller->Session->setFlash(sprintf(__('The %s you requested has been locked by %s', true), $this->Controller->prettyModelName, $this->Controller->data['Locker']['username']));
				$this->Controller->redirect(array('plugin' => 'locks', 'controller' => 'locks', 'action' => 'locked'));
			}
		}

		public function beforeRedirect(){
			if(isset($this->Controller->{$this->Controller->modelClass}->lockable) && $this->Controller->{$this->Controller->modelClass}->lockable){
				if(isset($this->Controller->params['form']['action']) && $this->Controller->params['form']['action'] == 'cancel'){
					$this->Controller->{$this->Controller->modelClass}->Lock->deleteAll(
						array(
							'Lock.class' => Inflector::camelize($this->Controller->plugin).'.'.$this->Controller->modelClass,
							'Lock.foreign_key' => $this->Controller->params['data'][$this->Controller->modelClass][$this->Controller->{$this->Controller->modelClass}->primaryKey]
						)
					);
				}
			}
		}
	}