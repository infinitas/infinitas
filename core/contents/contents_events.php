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
		public function onPluginRollCall() {
			return array(
				'name' => 'Content',
				'description' => 'Mange the way content works inside Infinitas',
				'icon' => '/contents/img/icon.png',
				'author' => 'Infinitas',
				'dashboard' => array('plugin' => 'contents', 'controller' => 'global_contents', 'action' => 'dashboard')
			);
		}
		
		public function onAdminMenu($event) {
			$menu['main'] = array(
				'Dashboard' => array('plugin' => 'contents', 'controller' => 'global_contents', 'action' => 'dashboard'),
				'Layouts' => array('plugin' => 'contents', 'controller' => 'global_layouts', 'action' => 'index'),
				'Contents' => array('plugin' => 'contents', 'controller' => 'global_contents', 'action' => 'index'),
				'Categories' => array('plugin' => 'contents', 'controller' => 'global_categories', 'action' => 'index'),
				'Tags' => array('plugin' => 'contents', 'controller' => 'global_tags', 'action' => 'index')
			);

			return $menu;
		}

		public function onAttachBehaviors($event) {
			if(is_subclass_of($event->Handler, 'Model') && isset($event->Handler->_schema) && is_array($event->Handler->_schema)) {
				if (isset($event->Handler->contentable) && $event->Handler->contentable && !$event->Handler->Behaviors->enabled('Contents.Contentable')) {					
					$event->Handler->Behaviors->attach('Contents.Contentable');
				}

				if (array_key_exists('category_id', $event->Handler->_schema)  && !$event->Handler->Behaviors->enabled('Contents.Categorisable')) {
					$event->Handler->Behaviors->attach('Contents.Categorisable');
				}
			}
		}

		public function onRequireComponentsToLoad($event = null) {
			return array(
				'Contents.GlobalContent'
			);
		}

		public function onRequireHelpersToLoad(){
			return array(
				'Contents.TagCloud',
				'Contents.GlobalContents'
			);
		}

		public function onRequireJavascriptToLoad($event){
			return array(
				'/contents/js/jq-tags',
				'/contents/js/tags'
			);
		}

		public function onRequireCssToLoad($event){
			return array(
				'/contents/css/tags'
			);
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

		public function onSetupRoutes($event, $data = null) {

			Router::connect(
				'/admin/contents',
				array(
					'plugin' => 'contents',
					'controller' => 'global_contents',
					'action' => 'dashboard',
					'admin' => true,
					'prefix' => 'admin'
				)
			);
			Router::connect(
				'/admin/contents/contents/index',
				array(
					'plugin' => 'contents',
					'controller' => 'global_contents',
					'action' => 'index',
					'admin' => true,
					'prefix' => 'admin'
				)
			);
		}
	}