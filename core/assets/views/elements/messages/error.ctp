<div class="user-warning error">
	<?php
		echo $this->Html->image(
			$this->Image->getRelativePath('notifications', 'stop'),
			array('alt' => 'error')
		);

		if(isset($code) && $code){
			sprintf('<b>%s:</b> %s', $code, $message);
		}

		else{
			echo $message;
		}
	?>
</div>