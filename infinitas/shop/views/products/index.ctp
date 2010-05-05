<h2 class="fade"><?php __('On Special'); ?></h2>
	<?php
		foreach($products as $product){
			echo $this->element('product', array('plugin' => 'shop', 'product' => $product));
		}
		echo $this->element('pagination/navigation');
		if(!empty($spotlights)){
			?>
				<div><?php
					echo $this->element('spotlights', array('plugin' => 'shop', 'spotlights' => $spotlights));?>
				</div>
			<?php
		}
		if(!empty($specials)){
			?>
				<div><?php
					echo $this->element('specials', array('plugin' => 'shop', 'specials' => $specials));?>
				</div>
			<?php
		}