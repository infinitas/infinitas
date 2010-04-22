<?php
	class ShopHelper extends AppHelper{
		var $helpers = array(
			'Number', 'Form',
			'Libs.Wysiwyg',
		);

		function currency($amount = 0, $currency = null){
			if(!$currency){
				$currency = Configure::read('Currency.unit');
			}

			return $this->Number->currency($amount, $currency);
		}
	}