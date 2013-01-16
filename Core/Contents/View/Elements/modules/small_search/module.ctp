<?php
$config = array_merge(array(
	'title' => __d('contents', 'Content search'),
), $config);

if (trim($config['title'])) {
	echo $this->Html->tag('h2', $config['title']);
}

echo $this->Form->create('Content', array(
	'url' => array(
		'plugin' => 'contents',
		'controller' => 'contents',
		'action' => 'search'
	)
));
	echo $this->Form->input('search', array(
		'label' => false,
		'placeholder' => __d('contents', 'Search')
	));
	echo $this->Form->submit(__d('contents', 'Search'));
echo $this->Form->end();
