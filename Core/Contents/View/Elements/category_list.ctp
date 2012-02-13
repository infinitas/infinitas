<?php
	$categories = ClassRegistry::init('Contents.GlobalCategory')->generateTreeList();

	if(!isset($options)) {
		$options = array();
	}
	
	$options = array_merge(
		(array)$options,
		array(
			'options' => $contentCategories,
			'empty' => __d('contents', 'Uncategorised'),
			'label' => __d('contents', 'Category')
		)
	);
	unset($categories);
	echo $this->Form->input('GlobalContent.global_category_id', $options);
?>