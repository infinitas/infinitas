<div class="dashboard half">
	<?php
		echo $this->ViewCounter->header('overview', $overview);
		if(empty($overview)){
			echo $this->ViewCounter->noData();
		}
		else{
			$this->Chart->display(
				'map',
				array(
					'data' => array_fill(0, count($overview['unique_views']['country_codes']), 1),
					'places' => $overview['unique_views']['country_codes'],
					'size' => '600,370',
					'colors' => array(
						'DBDBDB', // background
						'BFF7AA', // from
						'1A5903'  // to
					)
				)
			);
			$uniqueVisitMap = $this->Chart->output;

			$this->Chart->display(
				'map',
				array(
					'data' => array_fill(0, count($overview['new_visitors']['country_codes']), 1),
					'places' => $overview['new_visitors']['country_codes'],
					'size' => '600,370',
					'colors' => array(
						'DBDBDB', // background
						'BFF7AA', // from
						'1A5903'  // to
					)
				)
			);
			$newVisitorMap = $this->Chart->output;
			?>
				<table class="listing">
					<thead>
						<tr>
							<th colspan="3"><?php __('Visitor Statistics'); ?></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><?php __('Total Visitors'); ?></td>
							<td class="right"><?php echo $overview['total_views']; ?>&nbsp</td>
							<td class="right" style="width:50px;" title="<?php __('% of total'); ?>"><?php echo $this->Number->toPercentage($overview['total_ratio']); ?>&nbsp</td>
						</tr>
						<tr>
							<td>
								<?php
									echo $this->Html->link(
										__('Unique Visits', true),
										$uniqueVisitMap . '&TB_iframe=true',
										array(
											'class' => 'thickbox',
											'title' => __('Unique Visits by region', true)
										)
									);
								?>
							</td>
							<td class="right"><?php echo $overview['unique_views']['total_views']; ?>&nbsp</td>
							<td class="right" title="<?php __('% of visits'); ?>"><?php echo $this->Number->toPercentage($overview['unique_views']['ratio']); ?>&nbsp</td>
						</tr>
						<tr>
							<td><?php __('Average per Visitor'); ?></td>
							<td class="right"><?php echo round($overview['unique_views']['views_per_visit'], 2); ?>&nbsp</td>
							<td>&nbsp</td>
						</tr>
						<tr>
							<td>
								<?php
									echo $this->Html->link(
										__('New Visitors', true),
										$newVisitorMap . '&TB_iframe=true',
										array(
											'class' => 'thickbox',
											'title' => __('New Visitors by region', true)
										)
									);
								?>
							</td>
							<td class="right"><?php echo $overview['new_visitors']['total_views']; ?>&nbsp</td>
							<td class="right" title="<?php __('% of visitors'); ?>"><?php echo $this->Number->toPercentage($overview['new_visitors']['ratio']); ?>&nbsp</td>
						</tr>
						<tr>
							<td><?php __('Average per Visitor'); ?></td>
							<td class="right"><?php echo round($overview['new_visitors']['views_per_visit'], 2); ?>&nbsp</td>
							<td>&nbsp</td>
						</tr>
						<tr>
							<td><?php __('Registered Users'); ?></td>
							<td class="right"><?php echo $overview['visitor_type']['registered']; ?>&nbsp</td>
							<td class="right"><?php echo $this->Number->toPercentage($overview['visitor_type']['registered_percentage']); ?>&nbsp</td>
						</tr>
						<tr>
							<td><?php __('Public Users'); ?></td>
							<td class="right"><?php echo $overview['visitor_type']['public']; ?>&nbsp</td>
							<td class="right"><?php echo $this->Number->toPercentage($overview['visitor_type']['public_percentage']); ?></td>
						</tr>
					</tbody>
				</table>
			<?php
		}
	?>
</div>
