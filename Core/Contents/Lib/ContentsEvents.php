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

final class ContentsEvents extends AppEvents {
/**
 * @brief get the plugin details
 *
 * @return array
 */
	public function onPluginRollCall() {
		return array(
			'name' => 'Content',
			'description' => 'Mange the way content works inside Infinitas',
			'icon' => '/contents/img/icon.png',
			'author' => 'Infinitas',
			'dashboard' => array('plugin' => 'contents', 'controller' => 'global_contents', 'action' => 'dashboard')
		);
	}

/**
 * @brief get the admin menu
 *
 * @param Event $Event
 *
 * @return array
 */
	public function onAdminMenu(Event $Event) {
		$menu['main'] = array(
			'Dashboard' => array('plugin' => 'contents', 'controller' => 'global_contents', 'action' => 'dashboard'),
			'Layouts' => array('plugin' => 'contents', 'controller' => 'global_layouts', 'action' => 'index'),
			'Contents' => array('plugin' => 'contents', 'controller' => 'global_contents', 'action' => 'index'),
			'Categories' => array('plugin' => 'contents', 'controller' => 'global_categories', 'action' => 'index'),
			'Tags' => array('plugin' => 'contents', 'controller' => 'global_tags', 'action' => 'index')
		);

		return $menu;
	}

/**
 * @brief attach behaviors
 *
 * @param Event $Event
 */
	public function onAttachBehaviors(Event $Event) {
		if($Event->Handler->shouldAutoAttachBehavior()) {
			if (isset($Event->Handler->contentable) && $Event->Handler->contentable && !$Event->Handler->Behaviors->enabled('Contents.Contentable')) {
				$Event->Handler->Behaviors->attach('Contents.Contentable');
			}

			if (array_key_exists('category_id', $Event->Handler->schema())  && !$Event->Handler->Behaviors->enabled('Contents.Categorisable')) {
				$Event->Handler->Behaviors->attach('Contents.Categorisable');
			}
		}
	}

/**
 * @brief get the components to load
 *
 * @param Event $Event
 *
 * @return array
 */
	public function onRequireComponentsToLoad(Event $Event) {
		return array(
			'Contents.GlobalContents'
		);
	}

/**
 * @brief get the helpers to load
 *
 * @return array
 */
	public function onRequireHelpersToLoad() {
		return array(
			'Contents.TagCloud',
			'Contents.GlobalContents'
		);
	}

/**
 * @brief get the js to load
 *
 * @param Event $Event
 *
 * @return array
 */
	public function onRequireJavascriptToLoad(Event $Event) {
		return array(
			'Contents.jq-tags',
			'Contents.tags'
		);
	}

/**
 * @brief get the css to load
 *
 * @param Event $Event
 *
 * @return array
 */
	public function onRequireCssToLoad(Event $Event) {
		return array(
			'Contents.tags'
		);
	}

/**
 * @brief get data for building the sitemap
 *
 * @param Event $Event
 *
 * @return array
 */
	public function onSiteMapRebuild(Event $Event) {
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

		$categories = ClassRegistry::init('Contents.GlobalContent')->find(
			'list',
			array(
				'fields' => array(
					'GlobalContent.foreign_key',
					'GlobalContent.slug'
				),
				'conditions' => array(
					'GlobalContent.model' => 'Contents.GlobalCategory'
				)
			)
		);
		foreach($categories as $category) {
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

/**
 * @brief setup any hard coded routes
 *
 * @param Event $Event
 * @param mixed $data
 *
 * return void
 */
	public function onSetupRoutes(Event $Event, $data = null) {
		InfinitasRouter::connect(
			'/admin/contents',
			array(
				'plugin' => 'contents',
				'controller' => 'global_contents',
				'action' => 'dashboard',
				'admin' => true,
				'prefix' => 'admin'
			)
		);
	}

/**
 * @brief get the params for building a url
 *
 * @see AppEvents::onSlugUrl()
 *
 * @param Event $Event
 * @param mixed $data
 *
 * @return array
 */
	public function onSlugUrl(Event $Event, $data = null) {
		if(empty($data['type'])) {
			$data['type'] = 'category';
		}

		if(!empty($data['model'])) {
			$data = array('type' => $data['model'], 'data' => array('GlobalCategory' => $data));
		}

		if(!empty($data['GlobalCategory'])) {
			$data = array('type' => 'category', 'data' => array('GlobalCategory' => $data));
		}

		switch($data['type']) {
			case 'Contents.GlobalCategory':
				$data['type'] = 'category';
				break;
		}

		return parent::onSlugUrl($Event, $data['data'], $data['type']);
	}

/**
 * @brief parse a route to check if it should be used
 *
 * @param Event $Event
 * @param array $data
 *
 * @return boolean|array
 */
	public function onRouteParse(Event $Event, array $data) {
		$return = null;

		if(!empty($data['slug'])) {
			$return = ClassRegistry::init('Contents.GlobalContent')->find(
				'count',
				array(
					'conditions' => array(
						'GlobalContent.slug' => $data['slug']
					)
				)
			);

			if($return > 0) {
				return $data;
			}

			return false;
		}

		return $data;
	}
}