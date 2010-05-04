<h2><?php __('Whats Popular'); ?></h2>
<?php
	foreach((array)$mostViewedProducts as $mostViewed){
		echo $this->element('product', array('plugin' => 'shop', 'product' => $mostViewed));
	}