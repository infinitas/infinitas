<?php	
	App::uses('InfinitasRouter', 'Routes.Routing');
	App::uses('Router', 'Routing');
	
	InfinitasRouter::connect('/comment/*', array('plugin' => 'comments', 'controller' => 'infinitas_comments', 'action' => 'comments'));

	InfinitasRouter::setup();

	$prefixes = Router::prefixes();
	$plugins = CakePlugin::loaded();

	App::uses('PluginShortRoute', 'Routing/Route');
	foreach ($plugins as $key => $value) {
		$plugins[$key] = Inflector::underscore($value);
	}
	$pluginPattern = implode('|', $plugins);
	$match = array('plugin' => $pluginPattern);
	$shortParams = array('routeClass' => 'PluginShortRoute', 'plugin' => $pluginPattern);

	foreach ($prefixes as $prefix) {
		$params = array('prefix' => $prefix, $prefix => true);
		$indexParams = $params + array('action' => 'index');
		InfinitasRouter::connect("/{$prefix}/:plugin", $indexParams, $shortParams);
		InfinitasRouter::connect("/{$prefix}/:plugin/:controller", $indexParams, $match);
		InfinitasRouter::connect("/{$prefix}/:plugin/:controller/dashboard", $params + array('action' => 'dashboard'), $match);
		InfinitasRouter::connect("/{$prefix}/:plugin/:controller/:action/*", $params, $match);
	}
	
	InfinitasRouter::connect('/:plugin', array('action' => 'index'), $shortParams);
	InfinitasRouter::connect('/:plugin/:controller', array('action' => 'index'), $match);
	InfinitasRouter::connect('/:plugin/:controller/:action/*', array(), $match);

	$namedConfig = InfinitasRouter::namedConfig();
	if ($namedConfig['rules'] === false) {
		InfinitasRouter::connectNamed(true);
	}

	unset($namedConfig, $params, $indexParams, $prefix, $prefixes, $shortParams, $match,
		$pluginPattern, $plugins, $key, $value);