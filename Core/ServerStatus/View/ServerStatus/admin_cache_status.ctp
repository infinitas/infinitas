<div class="dashboard grid_8">
	<h1><?php echo __d('server_status', 'Cache Status'); ?></h1>
	<table class="listing" cellpadding="0" cellspacing="0">
		<thead>
			<tr>
				<th><?php echo __d('server_status', 'Section'); ?></th>
				<th><?php echo __d('server_status', 'Size'); ?></th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>123</td>
				<td>123</td>
			</tr>
		</tbody>
	</table>
</div>
<?php
	$icons = array(
		array(
			'name' => 'Cache',
			'description' => 'Clear all cached data',
			'icon' => '/server_status/img/cache.png',
			'author' => 'Infinitas',
			'dashboard' => array('plugin' => 'server_status', 'controller' => 'server_status', 'action' => 'cache_status'),
		)
	)
?>
<div class="dashboard grid_8">
	<h1><?php echo __d('server_status', 'Cache Options'); ?></h1>
	<ul class="icons"><li><?php echo implode('</li><li>', current((array)$this->Menu->builDashboardLinks($icons, 'cache_status'))); ?></li></ul>
</div>