<?php
	/**
	 * Menu plugin events.
	 * 
	 * The events for the menu plugin for setting up cache and the general
	 * configuration of the plugin.
	 *
	 * Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 *
	 * @filesource
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package Infinitas.Menus
	 * @subpackage Infinitas.Menus.AppEvents
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.8a
	 *
	 * @author dogmatic69
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */

	final class MenusEvents extends AppEvents {
		public function onSetupCache() {
			return array(
				'name' => 'menus',
				'config' => array(
					'prefix' => 'core.menus.'
				)
			);
		}

		public function onAdminMenu($event) {
			$menu['main'] = array(
				'Dashboard' => array('plugin' => 'management', 'controller' => 'management', 'action' => 'site'),
				'Menus' => array('controller' => false, 'action' => false),
				'Menu Items' => array('controller' => 'menu_items', 'action' => 'index')
			);

			return $menu;
		}

		public function onRequireHelpersToLoad($event) {
			return array(
				'Menus.Menu'
			);
		}
	}