<?php
$dashboardIcons = array(
	array(
		'name' => 'Users',
		'description' => 'Manage your sites users',
		'icon' => '/users/img/icon.png',
		'dashboard' => array(
			'controller' => 'users',
			'action' => 'index'
		)
	),
	array(
		'name' => 'Groups',
		'description' => 'Manage the different groups on your site',
		'icon' => '/users/img/group.png',
		'dashboard' => array(
			'controller' => 'groups',
			'action' => 'index'
		)
	),
	array(
		'name' => 'Access',
		'description' => 'Manage who can view what',
		'icon' => '/users/img/groups.png',
		'dashboard' => $this->here . '#'
	)
);

echo $this->Html->tag('div', implode('', array(
	$this->Html->tag('h1', __d('users', 'User Manager')),
	$this->Design->arrayToList(current((array)$this->Menu->builDashboardLinks($dashboardIcons, 'user_dashboard')), array('ul' => 'icons'))
)), array('class' => 'dashboard'));
echo $this->ModuleLoader->loadDirect('Users.registrations');