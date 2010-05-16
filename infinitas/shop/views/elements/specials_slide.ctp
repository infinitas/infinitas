<div id="slideshow">
	<div id="slidesContainer"><?php
		foreach((array)$specials as $special){
			$special['Product']['plugin'] = 'shop';
			$special['Product']['controller'] = 'product';
			$special['Product']['action'] = 'view';
			?>
	    		<div class="slide">
	    			<div class="data">
	    				<?php
							$eventData = $this->Event->trigger('shop.slugUrl', array('type' => 'products', 'data' => $special['Product']));
	    				?>
	    				<h2><?php echo $this->Html->link($special['Product']['name'], current($eventData['slugUrl']))?></h2>
	    				<p><?php echo $this->Text->truncate(strip_tags($special['Product']['description']), 300); ?></p>
	    			</div>
	    			<?php echo $this->Shop->getImage($special, array('width' => '200px'));?>
		    	</div>
			<?php
		} ?>
    </div>
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
    	);
    ?>
</div>