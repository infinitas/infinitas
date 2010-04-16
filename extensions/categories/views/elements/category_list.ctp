<?php
	$categories = ClassRegistry::init('Categories.Category')->generateTreeList();

	if(!isset($options)) {
		$options = array();
	}
	$options = array_merge($options, array('options' => $categories, 'empty' => __('Please select', true)));

	echo $this->Form->input('category_id', $options);
?>