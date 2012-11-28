<?php
$options = !empty($config['options']) ? (array)$config['options'] : array();
$options = array_merge(array(
	'layouts' => true,
	'categories' => true,
	'routes' => true,
	'modules' => true,
	'locks' => true,
	'trash' => true,
	'views' => true
), $options);

$links['config'] = array();

if ($options['layouts']) {
	$icon = array(
		'name' => 'Layout',
		'description' => 'Manage your content layouts',
		'icon' => 'table',
		'dashboard' => array('plugin' => 'contents', 'controller' => 'global_layouts', 'action' => 'index')
	);
	if ($this->request->plugin != 'contents') {
		$icon['GlobalLayout.model'] = $this->request->plugin;
	}
	$links['config'][] = $icon;
}

if ($options['categories']) {
	$icon = array(
		'name' => 'Contents',
		'description' => 'Manage the contents on your site',
		'icon' => 'book',
		'dashboard' => array('plugin' => 'contents', 'controller' => 'global_categories', 'action' => 'index')
	);
	if ($this->request->plugin != 'contents') {
		$icon['GlobalLayout.model'] = $this->request->plugin;
	}
	$links['config'][] = $icon;
}

if ($options['routes']) {
	$icon = array(
		'name' => __d('routes', 'Routes'),
		'description' => __d('routes', 'Manage content routes'),
		'icon' => 'road',
		'dashboard' => array('plugin' => 'routes', 'controller' => 'routes', 'action' => 'index')
	);

	if ($this->request->plugin != 'contents') {
		$icon['Route.plugin'] = $this->request->plugin;
	}
	$links['config'][] = $icon;
}

if ($options['modules']) {
	$icon = array(
		'name' => __d('modules', 'Modules'),
		'description' => __d('modules', 'Manage content modules'),
		'icon' => 'list-alt',
		'dashboard' => array('plugin' => 'modules', 'controller' => 'modules', 'action' => 'index')
	);

	if ($this->request->plugin != 'contents') {
		$icon['Module.plugin'] = $this->request->plugin;
	}
	$links['config'][] = $icon;
}

if ($options['locks']) {
	$icon = array(
		'name' => __d('locks', 'Locks'),
		'description' => __d('locks', 'Stop others editing things you are working on'),
		'icon' => 'locked',
		'dashboard' => array('plugin' => 'locks', 'controller' => 'locks', 'action' => 'index')
	);

	if ($this->request->plugin != 'contents') {
		$icon['Lock.class'] = $this->request->plugin;
	}
	$links['config'][] = $icon;
}

if ($options['trash']) {
	$icon = array(
		'name' => __d('trash', 'Trash'),
		'description' => __d('trash', 'Manage the deleted content'),
		'icon' => 'trash',
		'dashboard' => array('plugin' => 'trash', 'controller' => 'trash', 'action' => 'index')
	);

	if ($this->request->plugin != 'contents') {
		$icon['Trash.model'] = $this->request->plugin;
	}
	$links['config'][] = $icon;
}

if ($options['views'] && InfinitasPlugin::loaded('ViewCounter')) {
	$icon = array(
		'name' => __d('view_counter', 'Views'),
		'description' => __d('view_counter', 'Track content popularity'),
		'icon' => 'eye-open',
		'dashboard' => array('plugin' => 'view_counter', 'controller' => 'view_counter_views', 'action' => 'reports')
	);

	if ($this->request->plugin != 'contents') {
		$icon['ViewCounterView.model'] = $this->request->plugin;
	}
	$links['config'][] = $icon;
}

if (empty($links['config'])) {
	return;
}

$links = current($this->Menu->builDashboardLinks($links['config'], Inflector::underscore($this->request->plugin) . '_config_icons'));

echo $this->Design->dashboard($this->Design->arrayToList($links, 'icons'), __d('contents', 'Config / Data'), array(
	'class' => 'dashboard span6',
	'info' => Configure::read(Inflector::camelize($this->request->plugin) . '.info.config')
));