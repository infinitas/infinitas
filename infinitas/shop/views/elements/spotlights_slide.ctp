<div class="slidesContainerHolder">
	<div class="padding"> </div>
	<div class="slidesContainer">
		<?php
			foreach((array)$spotlights as $spotlight){
				$spotlight['Product']['plugin'] = 'shop';
				$spotlight['Product']['controller'] = 'product';
				$spotlight['Product']['action'] = 'view';
				?>
		    			<div class="data">
		    				<?php
								$eventData = $this->Event->trigger('shop.slugUrl', array('type' => 'products', 'data' => $spotlight['Product']));
		    				?>
		    				<h2 class="fade"><?php echo $this->Html->link($spotlight['Product']['name'], current($eventData['slugUrl']))?></h2>
		    				<p><?php echo $this->Text->truncate(strip_tags($spotlight['Product']['description']), 300); ?></p>
		    			<?php echo $this->Shop->getImage($spotlight, array('width' => '20%'));?>
		    			</div>

				<?php
			}
		?>
	</div>
</div>
<div class="slideNavigation"></div>
<?php
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
?>