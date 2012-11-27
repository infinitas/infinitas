<?php
$dashboardIcons = array(
	array(
		'name' => 'Layout',
		'description' => 'Manage your content layouts',
		'icon' => 'table',
		'dashboard' => array(
			'controller' => 'global_layouts',
			'action' => 'index'
		)
	),
	array(
		'name' => 'Contents',
		'description' => 'Manage the contents on your site',
		'icon' => 'book',
		'dashboard' => array(
			'controller' => 'global_contents',
			'action' => 'index'
		)
	),
	array(
		'name' => 'Categories',
		'description' => 'Manage the categories for your content',
		'icon' => 'folder-open',
		'dashboard' => array(
			'controller' => 'global_categories',
			'action' => 'index'
		)
	),
	array(
		'name' => 'Tags',
		'description' => 'Manage the tags for your content',
		'icon' => 'tags',
		'dashboard' => array(
			'controller' => 'global_tags',
			'action' => 'index'
		)
	),
	array(
		'name' => 'Static',
		'description' => 'Create and manage static content for your site',
		'icon' => 'paste',
		'dashboard' => array(
			'controller' => 'global_pages',
			'action' => 'index'
		)
	),
	array(
		'name' => 'Locks',
		'description' => 'Stop others editing things you are working on',
		'icon' => 'locked',
		'author' => 'Infinitas',
		'dashboard' => array('plugin' => 'locks', 'controller' => 'locks', 'action' => 'index')
	),
	array(
		'name' => 'Trash',
		'description' => 'Manage the deleted content',
		'icon' => 'trash',
		'author' => 'Infinitas',
		'dashboard' => array('plugin' => 'trash', 'controller' => 'trash', 'action' => 'index')
	),
);

$reportIcons = array(
	array(
		'name' => 'Issues',
		'description' => 'Find out what content is missing meta data',
		'icon' => 'minus-sign',
		'dashboard' => array(
			'controller' => 'global_contents',
			'action' => 'content_issues'
		)
	)
);

$dashboardIcons = current((array)$this->Menu->builDashboardLinks($dashboardIcons, 'contents_dashboard_icon'));
$reportIcons = current((array)$this->Menu->builDashboardLinks($reportIcons, 'contents_reports_icon'));

echo $this->Design->dashboard($this->Design->arrayToList($dashboardIcons, array('ul' => 'icons')), __d('contents', 'Content Management'), array(
	'class' => 'dashboard span6',
	'info' => Configure::read('Contents.info.contents')
));
echo $this->Design->dashboard($this->Design->arrayToList($reportIcons, array('ul' => 'icons')), __d('contents', 'Reports'), array(
	'class' => 'dashboard span6',
	'info' => Configure::read('Contents.info.reports')
));

echo $this->ModuleLoader->loadDirect('Contents.new_content');