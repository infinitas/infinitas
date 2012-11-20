<?php
/**
 * Events for the Infinitas Short Urls
 *

 *
 * @filesource
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.ShortUrls.Lib
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.8a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

class ShortUrlsEvents extends AppEvents {
/**
 * Get plugin details
 *
 * @param Event $Event
 *
 * @return array
 */
	public function onPluginRollCall(Event $Event) {
		return array(
			'name' => 'Short Urls',
			'description' => 'Create and manage short urls',
			'icon' => '/short_urls/img/icon.png',
			'author' => 'Infinitas',
			'dashboard' => array(
				'plugin' => 'short_urls',
				'controller' => 'short_urls',
				'action' => 'index'
			),
		);
	}

/**
 * create the admin menus
 *
 * @param Event $Event
 *
 * @return array
 */
	public function onAdminMenu(Event $Event) {
		$menu['main'] = array(
			'Dashboard' => array('plugin' => 'management', 'controller' => 'management', 'action' => 'site'),
			'Short Urls' => array('plugin' => 'short_urls', 'controller' => 'short_urls', 'action' => 'index')
		);

		return $menu;
	}

/**
 * configure some routes for short urls
 *
 * @return void
 */
	public function onSetupRoutes(Event $Event) {
		// preview
		InfinitasRouter::connect(
			'/s/p/*',
			array(
				'plugin' => 'short_urls',
				'controller' => 'short_urls',
				'action' => 'preview'
			)
		);

		// redirect
		InfinitasRouter::connect(
			'/s/*',
			array(
				'plugin' => 'short_urls',
				'controller' => 'short_urls',
				'action' => 'view'
			)
		);
	}

	public function onShortenUrl(Event $Event, $url) {

	}

/**
 * slug a shortened url
 *
 * @param Event $Event the event being triggered
 * @param array $data the data
 *
 * @return type
 */
	public function onSlugUrl(Event $Event, $data = null, $type = null) {
		$data['type'] = isset($data['type']) ? $data['type'] : '';
		switch($data['type']) {
			case 'preview':
				return array(
					'admin' => false,
					'plugin' => 'short_urls',
					'controller' => 'short_urls',
					'action' => 'preview',
					$data['code']
				);
				break;

			default:
				return array(
					'admin' => false,
					'plugin' => 'short_urls',
					'controller' => 'short_urls',
					'action' => 'view',
					$data['code']
				);
				break;
		}
	}

/**
 * get a shortened url
 *
 * event to create short urls from anywere in the code. show a preview
 * link by setting type => preview or leave type out type to go direct to
 * the url
 *
 * @param $Event the event object
 * @param $data array of type and url
 * @return array
 */
	public function onGetShortUrl(Event $Event, $data) {
		$data['code'] = ClassRegistry::init('ShortUrls.ShortUrl')->shorten($data['url']);
		if(!$data['code']) {
			throw new Exception('Could not find the url');
		}

		return $this->onSlugUrl($Event, $data);
	}

}