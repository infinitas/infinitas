<?php
	final class FeedEvents extends AppEvents {
		public function onPluginRollCall(Event $Event) {
			return array(
				'name' => 'Feeds',
				'description' => 'Provide RSS feeds of anything to your users',
				'icon' => 'rss',
				'author' => 'Infinitas',
				'dashboard' => array('plugin' => 'feed', 'controller' => 'feeds', 'action' => 'index')
			);
		}

		public function onAdminMenu(Event $Event) {
			$menu['main'] = array(
				'Feeds' => array('plugin' => 'feed', 'controller' => 'feeds', 'action' => 'index')
			);

			return $menu;
		}

		public function onSetupCacheStart(Event $Event) {
			return array(
				'name' => 'feed',
				'config' => array(
					'prefix' => 'feed.',
				)
			);
		}

		public function onSetupExtensions(Event $Event) {
			return array(
				'rss'
			);
		}

		public function onListAvailableFeeds(Event $Event) {
			return ClassRegistry::init('Feed.Feed')->listFeeds();
		}

		public function onSlugUrl(Event $Event, $data = null, $type = null) {
			return array(
				'html' => array(
					'plugin' => 'feed',
					'controller' => 'feeds',
					'action' => 'view',
					'slug' => $data['Feed']['slug']
				),
				'rss' => array(
					'plugin' => 'feed',
					'controller' => 'feeds',
					'action' => 'view',
					'slug' => $data['Feed']['slug'],
					'ext' => 'rss'
				)
			);
		}

		public function onSetupRoutes(Event $Event) {
			InfinitasRouter::connect(
				'/feeds/subscribe/:slug',
				array(
					'plugin' => 'feed',
					'controller' => 'feeds',
					'action' => 'view',
					'ext' => 'rss'
				),
				array(
					'pass' => 'slug'
				)
			);

			InfinitasRouter::connect(
				'/feeds/view/:slug',
				array(
					'plugin' => 'feed',
					'controller' => 'feeds',
					'action' => 'view',
					'ext' => ''
				),
				array(
					'pass' => 'slug'
				)
			);
		}
	}