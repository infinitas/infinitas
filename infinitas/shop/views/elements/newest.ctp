<div class="centerModule">
	<h2 class="fade"><?php __('Whats new'); ?></h2>
	<?php
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