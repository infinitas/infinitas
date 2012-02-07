<?php

	/**
	 * creators
	 */
	$options = array('options' => $contentAuthors, 'selected' => $this->Session->read('Auth.User.id'));
	if(strstr($this->params['action'], 'edit')) {
		$options = array('readonly' => true);
	}

	$fields = $this->Form->input('GlobalContent.author_id', $options);
	unset($options['options'], $options['selected']);
	$fields .= $this->Form->input('GlobalContent.author_alias', $options);

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
		$options = array('readonly' => true);
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