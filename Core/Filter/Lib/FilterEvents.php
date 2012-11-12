<?php
	/**
	 * Filter events.
	 *
	 * events for the filter plugin
	 *

	 *
	 * @filesource
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package Core.Filter.Lib
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.8a
	 *
	 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
	 */

	final class FilterEvents extends AppEvents {
		public function onRequireComponentsToLoad($event) {
			return array(
				'Filter.Filter' => array(
					'actions' => array('admin_index', 'index')
				)
			);
		}

		public function onRequireHelpersToLoad($event) {
			return array(
				'Filter.Filter'
			);
		}

		public function onRequireCssToLoad($event, $data = null) {
			return array(
				'Filter.filter'
			);
		}
	}