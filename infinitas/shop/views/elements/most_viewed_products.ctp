<div class="mostViewedCenterModule">
	<h2 class="fade"><?php __('Whats Popular'); ?></h2>
	<?php
		foreach((array)$mostViewedProducts as $mostViewed){
			echo $this->element('product', array('plugin' => 'shop', 'product' => $mostViewed));
		}
	?>
    <?php
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