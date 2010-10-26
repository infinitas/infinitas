<?php

	/**
	 * sample usage
	 * $this->Emailer->sendDirectMail(
				array(
					'dogmatic69@gmail.com'
				),
				array(
					'subject' => 'this is a test email',
					'body' => "This allows the requestAction call to bypass the usage of Router::url which can increase performance. The url based arrays are the same as the ones that HtmlHelper::link uses with one difference - if you are using named or passed parameters, you must put them in a second array and wrap them with the correct key. This is because requestAction only merges the named args array into the Controller::params member array and does not place the named args in the key 'named'.",
					'template' => 'User - Registration'
				)
			);
	 * @author dogmatic
	 *
	 */

	class EmailerComponent extends EmailComponent{
		public $settings = null;
		public $_default = array();
		/**
		* Controllers initialize function.
		*/
		public function initialize(&$controller, $settings = array()) {
			$this->Controller = &$controller;
			$this->settings = array_merge($this->_default, (array)$settings);
		}

		public function startup(&$controller){
			$this->settings();
		}

		public function settings(){
			$this->reset();
			$this->delivery = Configure::read('Newsletter.send_method');

			if (Configure::read('Newsletter.send_method') == 'smtp') {
				$this->smtpOptions = array(
					'port' => Configure::read('Newsletter.smtp_out_going_port'),
					'timeout' => Configure::read('Newsletter.smtp_timeout'),
					'host' => Configure::read('Newsletter.smtp_host'),
					'username' => Configure::read('Newsletter.smtp_username'),
					'password' => Configure::read('Newsletter.smtp_password'),
					'greeting' => Configure::read('Newsletter.greeting')
				);
			}

			$this->sendAs = Configure::read('Newsletter.send_as');

			$name = Configure::read('Website.name');
			if (Configure::read('Newsletter.from_name')) {
				$name = Configure::read('Newsletter.from_name');
			}

			if (empty($name)) {
				$name = 'Infinitas Mailer';
			}

			$this->template = Configure::read('Newsletter.template');

			$this->defaultFromName = $name;

			// $this->from = $name . ' <' . Configure::read('Newsletter.from_email') . '>';
			$this->from = Configure::read('Newsletter.from_email');

			$this->trackViews = Configure::read('Newsletter.track_views');
		}

		public function sendDirectMail($userDetails, $email = array()){
			$this->trackViews = false;
			if(empty($userDetails)){
				return false;
			}

			$email = array_merge(
				array(
					'subject' => '',
					'body' => '',
					'template' => ''
				),
				$email
			);

			$template = ClassRegistry::init('Newsletter.Template')->getTemplate($email['template']);

			if(empty($template)){
				$this->Controller->Session->setFlash(__('System error: Email not setup', true));
				$this->redirect('/');
			}

			$html = $template['Template']['header'] . $email['body'] . $template['Template']['footer'];
			$search = array('<br/>', '<br>', '</p><p>');
			$text = strip_tags(str_replace($search, "\n\r", $html));

			$sendEmail = array(
				'to' => $userDetails,
				'subject' => $email['subject'],
				'html' => $html,
				'text' => $text
			);

			$this->Controller->requestAction(
				array(
					'plugin' => 'newsletter',
					'controller' => 'newsletters',
					'action' => 'sendEmail'
				),
				array(
					'named' => array(
						'email' => $sendEmail
					)
				)
			);
		}
	}