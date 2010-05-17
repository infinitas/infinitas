<div>
	<?php
		$userId = $this->Session->read('Auth.User.id');
		if(!isset($usersWishlist)){
			$usersWishlist = Cache::read(cacheName('wishlist', $userId));

			if($usersWishlist === false){
				$usersWishlist = ClassRegistry::init('Shop.Wishlist')->getWishlistData($userId);
			}
		}

		if(empty($usersWishlist)){
			echo __('Your wishlist is empty', true);
		}
		else{
			?>
				<table>
					<tr>
						<th><?php echo __('Product' ,true) ?></th>
						<th style="width:50px;"><?php echo __('Price' ,true) ?></th>
					</tr>
					<?php
						foreach((array)$usersWishlist as $wishlistItem){
							$wishlistItem['Product']['plugin'] = 'shop';
							$wishlistItem['Product']['controller'] = 'products';
							$wishlistItem['Product']['action'] = 'view';
							$eventData = $this->Event->trigger('shop.slugUrl', array('type' => 'products', 'data' => $wishlistItem['Product']));

							$productLink = $this->Html->link(
								$wishlistItem['Wishlist']['name'],
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
						<td colspan="2">
							<?php
								if($this->Session->read('Auth.User.id') > 0){
									echo $this->Html->link(
										__('Manage', true),
										array(
											'plugin' => 'shop',
											'controller' => 'wishlists',
											'action' => 'index'
										)
									);
								}
								else{
									echo __('You must be logged in to manage your wishlist', true);
								}
							?>&nbsp;
						</td>
					</tr>
				</table>
			<?php
		}
	?>
</div>