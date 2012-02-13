<div class="dashboard">
	<h1><?php echo __('Missing Component Class'); ?></h1>
	<p class="error">
		<strong><?php echo __('Error'); ?>: </strong>
		<?php printf(__('Component class %1$s in %2$s was not found.'), '<em>' . $component . 'Component</em>', '<em>' . $controller . 'Controller</em>'); ?>
	</p>
	<p class="error">
		<strong><?php echo __('Error'); ?>: </strong>
		<?php printf(__('Create the class %s in file: %s'), '<em>' . $component . 'Component</em>', APP_DIR . DS . 'controllers' . DS . 'components' . DS . $file); ?>
	</p>
	<pre>
	&lt;?php
	class <?php echo $component;?>Component extends Object {<br />

	}
	?&gt;
	</pre>
</div>