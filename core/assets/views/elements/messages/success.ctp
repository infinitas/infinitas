<div class="user-warning success">
	<?php
		echo $this->Html->image(
			$this->Image->getRelativePath('notifications', 'success'),
			array('alt' => 'success')
		);

		if(isset($code) && $code){
			sprintf('<b>%s:</b> %s', $code, $message);
		}

		else{
			echo $message;
		}
	?>
</div>