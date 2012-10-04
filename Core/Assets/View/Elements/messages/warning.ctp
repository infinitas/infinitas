<div class="user-warning warning">
	<?php
		echo $this->Image->image('notifications', 'warning', array('alt' => 'warning'));

		if(isset($code) && $code) {
			sprintf('<b>%s:</b> %s', $code, $message);
		}

		else{
			echo $message;
		}
	?>
</div>