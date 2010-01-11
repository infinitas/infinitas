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
		function getCurrnetTheme(){

			$theme = Cache::read('currentTheme');

			if ($theme == false) {
				$theme = $this->find(
					'first',
					array(
						'conditions' => array(
							'Theme.active' => 1
						)
					)
				);
			}
			Cache::write('currentTheme', $theme, 'core');

			return $theme;
		}
	}
?>