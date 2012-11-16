<?php
	/**
	 * Newsletter events
	 *
	 * events for the newsletter system
	 *

	 *
	 * @filesource
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package Infinitas.Newsletter.Lib
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.8a
	 *
	 * @author dogmatic
	 *
	 *
	 *
	 */

	class NewsletterEvents extends AppEvents {
		public function onPluginRollCall(Event $Event) {
			return array(
				'name' => 'Newsletter',
				'description' => 'Keep in contact with your user base',
				'author' => 'Infinitas',
				'icon' => '/newsletter/img/icon.png',
				'dashboard' => array('plugin' => 'newsletter', 'controller' => 'newsletters', 'action' => 'dashboard'),
			);
		}

		public function onAdminMenu(Event $Event) {
			$menu['main'] = array(
				'Dashboard' => array('plugin' => 'newsletter', 'controller' => 'newsletters', 'action' => 'dashboard'),
				'Campaigns' => array('plugin' => 'newsletter', 'controller' => 'campaigns', 'action' => 'index'),
				'Templates' => array('plugin' => 'newsletter', 'controller' => 'templates', 'action' => 'index'),
				'Newsletters' => array('plugin' => 'newsletter', 'controller' => 'newsletters', 'action' => 'index')
			);

			return $menu;
		}

		public function onRequireComponentsToLoad(Event $Event) {
			return array(
				'Email',
				'Newsletter.Emailer'
			);
		}

		public function onSetupRoutes(Event $Event, $data = null) {
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

	}
