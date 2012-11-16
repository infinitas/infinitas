<?php
/**
 * EmailerComponent
 *
 * This allows the requestAction call to bypass the usage of
 * Router::url which can increase performance. The url based arrays are the
 * same as the ones that HtmlHelper::link uses with one difference - if you
 * are using named or passed parameters, you must put them in a second array
 * and wrap them with the correct key. This is because requestAction only merges
 * the named args array into the Controller::params member array and does
 * not place the named args in the key 'named'.
 *
 * @code
 *	$this->Emailer->sendDirectMail(
 *		array('someone@example.com'),
 *		array(
 *			'subject' => 'this is a test email',
 *			'body' => 'The body of the email',
 *			'template' => 'User - Registration'
 *		)
 * );
 *
 * @author dogmatic
 */

class EmailerComponent extends EmailComponent {
/**
 * component settings
 *
 * @var array
 */
	public $settings = array();

/**
 * default configs
 *
 * @var array
 */
	protected $_default = array();

/**
 * Component initialize function.
 */
	public function initialize(Controller $Controller, $settings = array()) {
		$this->settings = array_merge($this->_default, (array)$settings);
		$this->settings();

		return true;
	}

	public function startup(Controller $Controller) {
		$this->Controller = $Controller;
		$this->settings();

		return true;
	}

	public function settings() {
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

	public function sendDirectMail($userDetails, $email = array()) {
		$this->trackViews = false;
		if(empty($userDetails)) {
			return false;
		}

		$email = array_merge(array(
			'body' => '',
			'template' => ''
		), $email);

		try {
			$template = ClassRegistry::init('Newsletter.Template')->getTemplate($email['template']);
		}

		catch(Exception $e) {
			$this->Controller->notice(
				$e->getMessage(),
				array('redirect' => true)
			);
		}

		$html = $template['Template']['header'] . $email['body'] . $template['Template']['footer'];

		$Email = new InfinitasEmail();
		$Email->sendMail(array(
			'to' => $userDetails,
			'from' => $email['from'],
			'subject' => $email['subject'],
			'html' => $html,
			'text' => strip_tags(str_replace(array('<br/>', '<br>', '</p><p>'), "\n\r", $html))
		));
	}

}