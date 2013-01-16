<?php
$config = array_merge(array(
	'title' => __d('contents', 'Newsletter subscription'),
	'disclaimer' => null
), $config);

if (trim($config['title'])) {
	echo $this->Html->tag('h2', $config['title']);
}

echo $this->Form->create('Newsletter', array(
	'url' => array(
		'plugin' => 'newsletter',
		'controller' => 'newsletters',
		'action' => 'subscribe'
	)
));
	echo $this->Form->hidden('email', array(
		'value' => AuthComponent::user('id'),
	));
	echo $this->Form->input('name', array(
		'label' => false,
		'default' => AuthComponent::user('prefered_name'),
		'placeholder' => __d('newsletter', 'Name')
	));
	echo $this->Form->input('email', array(
		'label' => false,
		'default' => AuthComponent::user('email'),
		'placeholder' => __d('newsletter', 'Email')
	));
	echo $this->Form->submit(__d('newsletter', 'Subscribe'));
echo $this->Form->end();
if ($config['disclaimer']) {
	echo $this->Design->alert($config['disclaimer']);
}

