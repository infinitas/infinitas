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

	class Lock extends LocksAppModel{
		public $name = 'Lock';

		public $belongsTo = array(
			'Locker' => array(
				'className' => 'Users.User',
				'foreignKey' => 'user_id',
				'fields' => array(
					'Locker.id',
					'Locker.email',
					'Locker.username'
				)
			)
		);

		public function beforeDelete($cascade){
			$this->Behaviors->detach('Trashable');
			parent::beforeDelete($cascade);
		}

		public function clearOldLocks(){
			return $this->deleteAll(
				array(
					'Lock.created < ' => date('Y-m-d H:m:s', strtotime(Configure::read('Locks.timeout')))
				)
			);
		}
	}