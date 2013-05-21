<?php
/**
 * InstallerEvents
 *
 * @package Infinitas.Installer.Lib
 */

/**
 * InstallerEvents
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Installer.Lib
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.7a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

class InstallerEvents extends AppEvents{
/**
 * Configure Installer routes
 *
 * If infinitas is not installed yet all routes will be directed to the installer
 *
 * @return boolean
 */
	public function onSetupRoutes(Event $Event) {
		// infinitas is not installed
		$databaseConfig = APP . 'Config' . DS . 'database.php';
		InfinitasRouter::connect('/install/finish/*', array('plugin' => 'installer', 'controller' => 'install', 'action' => 'finish'));
		InfinitasRouter::connect('/install/:step', array('plugin' => 'installer', 'controller' => 'install', 'action' => 'index'), array('pass' => array('step')));

		if (file_exists($databaseConfig) && filesize($databaseConfig) > 0) {
			return true;
		}

		Configure::write('Session.save', 'php');
		InfinitasRouter::connect('/', array('plugin' => 'installer', 'controller' => 'install', 'action' => 'index'));
		InfinitasRouter::connect('/*', array('plugin' => 'installer', 'controller' => 'install', 'action' => 'index'));

		InfinitasTheme::defaultThemeInstall();
	}

/**
 * Plugin info
 *
 * @return array
 */
	public function onPluginRollCall(Event $Event) {
		return array(
			'name' => 'Plugins',
			'description' => 'Manage, install and remove plugins',
			'icon' => 'bolt',
			'author' => 'Infinitas',
			'dashboard' => array('plugin' => 'installer', 'controller' => 'plugins', 'action' => 'dashboard'),
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
			'Dashboard' => array('plugin' => 'installer', 'controller' => 'plugins', 'action' => 'dashboard'),
			'Plugins' => array('plugin' => 'installer', 'controller' => 'plugins', 'action' => 'index'),
			'Install' => array('plugin' => 'installer', 'controller' => 'plugins', 'action' => 'install'),
			'Update' => array('plugin' => 'installer', 'controller' => 'plugins', 'action' => 'update_infinitas'),
		);

		return $menu;
	}

}