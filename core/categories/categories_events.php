<?php
	/**
	 * @brief Categories plugin events.
	 *
	 * The events for the Categories plugin for setting up cache and the general
	 * configuration of the plugin.
	 *
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package Infinitas.Categories
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.8a
	 *
	 * @author dogmatic69
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */

	final class CategoriesEvents extends AppEvents{
		public function onAdminMenu($event){
			$menu['main'] = array(
				'Categories' => array('controller' => false, 'action' => false),
				'Active' => array('controller' => 'categories', 'action' => 'index', 'Category.active' => 1),
				'Disabled' => array('controller' => 'categories', 'action' => 'index', 'Category.active' => 0)
			);

			return $menu;
		}
		
		public function onAttachBehaviors($event) {
			if(is_subclass_of($event->Handler, 'Model') && isset($event->Handler->_schema) && is_array($event->Handler->_schema)) {
				if (array_key_exists('category_id', $event->Handler->_schema)  && !$event->Handler->Behaviors->enabled('Categories.Categorisable')) {
					$event->Handler->Behaviors->attach('Categories.Categorisable');
				}
			}
		}

		public function onSiteMapRebuild($event){
			$Category = ClassRegistry::init('Categories.Category');
			$newest = $Category->getNewestRow();
			$frequency = $Category->getChangeFrequency();

			$return = array();
			$return[] = array(
				'url' => Router::url(
					array(
						'plugin' => 'categories',
						'controller' => 'categories',
						'action' => 'index',
						'admin' => false,
						'prefix' => false
					),
					true
				),
				'last_modified' => $newest,
				'change_frequency' => $frequency
			);

			$categories = $Category->find('list');
			foreach($categories as $category){
				$return[] = array(
					'url' => Router::url(
						array(
							'plugin' => 'categories',
							'controller' => 'categories',
							'action' => 'view',
							'slug' => $category,
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