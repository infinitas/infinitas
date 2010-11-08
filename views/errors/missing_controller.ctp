<div class="dashboard">
	<h1><?php __('Missing Controller'); ?></h1>
	<p class="error">
		<strong><?php __('Error'); ?>: </strong>
		<?php printf(__('%s could not be found.', true), '<em>' . $controller . '</em>'); ?>
	</p>
	<p class="error">
		<strong><?php __('Error'); ?>: </strong>
		<?php printf(__('Create the class %s below in file: %s', true), '<em>' . $controller . '</em>', APP_DIR . DS . 'controllers' . DS . Inflector::underscore($controller) . '.php'); ?>
	</p>
	<pre>
	&lt;?php
	class <?php echo $controller;?> extends AppController {

		var $name = '<?php echo $controllerName;?>';
	}
	?&gt;
	</pre>
</div>