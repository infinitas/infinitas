<div class="centerModule">
	<h2 class="fade"><?php __('On Special'); ?></h2>
	<?php
		if(!isset($specials)){
			$specials = Cache::read('specials', 'shop');

			if(empty($specials)){
				$specials = ClassRegistry::init('Shop.Special')->getSpecials();
			}
		}

		foreach((array)$specials as $special){
			echo $this->element('product', array('plugin' => 'shop', 'product' => $special));
		}

	    if($this->params['controller'] != 'specials'){
	    	echo $this->Html->link(
	    		'('.__('See all', true).')',
	    		array(
	    			'plugin' => 'shop',
	    			'controller' => 'specials',
	    			'action' => 'index'
	    		),
	    		array(
	    			'class' => 'moreLink'
	    		)
	    	);
	    }
    ?>
</div>