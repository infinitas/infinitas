<?php
	$model = isset($model) ? $model : $this->params['models'][0];
	$model = ClassRegistry::init($model)->plugin . '.' . ClassRegistry::init($model)->alias;

	$groups = array(0 => __('Public', true)) + ClassRegistry::init($model)->GlobalContent->Group->find('list');

	$fields =
		$this->Form->input('GlobalContent.id') .
		$this->Form->hidden('GlobalContent.model', array('value' => $model)) .
		$this->Form->input('GlobalContent.title');

	if(!isset($intro) || $intro !== false) {
		$fields .= $this->Infinitas->wysiwyg('GlobalContent.introduction');
	}

	$fields .= $this->Infinitas->wysiwyg('GlobalContent.body') .
		'<div class="input smaller required">' .
			$this->Form->input('GlobalContent.layout_id', array('options' => $contentLayouts, 'empty' => Configure::read('Website.empty_select'), 'class' => 'smaller')) .
			$this->Form->input('GlobalContent.group_id', array('options' => $contentGroups, 'label' => __('Min Group', true), 'empty' => __d('contents', 'Public', true))) .
			$this->element('category_list', array('plugin' => 'contents')) .
		'</div>' .
		$this->Form->input('GlobalContent.tags');


	$template = '%s';
	if(!empty($metaFieldSet) && $metaFieldSet === true) {
		$template = sprintf(
			'<fieldset><h1>%s</h1>%%s</fieldset>',
			__d('contents', 'Content', true)
		);
	}

	echo sprintf($template, $fields);
