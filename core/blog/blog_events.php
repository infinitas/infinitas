<?php
	final class BlogEvents extends AppEvents{
		public function onPluginRollCall(){
			return array(
				'name' => 'Blog',
				'description' => 'Blogging platform',
				'icon' => '/blog/img/icon.png',
				'author' => 'Infinitas'
			);
		}

		public function onRequireTodoList(&$event){
			return array(
				array(
					'name' => 'warning no categories',
					'type' => 'warning',
					'url' => array(
						'plugin' => 'categories',
						'controlelr' => 'categories',
						'action' => 'add'
					)
				),
				array(
					'name' => 'Testing: error',
					'type' => 'error',
					'url' => array(
						'plugin' => 'categories',
						'controlelr' => 'categories',
						'action' => 'index'
					)
				),
				array(
					'name' => 'Testing: info',
					'type' => 'info',
					'url' => array(
						'plugin' => 'categories',
						'controlelr' => 'categories',
						'action' => 'add'
					)
				)
			);
		}

		public function onAdminMenu(&$event){
			$menu['main'] = array(
				'Dashboard' => array('controller' => 'posts', 'action' => 'dashboard'),
				'Posts' => array('controller' => 'posts', 'action' => 'index'),
				'Active' => array('controller' => 'posts', 'action' => 'index', 'Post.active' => 1),
				'Pending' => array('controller' => 'posts', 'action' => 'index', 'Post.active' => 0)
			);

			return $menu;
		}

		public function onSetupConfig(){
			return Configure::load('blog.config');
		}
		
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

		public function onRequireHelpersToLoad(&$event){
			
		}

		public function onRequireCssToLoad(&$event){
			if($event->Handler->params['plugin'] == 'blog'){
				return '/blog/css/blog';
			}
		}
	}