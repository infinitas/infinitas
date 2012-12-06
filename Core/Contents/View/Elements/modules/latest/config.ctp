<?php
echo $this->Form->input('ModuleConfig.model', array(
	'type' => 'text',
	'label' => __d('contents', 'Data to load from'),
	'placeholder' => __d('contents', 'Blank for dynamic')
));
echo $this->Form->input('ModuleConfig.category', array(
	'type' => 'text',
	'label' => __d('contents', 'Limited to category'),
	'placeholder' => __d('contents', 'Blank for all catgories, true for current category only or category alias')
));
echo $this->Form->input('ModuleConfig.title', array(
	'type' => 'text',
	'label' => __d('contents', 'The title of the module'),
	'placeholder' => __d('contents', 'Latest content')
));
echo $this->Form->input('ModuleConfig.limit', array(
	'type' => 'number',
	'label' => __d('contents', 'Rows to fetch'),
	'placeholder' => 5
));
echo $this->Form->input('ModuleConfig.truncate', array(
	'type' => 'number',
	'label' => __d('contents', 'Length to truncate'),
	'placeholder' => 60
));