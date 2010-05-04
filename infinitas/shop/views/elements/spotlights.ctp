<div class="centerModule">
	<h2 class="fade"><?php __('In the spotlight'); ?></h2>
	<?php
		foreach((array)$spotlights as $spotlight){
			echo $this->element('product', array('plugin' => 'shop', 'product' => $spotlight));
		}

	    if($this->params['controller'] != 'spotlights'){
	    	echo $this->Html->link(
	    		'('.__('See all', true).')',
	    		array(
	    			'plugin' => 'shop',
	    			'controller' => 'spotlights',
	    			'action' => 'index'
	    		),
	    		array(
	    			'class' => 'moreLink'
	    		)
	    	);
	    }
    ?>
</div>