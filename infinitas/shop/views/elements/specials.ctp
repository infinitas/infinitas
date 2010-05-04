<div class="centerModule">
	<h2 class="fade"><?php __('On Special'); ?></h2>
	<?php
		foreach((array)$specials as $special){
			echo $this->element('product', array('plugin' => 'shop', 'product' => $special));
		}
	?>
    <?php
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
    	)
    ?>
</div>