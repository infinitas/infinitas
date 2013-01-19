<?php
/**
 * NewsletterEvents
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 *
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Newsletter.Lib
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.8a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

class NewsletterEvents extends AppEvents {

/**
 * Plugin rollcall
 *
 * @param Event $Event
 *
 * @return array
 */
	public function onPluginRollCall(Event $Event) {
		return array(
			'name' => 'Newsletter',
			'description' => 'Keep in contact with your user base',
			'author' => 'Infinitas',
			'icon' => 'inbox',
			'dashboard' => array('plugin' => 'newsletter', 'controller' => 'newsletters', 'action' => 'dashboard'),
		);
	}

/**
 * admin menu
 *
 * @param Event $Event
 *
 * @return array
 */
	public function onAdminMenu(Event $Event) {
		$menu['main'] = array(
			'Dashboard' => array('plugin' => 'newsletter', 'controller' => 'newsletters', 'action' => 'dashboard'),
			'Campaigns' => array('plugin' => 'newsletter', 'controller' => 'newsletter_campaigns', 'action' => 'index'),
			'Templates' => array('plugin' => 'newsletter', 'controller' => 'newsletter_templates', 'action' => 'index'),
			'Newsletters' => array('plugin' => 'newsletter', 'controller' => 'newsletters', 'action' => 'index')
		);

		return $menu;
	}

/**
 * get required components
 *
 * @param Event $Event
 *
 * @return array
 */
	public function onRequireComponentsToLoad(Event $Event) {
		return array(
			'Email',
			'Newsletter.Emailer'
		);
	}

	public function onRequireHelpersToLoad(Event $Event) {
		return array(
			'Newsletter.Letter'
		);
	}

/**
 * Configure routes
 *
 * @param Event $Event
 * @param type $data
 */
	public function onSetupRoutes(Event $Event) {
		InfinitasRouter::connect(
			'/admin/newsletter',
			array(
				'admin' => true,
				'prefix' => 'admin',
				'plugin' => 'newsletter',
				'controller' => 'newsletters',
				'action' => 'dashboard'
			)
		);
	}

/**
 * USer profile information
 *
 * @param Event $Event
 * @param array $user
 */
	public function onUserProfile(Event $Event, array $user) {
		$View = $Event->Handler->_View;
	}

	public function onUserRegistration(Event $Event, array $user) {
		return ClassRegistry::init('Newsletter.NewsletterSubscriber')->updateUserDetails($user);
	}

/**
 * Event for sending out system mails (directly, no queue)
 *
 * This should be limited to things like register and forgot password that requires the email being
 * sent right away. For general newsletters you should use queues.
 *
 * $email should contain to arrays:
 *
 *	- email: The details of the email being sent
 *	- var: Any view variables that should be made available to the template
 *
 * Email requires an email address `email` and the newsletter to render `newsletter`. Optional is the
 * `name` of the user.
 *
 * Var differs per mail, but sometimes there is nothing required here
 *
 * @param Event $Event
 * @param array $email
 *
 * @return boolean
 *
 * @throws InvalidArgumentException
 */
	public function onSystemEmail(Event $Event, array $email) {
		if (empty($email['email'])) {
			throw new InvalidArgumentException(__d('newsletter', 'Missing email config'));
		}
		$email['email'] = array_merge(array(
			'email' => null,
			'name' => null,
			'newsletter' => null
		), $email['email']);
		if (empty($email['var'])) {
			$email['var'] = array();
		}

		return $this->_sendMail($email['email']['newsletter'], array(
			'to' => $email['email']['email'],
			'viewVars' => $email['var']
		));
	}

	public function onAdminEmail(Event $Event, array $email) {
		$email['email'] = array_merge(array(
			'newsletter' => null
		), $email['email']);
		if (empty($email['var'])) {
			$email['var'] = array();
		}

		$admins = ClassRegistry::init('Users.User')->find('all', array(
			'fields' => array(
				'User.id',
				'User.email',
				'User.prefered_name',
				'User.username'
			),
			'conditions' => array(
				'User.group_id' => 1
			)
		));
		foreach ($admins as $admin) {
			$email['var']['User'] = $admin['User'];
			$email['var']['User']['name'] = $admin['User']['prefered_name'] ?: $admin['User']['username'];
			$this->_sendMail($email['email']['newsletter'], array(
				'to' => $admin['User']['email'],
				'viewVars' => $email['var']
			));
		}

		return true;
	}

	protected function _sendMail($template, array $config) {
		$mail = ClassRegistry::init('Newsletter.Newsletter')->find('email', $template);
		$config = array_merge(array(
			'from' => $mail['Newsletter']['from'],
			'subject' => $mail['Newsletter']['subject'],
			'html' => $mail['NewsletterTemplate']['header'] . $mail['Newsletter']['html'] . $mail['NewsletterTemplate']['footer'],
			'text' => strip_tags(str_replace(array('<br/>', '<br>', '</p><p>'), "\n\r", implode("\n", array(
				$mail['NewsletterTemplate']['header'],
				$mail['Newsletter']['text'],
				$mail['NewsletterTemplate']['footer']
			))))
		), $config);

		$Email = new InfinitasEmail();
		$Email->sendMail($config);
	}
}