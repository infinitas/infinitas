<?php
/**
 * Contact plugin events.
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
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

class ContactEvents extends AppEvents{
/**
 * load the admin menu
 *
 * @param type $event
 *
 * @return array
 */
	public function onAdminMenu(Event $Event) {
		$menu['main'] = array(
			'Branches' => array('plugin' => 'contact', 'controller' => 'branches', 'action' => 'index'),
			'Contacts' => array('plugin' => 'contact', 'controller' => 'contacts', 'action' => 'index')
		);

		return $menu;
	}

/**
 * load required css
 *
 * @param Event $Event
 *
 * @return array
 */
	public function onRequireCssToLoad(Event $Event) {
		return array(
			'Contact.contact'
		);
	}

/**
 * specify any extentions that need to be registered
 *
 * @return array
 */
	public function onSetupExtensions() {
		return array(
			'vcf'
		);
	}

/**
 * get data for rebuilding the site map
 *
 * @param Event $Event
 * 
 * @return array
 */
	public function onSiteMapRebuild(Event $Event) {
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