<?php
	class ContactAppController extends AppController {
		var $helpers = array(
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