<?php
	class BlogEvents{
		function onSetupCache(){
			Cache::config(
				'blog',
				array(
					'engine' => 'File',
					'duration' => 3600,
					'probability' => 100,
					'prefix' => '',
					'lock' => false,
					'serialize' => true,
					'path' => CACHE . 'blog'
				)
			);
		}

		function onSlugUrl(&$event, $data){
			switch($data['type']){
				case 'posts':
					return array(
						'plugin' => $data['data']['plugin'],
						'controller' => $data['data']['controller'],
						'action' => $data['data']['action'],
						'id' => $data['data']['id'],
						'category' => 'news-feed',
						'slug' => $data['data']['slug']
					);
					break;
			} // switch
		}
	}