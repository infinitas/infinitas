<?php
	$model = isset($model) ? $model : $this->params['models'][0];
	$model = ClassRegistry::init($model)->plugin . '.' . ClassRegistry::init($model)->alias;
?>
<fieldset>
	<h1><?php echo __('Content', true); ?></h1><?php
	echo $this->Form->input('GlobalContent.id');
	echo $this->Form->hidden('GlobalContent.model', array('value' => $model));
	echo $this->Form->input('GlobalContent.title');
	echo $this->Form->input('GlobalContent.layout_id');
	echo $this->Form->input('GlobalContent.group_id', array('label' => __('Min Group', true)));
	echo $this->Cms->wysiwyg('GlobalContent.body'); ?>
</fieldset>
<fieldset>
	<h1><?php echo __('Meta Data', true); ?></h1><?php
	echo $this->Form->input('GlobalContent.meta_keywords');
	echo $this->Form->input('GlobalContent.meta_description'); ?>
</fieldset>