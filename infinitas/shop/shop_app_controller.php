<?php
	class ShopAppController extends AppController {
		var $helpers = array(
			'Shop.Shop'
		);

		var $components = array(
			'Shop.Shop'
		);

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