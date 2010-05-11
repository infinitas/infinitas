<h2 class="fade"><?php __('All Products'); ?></h2>
	<div class="list">
		<?php
			foreach($products as $product){
				echo $this->element('product', array('plugin' => 'shop', 'product' => $product));
			}
			echo $this->element('pagination/navigation');
		?>
	</div>
