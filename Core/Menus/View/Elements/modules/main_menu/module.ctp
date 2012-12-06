<?php
$config = array_merge(array(
	'menu' => null,
	'type' => 'horizontal'
), $config);
if (empty($config['menu'])) {
	return false;
}


echo $this->Menu->nestedList(ClassRegistry::init('Menus.MenuItem')->getMenu($config['menu']), $config['type']);