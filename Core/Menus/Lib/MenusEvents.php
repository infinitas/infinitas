<?php
/**
 * Menu plugin events.
 *
 * The events for the menu plugin for setting up cache and the general
 * configuration of the plugin.
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Menus.Lib
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.8a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

class MenusEvents extends AppEvents {

	public function onPluginRollCall(Event $Event) {
		return array(
			'name' => 'Menus',
			'description' => 'Build menus for your site',
			'icon' => 'list-ul',
			'author' => 'Infinitas',
			'dashboard' => array('plugin' => 'menus', 'controller' => 'menus', 'action' => 'index')
		);
	}

	public function onAdminMenu(Event $Event) {
		$menu['main'] = array(
			'Dashboard' => array('plugin' => 'management', 'controller' => 'management', 'action' => 'site'),
			'Menus' => array('controller' => false, 'action' => false),
			'Menu Items' => array('controller' => 'menu_items', 'action' => 'index')
		);

		return $menu;
	}

	public function onRequireHelpersToLoad(Event $Event) {
		return array(
			'Menus.Menu'
		);
	}

	public function onRequireJavascriptToLoad(Event $Event) {
		return array(
			'Menus.menus'
		);
	}
}