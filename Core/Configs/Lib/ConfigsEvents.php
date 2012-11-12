<?php
/**
 * ConfigsEvents for the Configs plugin to attach into the application
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Configs
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.8a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

class ConfigsEvents extends AppEvents {
/**
 * get the cache config for this plugin
 *
 * @return array
 */
	public function onSetupCache() {
		return array(
			'name' => 'configs',
			'config' => array(
				'prefix' => 'core.configs.',
			)
		);
	}

/**
 * get the admin menu
 *
 * @return array
 */
	public function onAdminMenu($event) {
		$menu['main'] = array(
			'Dashboard' => array('plugin' => 'management', 'controller' => 'management', 'action' => 'site'),
			'Configuration' => array('plugin' => 'configs', 'controller' => 'configs', 'action' => 'index'),
			'Available' => array('plugin' => 'configs', 'controller' => 'configs', 'action' => 'available')
		);

		return $menu;
	}

/**
 * get required fixtures
 *
 * @return array
 */
	public function onGetRequiredFixtures($event) {
		return array(
			'Configs.Config',
		);
	}
}