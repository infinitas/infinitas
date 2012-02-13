<div class="dashboard">
	<h1><?php echo __('Missing Component File'); ?></h1>
	<p class="error">
		<strong><?php echo __('Error'); ?>: </strong>
		<?php echo __('The component file was not found.'); ?>
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