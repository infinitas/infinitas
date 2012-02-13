<?php
	$fields = $this->Form->input('GlobalContent.meta_keywords') . 
			$this->Form->input('GlobalContent.meta_description') .
			$this->Form->input('GlobalContent.canonical_url', array('readonly' => true, 'type' => 'text')) .
			$this->Form->input('GlobalContent.canonical_redirect');

	$template = '%s';
	if(!empty($metaFieldSet) && $metaFieldSet === true) {
		$template = sprintf(
			'<fieldset><h1>%s</h1>%%s</fieldset>',
			__d('contents', 'Meta Data')
		);
	}

	echo sprintf($template, $fields);
