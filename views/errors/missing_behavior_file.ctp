<div class="dashboard">
	<h1><?php __('Missing Behavior File'); ?></h1>
	<p class="error">
		<strong><?php __('Error'); ?>: </strong>
		<?php printf(__('The Behavior file %s can not be found or does not exist.', true), APP_DIR . DS . 'models' . DS . 'behaviors' . DS . $file); ?>
	</p>
	<p  class="error">
		<strong><?php __('Error'); ?>: </strong>
		<?php printf(__('Create the class below in file: %s', true), APP_DIR . DS . 'models' . DS . 'behaviors' . DS . $file); ?>
	</p>
	<pre>
	&lt;?php
	class <?php echo $behaviorClass;?> extends ModelBehavior {

	}
	?&gt;
	</pre>
</div>