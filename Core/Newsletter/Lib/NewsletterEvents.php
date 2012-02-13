<?php
	/**
	 * Newsletter events
	 * 
	 * events for the newsletter system
	 * 
	 * Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * 
	 * @filesource
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package newsletter
	 * @subpackage infinitas.newsletter.events
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.8a
	 * 
	 * @author dogmatic
	 * 
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */

	final class NewsletterEvents extends AppEvents{
		public function onPluginRollCall(){
			return array(
				'name' => 'Newsletter',
				'description' => 'Keep in contact with your user base',
				'author' => 'Infinitas',
				'icon' => '/newsletter/img/icon.png',
				'dashboard' => array('plugin' => 'newsletter', 'controller' => 'newsletters', 'action' => 'dashboard'),
			);
		}

		public function onAdminMenu($event){
			$menu['main'] = array(
				'Dashboard' => array('plugin' => 'newsletter', 'controller' => 'newsletters', 'action' => 'dashboard'),
				'Campaigns' => array('plugin' => 'newsletter', 'controller' => 'campaigns', 'action' => 'index'),
				'Templates' => array('plugin' => 'newsletter', 'controller' => 'templates', 'action' => 'index'),
				'Newsletters' => array('plugin' => 'newsletter', 'controller' => 'newsletters', 'action' => 'index')
			);

			return $menu;
		}

		public function onSetupConfig(){
			return Configure::load('Newsletter.config');
		}
		 
		public function onRequireComponentsToLoad(){
			return array(
				'Email',
				'Newsletter.Emailer'
			);
		}

		public function onSetupRoutes($event, $data = null) {
			Router::connect(
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
