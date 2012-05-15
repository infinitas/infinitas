<?php
	$links['config'] = array(
		array(
			'name' => __d('contents', 'Layouts'),
			'description' => __d('blog', 'Configure the layouts for your content'),
			'icon' => '/contents/img/icon.png',
			'dashboard' => array('plugin' => 'contents', 'controller' => 'global_layouts', 'action' => 'index', 'GlobalLayout.model' => $this->request->plugin)
		),
		array(
			'name' => __('Categories'),
			'description' => __('Configure the categories for your content'),
			'icon' => '/contents/img/categories.png',
			'dashboard' => array('plugin' => 'contents', 'controller' => 'global_categories', 'action' => 'index', 'GlobalLayout.model' => $this->request->plugin)
		),
		array(
			'name' => __d('routes', 'Routes'),
			'description' => __d('blog', 'Manage content routes'),
			'icon' => '/routes/img/icon.png',
			'dashboard' => array('plugin' => 'routes', 'controller' => 'routes', 'action' => 'index', 'Route.plugin' => $this->request->plugin)
		),
		array(
			'name' => __d('modules', 'Modules'),
			'description' => __d('blog', 'Manage content modules'),
			'icon' => '/modules/img/icon.png',
			'dashboard' => array('plugin' => 'modules', 'controller' => 'modules', 'action' => 'index', 'Module.plugin' => $this->request->plugin)
		),
		array(
			'name' => __d('locks', 'Locked'),
			'description' => __d('blog', 'Manage locked content'),
			'icon' => '/locks/img/icon.png',
			'dashboard' => array('plugin' => 'locks', 'controller' => 'locks', 'action' => 'index', 'Lock.class' => $this->request->plugin)
		),
		array(
			'name' => __d('trash', 'Trash'),
			'description' => __d('blog', 'View / Restore previously removed content'),
			'icon' => '/trash/img/icon.png',
			'dashboard' => array('plugin' => 'trash', 'controller' => 'trash', 'action' => 'index', 'Trash.model' => $this->request->plugin)
		)
	);

	if($this->Infinitas->hasPlugin('ViewCounter')) {
		$links['config'][] =  array(
			'name' => __d('view_counter', 'Views'),
			'description' => __d('blog', 'Track content popularity'),
			'icon' => '/view_counter/img/icon.png',
			'dashboard' => array('plugin' => 'view_counter', 'controller' => 'view_counter_views', 'action' => 'reports', 'ViewCounterView.model' => $this->request->plugin)
		);
	}

	$links = current($this->Menu->builDashboardLinks($links['config'], Inflector::underscore($this->request->plugin) . '_config_icons'));
?>
<div class="dashboard grid_16">
	<h1><?php echo __d('contents', 'Config / Data'); ?></h1>
	<?php echo $this->Design->arrayToList($links, 'icons'); ?>
	<p class="info"><?php echo Configure::read(Inflector::camelize($this->request->plugin) . '.info.config'); ?></p>
</div>