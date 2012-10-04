<div class="user-warning success">
	<?php
		echo $this->Image->image('notifications', 'success', array('alt' => 'success'));

		if(isset($code) && $code) {
			sprintf('<b>%s:</b> %s', $code, $message);
		}

		else{
			echo $message;
		}
	?>
</div>