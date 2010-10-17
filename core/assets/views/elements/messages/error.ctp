<div class="user-warning error">
	<?php
		echo $this->Html->image('error.png', array('alt' => 'error'));
		if(isset($code) && $code){
			sprintf('<b>%s:</b> %s', $code, $message);
		}
		else{
			echo $message;
		}
	?>
</div>