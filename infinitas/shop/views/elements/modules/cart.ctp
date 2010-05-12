<?php
	if(!isset($usersCart)){
		$usersCart = Cache::read('cart', 'shop');

		if($usersCart === false){
			$usersCart = ClassRegistry::init('Shop.Cart')->getCartData();
		}
	}

	if(empty($usersCart)){
		echo __('Your cart is empty', true);
		return;
	}
?>
<table>
	<tr>
		<th><?php echo __('Product' ,true) ?></th>
		<th style="width:50px;"><?php echo __('price' ,true) ?></th>
	</tr>
	<?php
		foreach((array)$usersCart as $cartItem){
			$cartItem['Product']['plugin'] = 'shop';
			$cartItem['Product']['controller'] = 'products';
			$cartItem['Product']['action'] = 'view';
			$eventData = $this->Event->trigger('shop.slugUrl', array('type' => 'products', 'data' => $cartItem['Product']));

			$productLink = $this->Html->link(
				$cartItem['Cart']['name'],
				current($eventData['slugUrl'])
			);
			?>
				<tr>
					<td><?php echo $cartItem['Cart']['quantity'], 'x&nbsp;', $productLink; ?>&nbsp;</td>
					<td><?php echo $this->Shop->currency($cartItem['Cart']['price']); ?>&nbsp;</td>
				</tr>
			<?php
		}
	?>
	<tr>
		<td><?php echo __('Sub total', true)?></td>
		<td><?php echo $this->Shop->currency(array_sum(Set::extract('/Cart/sub_total', $usersCart))); ?>&nbsp;</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>
			<?php
				echo $this->Html->link(
					__('Checkout', true),
					array(
						'plugin' => 'shop',
						'controller' => 'carts',
						'action' => 'index'
					)
				);
			?>&nbsp;
		</td>
	</tr>
</table>