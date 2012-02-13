<div class="dashboard">
	<h1><?php echo __('Missing Database Table'); ?></h1>
	<p class="error">
		<strong><?php echo __('Error'); ?>: </strong>
		<?php printf(__('Database table %1$s for model %2$s was not found.'), '<em>' . $table . '</em>',  '<em>' . $model . '</em>'); ?>
	</p>
</div>