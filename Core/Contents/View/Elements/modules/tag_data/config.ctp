<?php
$config = array_merge(array(
	'missing' => null,
	'title' => null
), $config);

echo $this->Form->input('ModuleConfig.title', array(
	'type' => 'text',
	'label' => __d('contents', 'Module title'),
	'placeholder' => __d('contents', 'Showing posts related to "%s"')
));
echo $this->Form->input('ModuleConfig.missing', array(
	'type' => 'text',
	'label' => __d('contents', 'Missing text'),
	'placeholder' => __d('contents', 'There does not seem to be a description for this tag')
));


