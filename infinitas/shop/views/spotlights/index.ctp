<?php
	echo $this->element('spotlights', array('plugin' => 'shop', 'spotlights' => $spotlights));
	echo $this->element('pagination/navigation');
	if(!empty($specials)){
		?>
			<div><?php
				echo $this->element('specials', array('plugin' => 'shop', 'specials' => $specials));?>
			</div>
		<?php
	}