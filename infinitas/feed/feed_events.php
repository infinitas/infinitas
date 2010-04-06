<?php
	class FeedEvents {
		function onSetupCacheStart(){
			Cache::config(
				'feed',
				array(
					'engine' => 'File',
					'duration' => 3600,
					'probability' => 100,
					'prefix' => '',
					'lock' => false,
					'serialize' => true,
					'path' => CACHE . 'feed'
				)
			);
		}
	}