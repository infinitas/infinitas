<?php
$managementGeneral = array(
	array(
		'name' => 'Dashboard',
		'description' => 'Back to the main admin dashboard',
		'icon' => 'home',
		'author' => 'Infinitas',
		'dashboard' => array('controller' => 'management', 'action' => 'dashboard')
	),
	array(
		'name' => 'Configs',
		'description' => 'Configure Your site',
		'icon' => 'cogs',
		'author' => 'Infinitas',
		'dashboard' => array('plugin' => 'configs', 'controller' => 'configs', 'action' => 'index')
	),
	array(
		'name' => 'Server',
		'description' => 'Track your servers health',
		'icon' => 'dashboard',
		'author' => 'Infinitas',
		'dashboard' => array('plugin' => 'server_status', 'controller' => 'server_status', 'action' => 'dashboard')
	),
	array(
		'name' => 'Contact',
		'description' => 'Display your contact details and allow users to contact you',
		'icon' => 'phone-sign',
		'author' => 'Infinitas',
		'dashboard' => array('plugin' => 'contact', 'controller' => 'branches', 'action' => 'index')
	),
);

$managementGeneral = $this->Design->arrayToList(current((array)$this->Menu->builDashboardLinks($managementGeneral, 'management_general')), array(
	'ul' => 'icons'
));

echo $this->Design->dashboard($managementGeneral, __d('infinitas', 'Site Management'), array(
	'class' => 'dashboard span6',
));