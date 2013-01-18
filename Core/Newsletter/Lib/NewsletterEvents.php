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

		$Newsletter = ClassRegistry::init('Newsletter.Newsletter');
		$mail = $Newsletter->find('email', $email['email']['newsletter']);

		$Email = new InfinitasEmail();
		$sent = $Email->sendMail(array(
			'to' => $email['email']['email'],
			'from' => $mail['Newsletter']['from'],
			'subject' => $mail['Newsletter']['subject'],
			'html' => $mail['NewsletterTemplate']['header'] . $mail['Newsletter']['html'] . $mail['NewsletterTemplate']['footer'],
			'text' => strip_tags(str_replace(array('<br/>', '<br>', '</p><p>'), "\n\r", implode("\n", array(
				$mail['NewsletterTemplate']['header'],
				$mail['Newsletter']['text'],
				$mail['NewsletterTemplate']['footer']
			)))),
			'viewVars' => $email['var']
		));

		if (!$sent) {
			return false;
		}

		return true;
	}
}