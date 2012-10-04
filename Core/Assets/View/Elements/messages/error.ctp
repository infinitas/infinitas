<div class="user-warning error">
	<?php
		echo $this->Image->image('notifications', 'stop', array('alt' => 'error'));

		if(isset($code) && $code) {
			sprintf('<b>%s:</b> %s', $code, $message);
		}

		else{
			echo $message;
		}
	?>
</div>