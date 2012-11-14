<?php
/**
 * Filemanager Events
 *
 * @package Infinitas.Filemanager.Lib
 */

/**
 * Filemanager Events
 *
 * The events that can be triggered for the events class.
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Filemanager.Lib
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.7a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

class FilemanagerEvents extends AppEvents {
/**
 * Filemanager dashboard icon and details
 *
 * @return array
 */
	public function onPluginRollCall() {
		return;
		return array(
			'name' => 'Files',
			'description' => 'Allow you to manage files from the backend',
			'icon' => '/filemanager/img/icon.png',
			'author' => 'Infinitas'
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
		$menu['main'] = array(
			'Root Dir' => array('controller' => false, 'action' => false)
		);

		return $menu;
	}

/**
 * Filemanager cache configuration
 *
 * @return array
 */
	public function onSetupCache() {
		return array(
			'name' => 'filemanager',
			'config' => array(
				'prefix' => 'core.filemanager.',
			)
		);
	}

/**
 * Filemanager css
 *
 * @param Event $Event
 * @param array $data
 *
 * @return array
 */
	public function onRequireCssToLoad(Event $Event, $data = null) {
		return array(
			'Filemanager.jquery_file_tree'
		);
	}

/**
 * Filemanager JavaScript
 *
 * @param Event $Event
 * @param array $data
 *
 * @return array
 */
	public function onRequireJavascriptToLoad(Event $Event, $data = null) {
		return array(
			'Filemanager.jquery_file_tree'
		);
	}

}