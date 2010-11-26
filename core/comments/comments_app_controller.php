<?php
	/**
	 * Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 *
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package Infinitas.Comments
	 * @subpackage Infinits.Comments.AppController
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.8a
	 *
	 * @author dogmatic69
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */

	class CommentsAppController extends AppController{
		/**
		 * some helpers to load for this plugin
		 * @var array
		 */
		public $helpers = array(
			'Filter.Filter',
			'Libs.Gravatar'
		);
	}