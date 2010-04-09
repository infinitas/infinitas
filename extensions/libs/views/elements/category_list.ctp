<?php
	$categories = ClassRegistry::init('Management.Category')->generateTreeList();

	echo $this->Form->input('category_id', array('label' => __('Category', true), 'options' => $categories, 'empty' => __('Please select', true)));
?>