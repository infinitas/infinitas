<?php
	if(!isset($usersWishlist)){
		$usersWishlist = Cache::read('cart', 'shop');

		if(empty($usersWishlist)){
			$usersWishlist = ClassRegistry::init('Shop.Wishlist')->getWishlistData();
		}
	}

	if(empty($usersWishlist)){
		echo __('Your wishlist is empty', true);
		return;
	}

	if(!isset($this->Shop)){
		App::import('Helper', 'Shop.Shop');
	}
?>
<table>
	<tr>
		<th><?php echo __('Product' ,true) ?></th>
		<th style="width:50px;"><?php echo __('price' ,true) ?></th>
	</tr>
	<?php
		foreach((array)$usersWishlist as $wishlistItem){
			$wishlistItem['Product']['plugin'] = 'shop';
			$wishlistItem['Product']['controller'] = 'products';
			$wishlistItem['Product']['action'] = 'view';
			$eventData = $this->Event->trigger('shop.slugUrl', array('type' => 'products', 'data' => $wishlistItem['Product']));

			$productLink = $this->Html->link(
				$wishlistItem['Cart']['name'],
				current($eventData['slugUrl'])
			);
			?>
				<tr>
					<td><?php echo $productLink; ?>&nbsp;</td>
					<td><?php echo $this->Shop->currency($wishlistItem['Wishlist']['price']); ?>&nbsp;</td>
				</tr>
			<?php
		}
	?>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
</table>