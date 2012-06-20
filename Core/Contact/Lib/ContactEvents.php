<?php
	/**
	 * @brief Contact plugin events.
	 *
	 * The events for the Contact plugin for setting up cache and the general
	 * configuration of the plugin.
	 *
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package Infinitas.Contact
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.7a
	 *
	 * @author dogmatic69
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */
	Configure::load('Contact.config');

	final class ContactEvents extends AppEvents{
		public function onAdminMenu($event) {			
			$menu['main'] = array(
				'Branches' => array('plugin' => 'contact', 'controller' => 'branches', 'action' => 'index'),
				'Contacts' => array('plugin' => 'contact', 'controller' => 'contacts', 'action' => 'index')
			);

			return $menu;
		}
		
		public function onRequireCssToLoad($event) {
			return array(
				'Contact.contact'
			);
		}

		public function onSetupExtensions() {
			return array(
				'vcf'
			);
		}

		public function onSiteMapRebuild($event) {
			$newest = ClassRegistry::init('Contact.Branch')->getNewestRow();
			$frequency = ClassRegistry::init('Contact.Contact')->getChangeFrequency();

			$return = array();
			$return[] = array(
				'url' => Router::url(array('plugin' => 'contact', 'controller' => 'branches', 'action' => 'index', 'admin' => false, 'prefix' => false), true),
				'last_modified' => $newest,
				'change_frequency' => $frequency
			);

			$branches = ClassRegistry::init('Contact.Branch')->find('list');
			foreach($branches as $branch) {
				$return[] = array(
					'url' => Router::url(
						array(
							'plugin' => 'contact',
							'controller' => 'branches',
							'action' => 'view',
							'slug' => $branch,
							'admin' => false,
							'prefix' => false
						),
						true
					),
					'last_modified' => $newest,
					'change_frequency' => $frequency
				);
			}
			
			return $return;
		}
	}