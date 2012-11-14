<?php
/**
 * Lock Model
 *
 * @package Infinitas.Locks.Model
 */

/**
 * LockedHelper
 *
 * Helper for generating markup displaying what is locked and by who
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Locks.Model
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.7a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

class Lock extends LocksAppModel {
/**
 * BelongsTo relations
 *
 * @var array
 */
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

/**
 * Disable the trash behavior as it is not required for this data
 *
 * @param bool $cascade if the delete should cascade
 *
 * @return boolean
 */
	public function beforeDelete($cascade) {
		$this->Behaviors->detach('Trashable');
		return parent::beforeDelete($cascade);
	}

/**
 * method to clear out locks that are stale
 *
 * The time can be configured via the configs plugin in the backend
 *
 * @return boolean
 */
	public function clearOldLocks() {
		return $this->deleteAll(
			array(
				'Lock.created < ' => date('Y-m-d H:m:s', strtotime(Configure::read('Locks.timeout')))
			)
		);
	}

}