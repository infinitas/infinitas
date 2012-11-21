<?php
$core = array(
	array(
		'name' => 'Plugins',
		'description' => 'View core plugins',
		'icon' => '/installer/img/icon.png',
		'author' => 'Infinitas',
		'dashboard' => array('controller' => 'plugins', 'action' => 'index', 'Plugin.core' => 1)
	),
	array(
		'name' => 'Update',
		'description' => 'Update your site',
		'icon' => '/installer/img/update.png',
		'author' => 'Infinitas',
		'dashboard' => array('controller' => 'plugins', 'action' => 'update_infinitas')
	),
);
$nonCore = array(
	array(
		'name' => 'Plugins',
		'description' => 'View, install and manage your plugins',
		'icon' => '/installer/img/icon.png',
		'author' => 'Infinitas',
		'dashboard' => array('controller' => 'plugins', 'action' => 'index', 'Plugin.core' => 0)
	),
	array(
		'name' => 'Install',
		'description' => 'Install additional plugins and themes',
		'icon' => '/installer/img/install.png',
		'author' => 'Infinitas',
		'dashboard' => array('controller' => 'plugins', 'action' => 'install')
	),
);
$core = current((array)$this->Menu->builDashboardLinks($core, 'plugins_core'));
$nonCore = current((array)$this->Menu->builDashboardLinks($nonCore, 'plugins_non_core'));

echo $this->Design->dashboard($this->Design->arrayToList($core, array('ul' => 'icons')), __d('installer', 'Infinitas'), array(
	'class' => 'dashboard span6'
));
echo $this->Design->dashboard($this->Design->arrayToList($nonCore, array('ul' => 'icons')), __d('installer', 'Plugins'), array(
	'class' => 'dashboard span6'
));