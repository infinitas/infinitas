<?php
	/* 
	 * Short Description / title.
	 * 
	 * Overview of what the file does. About a paragraph or two
	 * 
	 * Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * 
	 * @filesource
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package {see_below}
	 * @subpackage {see_below}
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since {check_current_milestone_in_lighthouse}
	 * 
	 * @author {your_name}
	 * 
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */

	final class ContactEvents extends AppEvents{
		public function onAdminMenu($event){
			$menu['main'] = array(
				'Branches' => array('plugin' => 'contact', 'controller' => 'branches', 'action' => 'index'),
				'Contacts' => array('plugin' => 'contact', 'controller' => 'contacts', 'action' => 'index')
			);

			return $menu;
		}
		
		public function onRequireCssToLoad($event){
			return array(
				'/contact/css/contact'
			);
		}

		public function onSetupExtentions(){
			return array(
				'vcf'
			);
		}

		public function onSiteMapRebuild($event){
			$newest = ClassRegistry::init('Contact.Branch')->getNewestRow();
			$frequency = ClassRegistry::init('Contact.Contact')->getChangeFrequency();

			$return = array();
			$return[] = array(
				'url' => Router::url(array('plugin' => 'contact', 'controller' => 'branches', 'action' => 'index', 'admin' => false, 'prefix' => false), true),
				'last_modified' => $newest,
				'change_frequency' => $frequency
			);

			$branches = ClassRegistry::init('Contact.Branch')->find('list');
			foreach($branches as $branch){
				$return[] = array(
					'url' => Router::url(array('plugin' => 'contact', 'controller' => 'branches', 'action' => 'view', 'slug' => $branch, 'admin' => false, 'prefix' => false), true),
					'last_modified' => $newest,
					'change_frequency' => $frequency
				);
			}
			
			return $return;
		}
	}