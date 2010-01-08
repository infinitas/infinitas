<?php
	/**
	 *
	 *
	 */
	class Theme extends ThemeAppModel{
		var $name = 'Theme';

		/**
		 * Get the current Theme
		 *
		 * @param array $conditions
		 * @return array $theme the current theme.
		 */
		function getCurrnetTheme($admin = 0){

			$theme = Cache::read('currentTheme-'.$admin);

			if ($theme == false) {
				$theme = $this->find(
					'first',
					array(
						'conditions' => array(
							'Theme.admin' => $admin,
							'Theme.active' => 1
						)
					)
				);
			}
			Cache::write('currentTheme-'.$admin, $theme, 'core');

			return $theme;
		}
	}
?>