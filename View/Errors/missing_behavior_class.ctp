<div class="dashboard">
	<h1><?php echo __('Missing Behavior Class'); ?></h1>
	<p class="error">
		<strong><?php echo __('Error'); ?>: </strong>
		<?php printf(__('The behavior class <em>%s</em> can not be found or does not exist.'), $behaviorClass); ?>
	</p>
	<p  class="error">
		<strong><?php echo __('Error'); ?>: </strong>
		<?php printf(__('Create the class below in file: %s'), APP_DIR . DS . 'models' . DS . 'behaviors' . DS . $file); ?>
	</p>
	<pre>
	&lt;?php
	class <?php echo $behaviorClass;?> extends ModelBehavior {

	}
	?&gt;
	</pre>
</div>