<?php
	App::uses('InfinitasTheme', 'Themes.Lib');
	
	final class ThemesEvents extends AppEvents {
		public function onSetupCache() {
			return array(
				'name' => 'themes',
				'config' => array(
					'prefix' => 'core.themes.',
				)
			);
		}

		public function onAdminMenu($event) {
			$menu['main'] = array(
				'Dashboard' => array('plugin' => 'management', 'controller' => 'management', 'action' => 'site'),
				'Themes' => array('controller' => false, 'action' => false),
				'Default Theme' => array('controller' => 'themes', 'action' => 'index', 'Theme.active' => 1)
			);

			return $menu;
		}

		public function onRequireComponentsToLoad($event = null) {
			return array(
				'Themes.Themes'
			);
		}

		public function onGetRequiredFixtures($event) {
			return array(
				'Themes.Theme',
			);
		}
	}