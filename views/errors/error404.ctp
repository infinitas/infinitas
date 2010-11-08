<div class="dashboard">
	<h1><?php echo $name; ?></h1>
	<p class="error">
		<strong><?php __('Error'); ?>: </strong>
		<?php printf(__('The requested address <strong>\'%s\'</strong> was not found on this server.', true), htmlspecialchars($message)); ?>
	</p>
</div>