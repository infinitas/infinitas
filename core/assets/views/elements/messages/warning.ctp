<div class="user-warning notice">
	<?php
		echo $this->Html->image('warning.png', array('alt' => 'warning'));
		if(isset($code) && $code){
			sprintf('<b>%s:</b> %s', $code, $message);
		}
		else{
			echo $message;
		}
	?>
</div>