<?php
/**
 * WebmasterController
 *
 * @package Infinitas.Webmaster.Controller
 */

/**
 * WebmasterController
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Webmaster.Controller
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.7a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

class WebmasterController extends WebmasterAppController {
/**
 * Disable model loading
 *
 * @var boolean
 */
	public $uses = false;

/**
 * Webmaster dashboard
 *
 * @return void
 */
	public function admin_dashboard() {
		if(!is_file(APP . 'webroot' . DS . 'robots.txt')) {
			$this->notice(
				__('You do not seem to have a robots file'),
				array(
					'level' => 'warning'
				)
			);
		}
	}

}