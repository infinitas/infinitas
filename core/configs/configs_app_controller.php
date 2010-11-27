<?php

	/**
	 * @page Configs-Plugin Configs Plugin
	 *
	 * @section configs-overview What is it
	 *
	 * The configs plugin provides a way to overload configuration options from the
	 * admin backend. The configs plugin uses the normal Configure class from
	 * CakePHP. This is done to allow normal usage within the code but have the
	 * ability to overload options in the backend.
	 *
	 * @link http://api.cakephp.org/class/configure
	 *
	 * @section configs-usage How to use it
	 *
	 * You can use all the normal Configure methods within the code including
	 * Configure::load(), Configure::read() and Configure::write(). There are some
	 * Event callbacks that are triggered early on in the request so if you would
	 * like the data to be cached it should be loaded in these calls. See
	 * AppEvents::onSetupConfig() for more information.
	 *
	 * Configs plugin provides a number of things to manage configs, including
	 * adding new ones from the backend, overloading options and seeing what has
	 * changed from the defaults.
	 *
	 * @section configs-see-also Also see
	 * @ref AppEvents
	 * @ref EventCore
	 */

	/**
	 * @brief ConfigsAppController is the main controller class that all other
	 * configuration related controllers extend.
	 * 
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package Infinitas.Configs
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.5a
	 *
	 * @author dogmatic69
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */

	class ConfigsAppController extends CoreAppController {
	}