<?php
	final class ShopEvents extends AppEvents{
		public function onSetupConfig(){
			return Configure::load('shop.config');
		}
		
		public function onSetupCache(){
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

		public function onSlugUrl(&$event, $data){
			switch($data['type']){
				case 'products':
					return array(
						'plugin' => 'shop',
						'controller' => 'products',
						'action' => $data['data']['action'],
						'id' => $data['data']['id'],
						'slug' => $data['data']['slug']
					);
					break;

				case 'categories':
					return array(
						'plugin' => 'shop',
						'controller' => 'shop_categories',
						'action' => $data['data']['action'],
						'id' => $data['data']['id'],
						'slug' => $data['data']['slug']
					);
					break;
			} // switch
		}

		public function onSetupConfigEnd(&$event){
			Configure::load('Shop.config');
		}

		public function onSetupThemeLayout(&$event, $data){
			if($data['params']['plugin'] == 'shop' && $data['params']['controller'] == 'carts' && $data['params']['action'] == 'index'){
				//return 'checkout';
			}
		}

		public function onSetupTabs(&$event, $data){
			echo 'yey: shop event';
			exit;
		}

		public function onUserLogin(&$event, $data){
			App::import('CakeSession');
			$this->Session = new CakeSession();

			if(ClassRegistry::init('Shop.Cart')->moveSessionToDb($this->Session->read('Cart.TempCart'), $data) === true){
				$this->Session->delete('Cart');
			}

			if(ClassRegistry::init('Shop.Wishlist')->moveSessionToDb($this->Session->read('Wishlist.TempWishlist'), $data) === true){
				$this->Session->delete('Wishlist');
			}
		}

		public function onRequireHelpersToLoad(){
			return 'Shop.Shop';
		}

		public function onRequireComponentsToLoad(){
			return 'Libs.Voucher';
		}
	}