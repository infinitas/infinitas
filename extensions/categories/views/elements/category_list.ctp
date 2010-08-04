<?php
	$categories = ClassRegistry::init('Categories.Category')->generateTreeList();

	if(!isset($options)) {
		$options = array();
	}
	$options = array_merge((array)$options, array('options' => $categories, 'empty' => Configure::read('Website.empty_select')));
	unset($categories);
	echo $this->Form->input('category_id', $options);
?>