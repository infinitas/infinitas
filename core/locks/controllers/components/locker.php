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

			if($this->Controller->action != 'admin_edit' || !isset($Controller->{$Controller->modelClass}->Behaviors)){
				return false;
			}

			$Controller->{$Controller->modelClass}->Behaviors->attach('Locks.Lockable');
			$Controller->{$Controller->modelClass}->bindModel(
				array(
					'hasOne' => array(
						'Lock' => array(
							'className' => 'Locks.Lock',
							'foreignKey' => false,
							'dependent' => true
						)
					)
				),
				false
			);

			$Controller->{$Controller->modelClass}->Lock->bindModel(
				array(
					'belongsTo' => array(
						$Controller->{$Controller->modelClass}->alias => array(
							'foreignKey' => $Controller->{$Controller->modelClass}->primaryKey,
							'dependent' => false,
							'fields' => array(
								$Controller->{$Controller->modelClass}->alias.'.'.$Controller->{$Controller->modelClass}->primaryKey,
								$Controller->{$Controller->modelClass}->alias.'.'.$Controller->{$Controller->modelClass}->dispalyField,
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