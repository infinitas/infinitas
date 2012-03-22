<?php
	if(empty($contentCategories)) {
		$contentCategories = ClassRegistry::init('Contents.GlobalCategory')->find('list');
	}

	if(!isset($options)) {
		$options = array();
	}
	
	$options = array_merge(
		array(
			'options' => $contentCategories,
			'empty' => __d('contents', 'Uncategorised'),
			'label' => __d('contents', 'Category')
		),
		(array)$options
	);
	echo $this->Form->input('GlobalContent.global_category_id', $options);
?>