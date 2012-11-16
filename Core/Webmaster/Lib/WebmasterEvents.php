<?php
/**
 * WebmasterEvents
 *
 * @package Infinitas.Webmaster.Lib
 */

/**
 * WebmasterEvents
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Webmaster.Lib
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.7a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

class WebmasterEvents extends AppEvents {
/**
 * Plugin info
 *
 * @return array
 */
	public function onPluginRollCall(Event $Event) {
		return array(
			'name' => 'Webmaster',
			'description' => 'Manage your sites robots files and sitemaps',
			'icon' => '/webmaster/img/icon.png',
			'author' => 'Infinitas',
			'dashboard' => array('plugin' => 'webmaster', 'controller' => 'webmaster', 'action' => 'dashboard')
		);
	}

/**
 * Admin nav bar links
 *
 * @param Event $Event
 *
 * @return array
 */
	public function onAdminMenu(Event $Event) {
		$menu['main'] = array(
			'Dashboard' => array('plugin' => 'webmaster', 'controller' => 'webmaster', 'action' => 'dashboard'),
			'Edit Robots' => array('plugin' => 'webmaster', 'controller' => 'robots', 'action' => 'edit'),
			'View Sitemap' => '/sitemap.xml'
		);

		return $menu;
	}

/**
 * Webmaster helper load
 *
 * @param Event $Event
 *
 * @return array
 */
	public function onRequireHelpersToLoad(Event $Event) {
		return array(
			'Webmaster.Webmaster'
		);
	}

/**
 * Webmaster cache config
 *
 * @return array
 */
	public function onSetupCache(Event $Event) {
		return array(
			'name' => 'webmaster',
			'config' => array(
				'prefix' => 'webmaster.',
			)
		);
	}

/**
 * Configure route for sitemaps
 *
 * @return void
 */
	public function onSetupRoutes(Event $Event) {
		InfinitasRouter::connect(
			'/sitemap',
			array(
				'plugin' => 'webmaster',
				'controller' => 'site_maps',
				'action' => 'index',
				'ext' => 'xml'
			)
		);
	}

/**
 * Register XML extension parsing
 *
 * @return array
 */
	public function onSetupExtensions(Event $Event) {
		return array(
			'xml'
		);
	}

}