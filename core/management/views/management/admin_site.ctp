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
			'name' => 'Installer',
			'description' => 'Manage your Infinitas install',
			'author' => 'Infinitas',
			'icon' => '/installer/img/icon.png',
			'dashboard' => array('plugin' => 'installer', 'controller' => 'install', 'action' => 'index')
		),
		array(
			'name' => 'Menus',
			'description' => 'Build menus for your site',
			'icon' => '/menus/img/icon.png',
			'author' => 'Infinitas',
			'dashboard' => array('plugin' => 'menus', 'controller' => 'menus', 'action' => 'index')
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
			'name' => 'Webmaster',
			'description' => 'Manage your sites robots files and sitemaps',
			'icon' => '/webmaster/img/icon.png',
			'author' => 'Infinitas',
			'dashboard' => array('plugin' => 'webmaster', 'controller' => 'webmaster', 'action' => 'dashboard')
		)
	);

	$managementContent = array(
		array(
			'name' => 'Categories',
			'description' => 'Categorize your content',
			'icon' => '/categories/img/icon.png',
			'author' => 'Infinitas',
			'dashboard' => array('plugin' => 'categories', 'controller' => 'categories', 'action' => 'index')
		),
		array(
			'name' => 'Contact',
			'description' => 'Display your contact details and allow users to contact you',
			'icon' => '/contact/img/icon.png',
			'author' => 'Infinitas',
			'dashboard' => array('plugin' => 'contact', 'controller' => 'branches', 'action' => 'index')
		),
		array(
			'name' => 'Locks',
			'description' => 'Stop others editing things you are working on',
			'icon' => '/locks/img/icon.png',
			'author' => 'Infinitas',
			'dashboard' => array('plugin' => 'locks', 'controller' => 'locks', 'action' => 'index')
		),
		array(
			'name' => 'Modules',
			'description' => 'Add sections of output to your site with ease',
			'icon' => '/modules/img/icon.png',
			'author' => 'Infinitas',
			'dashboard' => array('plugin' => 'modules', 'controller' => 'modules', 'action' => 'index', 'Module.admin' => 0)
		),
		array(
			'name' => 'Tags',
			'description' => 'Attach tags to records',
			'icon' => '/tags/img/icon.png',
			'author' => 'CakeDC',
			'dashboard' => array('plugin' => 'tags', 'controller' => 'tags', 'action' => 'index')
		),
		array(
			'name' => 'View Counts',
			'description' => 'View your sites traffic',
			'icon' => '/view_counter/img/icon.png',
			'author' => 'Infinitas',
			'dashboard' => array('plugin' => 'view_counter', 'controller' => 'view_counter', 'action' => 'dashboard')
		)
	);
	$managementGeneral = $this->Menu->builDashboardLinks($managementGeneral, 'management_general');
	$managementContent = $this->Menu->builDashboardLinks($managementContent, 'management_content');
?>
<div class="dashboard grid_16">
	<h1><?php echo __('Site Management', true); ?></h1>
	<ul class="icons"><li><?php echo implode('</li><li>', current((array)$managementGeneral)); ?></li></ul>
</div>

<div class="dashboard grid_16">
	<h1><?php echo __('Content Related', true); ?></h1>
	<ul class="icons"><li><?php echo implode('</li><li>', current((array)$managementContent)); ?></li></ul>
</div>