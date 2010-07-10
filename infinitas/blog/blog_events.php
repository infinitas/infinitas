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
			if(!isset($data['data'])){
				$data['data'] = $data;
			}
			if(!isset($data['type'])){
				$data['type'] = 'posts';
			}
			switch($data['type']){
				case 'posts':
					$post = isset($data['data']['Post']) ? $data['data']['Post'] : $data['data'];
					return array(
						'plugin' => $post['plugin'],
						'controller' => $post['controller'],
						'action' => $post['action'],
						'id' => $post['id'],
						'category' => isset($data['data']['Category']['slug']) ? $data['data']['Category']['slug'] : 'news-feed',
						'slug' => $post['slug']
					);
					break;
			} // switch
		}
	}