<?php
	class BlogEvents{
		function onSetupCache(){
			return array(
				'name' => 'blog',
				'config' => array(
					'duration' => 3600,
					'probability' => 100,
					'prefix' => 'blog.',
					'lock' => false,
					'serialize' => true
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