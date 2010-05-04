<?php
	echo $this->element('specials', array('plugin' => 'shop', 'specials' => $specials));
	echo $this->element('pagination/navigation');
	if(!empty($spotlights)){
		?>
			<div><?php
				echo $this->element('spotlights', array('plugin' => 'shop', 'spotlights' => $spotlights));?>
			</div>
		<?php
	}