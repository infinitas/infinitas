<div class="dashboard">
	<h1><?php __('Missing Database Table'); ?></h1>
	<p class="error">
		<strong><?php __('Error'); ?>: </strong>
		<?php printf(__('Database table %1$s for model %2$s was not found.', true), '<em>' . $table . '</em>',  '<em>' . $model . '</em>'); ?>
	</p>
</div>