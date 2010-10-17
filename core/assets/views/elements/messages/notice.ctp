<div class="user-warning notice">
	<?php
		echo $this->Html->image('notice.png', array('alt' => 'notice'));
		if(isset($code) && $code){
			sprintf('<b>%s:</b> %s', $code, $message);
		}
		else{
			echo $message;
		}
	?>
</div>