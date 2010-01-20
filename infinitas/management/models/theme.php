<?php
	/**
	 * Comment Template.
	 *
	 * @todo Implement .this needs to be sorted out.
	 *
	 * Copyright (c) 2009 Carl Sutton ( dogmatic69 )
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 * @filesource
	 * @copyright Copyright (c) 2009 Carl Sutton ( dogmatic69 )
	 * @link http://www.dogmatic.co.za
	 * @package sort
	 * @subpackage sort.comments
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.5a
	 */

	class Theme extends ManagementAppModel {
		var $name = 'Theme';

		var $tablePrefix = 'core_';

		var $hasMay = array(
			'Management.Route'
		);

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

		function afterSave($created) {
			parent::afterSave($created);

			$this->__clearCache();
			return true;
		}

		function afterDelete() {
			parent::afterDelete();

			$this->__clearCache();
			return true;
		}

		function __clearCache() {
			if (is_file(CACHE . 'core' . DS . 'current_theme')) {
				unlink(CACHE . 'core' . DS . 'current_theme');
			}

			return true;
		}
	}
?>