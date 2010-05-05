<?php
	echo $this->element('specials_slide', array('plugin' => 'shop', 'specials' => $specials));
	echo $this->element('most_viewed_products', array('plugin' => 'shop', 'mostViewedProducts' => $mostViewedProducts));
	echo $this->element('specials', array('plugin' => 'shop', 'specials' => $specials));
	echo $this->element('spotlights', array('plugin' => 'shop', 'spotlights' => $spotlights));
	echo $this->element('newest', array('plugin' => 'shop', 'newest' => $newest));
?>