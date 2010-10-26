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
	 * @link http://infinitas-cms.org
	 * @package sort
	 * @subpackage sort.comments
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.5a
	 */

	class ManagementController extends ManagementAppController {
		public $name = 'Management';

		public $uses = array();

		public function beforeFilter(){
			parent::beforeFilter();

			$this->helpers[] = 'Google.Chart';
		}

		/**
		 * This is the main admin dashboard when visiting admin/
		 */
		public function admin_dashboard() {
		}

		/**
		 * This is a few of the smaller items that dont need to be directly on the
		 * main dashboard as they will not be used all that often.
		 */
		public function admin_site(){

		}
	}