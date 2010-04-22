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

		function calculateMargin($cost = 0, $sell = 0, $toCurrency = true){
			if($cost = 0 || $sell =0){
				return __('N/a', true);
			}

			$margin = ($this->calculateProfit($cost, $sell, false)/$sell)*100;

			if($toCurrency){
				return $this->Number->toPercentage($margin);
			}

			return $margin;
		}

		function calculateProfit($cost = 0, $sell = 0, $toCurrency = true){
			if($cost = 0 || $sell =0){
				return __('N/a', true);
			}

			$profit = $sell - $cost;

			if($toCurrency){
				return $this->currency($profit);
			}

			return $profit;
		}

		function breakdown($product = null, $special){
			if(!$product){
				return __('No information available');
			}

			$price = $product['price'];
			if(!empty($special)){
				$price = $this->calculateSpecial($product, $special, false);
			}


			return __('Breakdown', true).' :: '.sprintf(
				__('Retail: %s</br>Cost: %s vs Price: %s</br>Margin: %s vs Profit: %s', true),
				$this->currency($product['retail']),
				$this->currency($product['cost']),
				$this->currency($price),
				$this->currency($this->calculateMargin($product['cost'], $price)),
				$this->currency($this->calculateProfit($product['cost'], $price))
			);
		}
	}