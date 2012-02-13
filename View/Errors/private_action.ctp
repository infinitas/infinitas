<div class="dashboard">
	<h1><?php printf(__('Private Method in %s'), $controller); ?></h1>
	<p class="error">
		<strong><?php echo __('Error'); ?>: </strong>
		<?php printf(__('%s%s cannot be accessed directly.'), '<em>' . $controller . '::</em>', '<em>' . $action . '()</em>'); ?>
	</p>
</div>