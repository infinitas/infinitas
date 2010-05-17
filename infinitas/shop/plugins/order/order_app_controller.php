<?php
	class OrderAppController extends AppController {
		var $helpers = array(
			'Shop.Shop',
			'Filter.Filter'
		);

		var $components = array(
			'Shop.Shop'
		);

		function beforeRender(){
			parent::beforeRender();
		}

		function beforeFilter(){
			parent::beforeFilter();
		}
	}