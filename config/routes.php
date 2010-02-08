<?php
	Cache::config(
		'core',
		array(
			'engine' => 'File',
			'duration' => 3600,
			'probability' => 100,
			'prefix' => '',
			'lock' => false,
			'serialize' => true,
			'path' => CACHE . 'core'
		)
	);

	/**
	 * redirect to the installer if there is nothing
	 */
	if (!file_exists(APP . 'config' . DS . 'database.php')) {
		Router::connect('/', array('plugin' => 'installer', 'controller' => 'install', 'action' => 'index'));
	}
	else{
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
	}
?>