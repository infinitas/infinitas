<?php
	if (empty($model)) {
		$model = implode('.', $this->request->models[current(array_keys($this->request->models))]);
	}

	else if (!strstr($model, '.')) {
		$model = ClassRegistry::init($model)->plugin . '.' . ClassRegistry::init($model)->alias;
	}

	$groups = array(0 => __d('contents', 'Public')) + ClassRegistry::init($model)->GlobalContent->Group->find('list');

	$fields =
		$this->Form->input('GlobalContent.id') .
		$this->Form->hidden('GlobalContent.model', array('value' => $model)) .
		$this->Html->tag('div', implode('', array(
			$this->Form->input('GlobalContent.title', array(
				'div' => 'span6'
			)),
			$this->Form->input('GlobalContent.slug', array(
				'label' => __d('contents', 'Alias'),
				'div' => 'span6'
			))
		)), array('class' => 'row-fluid'));

	$tags = Hash::extract($contentTags, '{n}.GlobalTag.name');
	$currentTags = (array)Hash::extract($this->request->data, 'GlobalTagged.{n}.GlobalTag.name');
	$tagAvailabel = array();
	foreach ($tags as &$tag) {
		if (in_array($tag, $currentTags)) {
			continue;
		}
		$tagAvailabel[] = $this->Design->label($tag);
	}

	$fields .= $this->Html->tag('div', implode('', array(
			$this->Form->input('GlobalContent.layout_id', array(
				'options' => $contentLayouts,
				'empty' => Configure::read('Website.empty_select'),
				'class' => 'smaller',
				'div' => 'span6'
			)),
			$this->Form->input('GlobalContent.group_id', array(
				'options' => $contentGroups,
				'label' => __d('contents', 'Min Group'),
				'empty' => __d('contents', 'Public'),
				'div' => 'span6'
			))
		)), array('class' => 'row-fluid')) .
		$this->Html->tag('div', implode('', array(
			$this->Html->tag('div', implode('', array(
				$this->Form->input('GlobalContent.tags', array(
					'value' => implode(',', $currentTags),
					'data-tags' => htmlspecialchars(implode(',', $tags)),
					'label' => __d('contents', 'Tags')
				)),
				$this->element('Contents.category_list')
			)), array('class' => 'span5')),
			$this->Html->tag('div', $this->Form->label(__d('contents', 'Existing tags')) . implode('', $tagAvailabel), array(
				'class' => 'tags span7'
			))
		)), array('class' => 'row-fluid'));


	if (!isset($image) || $image !== false) {
		$fields .= $this->element('Filemanager.file_upload', array('fieldName' => 'GlobalContent.image', 'inputOptions' => array('label' => __d('contents', 'Content Image'))));
	}

	if (!isset($intro) || $intro !== false) {
		$fields .= $this->Infinitas->wysiwyg('GlobalContent.introduction');
	}
	$fields .= $this->Infinitas->wysiwyg('GlobalContent.body');

	$template = '%s';
	if (!empty($metaFieldSet) && $metaFieldSet === true) {
		$template = sprintf(
			'<fieldset><h1>%s</h1>%%s</fieldset>',
			__d('contents', 'Content')
		);
	}

	echo sprintf($template, $fields);
