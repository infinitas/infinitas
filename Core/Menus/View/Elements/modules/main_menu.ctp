<?php
	if(!isset($config['menu'])){
		//trow error
		return false;
	}

	$type = isset($config['type']) ? $config['type'] : 'horizontal';
	$menus = ClassRegistry::init('Menus.MenuItem')->getMenu($config['menu']);
	

	echo $this->Menu->nestedList($menus, $type);
?>