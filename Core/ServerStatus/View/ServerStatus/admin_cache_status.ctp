<div class="dashboard grid_8">
	<h1><?php echo __d('server_status', 'Cache Status'); ?></h1>
	<table class="listing" cellpadding="0" cellspacing="0">
		<thead>
			<tr>
				<th><?php echo __d('server_status', 'Section'); ?></th>
				<th><?php echo __d('server_status', 'Used'); ?></th>
				<th><?php echo __d('server_status', 'Total'); ?></th>
				<th><?php echo __d('server_status', 'Available'); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php
				$total = 0;
				foreach($cacheStatus as $type => $amount) { ?>
					<tr>
						<td>
							<?php
								$type = __d('server_status', Inflector::humanize($type));
								switch(strtolower($type)) {
									case 'apc':
										$type = $this->Html->link(
											$type,
											array(
												'plugin' => 'server_status',
												'controller' => 'php',
												'action' => 'apc'
											)
										);
										break;
								}
								echo $type;
							?>
						</td>
						<td><?php echo convert($amount['used']); ?></td>
						<td><?php echo convert($amount['total']); ?></td>
						<td><?php echo convert($amount['available']); ?></td>
					</tr><?php
					$total += $amount['used'];
				}
			?>
			<tr>
				<td><?php echo __d('server_status', 'Total Cache'); ?></td>
				<td colspan="100"><?php echo convert($total); ?></td>
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