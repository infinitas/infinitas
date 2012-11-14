<?php
/**
 * DatabasesController
 *
 * @package Infinitas.ServerStatus.Controller
 */

/**
 * DatabasesController
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.ServerStatus.Controller
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.7a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */
class DatabasesController extends ServerStatusAppController {
/**
 * Disable model loading
 *
 * @var boolean
 */
	public $uses = false;

/**
 * ServerStatus dashboard
 *
 * @return void
 */
	public function admin_dashboard() {

	}

/**
 * MySQL database information
 *
 * Get the information for the mysql database in use for display in the frontend
 *
 * @return void
 */
	public function admin_mysql() {
		$User = ClassRegistry::init('Users.User');
		$globalVars = $User->query('show global variables');
		$globalVars = array_combine(
			Set::extract('/VARIABLES/Variable_name', $globalVars),
			Set::extract('/VARIABLES/Value', $globalVars)
		);

		$localVars = $User->query('show variables');
		$localVars = array_combine(
			Set::extract('/VARIABLES/Variable_name', $localVars),
			Set::extract('/VARIABLES/Value', $localVars)
		);

		$localVars = Set::diff($localVars, $globalVars);

		$this->set(compact('globalVars', 'localVars'));
	}

}