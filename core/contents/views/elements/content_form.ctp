<?php
	$model = isset($model) ? $model : $this->params['models'][0];
	$model = ClassRegistry::init($model)->plugin . '.' . ClassRegistry::init($model)->alias;

	$groups = array(0 => __('Public', true)) + ClassRegistry::init($model)->GlobalContent->Group->find('list');
?>
<fieldset>
	<h1><?php echo __('Content', true); ?></h1><?php
	echo $this->Form->input('GlobalContent.id');
	echo $this->Form->hidden('GlobalContent.model', array('value' => $model));
	echo $this->Form->input('GlobalContent.title');
	echo $this->Form->input('GlobalContent.layout_id', array('options' => $contentLayouts, 'empty' => Configure::read('Website.empty_select')));
	echo $this->Form->input('GlobalContent.group_id', array('options' => $contentGroups, 'label' => __('Min Group', true), 'empty' => __d('contents', 'Public', true)));
	echo $this->element('category_list', array('plugin' => 'contents'));
	echo $this->Infinitas->wysiwyg('GlobalContent.body'); ?>
</fieldset>
<fieldset>
	<h1><?php echo __('Meta Data', true); ?></h1><?php
	echo $this->Form->input('GlobalContent.meta_keywords');
	echo $this->Form->input('GlobalContent.meta_description'); ?>
</fieldset>