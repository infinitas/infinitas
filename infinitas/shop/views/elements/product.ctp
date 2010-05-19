<div class="product_item">
    <div class="product_image" title="<?php echo $product['Product']['name']; ?>">
    	<?php
			$product['Product']['plugin'] = 'shop';
			$product['Product']['controller'] = 'products';
			$product['Product']['action'] = 'view';
			$eventData = $this->Event->trigger('shop.slugUrl', array('type' => 'products', 'data' => $product['Product']));

			$overlay = '';
			if($this->Shop->isFeatured($product)){
				$overlay = $this->Shop->overlay('isFeatured');
			}
			if($this->Shop->isSpecial($product)){
				$overlay = $this->Shop->overlay('isSpecial');
			}
			echo $this->Html->link(
				$this->Shop->getImage(
					$product,
					array(
						'width' => '100px',
						'title' => $product['Product']['name'],
						'alt' => $product['Product']['name']
					)
				).$overlay,
				current($eventData['slugUrl']),
				array(
					'escape' => false
				)
			);
    	?>
    </div>
    <div class="product_details">
	    <div class="product">
	    	<?php
	    		echo $this->Html->link(
					$product['Product']['name'],
					current($eventData['slugUrl']),
					array(
						'escape' => false,
						'title' => $product['Product']['name'],
					)
				);
	    	?>
	    </div>
	   	<div class="price">
			<?php
				if($this->Shop->isSpecial($product)){
					echo '<span>'.$this->Shop->currency($product['Product']['price']).'</span><br/>';
				}
				$product['Special'] = isset($product['Special']) ? $product['Special'] : null;
				echo $this->Shop->calculateSpecial($product['Product'], $product['Special']);
			?>
		</div>
	</div>
    <div class="product_add_to_cart">
    	<center>
	    	<?php
				echo $this->Html->image(
					'/shop/img/add_to_cart.png',
					array(
						'url' => array(
							'plugin' => 'shop',
							'controller' => 'carts',
							'action' => 'adjust',
							'product_id' => $product['Product']['id'],
							'quantity' => 1
						),
						'title' => __('Add to cart', true),
						'alt' => __('Add to cart', true),
					)
				);
	    	?>
    	</center>
    </div>

    <div class="product_add_to_wishlist">
    	<center>
	    	<?php
				echo $this->Html->image(
					'/shop/img/add_to_wishlist.png',
					array(
						'url' => array(
							'plugin' => 'shop',
							'controller' => 'wishlists',
							'action' => 'adjust',
							'product_id' => $product['Product']['id'],
							'quantity' => 1
						),
						'title' => __('Add to wishlist', true),
						'alt' => __('Add to wishlist', true),
					)
				);
	    	?>
    	</center>
    </div>
</div>