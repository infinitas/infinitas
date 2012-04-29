<?php
	if(!isset($allTime) || empty($allTime)) {
		return false;
	}
?>
<div class="dashboard half">
	<?php
		echo sprintf(__('<h1>%s</h1>'), __('Overall usage statistics'));
	?>
	<table class="listing" style="margin: auto; width: 96%; background: none;">
		<thead>
			<tr>
				<th colspan="3"><?php echo __('Server Loads'); ?></th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<th rowspan="2"><?php echo __('Memory'); ?>&nbsp</th>
				<td><?php echo __('Agerage'); ?>&nbsp</td>
				<td class="right"><?php echo convert($allTime['average_memory']); ?>&nbsp</td>
			</tr>
			<tr>
				<td><?php echo __('Max'); ?>&nbsp</td>
				<td class="right"><?php echo convert($allTime['max_memory']); ?>&nbsp</td>
			</tr>
			<tr>
				<th rowspan="2"><?php echo __('Load'); ?>&nbsp</th>
				<td><?php echo __('Agerage'); ?>&nbsp</td>
				<td class="right"><?php echo $allTime['average_load']; ?>&nbsp</td>
			</tr>
			<tr>
				<td><?php echo __('Max'); ?>&nbsp</td>
				<td class="right"><?php echo $allTime['max_load']; ?>&nbsp</td>
			</tr>
		</tbody>
	</table>
</div>