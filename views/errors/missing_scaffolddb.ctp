<div class="dashboard">
	<h1><?php __('Missing Database Connection'); ?></h1>
	<p class="error">
		<strong><?php __('Error'); ?>: </strong>
		<?php __('Scaffold requires a database connection'); ?>
	</p>
	<p class="error">
		<strong><?php __('Error'); ?>: </strong>
		<?php printf(__('Confirm you have created the file: %s', true), APP_DIR . DS . 'config' . DS . 'database.php'); ?>
	</p>
</div>