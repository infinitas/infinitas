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
	
	$reportIcons = array(
		array(
			'name' => 'Issues',
			'description' => 'Find out what content is missing meta data',
			'icon' => '/contents/img/report-missing.png',
			'dashboard' => array(
				'plugin' => 'contents',
				'controller' => 'global_contents',
				'action' => 'content_issues'
			)
		)
	);
	
	$dashboardIcons = $this->Menu->builDashboardLinks($dashboardIcons, 'contents_dashboard_icon');
	$reportIcons = $this->Menu->builDashboardLinks($reportIcons, 'contents_reports_icon');
?>
<div class="dashboard grid_8 half">
	<h1><?php echo __('Content Management'); ?></h1>
	<?php echo $this->Design->arrayToList(current((array)$dashboardIcons), 'icons'); ?>
</div>
<div class="dashboard grid_8 half">
	<h1><?php echo __('Reports'); ?></h1>
	<?php echo $this->Design->arrayToList(current((array)$reportIcons), 'icons'); ?>
</div>
<?php
	echo $this->ModuleLoader->loadDirect('Contents.new_content');