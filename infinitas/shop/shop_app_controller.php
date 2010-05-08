<?php
	class ShopAppController extends AppController {
		var $helpers = array(
			'Shop.Shop'
		);

		var $components = array(
			'Shop.Shop'
		);

		function beforeRender(){
			parent::beforeRender();
			$user_id = $this->Session->read('Auth.User.id');

			if(isset($this->Cart)){
				$usersCart = $this->Cart->getCartData($user_id);
			}
			else{
				$usersCart = ClassRegistry::init('Shop.Cart')->getCartData($user_id);
			}

			$this->set(compact('usersCart'));
		}

		function beforeFilter(){
			parent::beforeFilter();

			$this->addCss(
				array(
					'/shop/css/shop'
				)
			);

			$this->addJs(
				array(
					'/shop/js/shop'
				)
			);

			if(!$this->Session->read('Shop.shipping_method')){
				$this->Session->write('Shop.shipping_method', Configure::read('Shop.shipping_method'));
			}
		}
	}