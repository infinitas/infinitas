<?php
/**
 * Newsletter App Controller class file.
 *
 * The parent class that all the newsletter plugin controller classes extend
 * from. This is used to make functionality that is needed all over the
 * newsletter plugin.
 *
 * @copyright Copyright (c) 2009 Carl Sutton ( dogmatic69 )
 *
 * @link http://infinitas-cms.org
 * @package Infinitas.Newsletter.Controller
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

class NewsletterAppController extends AppController {

/**
 * beforeFilter callback
 *
 * @return void
 */
	public function beforeFilter() {
		parent::beforeFilter();

		// $this->set( 'newsletterPending', ClassRegistry::init( 'Newsletter.Newsletter' )->getPending() );
		// $this->set( 'newsletterSending', ClassRegistry::init( 'Newsletter.Newsletter' )->getSending() );
	}

/**
 * BeforeRender callback
 *
 * @return void
 */
	public function beforeRender() {
		parent::beforeRender();

		if (strtolower(Configure::read('Newsletter.send_method')) == 'smtp' && $this->Email->smtpError) {
			$this->log('newsletter_smtp_errors', $this->Emailer->smtpError);
			Configure::write('Newsletter.smtp_errors', $this->Emailer->smtpError);
		}
	}
}