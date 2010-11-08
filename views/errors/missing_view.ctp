<div class="dashboard">
	<h1><?php __('Missing View'); ?></h1>
	<p class="error">
		<strong><?php __('Error'); ?>: </strong>
		<?php printf(__('The view for %1$s%2$s was not found.', true), '<em>' . $controller . 'Controller::</em>', '<em>' . $action . '()</em>'); ?>
	</p>
	<p class="error">
		<strong><?php __('Error'); ?>: </strong>
		<?php printf(__('Confirm you have created the file: %s', true), $file); ?>
	</p>
</div>