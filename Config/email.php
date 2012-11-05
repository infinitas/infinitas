<?php
/**
 * @brief email config
 *
 * Infinitas uses the CakeEmail libs for sending emails, with the configurations
 * stored in the database.
 *
 * If making use of the built in Infinitas email sending and recieving you should
 * not specify configs here
 *
 * @see http://infinitas-cms.org/infinitas_docs/Emails
 */

class EmailConfig {
	public $default = array();

	public function __construct() {
		$this->default = array(
			'transport' => 'Mail',
			'from' => array(
				Configure::read('Website.email') => Configure::read('Website.name')
			)
		);
	}
}
