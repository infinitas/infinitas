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
	 * @author Carl Sutton ( dogmatic69 )
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */

	class ContactAppController extends AppController {
		/**
		 * Helpers used within this plugin
		 * 
		 * @var array
		 * @access public
		 */
		public $helpers = array(
			'Filter.Filter',
			'Contact.Vcf',
			'Html',
			'Google.StaticMap'
		);

		function beforeFilter(){
			parent::beforeFilter();

			$this->RequestHandler->setContent('vcf', 'text/x-vcard');
		}
	}