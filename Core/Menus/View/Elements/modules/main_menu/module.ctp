<?php
$config = array_merge(array(
	'menu' => null,
	'type' => 'horizontal'
), $config);
if (empty($config['menu'])) {
	return false;
}

$menu = ClassRegistry::init('Menus.MenuItem')->find('menu', $config['menu']);
if (!empty($menu)) {
	echo $this->Menu->nestedList($menu, $config['type']);
}