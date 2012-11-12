<?php
	/*
	 * Short Description / title.
	 *
	 * Overview of what the file does. About a paragraph or two
	 *
	 * Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 *
	 * @filesource
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package Core.Webmaster.Controller
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.7a
	 *
	 * @author dogmatic69
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */

	class WebmasterController extends WebmasterAppController {
		public $uses = array();

		public function admin_dashboard() {
			if(!is_file(APP . 'webroot' . DS . 'robots.txt')) {
				$this->notice(
					__('You do not seem to have a robots file'),
					array(
						'level' => 'warning'
					)
				);
			}
		}
	}