<?php
	class ShopEvents{
		function onSetupCache(){
			return array(
				'name' => 'shop',
				'config' => array(
					'duration' => 3600,
					'probability' => 100,
					'prefix' => 'shop.',
					'lock' => false,
					'serialize' => true
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
						'slug' => $data['data']['slug']
					);
					break;

				case 'categories':
					return array(
						'plugin' => $data['data']['plugin'],
						'controller' => $data['data']['controller'],
						'action' => $data['data']['action'],
						'id' => $data['data']['id'],
						'slug' => $data['data']['slug']
					);
					break;
			} // switch
		}

		function onSetupConfigEnd(&$event){
			Configure::load('Shop.config');
		}

		function onSetupThemeLayout(&$event, $data){
			if($data['params']['plugin'] == 'shop' && $data['params']['controller'] == 'carts' && $data['params']['action'] == 'index'){
				return 'checkout';
			}
		}

		function onSetupTabs(&$event, $data){
			echo 'yey: shop event';
			exit;
		}
	}