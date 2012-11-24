<?php
if (!$viewCount) {
	echo $this->Design->dashboard('', __d('view_counter', 'You have not recorded any views yet'), array(
		'alert' => __d('view_counter', 'Once your site is live you will be able to track site stats from here')
	));
	return;
}

$links = array();
$links['main'] = array(
	array(
		'name' => __d('view_counter', 'Report'),
		'description' => __d('view_counter', 'See what content is popular'),
		'icon' => '/view_counter/img/icon.png',
		'dashboard' => array('controller' => 'view_counter_views', 'action' => 'reports')
	),
	array(
		'name' => __d('view_counter', 'Referers'),
		'description' => __d('view_counter', 'Track where traffic is from'),
		'icon' => '/view_counter/img/referer.png',
		'dashboard' => array('controller' => 'view_counter_views', 'action' => 'referers')
	),
);

$links['main'] = current((array)$this->Menu->builDashboardLinks($links['main'], 'view_counter_dashboard'));
echo $this->Design->dashboard($this->Design->arrayToList($links['main'], 'icons'), __d('view_counter', 'View Stats'), array(
	'class' => 'dashboard span6',
));

echo $this->ModuleLoader->loadDirect('ViewCounter.popular_items');