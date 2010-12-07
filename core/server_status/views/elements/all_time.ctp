<div class="dashboard half">
	<?php
		echo sprintf(__('<h1>%s</h1>', true), __('Overall usage statistics', true));
	?>
	<table class="listing" style="margin: auto; width: 96%; background: none;">
		<thead>
			<tr>
				<th colspan="3"><?php __('Server Loads'); ?></th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<th rowspan="2"><?php __('Memory'); ?>&nbsp</th>
				<td><?php __('Agerage'); ?>&nbsp</td>
				<td class="right"><?php echo convert($allTime['average_memory']); ?>&nbsp</td>
			</tr>
			<tr>
				<td><?php echo __('Max'); ?>&nbsp</td>
				<td class="right"><?php echo convert($allTime['max_memory']); ?>&nbsp</td>
			</tr>
			<tr>
				<th rowspan="2"><?php __('Load'); ?>&nbsp</th>
				<td><?php __('Agerage'); ?>&nbsp</td>
				<td class="right"><?php echo $allTime['average_load']; ?>&nbsp</td>
			</tr>
			<tr>
				<td><?php echo __('Max'); ?>&nbsp</td>
				<td class="right"><?php echo $allTime['max_load']; ?>&nbsp</td>
			</tr>
		</tbody>
	</table>
</div>