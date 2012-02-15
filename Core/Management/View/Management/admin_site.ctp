<?php
	$managementGeneral = array(
		array(
			'name' => 'Dashboard',
			'description' => 'Back to the main admin dashboard',
			'icon' => '/management/img/icon.png',
			'author' => 'Infinitas',
			'dashboard' => array('plugin' => 'management', 'controller' => 'management', 'action' => 'dashboard')
		),
		array(
			'name' => 'Configs',
			'description' => 'Configure Your site',
			'icon' => '/configs/img/icon.png',
			'author' => 'Infinitas',
			'dashboard' => array('plugin' => 'configs', 'controller' => 'configs', 'action' => 'index')
		),
		array(
			'name' => 'Locks',
			'description' => 'Stop others editing things you are working on',
			'icon' => '/locks/img/icon.png',
			'author' => 'Infinitas',
			'dashboard' => array('plugin' => 'locks', 'controller' => 'locks', 'action' => 'index')
		),
		array(
			'name' => 'Menus',
			'description' => 'Build menus for your site',
			'icon' => '/menus/img/icon.png',
			'author' => 'Infinitas',
			'dashboard' => array('plugin' => 'menus', 'controller' => 'menus', 'action' => 'index')
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
			'name' => 'Themes',
			'description' => 'Theme your site',
			'icon' => '/themes/img/icon.png',
			'author' => 'Infinitas',
			'dashboard' => array('plugin' => 'themes', 'controller' => 'themes', 'action' => 'index')
		),
		array(
			'name' => 'Trash',
			'description' => 'Manage the deleted content',
			'icon' => '/trash/img/icon.png',
			'author' => 'Infinitas',
			'dashboard' => array('plugin' => 'trash', 'controller' => 'trash', 'action' => 'index')
		)
	);

	$managementContent = array(
		array(
			'name' => 'Contact',
			'description' => 'Display your contact details and allow users to contact you',
			'icon' => '/contact/img/icon.png',
			'author' => 'Infinitas',
			'dashboard' => array('plugin' => 'contact', 'controller' => 'branches', 'action' => 'index')
		),
		array(
			'name' => 'Modules',
			'description' => 'Add sections of output to your site with ease',
			'icon' => '/modules/img/icon.png',
			'author' => 'Infinitas',
			'dashboard' => array('plugin' => 'modules', 'controller' => 'modules', 'action' => 'index', 'Module.admin' => 0)
		)
	);
	$managementGeneral = $this->Menu->builDashboardLinks($managementGeneral, 'management_general');
	$managementContent = $this->Menu->builDashboardLinks($managementContent, 'management_content');
?>
<div class="dashboard grid_16">
	<h1><?php echo __('Site Management'); ?></h1>
	<ul class="icons"><li><?php echo implode('</li><li>', current((array)$managementGeneral)); ?></li></ul>
</div>

<div class="dashboard grid_16">
	<h1><?php echo __('Content Related'); ?></h1>
	<ul class="icons"><li><?php echo implode('</li><li>', current((array)$managementContent)); ?></li></ul>
</div>