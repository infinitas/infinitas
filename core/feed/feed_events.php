<?php
	final class FeedEvents extends AppEvents {
		public function onPluginRollCall(){
			return array(
				'name' => 'feed',
				'description' => 'Provide RSS feeds of anything to your users',
				'icon' => '/feed/img/icon.png',
				'author' => 'Infinitas'
			);
		}

		public function onSetupCacheStart(){
			return array(
				'name' => 'feed',
				'config' => array(
					'duration' => 3600,
					'probability' => 100,
					'prefix' => 'feed.',
					'lock' => false,
					'serialize' => true
				)
			);
		}
	}