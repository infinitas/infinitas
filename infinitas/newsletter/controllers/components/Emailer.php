<?php
	App::import('Component', 'Email');

	class EmailerComponent extends EmailComponent{
		var $settings = null;
		var $_default = array();
		/**
		* Controllers initialize function.
		*/
		function initialize(&$controller, $settings = array()) {
			Configure::write('CORE.current_route', Router::currentRoute());
			$this->Controller = &$controller;
			$this->settings = array_merge($this->_default, (array)$settings);
		}

		function startup(&$controller){
			$this->settings();
		}

		function settings(){
			$this->reset();
			$this->delivery = Configure::read('Newsletter.send_method');

			if (Configure::read('Newsletter.send_method') == 'smtp') {
				$this->smtpOptions = array(
					'port' => Configure::read('Newsletter.smtp_out_going_port'),
					'timeout' => Configure::read('Newsletter.smtp_timeout'),
					'host' => Configure::read('Newsletter.smtp_host'),
					'username' => Configure::read('Newsletter.smtp_username'),
					'password' => Configure::read('Newsletter.smtp_password')
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

		function sendDirectMail($userDetails, $email = array()){
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