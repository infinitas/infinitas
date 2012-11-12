<?php
	/*
	 * Short Description / title.
	 *
	 * Overview of what the file does. About a paragraph or two
	 *

	 *
	 * @filesource
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package Infinitas.Webmaster.Lib
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.7a
	 *
	 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
	 */

	class WebmasterEvents extends AppEvents {
		public function onPluginRollCall() {
			return array(
				'name' => 'Webmaster',
				'description' => 'Manage your sites robots files and sitemaps',
				'icon' => '/webmaster/img/icon.png',
				'author' => 'Infinitas',
				'dashboard' => array('plugin' => 'webmaster', 'controller' => 'webmaster', 'action' => 'dashboard')
			);
		}
		public function onAdminMenu($event) {
			$menu['main'] = array(
				'Dashboard' => array('plugin' => 'webmaster', 'controller' => 'webmaster', 'action' => 'dashboard'),
				'Edit Robots' => array('plugin' => 'webmaster', 'controller' => 'robots', 'action' => 'edit'),
				'View Sitemap' => '/sitemap.xml'
			);

			return $menu;
		}

		public function onRequireHelpersToLoad($event = null) {
			return array(
				'Webmaster.Webmaster'
			);
		}

		public function onSetupCache() {
			return array(
				'name' => 'webmaster',
				'config' => array(
					'prefix' => 'webmaster.',
				)
			);
		}

		public function onSetupRoutes() {
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

		public function onSetupExtensions() {
			return array(
				'xml'
			);
		}
	}