<?php
	class ShopAppController extends AppController {
		var $helpers = array(
			'Shop.Shop'
		);

		function beforeFilter(){
			parent::beforeFilter();

			$this->addCss(
				array(
					'/shop/css/shop'
				)
			);
		}
	}