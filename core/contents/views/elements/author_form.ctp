<?php

	/**
	 * creators
	 */
	$options = array('options' => $contentAuthors, 'selected' => $this->Session->read('Auth.User.id'));
	if(strstr($this->params['action'], 'edit')) {
		$options = false;
	}

	if($options) {
		$fields = $this->Form->input('GlobalContent.author_id', $options);
		unset($options['options'], $options['selected']);
		$fields .= $this->Form->input('GlobalContent.author_alias', $options);
	}
	else {
		$value = isset($contentAuthors[$this->data['GlobalContent']['author_id']]) ? $contentAuthors[$this->data['GlobalContent']['author_id']] : '';
		$fields = $this->Form->input('created_by', array('label' => 'author', 'value' => $value, 'readonly' => true, 'type' => 'text'));
		$fields .= $this->Form->input('GlobalContent.author_alias', array('label' => 'author', 'value' => $this->data['GlobalContent']['author_alias'], 'readonly' => true, 'type' => 'text'));
	}

	$template = '%s';
	if(!empty($metaFieldSet) && $metaFieldSet === true) {
		$template = sprintf(
			'<fieldset><h1>%s</h1>%%s</fieldset>',
			__d('contents', 'Author', true)
		);
	}

	echo sprintf($template, $fields);

	/**
	 * editors
	 */

	$options = array('options' => $contentAuthors, 'selected' => $this->Session->read('Auth.User.id'));
	if(strstr($this->params['action'], 'add')) {
		$options['readonly'] = true;
	}

	$fields = $this->Form->input('GlobalContent.editor_id', $options);
	unset($options['options'], $options['selected']);
	$fields .= $this->Form->input('GlobalContent.editor_alias', $options);

	$template = '%s';
	if(!empty($metaFieldSet) && $metaFieldSet === true) {
		$template = sprintf(
			'<fieldset><h1>%s</h1>%%s</fieldset>',
			__d('contents', 'Editor', true)
		);
	}

	echo sprintf($template, $fields);