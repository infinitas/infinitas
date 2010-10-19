<?php
	$core = array(
		array(
			'name' => 'Plugins',
			'description' => 'View core plugins',
			'icon' => '/installer/img/icon.png',
			'author' => 'Infinitas',
			'dashboard' => array('plugin' => 'installer', 'controller' => 'plugins', 'action' => 'index', 'Plugin.core' => 1)
		),
		array(
			'name' => 'Update',
			'description' => 'Update your site',
			'icon' => '/installer/img/update.png',
			'author' => 'Infinitas',
			'dashboard' => array('plugin' => 'installer', 'controller' => 'plugins', 'action' => 'update_infinitas')
		),
	);
	$nonCore = array(
		array(
			'name' => 'Plugins',
			'description' => 'View, install and manage your plugins',
			'icon' => '/installer/img/icon.png',
			'author' => 'Infinitas',
			'dashboard' => array('plugin' => 'installer', 'controller' => 'plugins', 'action' => 'index', 'Plugin.core' => 0)
		),
		array(
			'name' => 'Install',
			'description' => 'Install additional plugins and themes',
			'icon' => '/installer/img/install.png',
			'author' => 'Infinitas',
			'dashboard' => array('plugin' => 'installer', 'controller' => 'plugins', 'action' => 'install')
		),
	);
	$core = $this->Menu->builDashboardLinks($core, 'plugins_core');
	$nonCore = $this->Menu->builDashboardLinks($nonCore, 'plugins_non_core');
?>
<div class="dashboard grid_16">
	<h1><?php echo __('Infinitas Core', true); ?></h1>
	<ul class="icons"><li><?php echo implode('</li><li>', current((array)$core)); ?></li></ul>
</div>
<div class="dashboard grid_16">
	<h1><?php echo __('Plugins', true); ?></h1>
	<ul class="icons"><li><?php echo implode('</li><li>', current((array)$nonCore)); ?></li></ul>
</div>