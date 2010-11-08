<div class="dashboard">
	<h1><?php __('Missing Model'); ?></h1>
	<p class="error">
		<strong><?php __('Error'); ?>: </strong>
		<?php printf(__('<em>%s</em> could not be found.', true), $model); ?>
	</p>
	<p class="error">
		<strong><?php __('Error'); ?>: </strong>
		<?php printf(__('Create the class %s in file: %s', true), '<em>' . $model . '</em>', APP_DIR . DS . 'models' . DS . Inflector::underscore($model) . '.php'); ?>
	</p>
	<pre>
	&lt;?php
	class <?php echo $model;?> extends AppModel {

		var $name = '<?php echo $model;?>';

	}
	?&gt;
	</pre>
</div>