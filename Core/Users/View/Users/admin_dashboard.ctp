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
	<h1><?php echo __('User Manager'); ?></h1>
	<?php echo $this->Design->arrayToList(current((array)$dashboardIcons), array('ul' => 'icons')); ?>
</div>
<?php
	echo $this->ModuleLoader->loadDirect('Users.registrations');