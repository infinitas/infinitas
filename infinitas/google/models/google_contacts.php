<?php
/**
* Google Contacts Model
*
* Allows use of google contacts datasource
*
* Copyright (c) 2009 Juan Carlos del Valle ( imekinox )
*
* Licensed under The MIT License
* Redistributions of files must retain the above copyright notice.
*
* @copyright Copyright (c) 2009 Juan Carlos del Valle ( imekinox )
* @link http://www.imekinox.com
* @package google
* @subpackage google.models.google_contacts
* @license MIT License (http://www.opensource.org/licenses/mit-license.php)
*/
class GoogleContacts extends AppModel {
	var $name = 'GoogleContacts';
	var $useDbConfig = 'google_contacts';
	var $primaryKey = 'id';

	public function create($model) {
		if(!isset($model['updated'])) {
			$this->data[$this->name]['updated'] = "";
		}
	}
}