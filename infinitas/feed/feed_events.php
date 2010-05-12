<?php
	class FeedEvents {
		function onSetupCacheStart(){
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