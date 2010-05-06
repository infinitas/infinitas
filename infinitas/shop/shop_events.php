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

			Configure::write('Shop.shipping_method', 'courier');
			Configure::write('Shop.shipping_methods', array('courier', 'store_pickup'));
		}

		function onSetupThemeLayout(&$event, $data){
			if($data['params']['plugin'] == 'shop' && $data['params']['controller'] == 'carts' && $data['params']['action'] == 'index'){
				return 'checkout';
			}
		}

		function onCalculateShipping(&$event, $data){
			switch($data['method']){
				case 'pick_up':
					return 0;
					break;

				case 'courier':
					if(isset($data['total'])){
						if($data['total'] > 150){
							return 0;
						}
						else{
							return 35;
						}
					}
			}
		}
	}