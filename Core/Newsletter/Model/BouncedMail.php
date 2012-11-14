<?php
/**
 * BouncedMail
 *
 * @package Infinitas.Newsletter.Model
 */

/**
 * BouncedMail
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Newsletter.Model
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.7a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

class BouncedMail extends NewsletterAppModel {
/**
 * database configuration to use
 *
 * @var string
 */
	public $useDbConfig = 'imap';

/**
 * Behaviors to attach
 *
 * @var boolean
 */
	public $actsAs = false;

/**
 * database table to use
 *
 * @var boolean
 */
	public $useTable = false;

/**
 * Server details
 *
 * @var array
 */
	public $server = array();

/**
 * Constructor
 *
 * @param type $id
 * @param type $table
 * @param type $ds
 *
 * @return void
 */
	public function  __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);

		$this->server = array(
			'server' => Configure::read('Newsletter.smtp_host'),
			'username' => Configure::read('Newsletter.smtp_username'),
			'email' => Configure::read('Newsletter.smtp_username'),
			'password' => Configure::read('Newsletter.smtp_password'),
			'ssl' => Configure::read('Newsletter.ssl'),
			'port' => Configure::read('Newsletter.port'),
			'type' => Configure::read('Newsletter.type')
		);
	}

}