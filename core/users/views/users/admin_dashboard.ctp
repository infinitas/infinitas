<?php
	$dashboardIcons = array(
		array(
			'name' => 'Users',
			'description' => 'Manage your sites users',
			'icon' => '/users/img/icon.png',
			'dashboard' => array(
				'action' => 'index'
			)
		),
		array(
			'name' => 'Groups',
			'description' => 'Manage the different groups on your site',
			'icon' => '/users/img/groups.png',
			'dashboard' => array(
				'controller' => 'groups',
				'action' => 'index'
			)
		),
		array(
			'name' => 'Access - ACL',
			'description' => 'Manage who can view what',
			'icon' => '/users/img/groups.png',
			'dashboard' => array(
				'controller' => 'access',
				'action' => 'index'
			)
		)
	);
	$dashboardIcons = $this->Menu->builDashboardLinks($dashboardIcons, 'user_dashboard');
?>
<div class="dashboard grid_16">
	<h1><?php __('Email Manager', true); ?></h1>
	<ul class="icons"><li><?php echo implode('</li><li>', current((array)$dashboardIcons)); ?></li></ul>
</div>