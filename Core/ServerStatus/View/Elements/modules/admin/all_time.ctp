<?php
	if(!isset($allTime) || empty($allTime)) {
		return false;
	}
?>
<div class="page-header span6">
	<?php
		echo sprintf(__d('server_status', '<h1>%s</h1>'), __d('server_status', 'Overall usage statistics'));
	?>
	<table class="listing" style="margin: auto; width: 96%; background: none;">
		<thead>
			<tr>
				<th colspan="3"><?php echo __d('server_status', 'Server Loads'); ?></th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<th rowspan="2"><?php echo __d('server_status', 'Memory'); ?>&nbsp</th>
				<td><?php echo __d('server_status', 'Average'); ?>&nbsp</td>
				<td class="right"><?php echo convert($allTime['average_memory']); ?>&nbsp</td>
			</tr>
			<tr>
				<td><?php echo __d('server_status', 'Max'); ?>&nbsp</td>
				<td class="right"><?php echo convert($allTime['max_memory']); ?>&nbsp</td>
			</tr>
			<tr>
				<th rowspan="2"><?php echo __d('server_status', 'Load'); ?>&nbsp</th>
				<td><?php echo __d('server_status', 'Agerage'); ?>&nbsp</td>
				<td class="right"><?php echo $allTime['average_load']; ?>&nbsp</td>
			</tr>
			<tr>
				<td><?php echo __d('server_status', 'Max'); ?>&nbsp</td>
				<td class="right"><?php echo $allTime['max_load']; ?>&nbsp</td>
			</tr>
		</tbody>
	</table>
</div>