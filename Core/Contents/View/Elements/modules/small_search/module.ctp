<?php
$config = array_merge(array(
	'title' => __d('contents', 'Content search'),
	'button' => false
), $config);

if (trim($config['title'])) {
	echo $this->Html->tag('h2', $config['title']);
}

echo $this->Form->create('GlobalContent', array(
	'url' => array(
		'plugin' => 'contents',
		'controller' => 'global_search',
		'action' => 'search'
	)
));

$default = !empty($this->request->params['pass'][0]) ? $this->request->params['pass'][0] : null;
if ($default && $this->request->params['action'] != 'search') {
	$default = Inflector::slug($default, ' ');
}
	echo $this->Form->input('search', array(
		'label' => false,
		'placeholder' => __d('contents', 'Search'),
		'default' => $default
	));
	if ($config['button']) {
		echo $this->Form->submit(__d('contents', 'Search'));
	}
echo $this->Form->end();
