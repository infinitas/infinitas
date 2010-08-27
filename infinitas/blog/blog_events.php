<?php
	final class BlogEvents{
		public function onSetupCache(){
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

		public function onSlugUrl(&$event, $data){
			if(!isset($data['data'])){
				$data['data'] = $data;
			}
			if(!isset($data['type'])){
				$data['type'] = 'posts';
			}
			switch($data['type']){
				case 'posts':
					return array(
						'plugin' => 'blog',
						'controller' => 'posts',
						'action' => 'view',
						'id' => $data['data']['Post']['id'],
						'category' => isset($data['data']['Category']['slug']) ? $data['data']['Category']['slug'] : 'news-feed',
						'slug' => $data['data']['Post']['slug']
					);
					break;
			} // switch
		}

		public function onRequireHelpersToLoad(){
			return array(
				'Tags.TagCloud'
			);
		}
	}