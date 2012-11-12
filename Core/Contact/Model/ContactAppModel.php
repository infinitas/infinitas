<?php
	/**
	 * The main contact's plugin model
	 *
	 * this is extended by the other models in contact plugin
	 *
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package Infinitas.Contact
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.7a
	 *
	 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
	 */

	class ContactAppModel extends AppModel {
		/**
		 * the table prefix for this plugin
		 *
		 * @var string
		 * @access public
		 */
		public $tablePrefix = 'contact_';
	}