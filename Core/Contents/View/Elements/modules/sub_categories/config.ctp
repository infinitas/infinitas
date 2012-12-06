<?php
$config = array_merge(array(
	'model' => null,
	'category' => null,
	'title' => null,
	'limit' => null,
	'truncate' => null
), $config);

echo $this->Form->input('ModuleConfig.title', array(
	'type' => 'text',
	'label' => __d('contents', 'The title of the module'),
	'placeholder' => __d('contents', 'Sub Categories')
));

echo $this->Form->input('ModuleConfig.explore', array(
	'type' => 'text',
	'label' => __d('contents', 'Link text for explore'),
	'placeholder' => __d('contents', 'Explore this category')
));
echo $this->Form->input('ModuleConfig.view_all', array(
	'type' => 'text',
	'label' => __d('contents', 'Link text for view all'),
	'placeholder' => __d('contents', 'View all categories')
));
echo $this->Form->input('ModuleConfig.nothing_found', array(
	'type' => 'text',
	'label' => __d('contents', 'Nothing found text'),
	'placeholder' => __d('contents', 'No sub categories found')
));
echo $this->Form->input('ModuleConfig.back', array(
	'type' => 'text',
	'label' => __d('contents', 'Back link text'),
	'placeholder' => __d('contents', 'Back to %s')
));

echo $this->Form->input('ModuleConfig.div_class', array(
	'type' => 'text',
	'label' => __d('contents', 'CSS class for the div'),
	'placeholder' => __d('contents', 'widget-content')
));
echo $this->Form->input('ModuleConfig.ul_class', array(
	'type' => 'text',
	'label' => __d('contents', 'CSS class for the ul'),
	'placeholder' => __d('contents', 'arrow-list')
));