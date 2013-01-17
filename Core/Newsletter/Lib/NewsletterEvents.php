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
}