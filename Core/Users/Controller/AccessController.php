<?php
/**
 * AccessController
 *
 * @package Infinitas.Users.Controller
 */

/**
 * AccessController
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Users.Controller
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.6a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */
class AccessController extends UsersAppController {
/**
 * Remove model loading
 *
 * @var boolean
 */
	public $uses = false;

/**
 * BeforeFilter callback
 *
 * @return void
 */
	public function beforeFilter() {
		parent::beforeFilter();

		$this->notice(
			__d('users', 'ACL is not currently in use'),
			array(
				'level' => 'warning',
				'redirect' => true
			)
		);
	}
	
}