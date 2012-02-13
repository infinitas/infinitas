<?php
	$devIcons = array(
		array(
			'name' => 'Status',
			'description' => 'Check on the health of your setup',
			'icon' => '/server_status/img/icon.png',
			'author' => 'Infinitas',
			'dashboard' => array('plugin' => 'server_status', 'controller' => 'server_status', 'action' => 'status'),
		),
		array(
			'name' => 'Php',
			'description' => 'See and manage your Php configuration',
			'icon' => '/server_status/img/php.png',
			'author' => 'Infinitas',
			'dashboard' => array('plugin' => 'server_status', 'controller' => 'php', 'action' => 'info'),
		),
		array(
			'name' => 'MySQL',
			'description' => 'Information regarding the MySQL server currently running',
			'icon' => '/server_status/img/mysql.png',
			'author' => 'Infinitas',
			'dashboard' => array('plugin' => 'server_status', 'controller' => 'databases', 'action' => 'mysql'),
		)
	);
?>
<div class="dashboard grid_16">
	<h1><?php echo __('Manage your server'); ?></h1>
	<ul class="icons"><li><?php echo implode('</li><li>', current((array)$this->Menu->builDashboardLinks($devIcons, 'server_status'))); ?></li></ul>
</div>