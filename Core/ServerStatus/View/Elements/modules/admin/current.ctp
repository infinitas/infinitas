<div class="page-header span6" style="height: 645px;">
	<?php
		echo sprintf(
			__d('server_status', '<h1>%s<small>%s<br/>%s</small></h1>'),
			$current['Server']['name'],
			$current['Server']['version'],
			$current['Server']['release']
		);

		$plugins = App::objects('plugin');
	?>
	<table class="listing" style="margin: auto; width: 96%; background: none;">
		<thead>
			<tr>
				<th colspan="3"><?php echo __d('server_status', 'Server Details'); ?></th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<th rowspan="3"><?php echo __d('server_status', 'Php'); ?>&nbsp</th>
				<td><?php echo __d('server_status', 'version'); ?>&nbsp</td>
				<td class="right"><?php echo $current['Php']['version']; ?>&nbsp</td>
			</tr>
			<tr>
				<td><?php echo __d('server_status', 'Server API'); ?>&nbsp</td>
				<td class="right"><?php echo $current['Php']['sapi']; ?>&nbsp</td>
			</tr>
			<tr>
				<td><?php echo __d('server_status', 'Memory Limit'); ?>&nbsp</td>
				<td class="right"><?php echo $current['Php']['memory_limit']; ?>&nbsp</td>
			</tr>
			<tr>
				<th rowspan="3"><?php echo __d('server_status', 'Infinitas'); ?>&nbsp</th>
				<td><?php echo __d('server_status', 'version'); ?>&nbsp</td>
				<td class="right"><?php echo Configure::read('Infinitas.version'); ?>&nbsp</td>
			</tr>
			<tr>
				<td><?php echo $this->Html->link(__d('server_status', 'Plugins'), array('plugin' => 'installer', 'controller' => 'plugins', 'action' => 'index')); ?>&nbsp</td>
				<td class="right"><?php echo count($plugins); ?>&nbsp</td>
			</tr>
			<tr>
				<td colspan="2"><?php echo implode(', ', $plugins); ?>&nbsp</td>
			</tr>
			<tr>
				<th rowspan="3"><?php echo __d('server_status', 'Load'); ?>&nbsp</th>
				<td><?php echo __d('server_status', '1 Minute'); ?>&nbsp</td>
				<td class="right"><?php echo $current['Load']['1 min']; ?>&nbsp</td>
			</tr>
			<tr>
				<td><?php echo __d('server_status', '5 Minute'); ?>&nbsp</td>
				<td class="right"><?php echo $current['Load']['5 min']; ?>&nbsp</td>
			</tr>
			<tr>
				<td><?php echo __d('server_status', '11 Minute'); ?>&nbsp</td>
				<td class="right"><?php echo $current['Load']['15 min']; ?>&nbsp</td>
			</tr>
		</tbody>
	</table>
	<?php
		echo $this->Charts->draw(
			'gauge',
			array(
				'data' => array(
					$current['Load']['1 min'] * 50
				),
				'normalize' => false,
				'axes' => array(
					'x' => array($current['Load']['1 min']),
					'y' => array(
						'0.0 Low',
						'Medium',
						'High 2.0'
					)
				),
				'color' => array(
					'series' => array(
						'00FF00',
						'FFFF00',
						'FF0000'
					)
				),
				'size' => array(
					'width' => 400,
					'height' => 180
				),
				'extra' => array(
					'class' => 'chart'
				)
			)
		);
	?>
</div>