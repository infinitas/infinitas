<div class="dashboard">
	<h1><?php echo $name; ?></h1>
	<p class="error">
		<strong><?php echo __('Error'); ?>: </strong>
		<?php printf(__('An Internal Error Has Occurred.'), "<strong>'{$message}'</strong>"); ?>
	</p>
</div>