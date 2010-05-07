<h2 class="fade"><?php __('On Special'); ?></h2>
	<?php
		foreach($products as $product){
			echo $this->element('product', array('plugin' => 'shop', 'product' => $product));
		}
		echo $this->element('pagination/navigation');