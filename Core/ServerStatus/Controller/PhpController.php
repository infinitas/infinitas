<?php
/**
 * PhpController
 *
 * @package Infinitas.ServerStatus.Controller
 */

/**
 * PhpController
 *
 * Disply server information regarding the PHP install
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.ServerStatus.Controller
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.8a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

class PhpController extends ServerStatusAppController {
	public $uses = array();

/**
 * Status pannel for apc
 *
 * Display the APC info from apc.php
 *
 * @return void
 */
	public function admin_apc() {
		// Configure::write('debug', 0);
		if(!function_exists('apc_cache_info')) {
			$this->notice(
				__('APC is not installed, or has been deactivated'),
				array(
					'level' => 'warning',
					'redirect' => true
				)
			);
		}

		$this->layout = 'ajax';
	}

/**
 * Display info
 *
 * @return void
 */
	public function admin_info() {

	}

}