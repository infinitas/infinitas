<?php
/**
 * VisitorComponent
 *
 * @package Infinitas.ViewCounter.Controller.Component
 */

App::uses('InfinitasComponent', 'Libs.Controller/Component');

/**
 * VisitorComponent
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.ViewCounter.Controller.Component
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.7a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

class VisitorComponent extends InfinitasComponent {

/**
 * Initialize callback
 *
 * @param Controller $Controller
 * @param array $settings component settings
 *
 * @return void
 */
	public function initialize(Controller $Controller, $settings = array()) {
		ClassRegistry::init('Users.User')->updateLastClick();
	}

}