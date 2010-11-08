<div class="dashboard">
	<h1><?php __('Missing Helper File'); ?></h1>
	<p class="error">
		<strong><?php __('Error'); ?>: </strong>
		<?php printf(__('The helper file %s can not be found or does not exist.', true), APP_DIR . DS . 'views' . DS . 'helpers' . DS . $file); ?>
	</p>
	<p  class="error">
		<strong><?php __('Error'); ?>: </strong>
		<?php printf(__('Create the class below in file: %s', true), APP_DIR . DS . 'views' . DS . 'helpers' . DS . $file); ?>
	</p>
	<pre>
	&lt;?php
	class <?php echo $helperClass;?> extends AppHelper {

	}
	?&gt;
	</pre>
</div>