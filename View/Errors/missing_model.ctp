<div class="dashboard">
	<h1><?php echo __('Missing Model'); ?></h1>
	<p class="error">
		<strong><?php echo __('Error'); ?>: </strong>
		<?php printf(__('<em>%s</em> could not be found.'), $model); ?>
	</p>
	<p class="error">
		<strong><?php echo __('Error'); ?>: </strong>
		<?php printf(__('Create the class %s in file: %s'), '<em>' . $model . '</em>', APP_DIR . DS . 'models' . DS . Inflector::underscore($model) . '.php'); ?>
	</p>
	<pre>
	&lt;?php
	class <?php echo $model;?> extends AppModel {

		var $name = '<?php echo $model;?>';

	}
	?&gt;
	</pre>
</div>