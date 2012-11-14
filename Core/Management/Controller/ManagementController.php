<?php
/**
 * ManagementController
 *
 * @package Infinitas.Management.Controller
 */

/**
 * ManagementController
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Management.Controller
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.5a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

class ManagementController extends ManagementAppController {
/**
 * Disable model loading
 *
 * @var boolean
 */
	public $uses = false;

/**
 * Management dashboard
 *
 * This is the main admin dashboard when visiting admin
 *
 * @return void
 */
	public function admin_dashboard() {
	}

/**
 * Site
 * 
 * This is a few of the smaller items that dont need to be directly on the
 * main dashboard as they will not be used all that often.
 *
 * @return void
 */
	public function admin_site() {

	}

}