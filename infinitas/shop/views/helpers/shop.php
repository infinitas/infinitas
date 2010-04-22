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

		function calculateSpecial($product = array(), $special = null, $toCurrency = true){
			if(!$special){
				if(!isset($product['Special']) || empty($product['Special'])){
					if ($toCurrency){
						return $this->currency($product['price']);
					}
					return $product['price'];
				}
				$special = $product['Special'];
			}

			if($special['discount'] > 0){
				$newPrice = $product['price'] - (($product['price'] / 100) * $special['discount']);
			}

			if ($toCurrency){
				return $this->currency($newPrice);
			}
			return $newPrice;
		}
	}