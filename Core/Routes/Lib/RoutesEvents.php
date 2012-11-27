<?php
/**
 * RoutesEvents
 *
 * @package Infinitas.Routes.Lib
 */

/**
 * RoutesEvents
 *
 * The events that can be triggered for the Routes plugin
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Routes.Lib
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.8a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

class RoutesEvents extends AppEvents {
/**
 * Plugin rollcall
 *
 * @return array
 */
	public function onPluginRollCall(Event $Event) {
		return array(
			'name' => 'Routes',
			'description' => 'Route pretty urls to your code',
			'icon' => 'road',
			'author' => 'Infinitas'
		);
	}

/**
 * Configure routes cache
 *
 * @return array
 */
	public function onSetupCache(Event $Event) {
		return array(
			'name' => 'routes',
			'config' => array(
				'prefix' => 'core.routes.'
			)
		);
	}

/**
 * Admin menu bar
 *
 * @param Event $Event
 *
 * @return array
 */
	public function onAdminMenu(Event $Event) {
		$menu = array(
			'main' => array(
				'Routes' => array('plugin' => 'routes', 'controller' => 'routes', 'action' => 'index')
			),
			'filter' => array(
				'Active' => array('plugin' => 'routes', 'controller' => 'routes', 'action' => 'index', 'Route.active' => 1),
				'Disabled' => array('plugin' => 'routes', 'controller' => 'routes', 'action' => 'index', 'Route.active' => 0)
			)
		);

		return $menu;
	}

}