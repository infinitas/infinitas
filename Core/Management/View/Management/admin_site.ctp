<?php
$managementGeneral = array(
	array(
		'name' => 'Dashboard',
		'description' => 'Back to the main admin dashboard',
		'icon' => '/management/img/icon.png',
		'author' => 'Infinitas',
		'dashboard' => array('controller' => 'management', 'action' => 'dashboard')
	),
	array(
		'name' => 'Configs',
		'description' => 'Configure Your site',
		'icon' => '/configs/img/icon.png',
		'author' => 'Infinitas',
		'dashboard' => array('plugin' => 'configs', 'controller' => 'configs', 'action' => 'index')
	),
	array(
		'name' => 'Server',
		'description' => 'Track your servers health',
		'icon' => '/server_status/img/icon.png',
		'author' => 'Infinitas',
		'dashboard' => array('plugin' => 'server_status', 'controller' => 'server_status', 'action' => 'dashboard')
	),
	array(
		'name' => 'Short Urls',
		'description' => 'Manage the short urls pointing to your site',
		'icon' => '/short_urls/img/icon.png',
		'author' => 'Infinitas',
		'dashboard' => array('plugin' => 'short_urls', 'controller' => 'short_urls', 'action' => 'index')
	),
	array(
		'name' => 'Contact',
		'description' => 'Display your contact details and allow users to contact you',
		'icon' => '/contact/img/icon.png',
		'author' => 'Infinitas',
		'dashboard' => array('plugin' => 'contact', 'controller' => 'branches', 'action' => 'index')
	),
);

$managementContent = array(
	array(
		'name' => 'Menus',
		'description' => 'Build menus for your site',
		'icon' => '/menus/img/icon.png',
		'author' => 'Infinitas',
		'dashboard' => array('plugin' => 'menus', 'controller' => 'menus', 'action' => 'index')
	),
	array(
		'name' => 'Modules',
		'description' => 'Add sections of output to your site with ease',
		'icon' => '/modules/img/icon.png',
		'author' => 'Infinitas',
		'dashboard' => array('plugin' => 'modules', 'controller' => 'modules', 'action' => 'index', 'Module.admin' => 0)
	),
	array(
		'name' => 'Themes',
		'description' => 'Theme your site',
		'icon' => '/themes/img/icon.png',
		'author' => 'Infinitas',
		'dashboard' => array('plugin' => 'themes', 'controller' => 'themes', 'action' => 'index')
	)
);

$managementGeneral = $this->Design->arrayToList(current((array)$this->Menu->builDashboardLinks($managementGeneral, 'management_general')), array(
	'ul' => 'icons'
));
$managementContent = $this->Design->arrayToList(current((array)$this->Menu->builDashboardLinks($managementContent, 'management_content')), array(
	'ul' => 'icons'
));

echo $this->Design->dashboard($managementContent, __d('infinitas', 'Site Layout'), array(
	'class' => 'dashboard span6',
));
echo $this->Design->dashboard($managementGeneral, __d('infinitas', 'Site Management'), array(
	'class' => 'dashboard span6',
));