<?php
/**
 * ThemesEvents
 *
 * @package Infinitas.Themes.Lib
 */

App::uses('InfinitasTheme', 'Themes.Lib');

/**
 * ThemesEvents
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Themes.Lib
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.6a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

class ThemesEvents extends AppEvents {
/**
 * Setup theme cache
 *
 * @return array
 */
	public function onSetupCache(Event $Event) {
		return array(
			'name' => 'themes',
			'config' => array(
				'prefix' => 'core.themes.',
			)
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
		$menu = array(
			'main' => array(
				'Dashboard' => array('plugin' => 'management', 'controller' => 'management', 'action' => 'site'),
				'Themes' => array('controller' => false, 'action' => false)
			),
			'filter' => array(
				'Backend' => array('controller' => 'themes', 'action' => 'index', 'Theme.admin' => 1),
				'Frontend' => array('controller' => 'themes', 'action' => 'index', 'Theme.frontend' => 1),
			)
		);

		return $menu;
	}

/**
 * Load theme components
 *
 * @param Event $Event
 *
 * @return array
 */
	public function onRequireComponentsToLoad(Event $Event) {
		return array(
			'Themes.Themes'
		);
	}

/**
 * Return the default theme config for the insatller to use
 *
 * @param Event $Event
 *
 * @return array
 */
	public function onInstallerTheme(Event $Event) {
		return array(
			'theme' => 'infinitas',
			'layout' => 'installer'
		);
	}

}