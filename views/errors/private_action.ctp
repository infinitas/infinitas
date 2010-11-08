<div class="dashboard">
	<h1><?php printf(__('Private Method in %s', true), $controller); ?></h1>
	<p class="error">
		<strong><?php __('Error'); ?>: </strong>
		<?php printf(__('%s%s cannot be accessed directly.', true), '<em>' . $controller . '::</em>', '<em>' . $action . '()</em>'); ?>
	</p>
</div>