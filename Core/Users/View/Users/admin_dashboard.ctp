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

$dashboardIcons = current((array)$this->Menu->builDashboardLinks($dashboardIcons, 'user_dashboard'));
echo $this->Design->dashboard($this->Design->arrayToList($dashboardIcons, array('ul' => 'icons')), __d('users', 'User Manager'), array(
	'class' => 'dashboard span6'
));
echo $this->ModuleLoader->loadDirect('Users.registrations');