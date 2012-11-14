<?php
/**
 * LocksController
 *
 * @package Infinitas.Locks.Controller
 */

/**
 * LocksController
 *
 * This controller will unlock records that are locked.
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Locks.Controller
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.7a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

class LocksController extends LocksAppController {
/**
 * Custom notice data
 *
 * @var array
 */
	public $notice = array(
		'deleted' => array(
			'message' => 'The selected records have been unlocked',
			'redirect' => true
		),
	);

/**
 * Display all locked records
 *
 * @return void
 */
	public function admin_index() {
		$this->Paginator->settings = array(
			'contain' => array(
				'Locker'
			)
		);
		$locks = $this->Paginator->paginate();
		$this->set(compact('locks'));
	}

/**
 * unlock the rows by deleting them.
 *
 * @param array $ids list of ids to unlock
 *
 * @return void
 */
	public function __massActionUnlock($ids = array()) {
		return $this->MassAction->__handleDeletes($ids);
	}

/**
 * Action to show when attemptying to edit a record that is locked.
 *
 * @return void
 */
	public function admin_locked() {
		$this->set('title_for_layout', __('This content is currently locked'));
	}

}