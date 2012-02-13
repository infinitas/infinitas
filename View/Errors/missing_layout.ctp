<div class="dashboard">
	<h1><?php echo __('Missing Layout'); ?></h1>
	<p class="error">
		<strong><?php echo __('Error'); ?>: </strong>
		<?php printf(__('The layout file %s can not be found or does not exist.'), '<em>' . $file . '</em>'); ?>
	</p>
	<p class="error">
		<strong><?php echo __('Error'); ?>: </strong>
		<?php printf(__('Confirm you have created the file: %s'), '<em>' . $file . '</em>'); ?>
	</p>
</div>