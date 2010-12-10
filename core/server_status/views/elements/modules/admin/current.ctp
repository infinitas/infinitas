<div class="dashboard half" style="height: 645px;">
	<?php
		echo sprintf(
			__('<h1>%s<small>%s<br/>%s</small></h1>', true),
			$current['Server']['name'],
			$current['Server']['version'],
			$current['Server']['release']
		);
		
		$plugins = App::objects('plugin');
	?>
	<table class="listing" style="margin: auto; width: 96%; background: none;">
		<thead>
			<tr>
				<th colspan="3"><?php __('Server Details'); ?></th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<th rowspan="3"><?php __('Php'); ?>&nbsp</th>
				<td><?php __('version'); ?>&nbsp</td>
				<td class="right"><?php echo $current['Php']['version']; ?>&nbsp</td>
			</tr>
			<tr>
				<td><?php echo __('Server API'); ?>&nbsp</td>
				<td class="right"><?php echo $current['Php']['sapi']; ?>&nbsp</td>
			</tr>
			<tr>
				<td><?php echo __('Memory Limit'); ?>&nbsp</td>
				<td class="right"><?php echo $current['Php']['memory_limit']; ?>&nbsp</td>
			</tr>
			<tr>
				<th rowspan="3"><?php __('Infinitas'); ?>&nbsp</th>
				<td><?php __('version'); ?>&nbsp</td>
				<td class="right"><?php echo Configure::read('Infinitas.version'); ?>&nbsp</td>
			</tr>
			<tr>
				<td><?php echo $this->Html->link(__('Plugins', true), array('plugin' => 'installer', 'controller' => 'plugins', 'action' => 'index')); ?>&nbsp</td>
				<td class="right"><?php echo count($plugins); ?>&nbsp</td>
			</tr>
			<tr>
				<td colspan="2"><?php echo implode(', ', $plugins); ?>&nbsp</td>
			</tr>
			<tr>
				<th rowspan="3"><?php __('Load'); ?>&nbsp</th>
				<td><?php __('1 Minute'); ?>&nbsp</td>
				<td class="right"><?php echo $current['Load']['1 min']; ?>&nbsp</td>
			</tr>
			<tr>
				<td><?php __('5 Minute'); ?>&nbsp</td>
				<td class="right"><?php echo $current['Load']['5 min']; ?>&nbsp</td>
			</tr>
			<tr>
				<td><?php __('11 Minute'); ?>&nbsp</td>
				<td class="right"><?php echo $current['Load']['15 min']; ?>&nbsp</td>
			</tr>
		</tbody>
	</table>
</div>