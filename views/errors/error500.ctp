<div class="dashboard">
	<h1><?php echo $name; ?></h1>
	<p class="error">
		<strong><?php __('Error'); ?>: </strong>
		<?php printf(__('An Internal Error Has Occurred.', true), "<strong>'{$message}'</strong>"); ?>
	</p>
</div>