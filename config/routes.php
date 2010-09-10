<?php
	Router::parseExtensions('rss', 'vcf', 'json', 'csv');

	EventCore::trigger(new StdClass(), 'setupRoutes');

	/**
	 * redirect to the installer if there is nothing
	 */
	if (!file_exists(APP . 'config' . DS . 'database.php')) {
		Configure::write('Session.save', 'php');

		Router::connect('/', array('plugin' => 'installer', 'controller' => 'install', 'action' => 'index'));
	}
	else{
		$routes = Cache::read('routes', 'core');

		if (!$routes) {
			$routes = Classregistry::init('Routes.Route')->getRoutes();
			if (empty($routes)) {
				//something is broken
				// @todo -c Implement .some error message or something
			}
			Cache::write('routes', $routes, 'core');
		}

		if (!empty($routes)) {
			foreach($routes as $route ){
				if (false) {
					debugRoute($route);
					continue;
				}
				Router::connect($route['Route']['url'], $route['Route']['values'], $route['Route']['regex']);
			}
		}
	}