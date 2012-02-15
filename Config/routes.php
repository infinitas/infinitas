<?php	
	App::uses('InfinitasRouting', 'Routes.Lib');

	InfinitasRouting::setup();

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
		Router::connect("/{$prefix}/:plugin", $indexParams, $shortParams);
		Router::connect("/{$prefix}/:plugin/:controller", $indexParams, $match);
		Router::connect("/{$prefix}/:plugin/:controller/dashboard", $params + array('action' => 'dashboard'), $match);
		Router::connect("/{$prefix}/:plugin/:controller/:action/*", $params, $match);
	}
	Router::connect('/:plugin', array('action' => 'index'), $shortParams);
	Router::connect('/:plugin/:controller', array('action' => 'index'), $match);
	Router::connect('/:plugin/:controller/:action/*', array(), $match);

	$namedConfig = Router::namedConfig();
	if ($namedConfig['rules'] === false) {
		Router::connectNamed(true);
	}

	unset($namedConfig, $params, $indexParams, $prefix, $prefixes, $shortParams, $match,
		$pluginPattern, $plugins, $key, $value);