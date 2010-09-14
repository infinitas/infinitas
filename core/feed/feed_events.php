<?php
	final class FeedEvents extends AppEvents {
		public function onPluginRollCall(){
			return array(
				'name' => 'Feeds',
				'description' => 'Provide RSS feeds of anything to your users',
				'icon' => '/feed/img/icon.png',
				'author' => 'Infinitas',
				'dashboard' => array('plugin' => 'feed', 'controller' => 'feeds', 'action' => 'index')
			);
		}

		public function onAdminMenu(&$event){
			$menu['main'] = array(
				'Feeds' => array('plugin' => 'feed', 'controller' => 'feeds', 'action' => 'index'),
				'Feed Items' => array('plugin' => 'feed', 'controller' => 'feed_items', 'action' => 'index')
			);

			return $menu;
		}

		public function onSetupCacheStart(){
			return array(
				'name' => 'feed',
				'config' => array(
					'duration' => 3600,
					'probability' => 100,
					'prefix' => 'core.feed.',
					'lock' => false,
					'serialize' => true
				)
			);
		}

		public function onSetupExtentions(){
			return array(
				'rss'
			);
		}
	}