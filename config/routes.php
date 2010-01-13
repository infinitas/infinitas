<?php
	/**
	 * redirect to the installer if there is nothing
	 */
	if (!file_exists(APP . 'config' . DS . 'database.php')) {
		Router::connect('/', array('plugin' => 'installer', 'controller' => 'install', 'action' => 'index'));
	}

	$routes = Cache::read('routes', 'core');

	if (!$routes) {
		$routes = Classregistry::init('Management.Route')->getRoutes();
		if (empty($routes)) {
			//something is broken
			// @todo -c Implement .some error message or something
		}
	}

	foreach($routes as $route ){
		Router::connect($route['Route']['url'], $route['Route']['values'], $route['Route']['regex'] );
	}
?>