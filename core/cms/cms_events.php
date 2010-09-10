<?php
	final class CmsEvents extends AppEvents{
		public function onPluginRollCall(){
			return array(
				'name' => 'Cms',
				'description' => 'Content Management',
				'icon' => '/cms/img/icon.png',
				'author' => 'Infinitas'
			);
		}
		
		public function onSetupConfig(){
			return Configure::load('cms.config');
		}
		
		public function onSetupCache(){
			return array(
				'name' => 'cms',
				'config' => array(
					'duration' => 3600,
					'probability' => 100,
					'prefix' => 'cms.',
					'lock' => false,
					'serialize' => true
				)
			);
		}

		public function onSlugUrl(&$event, $data){
			switch(strtolower($data['type'])){
				case 'contents':					
					$url = array(
						'plugin'     => 'cms',
						'controller' => 'contents',
						'action'     => 'view',
						'id'         => $data['data']['Content']['id'],
						'slug'       => $data['data']['Content']['slug'],
						'category'   => isset($data['data']['Category']['slug']) ? $data['data']['Category']['slug'] : __('news-item',true)
					);
					break;

				default:
					echo 'Error: invalid url type';
					break;
			} // switch

			return $url;
		}
	}