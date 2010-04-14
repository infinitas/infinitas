<?php
	$categories = ClassRegistry::init('Management.Category')->generateTreeList();

	$options = array('label' => __('Category', true), 'options' => $categories, 'empty' => __('Please select', true));

	if(isset($multiple) && $multiple == true) {
		$options['multiple'] = 'checkbox';
	}

	echo $this->Form->input('Category.id', $options);
?>