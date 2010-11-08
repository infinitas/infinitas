<div class="dashboard">
	<h1><?php __('Missing Database Connection'); ?></h1>
	<p class="error">
		<strong><?php __('Error'); ?>: </strong>
		<?php printf(__('%s requires a database connection', true), $model); ?>
	</p>
	<p class="error">
		<strong><?php __('Error'); ?>: </strong>
		<?php printf(__('Confirm you have created the file : %s.', true), APP_DIR . DS . 'config' . DS . 'database.php'); ?>
	</p>
</div>