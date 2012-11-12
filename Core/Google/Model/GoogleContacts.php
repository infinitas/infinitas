<?php
	/**
	 * Google Contacts Model
	 *
	 * Allows use of google contacts datasource
	 *
	 * Copyright (c) 2009 Juan Carlos del Valle ( imekinox )
	 *
	 *
	 *
	 *
	 * @copyright Copyright (c) 2009 Juan Carlos del Valle ( imekinox )
	 * @link http://www.imekinox.com
	 * @package Infinitas.Google.Model
	 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
	 */
	class GoogleContacts extends AppModel {
		public $useDbConfig = 'google_contacts';

		public $primaryKey = 'id';

		public function create($model) {
			if (!isset($model['updated'])) {
				$this->data[$this->name]['updated'] = '';
			}
		}
	}