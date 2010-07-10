<?php
	class ShopHelper extends AppHelper{
		var $helpers = array(
			'Number', 'Form', 'Html',
			'Libs.Wysiwyg', 'Libs.Image'
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

			// @todo figure which is the current special
			$special = isset($special[0]) ? $special[0] : $special;
			$newPrice = $product['price'];

			if(isset($special['discount']) && (int)$special['discount'] > 0){
				$newPrice = $product['price'] - (($product['price'] / 100) * $special['discount']);
			}

			else if(isset($special['amount']) && (int)$special['amount'] > 0){
				$newPrice = $product['price'] - $special['amount'];
			}

			if ((bool)$toCurrency){
				return $this->currency($newPrice);
			}
			return $newPrice;
		}

		function calculateMargin($cost = 0, $sell = 0, $toCurrency = true){
			if($cost == 0 || $sell == 0){
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

		function breakdown($product = null, $special = array()){
			if(!$product){
				return __('No information available');
			}

			$price = $product['price'];
			if(!empty($special)){
				$price = $this->calculateSpecial($product, $special, false);
			}


			return __('Breakdown', true).' :: '.sprintf(
				__('Retail: %s<br/>Cost: %s vs Price: %s<br/>Margin: %s vs Profit: %s', true),
				$this->currency($product['retail']),
				$this->currency($product['cost']),
				$this->currency($price),
				$this->calculateMargin($product['cost'], $price),
				$this->calculateProfit($product, $special)
			);
		}

		function getImage($data, $params = array('height' => '35px'), $link = false){
			if(isset($data['Image']['image'])){
				$img = $data['Image']['image'];
			}

			else if(isset($data['Product']['Image']['image'])){
				$img = $data['Product']['Image']['image'];
			}

			else if(isset($data['ShopCategory']['Image']['image'])){
				$img = $data['ShopCategory']['Image']['image'];
			}

			else{
				$img = Configure::read('Shop.default_image');
			}

			if($link){
				$params['url'] = '../../img/content/shop/global/'.$img;
			}

			return $this->Html->image(
				'content/shop/global/'.$img,
				(array)$params
			);
		}

		function isSpecial($product){
			if(isset($product['Special']) && !empty($product['Special'])){
				return true;
			}

			return false;
		}

		function isFeatured($product){
			if(isset($product['Spotlight']) && !empty($product['Spotlight'])){
				return true;
			}

			return false;
		}

		function overlay($type = 'isSpecial'){
			if(!isset($this->Html)){
				$this->Html = new HtmlHelper();
			}
			return '<span class="'.(string)$type.'">'.
			$this->Html->image(
				'/shop/img/'.$type.'.png',
				array(
					'width' => '32px',
					'height' => '32px',
					'alt' => __(Inflector::humanize($type), true)
				)
			).'</span>';
		}

		function cartActions($cart = null){
			if(!$cart){
				$this->errors[] = 'You must pass a record in';
				return false;
			}

			$link = '';

			if(isset($cart['Cart']['product_id'])){
				if(isset($cart['Cart']['quantity']) && $cart['Cart']['quantity'] > 1){
					$link .= $this->Html->link(
						$this->Html->image(
							$this->Image->getRelativePath('actions', 'arrow-down'),
							array(
								'alt' => __('Less', true),
								'title' => __('Less', true),
								'width' => '16px',
								'class' => 'arrow-down'
							)
						),
						array(
							'plugin' => 'shop',
							'controller' => 'carts',
							'action' => 'adjust',
							'product_id' => $cart['Cart']['product_id'],
							'quantity' => -1
						),
						array(
							'escape' => false,
						)
					);
				}

				$link .= $this->Html->link(
					$this->Html->image(
						$this->Image->getRelativePath('actions', 'arrow-up'),
						array(
							'alt' => __('More', true),
							'title' => __('More', true),
							'width' => '16px',
							'class' => 'arrow-up'
						)
					),
					array(
						'plugin' => 'shop',
						'controller' => 'carts',
						'action' => 'adjust',
						'product_id' => $cart['Cart']['product_id'],
						'quantity' => 1
					),
					array(
						'escape' => false,
					)
				);

				$link .= $this->Html->link(
					$this->Html->image(
						$this->Image->getRelativePath('actions', 'trash'),
						array(
							'alt' => __('Remove', true),
							'title' => __('Remove', true),
							'width' => '16px'
						)
					),
					array(
						'plugin' => 'shop',
						'controller' => 'carts',
						'action' => 'adjust',
						'product_id' => $cart['Cart']['product_id'],
						'quantity' => 0
					),
					array(
						'escape' => false,
					)
				);

				return $link;
			}
		}

		function wishlistActions($wishlist){
			return
				$this->Html->link(
					$this->Html->image(
						$this->Image->getRelativePath('actions', 'arrow-right'),
						array(
							'alt' => __('Add to cart', true),
							'title' => __('Add to cart', true),
							'width' => '16px'
						)
					),
					array(
						'plugin' => 'shop',
						'controller' => 'wishlists',
						'action' => 'move',
						$wishlist['Wishlist']['product_id']
					),
					array(
						'escape' => false,
					)
				).
				$this->Html->link(
					$this->Html->image(
						$this->Image->getRelativePath('actions', 'trash'),
						array(
							'alt' => __('Remove', true),
							'title' => __('Remove', true),
							'width' => '16px'
						)
					),
					array(
						'plugin' => 'shop',
						'controller' => 'wishlists',
						'action' => 'adjust',
						'product_id' => $wishlist['Wishlist']['product_id']
					),
					array(
						'escape' => false,
					)
				);
		}

		/**
		 * Order number default config
		 *
		 * @var unknown_type
		 */
		var $orderNumber = array(
			'prefix' => '#',
			'count' => 5,
			'padding' => '0',
			'suffix' => ''
		);

		/**
		 * Format the order number.
		 *
		 * @param $number int
		 */
		function orderNumber($number = null){
			if(!$number){
				return __('Error!', true);
			}
			return $this->orderNumber['prefix'].str_pad($number, $this->orderNumber['count'], $this->orderNumber['padding'], STR_PAD_LEFT).$this->orderNumber['suffix'];
		}
	}