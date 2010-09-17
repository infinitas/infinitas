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
		function initialize(&$Controller){
			$this->Controller = $Controller;

			$lockable = strstr($this->Controller->action, 'admin') &&
				isset($Controller->{$Controller->modelClass}->Behaviors) &&
				isset($Controller->{$Controller->modelClass}->lockable) &&
				$Controller->{$Controller->modelClass}->lockable === true;

			if(!$lockable){
				return false;
			}

			$Controller->{$Controller->modelClass}->Behaviors->attach('Locks.Lockable');
			$Controller->{$Controller->modelClass}->bindModel(
				array(
					'hasOne' => array(
						'Lock' => array(
							'className' => 'Locks.Lock',
							'foreignKey' => false,
							'conditions' => array(
								'Lock.class' => Inflector::camelize($Controller->{$Controller->modelClass}->plugin) . '.' . $Controller->{$Controller->modelClass}->alias,
								'Lock.foreign_key = `'.$Controller->{$Controller->modelClass}->alias . '`.`' . $Controller->{$Controller->modelClass}->primaryKey.'`'
							),
							'fields' => array(
								'Lock.id',
								'Lock.created',
								'Lock.user_id'						
							),
							'dependent' => true
						),
						'Locker' => array(
							'className' => 'Users.User',
							'foreignKey' => false,
							'conditions' => array(
								'Locker.id = `Lock`.`user_id'
							),
							'fields' => array(
								'Locker.id',
								'Locker.username'
							)
						)
					)
				),
				false
			);
		}

		public function beforeRender(){
			if(isset($this->Controller->data['Lock'])){
				$this->Controller->Session->setFlash(sprintf(__('The %s you requested has been locked by %s', true), $this->Controller->prettyModelName, $this->Controller->data['Locker']['username']));
				$this->Controller->redirect(array('plugin' => 'locks', 'controller' => 'locks', 'action' => 'locked'));
			}
		}
	}