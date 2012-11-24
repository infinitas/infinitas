<?php
$gravitar = $this->Gravatar->image(AuthComponent::user('email'), array(
	'size' => 20
));
$name = AuthComponent::user('prefered_name');
if (empty($name)) {
	$name = AuthComponent::user('username');
}

$helpPlugin = !empty($this->request->plugin) ? Inflector::camelize($this->request->plugin) : null;
$helpUrl = 'http://infinitas-cms.org/infinitas_docs/' . $helpPlugin;
if (Configure::read('debug') && in_array('InfinitasDocs', InfinitasPlugin::listPlugins())) {
	$helpUrl = array(
		'plugin' => 'infinitas_docs',
		'controller' => 'infinitas_docs',
		'action' => 'index',
		'doc_plugin' => $helpPlugin,
		'admin' => false
	);
}

$links = array(
	$this->Html->link($gravitar . $this->Html->tag('span', $name) . $this->Html->tag('b', '', array('class' => 'caret')), '#', array(
		'class' => 'dropdown-toggle',
		'role' => 'button',
		'data-toggle' => 'dropdown',
		'id' => 'user-profile',
		'escape' => false
	)),
	$this->Html->tag('li', $this->Html->link(
		$this->Html->tag('i', '', array('class' => 'icon-user-md')) . __d('users', 'Profile'), array(
			'plugin' => 'users',
			'controller' => 'users',
			'action' => 'mine'
		), array('escape' => false)
	)),
	$this->Html->tag('li', $this->Html->link(
		$this->Html->tag('i', '', array('class' => 'icon-wrench')) . __d('configs', 'Config'), array(
			'plugin' => 'management',
			'controller' => 'management',
			'action' => 'site'
		), array('escape' => false)
	)),
	$this->Html->tag('li', $this->Html->link(
		$this->Html->tag('i', '', array('class' => 'icon-question-sign')) . __d('infinitas_docs', 'Help'),
		$helpUrl,
		array('target' => '_blank', 'escape' => false)
	)),
	$this->Html->tag('li', '', array('class' => 'divider')),
	$this->Html->tag('li', $this->Html->link(__d('users', 'Logout'), array(
		'plugin' => 'users',
		'controller' => 'users',
		'action' => 'logout'
	)))
);

$mainLink = array_shift($links);

$links = $this->Html->tag('ul', implode('', $links), array(
	'class' => 'dropdown-menu',
	'aria-labelledby' => 'user-profile'
));
echo $this->Html->tag('ul', implode('', array(
	$this->Html->tag('li', $mainLink . $links),
)), array('class' => 'nav user-profile'));