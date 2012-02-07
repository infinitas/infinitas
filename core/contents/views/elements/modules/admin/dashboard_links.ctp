<?php
	$links['config'] = array(
		array(
			'name' => __d('contents', 'Layouts', true),
			'description' => __d('blog', 'Configure the layouts for your content', true),
			'icon' => '/contents/img/icon.png',
			'dashboard' => array('plugin' => 'contents', 'controller' => 'global_layouts', 'action' => 'index', 'GlobalLayout.model' => $this->params['plugin'])
		),
		array(
			'name' => __('Categories', true),
			'description' => __('Configure the categories for your content', true),
			'icon' => '/contents/img/categories.png',
			'dashboard' => array('plugin' => 'contents', 'controller' => 'global_categories', 'action' => 'index', 'GlobalLayout.model' => $this->params['plugin'])
		),
		array(
			'name' => __d('routes', 'Routes', true),
			'description' => __d('blog', 'Manage content routes', true),
			'icon' => '/routes/img/icon.png',
			'dashboard' => array('plugin' => 'routes', 'controller' => 'routes', 'action' => 'index', 'Route.plugin' => $this->params['plugin'])
		),
		array(
			'name' => __d('modules', 'Modules', true),
			'description' => __d('blog', 'Manage content modules', true),
			'icon' => '/modules/img/icon.png',
			'dashboard' => array('plugin' => 'modules', 'controller' => 'modules', 'action' => 'index', 'Module.plugin' => $this->params['plugin'])
		),
		array(
			'name' => __d('filemanager', 'Assets', true),
			'description' => __d('blog', 'Manage content assets', true),
			'icon' => '/filemanager/img/icon.png',
			'dashboard' => array('plugin' => 'filemanager', 'controller' => 'filemanager', 'action' => 'index', 'webroot', 'img')
		),
		array(
			'name' => __d('locks', 'Locked', true),
			'description' => __d('blog', 'Manage locked content', true),
			'icon' => '/locks/img/icon.png',
			'dashboard' => array('plugin' => 'locks', 'controller' => 'locks', 'action' => 'index', 'Lock.class' => $this->params['plugin'])
		),
		array(
			'name' => __d('trash', 'Trash', true),
			'description' => __d('blog', 'View / Restore previously removed content', true),
			'icon' => '/trash/img/icon.png',
			'dashboard' => array('plugin' => 'trash', 'controller' => 'trash', 'action' => 'index', 'Trash.model' => $this->params['plugin'])
		)
	);

	if($this->Infinitas->hasPlugin('ViewCounter')) {
		$links['config'][] =  array(
			'name' => __d('view_counter', 'Views', true),
			'description' => __d('blog', 'Track content popularity', true),
			'icon' => '/view_counter/img/icon.png',
			'dashboard' => array('plugin' => 'view_counter', 'controller' => 'view_counts', 'action' => 'reports', 'ViewCount.model' => $this->params['plugin'])
		);
	}

	$links = current($this->Menu->builDashboardLinks($links['config'], Inflector::underscore($this->params['plugin']) . '_config_icons'));
?>
<div class="dashboard grid_16">
	<h1><?php __d('contents', 'Config / Data'); ?></h1>
	<?php echo $this->Design->arrayToList($links, 'icons'); ?>
	<p class="info"><?php echo Configure::read(Inflector::camelize($this->params['plugin']) . '.info.config'); ?></p>
</div>