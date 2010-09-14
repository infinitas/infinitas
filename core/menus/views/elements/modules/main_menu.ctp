<?php
	$menuToLoad  = isset($config['public']) ? $config['public']: 'main_menu';

	if(isset($config['registered']) && $this->Session->read('Auth.User.id') > 0){
		$menuToLoad = $config['registered'];
	}

	$type = isset($config['type']) ? $config['type'] : 'horizontal';

	$menus = Cache::read('menu.'.$type, 'core');
	if(empty($menus)){
		$menus = ClassRegistry::init('Menu.MenuItem')->getMenu($menuToLoad);
	}

	echo $this->Menu->nestedList($menus, $type);
?>