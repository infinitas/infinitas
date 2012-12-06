<?php
$config = array_merge(array(
	'title' => null,
	'limit' => null,
	'model' => null,
	'category' => null,
	'tag_before' => null,
	'tag_after' => null
), $config);

echo $this->Form->input('ModuleConfig.title', array(
	'type' => 'text',
	'label' => __d('contents', 'The title of the module'),
	'placeholder' => __d('contents', 'Tag cloud')
));

echo $this->Form->input('ModuleConfig.limit', array(
	'type' => 'number',
	'label' => __d('contents', 'The number of tags to show'),
	'placeholder' => 50
));
echo $this->Form->input('ModuleConfig.model', array(
	'type' => 'text',
	'label' => __d('contents', 'The model to load tags for'),
	'placeholder' => __d('contents', 'PluginName.ModelName')
));
echo $this->Form->input('ModuleConfig.category', array(
	'type' => 'text',
	'label' => __d('contents', 'The category to limit tags to'),
	'placeholder' => __d('contents', 'category-alias')
));
echo $this->Form->input('ModuleConfig.tag_before', array(
	'type' => 'text',
	'label' => __d('contents', 'The start tag'),
	'placeholder' => '<li size="%size%" class="tag">'
));
echo $this->Form->input('ModuleConfig.tag_after', array(
	'type' => 'text',
	'label' => __d('contents', 'The end tag'),
	'placeholder' => '</li>'
));