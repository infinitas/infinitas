<div class="dashboard">
	<h1><?php __('Missing Behavior Class'); ?></h1>
	<p class="error">
		<strong><?php __('Error'); ?>: </strong>
		<?php printf(__('The behavior class <em>%s</em> can not be found or does not exist.', true), $behaviorClass); ?>
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