<?php
	/**
	 * @brief ContentsEvents plugin events.
	 *
	 * The events for the Contents plugin for setting up cache and the general
	 * configuration of the plugin.
	 *
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package Infinitas.Contents
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.8a
	 *
	 * @author dogmatic69
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */

	final class ContentsEvents extends AppEvents{
		public function  onPluginRollCall() {
			return array(
				'name' => 'Content',
				'description' => 'Mange the way content works inside Infinitas',
				'icon' => '/contents/img/icon.png',
				'author' => 'Infinitas',
				'dashboard' => array('plugin' => 'contents', 'controller' => 'global_contents', 'action' => 'index')
			);
		}
		public function onAdminMenu($event){
			$menu['main'] = array(
				'Contents' => array('plugin' => 'contents', 'controller' => 'global_contents', 'action' => 'index'),
				'Layouts' => array('plugin' => 'contents', 'controller' => 'global_layouts', 'action' => 'index'),
				'Categories' => array('plugin' => 'contents', 'controller' => 'global_categories', 'action' => 'index'),
			);

			return $menu;
		}

		public function onAttachBehaviors($event) {
			if(is_subclass_of($event->Handler, 'Model') && isset($event->Handler->_schema) && is_array($event->Handler->_schema)) {
				if (isset($event->Handler->contentable) && $event->Handler->contentable && !$event->Handler->Behaviors->enabled('Contents.Contentable')) {					
					$event->Handler->Behaviors->attach('Contents.Contentable');
				}
			}
			
			if(is_subclass_of($event->Handler, 'Model') && isset($event->Handler->_schema) && is_array($event->Handler->_schema)) {
				if (array_key_exists('category_id', $event->Handler->_schema)  && !$event->Handler->Behaviors->enabled('Categories.Categorisable')) {
					$event->Handler->Behaviors->attach('Categories.Categorisable');
				}
			}
		}

		public function onSiteMapRebuild($event){
			$Category = ClassRegistry::init('Contents.GlobalCategory');
			$newest = $Category->getNewestRow();
			$frequency = $Category->getChangeFrequency();

			$return = array();
			$return[] = array(
				'url' => Router::url(
					array(
						'plugin' => 'contents',
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

			$categories = $Category->find('list', array('fields' => array('GlobalCategory.id', 'GlobalCategory.slug')));
			foreach($categories as $category){
				$return[] = array(
					'url' => Router::url(
						array(
							'plugin' => 'contents',
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