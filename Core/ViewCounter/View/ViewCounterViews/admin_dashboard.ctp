<?php
	if(!$viewCount) { ?>
		<div class="dashboard grid_16">
			<h1><?php echo __d('view_counter', 'You have not recorded any views yet'); ?></h1>
			<p class="info">
				<?php echo __d('view_counter', 'Once your site is live you will be able to track site stats from here'); ?>
			</p>
		</div> <?php
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
?>
<div class="dashboard grid_16">
	<h1><?php echo __d('view_counter', 'View Stats'); ?></h1>
	<?php echo $this->Design->arrayToList(current($this->Menu->builDashboardLinks($links['main'], 'blog_main_icons')), 'icons'); ?>
</div>
<?php
	echo $this->ModuleLoader->loadDirect('ViewCounter.popular_items');