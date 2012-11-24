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

if($options['layouts']) {
	$links['config'][] = array(
		'name' => __d('contents', 'Layouts'),
		'description' => __d('blog', 'Configure the layouts for your content'),
		'icon' => '/contents/img/icon.png',
		'dashboard' => array('plugin' => 'contents', 'controller' => 'global_layouts', 'action' => 'index', 'GlobalLayout.model' => $this->request->plugin)
	);
}

if($options['categories']) {
	$links['config'][] = array(
		'name' => __d('contents', 'Categories'),
		'description' => __d('contents', 'Configure the categories for your content'),
		'icon' => '/contents/img/categories.png',
		'dashboard' => array('plugin' => 'contents', 'controller' => 'global_categories', 'action' => 'index', 'GlobalLayout.model' => $this->request->plugin)
	);
}

if($options['routes']) {
	$links['config'][] = array(
		'name' => __d('routes', 'Routes'),
		'description' => __d('blog', 'Manage content routes'),
		'icon' => '/routes/img/icon.png',
		'dashboard' => array('plugin' => 'routes', 'controller' => 'routes', 'action' => 'index', 'Route.plugin' => $this->request->plugin)
	);
}

if($options['modules']) {
	$links['config'][] = array(
		'name' => __d('modules', 'Modules'),
		'description' => __d('blog', 'Manage content modules'),
		'icon' => '/modules/img/icon.png',
		'dashboard' => array('plugin' => 'modules', 'controller' => 'modules', 'action' => 'index', 'Module.plugin' => $this->request->plugin)
	);
}

if($options['locks']) {
	$links['config'][] = array(
		'name' => __d('locks', 'Locked'),
		'description' => __d('blog', 'Manage locked content'),
		'icon' => '/locks/img/icon.png',
		'dashboard' => array('plugin' => 'locks', 'controller' => 'locks', 'action' => 'index', 'Lock.class' => $this->request->plugin)
	);
}

if($options['trash']) {
	$links['config'][] = array(
		'name' => __d('trash', 'Trash'),
		'description' => __d('blog', 'View / Restore previously removed content'),
		'icon' => '/trash/img/icon.png',
		'dashboard' => array('plugin' => 'trash', 'controller' => 'trash', 'action' => 'index', 'Trash.model' => $this->request->plugin)
	);
}

if($options['views'] && InfinitasPlugin::loaded('ViewCounter')) {
	$links['config'][] =  array(
		'name' => __d('view_counter', 'Views'),
		'description' => __d('blog', 'Track content popularity'),
		'icon' => '/view_counter/img/icon.png',
		'dashboard' => array('plugin' => 'view_counter', 'controller' => 'view_counter_views', 'action' => 'reports', 'ViewCounterView.model' => $this->request->plugin)
	);
}

if(empty($links['config'])) {
	return;
}

$links = current($this->Menu->builDashboardLinks($links['config'], Inflector::underscore($this->request->plugin) . '_config_icons'));

echo $this->Design->dashboard($this->Design->arrayToList($links, 'icons'), __d('contents', 'Config / Data'), array(
	'class' => 'dashboard span6',
	'info' => Configure::read(Inflector::camelize($this->request->plugin) . '.info.config')
));