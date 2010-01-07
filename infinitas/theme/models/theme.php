<?php
	/**
	 *
	 *
	 */
	class Theme extends ThemeAppModel{
		var $name = 'Theme';
		function getCurrnetTheme($conditions = array()){
			$theme = $this->find(
				'first',
				array(
					'conditions' => $conditions
				)
			);

			return $theme;
		}
	}
?>