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
	 * @package Infinitas.Installer.Lib
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.7
	 *
	 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
	 */

	class InstallerEvents extends AppEvents{
		public function onSetupRoutes() {
			// infinitas is not installed
			$databaseConfig = APP . 'Config' . DS . 'database.php';
			InfinitasRouter::connect('/install/finish/*', array('plugin' => 'installer', 'controller' => 'install', 'action' => 'finish'));
			InfinitasRouter::connect('/install/:step', array('plugin' => 'installer', 'controller' => 'install', 'action' => 'index'), array('pass' => array('step')));

			if(!file_exists($databaseConfig) || filesize($databaseConfig) == 0) {
				if(!file_exists($databaseConfig)) {
					$file = fopen($databaseConfig, 'w');
					fclose($file);
				}

				Configure::write('Session.save', 'php');
				InfinitasRouter::connect('/', array('plugin' => 'installer', 'controller' => 'install', 'action' => 'index'));
				InfinitasRouter::connect('/*', array('plugin' => 'installer', 'controller' => 'install', 'action' => 'index'));
			}

			return true;
		}

		public function onPluginRollCall() {
			return array(
				'name' => 'Plugins',
				'description' => 'Manage, install and remove plugins',
				'icon' => '/installer/img/icon.png',
				'author' => 'Infinitas',
				'dashboard' => array('plugin' => 'installer', 'controller' => 'plugins', 'action' => 'dashboard'),
			);
		}

		public function onAdminMenu($event) {
			$menu['main'] = array(
				'Dashboard' => array('plugin' => 'installer', 'controller' => 'plugins', 'action' => 'dashboard'),
				'Plugins' => array('plugin' => 'installer', 'controller' => 'plugins', 'action' => 'index'),
				'Install' => array('plugin' => 'installer', 'controller' => 'plugins', 'action' => 'install'),
				'Update' => array('plugin' => 'installer', 'controller' => 'plugins', 'action' => 'update_infinitas'),
			);

			return $menu;
		}
	 }