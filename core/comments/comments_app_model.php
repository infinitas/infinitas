<?php
	/**
	 * This is the base model that all the models in comments extends
	 * 
	 * Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * 
	 * @filesource
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package Infinitas.Comments
	 * @subpackage Infinitas.Comments.AppModel
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.8a
	 * 
	 * @author dogmatic69
	 * 
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */

	class CommentsAppModel extends AppModel{
		/**
		 * the table prefix used in this plugin
		 * @var string
		 */
		public $tablePrefix = 'global_';
	}