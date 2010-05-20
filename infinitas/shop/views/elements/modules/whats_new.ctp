<?php
	if(!isset($whatsNew) || empty($whatsNew)){
		$whatsNew = ClassRegistry::init('Shop.Product')->getNewest();
	}
?>
<div class="header">
	<h2><?php echo __('Whats New', true); ?></h2>
</div>
        <?php
			foreach($whatsNew as $product){
				echo $this->element(
					'product_side',
					array(
						'plugin' => 'shop',
						'product' => $product
					)
				);
			}
?>