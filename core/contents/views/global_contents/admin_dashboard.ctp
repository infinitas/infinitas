<?php
	$dashboardIcons = array(
		array(
			'name' => 'Layout',
			'description' => 'Manage your content layouts',
			'icon' => '/contents/img/layout.png',
			'dashboard' => array(
				'plugin' => 'contents',
				'controller' => 'global_layouts',
				'action' => 'index'
			)
		),
		array(
			'name' => 'Contents',
			'description' => 'Manage the contents on your site',
			'icon' => '/contents/img/contents.png',
			'dashboard' => array(
				'plugin' => 'contents',
				'controller' => 'global_contents',
				'action' => 'index'
			)
		),
		array(
			'name' => 'Categories',
			'description' => 'Manage the categories for your content',
			'icon' => '/contents/img/categories.png',
			'dashboard' => array(
				'plugin' => 'contents',
				'controller' => 'global_categories',
				'action' => 'index'
			)
		),
		array(
			'name' => 'Tags',
			'description' => 'Manage the tags for your content',
			'icon' => '/contents/img/tags.png',
			'dashboard' => array(
				'plugin' => 'contents',
				'controller' => 'global_tags',
				'action' => 'index'
			)
		)
	);
	$dashboardIcons = $this->Menu->builDashboardLinks($dashboardIcons, 'user_dashboard');
?>
<div class="dashboard grid_16">
	<h1><?php __('Email Manager', true); ?></h1>
	<ul class="icons"><li><?php echo implode('</li><li>', current((array)$dashboardIcons)); ?></li></ul>
</div>
<?php
	echo $this->element('modules/admin/new_content', array('plugin' => 'contents'));