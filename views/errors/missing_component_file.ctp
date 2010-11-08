<div class="dashboard">
	<h1><?php __('Missing Component File'); ?></h1>
	<p class="error">
		<strong><?php __('Error'); ?>: </strong>
		<?php __('The component file was not found.'); ?>
	</p>
	<p class="error">
		<strong><?php __('Error'); ?>: </strong>
		<?php printf(__('Create the class %s in file: %s', true), '<em>' . $component . 'Component</em>', APP_DIR . DS . 'controllers' . DS . 'components' . DS . $file); ?>
	</p>
	<pre>
	&lt;?php
	class <?php echo $component;?>Component extends Object {<br />

	}
	?&gt;
	</pre>
</div>