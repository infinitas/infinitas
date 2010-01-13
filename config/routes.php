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
		}
	}

	foreach($routes as $route ){
		//Router::connect($route['Route']['url'], $route['Route']['values'], $route['Route']['regex'] );
		//Router::connect('/p/:year/:month/:day', array('plugin'=>'blog','controller'=>'posts','day'=>null,'admin'=>null),  array('year'=>'[12][0-9]{3}','month'=>'0[1-9]|1[012]','day'=>'0[1-9]|[12][0-9]|3[01]') );
	}
	$db = array(
		'url' => $routes[0]['Route']['url'],
		'values' => $routes[0]['Route']['values'],
		'regex' => $routes[0]['Route']['regex']

	);

	$code = array(
		'url' => '/p/:year/:month/:day',
		'values' => array('plugin'=>'blog','controller'=>'posts','day'=>null,'admin'=>null),
		'regex' => array('year'=>'[12][0-9]{3}','month'=>'0[1-9]|1[012]','day'=>'0[1-9]|[12][0-9]|3[01]')
	);

	var_dump( $db );
	echo '<br/>';
	var_dump( $code );
	exit;
?>