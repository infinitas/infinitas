<?php
	if(!isset($bestSellers) || empty($bestSellers)){
		$bestSellers = ClassRegistry::init('Shop.Product')->getBestSellers();
	}
?>
<div class="header">
	<h2><?php echo __('Best Sellers', true); ?></h2>
</div>
        <?php
			foreach($bestSellers as $product){
				echo $this->element(
					'product_side',
					array(
						'plugin' => 'shop',
						'product' => $product
					)
				);
			}
?>