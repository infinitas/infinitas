<?php
	$icons = array();
	foreach($models as $model){
		$plugin = pluginSplit($model['ViewCount']['model']);
		$icons[] = array(
			'name' => __(Inflector::pluralize(implode(' ', $plugin)), true),
			'description' => sprintf('%s views in total', $model['ViewCount']['sub_total']),
			'icon' => '/' . $plugin[0] . '/img/icon.png',
			'dashboard' => array(
				'action' => 'index',
				'ViewCount.model' => $model['ViewCount']['model']
			)
		);
	}
	$icons = $this->Menu->builDashboardLinks($icons, 'view_counts_totals');
?>
<div class="dashboard grid_16">
	<h1><?php echo __('Totals per model', true); ?></h1>
	<ul class="icons"><li><?php echo implode('</li><li>', current((array)$icons)); ?></li></ul>
</div>