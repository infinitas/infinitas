<div class="dashboard">
	<h1><?php echo $name; ?></h1>
	<p class="error">
		<strong><?php echo __('Error'); ?>: </strong>
		<?php printf(__('The requested address <strong>\'%s\'</strong> was not found on this server.'), htmlspecialchars($message)); ?>
	</p>
</div>