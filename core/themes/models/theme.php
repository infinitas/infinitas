<?php
	/**
	 * Model for themes
	 *
	 * handles the themes for infinitas finding and making sure only one can be
	 * active at a time.
	 *
	 * Copyright (c) 2009 Carl Sutton ( dogmatic69 )
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 * @filesource
	 * @copyright Copyright (c) 2009 Carl Sutton ( dogmatic69 )
	 * @link http://infinitas-cms.org
	 * @package management
	 * @subpackage management.models.theme
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.5a
	 */
	class Theme extends ThemesAppModel {
		public $name = 'Theme';

		public $hasMay = array(
			'Routes.Route'
		);

		/**
		 * Get the current Theme
		 *
		 * @param array $conditions
		 * @return array $theme the current theme.
		 */
		public function getCurrentTheme(){
			$theme = Cache::read('current_theme', 'core');

			if ($theme !== false) {
				return $theme;
			}

			$theme = $this->find(
				'first',
				array(
					'fields' => array(
						'Theme.id',
						'Theme.name',
						'Theme.core'
					),
					'conditions' => array(
						'Theme.active' => 1
					)
				)
			);

			Cache::write('current_theme', $theme, 'core');

			return $theme;
		}

		/**
		 * before saving
		 *
		 * if the new / edited theme is active deactivte everything.
		 * 
		 * @return bool
		 */
		public function beforeSave(){
			if(isset($this->data['Theme']['active']) && $this->data['Theme']['active']){
				return $this->deactivateAll();
			}

			return parent::beforeSave();
		}

		/**
		 * before deleteing
		 *
		 * If the theme is active do not let it be deleted.
		 * 
		 * @param $cascade bool
		 * @return bool true to delete, false to stop
		 */
		public function beforeDelete($cascade){
			$active = $this->read('active');
			return isset($active['Theme']['active']) && !$active['Theme']['active'];
		}

		/**
		 * deactivate all themes.
		 *
		 * This is used before activating a theme to make sure that there is only
		 * ever one theme active.
		 *
		 * @return bool true on sucsess false if not.
		 */
		public function deactivateAll(){
			return $this->updateAll(
				array(
					'Theme.active' => '0'
				)
			);
		}
	}