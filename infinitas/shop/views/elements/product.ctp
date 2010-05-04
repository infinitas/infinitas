<div class="product_item">
    <div class="product_image" title="Henri Van Vuren - I'm Just Me">
    	<?php
			$product['Product']['plugin'] = 'shop';
			$product['Product']['controller'] = 'product';
			$product['Product']['action'] = 'view';
			$product['Product']['Category'] = isset($product['ProductCategory'][0]) ? $product['ProductCategory'][0] : 'missing-category';
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
						'width' => '80px',
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
				echo $this->Shop->calculateSpecial($product['Product'], $product['Special']);
			?>
		</div>
	</div>
    <div class="product_add_to_cart">
    	<?php
			$product['Product']['controller'] = 'carts';
			$eventData = $this->Event->trigger('shop.slugUrl', array('type' => 'carts', 'data' => $product['Product']));

			echo $this->Html->image(
				'/shop/img/add_to_cart.png',
				array(
					'url' => current($eventData['slugUrl']),
					'title' => __('Add to cart', true),
					'alt' => __('Add to cart', true),
					'width' => '90px'
				)
			);
    	?>
    </div>

    <div class="product_add_to_wishlist">
    	<?php
			$product['Product']['controller'] = 'wishlists';
			$eventData = $this->Event->trigger('shop.slugUrl', array('type' => 'carts', 'data' => $product['Product']));

			echo $this->Html->image(
				'/shop/img/add_to_wishlist.png',
				array(
					'url' => current($eventData['slugUrl']),
					'title' => __('Add to wishlist', true),
					'alt' => __('Add to wishlist', true),
					'width' => '90px'
				)
			);
    	?>
    </div>
</div>