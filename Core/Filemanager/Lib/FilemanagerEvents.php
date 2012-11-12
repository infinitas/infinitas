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
	 * @package Core.Filemanager.Lib
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since {check_current_milestone_in_lighthouse}
	 *
	 * @author {your_name}
	 *
	 *
	 *
	 */

	final class FilemanagerEvents extends AppEvents {
		public function onPluginRollCall() {
			return;
			return array(
				'name' => 'Files',
				'description' => 'Allow you to manage files from the backend',
				'icon' => '/filemanager/img/icon.png',
				'author' => 'Infinitas'
			);
		}

		public function onAdminMenu($event) {
			$menu['main'] = array(
				'Root Dir' => array('controller' => false, 'action' => false)
			);

			return $menu;
		}

		public function onSetupCache() {
			return array(
				'name' => 'filemanager',
				'config' => array(
					'prefix' => 'core.filemanager.',
				)
			);
		}

		public function onRequireCssToLoad($event, $data = null) {
			return array(
				'Filemanager.jquery_file_tree'
			);
		}

		public function onRequireJavascriptToLoad($event, $data = null) {
			return array(
				'Filemanager.jquery_file_tree'
			);
		}
	}