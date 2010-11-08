<div class="dashboard">
	<h1><?php printf(__('Missing Method in %s', true), $controller); ?></h1>
	<p class="error">
		<strong><?php __('Error'); ?>: </strong>
		<?php printf(__('The action %1$s is not defined in controller %2$s', true), '<em>' . $action . '</em>', '<em>' . $controller . '</em>'); ?>
	</p>
	<p class="error">
		<strong><?php __('Error'); ?>: </strong>
		<?php printf(__('Create %1$s%2$s in file: %3$s.', true), '<em>' . $controller . '::</em>', '<em>' . $action . '()</em>', APP_DIR . DS . 'controllers' . DS . Inflector::underscore($controller) . '.php'); ?>
	</p>
	<pre>
	&lt;?php
	class <?php echo $controller;?> extends AppController {

		var $name = '<?php echo $controllerName;?>';

	<strong>
		function <?php echo $action;?>() {

		}
	</strong>
	}
	?&gt;
	</pre>
</div>