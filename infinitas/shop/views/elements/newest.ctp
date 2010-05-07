<div class="centerModule">
	<h2 class="fade"><?php __('Whats new'); ?></h2>
	<?php
		if(!isset($newest)){
			$newest = Cache::read('products/newest', 'shop');

			if(empty($newest)){
				$newest = ClassRegistry::init('Shop.Product')->getNewest();
			}
		}

		foreach((array)$newest as $new){
			echo $this->element('product', array('plugin' => 'shop', 'product' => $new));
		}

	    if($this->params['controller'] != 'specials'){
	    	echo $this->Html->link(
	    		'('.__('See all', true).')',
	    		array(
	    			'plugin' => 'shop',
	    			'controller' => 'products',
	    			'action' => 'index',
	    			'sort' => 'created',
	    			'order' => 'DESC'
	    		),
	    		array(
	    			'class' => 'moreLink'
	    		)
	    	);
	    }
    ?>
</div>