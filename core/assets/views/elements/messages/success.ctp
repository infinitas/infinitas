<div class="user-warning success">
	<?php
		echo $this->Html->image('success.png', array('alt' => 'success'));
		if(isset($code) && $code){
			sprintf('<b>%s:</b> %s', $code, $message);
		}
		else{
			echo $message;
		}
	?>
</div>