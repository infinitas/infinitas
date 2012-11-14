<?php
/**
 * ConfigsAppController
 *
 * @package Infinitas.Configs.Controller
 */

App::uses('AppController', 'Controller');

/**
 * ConfigsAppController
 *
 * The configs plugin provides a way to overload configuration options from the
 * admin backend. The configs plugin uses the normal Configure class from
 * CakePHP. This is done to allow normal usage within the code but have the
 * ability to overload options in the backend.
 *
 * You can use all the normal Configure methods within the code including
 * Configure::load(), Configure::read('debug') and Configure::write(). There are some
 * Event callbacks that are triggered early on in the request so if you would
 * like the data to be cached it should be loaded in these calls.
 *
 * Configs plugin provides a number of things to manage configs, including
 * adding new ones from the backend, overloading options and seeing what has
 * changed from the defaults.
 *
 * @image html sql_configs_plugin.png "Configs Plugin table structure"
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Configs.Controller
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.5a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

class ConfigsAppController extends AppController {
	
}