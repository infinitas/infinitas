<div class="dashboard">
	<h1><?php echo __('Missing Database Connection'); ?></h1>
	<p class="error">
		<strong><?php echo __('Error'); ?>: </strong>
		<?php printf(__('%s requires a database connection'), $model); ?>
	</p>
	<p class="error">
		<strong><?php echo __('Error'); ?>: </strong>
		<?php printf(__('Confirm you have created the file : %s.'), APP_DIR . DS . 'config' . DS . 'database.php'); ?>
	</p>
</div>