<?php
	echo $this->element('specials_slide', array('plugin' => 'shop', 'specials' => $specials));
	echo $this->element('most_viewed_products', array('plugin' => 'shop', 'mostViewedProducts' => $mostViewedProducts));
?>