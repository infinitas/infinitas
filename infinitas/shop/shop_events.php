<?php
	class ShopEvents{
		function onSetupCache(){
			Cache::config(
				'shop',
				array(
					'engine' => 'File',
					'duration' => 3600,
					'probability' => 100,
					'prefix' => '',
					'lock' => false,
					'serialize' => true,
					'path' => CACHE . 'shop'
				)
			);
		}

		function onSlugUrl(&$event, $data){
			switch($data['type']){
				case 'products':
					return array(
						'plugin' => $data['data']['plugin'],
						'controller' => $data['data']['controller'],
						'action' => $data['data']['action'],
						'id' => $data['data']['id'],
						'category' => !empty($data['data']['Category']['slug']) ? $data['data']['Category']['slug'] : __('news-feed', true),
						'slug' => $data['data']['slug']
					);
					break;
			} // switch
		}

		function onSetupConfigEnd(&$event){
			Configure::load('Shop.config');
		}
	}