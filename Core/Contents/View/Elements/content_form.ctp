<?php
	if(empty($model)) {
		$model = implode('.', $this->request->models[current(array_keys($this->request->models))]);
	}
	
	else if(!strstr($model, '.')) {
		$model = ClassRegistry::init($model)->plugin . '.' . ClassRegistry::init($model)->alias;
	}

	$groups = array(0 => __('Public')) + ClassRegistry::init($model)->GlobalContent->Group->find('list');

	$fields =
		$this->Form->input('GlobalContent.id') .
		$this->Form->hidden('GlobalContent.model', array('value' => $model)) .
		$this->Form->input('GlobalContent.title') .
		$this->Form->input('GlobalContent.slug', array('label' => __d('contents', 'Url Slug')));

	if(!isset($intro) || $intro !== false) {
		$fields .= $this->Infinitas->wysiwyg('GlobalContent.introduction');
	}

	$fields .= $this->Infinitas->wysiwyg('GlobalContent.body') .
		'<div class="input smaller required">' .
			$this->Form->input('GlobalContent.layout_id', array('options' => $contentLayouts, 'empty' => Configure::read('Website.empty_select'), 'class' => 'smaller')) .
			$this->Form->input('GlobalContent.group_id', array('options' => $contentGroups, 'label' => __('Min Group'), 'empty' => __d('contents', 'Public'))) .
			$this->element('Contents.category_list') .
		'</div>' .
		$this->Form->input('GlobalContent.tags');


	$template = '%s';
	if(!empty($metaFieldSet) && $metaFieldSet === true) {
		$template = sprintf(
			'<fieldset><h1>%s</h1>%%s</fieldset>',
			__d('contents', 'Content')
		);
	}

	echo sprintf($template, $fields);
