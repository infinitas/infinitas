<div class="centerModule">
	<h2 class="fade"><?php __('Whats Popular'); ?></h2>
	<?php
		if(!isset($mostViewedProducts)){
			$mostViewedProducts = Cache::read('products/most_viewed', 'shop');

			if(empty($mostViewedProducts)){
				$mostViewedProducts = ClassRegistry::init('Shop.Product')->getMostViewed();
			}
		}

		foreach((array)$mostViewedProducts as $mostViewed){
			echo $this->element('product', array('plugin' => 'shop', 'product' => $mostViewed));
		}

    	echo $this->Html->link(
    		'('.__('See all', true).')',
    		array(
    			'plugin' => 'shop',
    			'controller' => 'products',
    			'action' => 'index',
    			'sort' => 'views',
    			'direction' => 'DESC'
    		),
    		array(
    			'class' => 'moreLink'
    		)
    	)
    ?>
</div>