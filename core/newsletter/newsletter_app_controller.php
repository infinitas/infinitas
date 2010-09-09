<?php
	/**
	* Newsletter App Controller class file.
	*
	* The parent class that all the newsletter plugin controller classes extend
	* from. This is used to make functionality that is needed all over the
	* newsletter plugin.
	*
	* Copyright (c) 2009 Carl Sutton ( dogmatic69 )
	*
	* Licensed under The MIT License
	* Redistributions of files must retain the above copyright notice.
	*
	* @filesource
	* @copyright Copyright (c) 2009 Carl Sutton ( dogmatic69 )
	* @link http://infinitas-cms.org
	* @package newsletter
	* @subpackage newsletter.controllers.newsletterAppController
	* @license http://www.opensource.org/licenses/mit-license.php The MIT License
	*/
	class NewsletterAppController extends AppController {
		/**
		* The helpers that the newsletter plugin needs to function.
		*/
		var $helpers = array(
			// cake helpers
			'Time', 'Text', 'Form',
			// core helpers
			'Libs.TagCloud',
			// plugin helpers
			'Newsletter.Letter', 'Google.Chart',
			// layout helpers
			'Newsletter.CampaignLayout', 'Newsletter.NewsletterLayout'
			);

		/**
		* Components.
		*
		* @access public
		* @var array
		*/
		var $components = array(
			'Emailer'
			);

		/**
		* beforeFilter callback
		*
		* this method is run before any of the controllers in the blog plugin.
		* It is used to set up a cache config and some other variables that are
		* needed throughout the plugin.
		*
		* @param nothing $
		* @return nothing
		*/
		function beforeFilter() {
			parent::beforeFilter();
			// $this->set( 'newsletterPending', ClassRegistry::init( 'Newsletter.Newsletter' )->getPending() );
			// $this->set( 'newsletterSending', ClassRegistry::init( 'Newsletter.Newsletter' )->getSending() );
		}

		function beforeRender() {
			parent::beforeRender();

			if (strtolower(Configure::read('Newsletter.send_method')) == 'smtp' && $this->Email->smtpError) {
				$this->log('newsletter_smtp_errors', $this->Emailer->smtpError);
				Configure::write('Newsletter.smtp_errors', $this->Emailer->smtpError);
			}
		}

		/**
		* afterFilter callback.
		*
		* used to do stuff before the code is rendered but after all the
		* controllers have finnished.
		*
		* @param nothing $
		* @return nothing
		*/
		function afterFilter() {
			parent::afterFilter();
		}
	}