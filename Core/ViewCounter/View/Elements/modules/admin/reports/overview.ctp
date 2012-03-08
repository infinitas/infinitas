<div class="dashboard half">
	<?php
		echo $this->ViewCounter->header('overview', $overview);
		if(empty($overview)){
			echo $this->ViewCounter->noData();
		}
		else{
			$this->Charts->draw(
				'map',
				array(
					'data' => array_fill(0, count($overview['unique_views']['country_codes']), 1),
					'axes' => array(
						'x' => $overview['unique_views']['country_codes'],
						'y' => true
					),
					'size' => '600,370',
					'colors' => array(
						'DBDBDB', // background
						'BFF7AA', // from
						'1A5903'  // to
					)
				)
			);
			$uniqueVisitMap = $this->Chart->output;

			if(count($overview['new_visitors']['country_codes'])) {
				$this->Charts->draw(
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
			}
			$newVisitorMap = $this->Charts->output;
			?>
				<table class="listing">
					<thead>
						<tr>
							<th colspan="3"><?php echo __('Visitor Statistics'); ?></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><?php echo __('Total Visitors'); ?></td>
							<td class="right"><?php echo $overview['total_views']; ?>&nbsp</td>
							<td class="right" style="width:50px;" title="<?php echo __('% of total'); ?>"><?php echo $this->Number->toPercentage($overview['total_ratio']); ?>&nbsp</td>
						</tr>
						<tr>
							<td>
								<?php
									echo $this->Html->link(
										__('Unique Visits'),
										$uniqueVisitMap . '&TB_iframe=true',
										array(
											'class' => 'thickbox',
											'title' => __('Unique Visits by region')
										)
									);
								?>
							</td>
							<td class="right"><?php echo $overview['unique_views']['total_views']; ?>&nbsp</td>
							<td class="right" title="<?php echo __('% of visits'); ?>"><?php echo $this->Number->toPercentage($overview['unique_views']['ratio']); ?>&nbsp</td>
						</tr>
						<tr>
							<td><?php echo __('Average per Visitor'); ?></td>
							<td class="right"><?php echo round($overview['unique_views']['views_per_visit'], 2); ?>&nbsp</td>
							<td>&nbsp</td>
						</tr>
						<tr>
							<td>
								<?php
									echo $this->Html->link(
										__('New Visitors'),
										$newVisitorMap . '&TB_iframe=true',
										array(
											'class' => 'thickbox',
											'title' => __('New Visitors by region')
										)
									);
								?>
							</td>
							<td class="right"><?php echo $overview['new_visitors']['total_views']; ?>&nbsp</td>
							<td class="right" title="<?php echo __('% of visitors'); ?>"><?php echo $this->Number->toPercentage($overview['new_visitors']['ratio']); ?>&nbsp</td>
						</tr>
						<tr>
							<td><?php echo __('Average per Visitor'); ?></td>
							<td class="right"><?php echo round($overview['new_visitors']['views_per_visit'], 2); ?>&nbsp</td>
							<td>&nbsp</td>
						</tr>
						<tr>
							<td><?php echo __('Registered Users'); ?></td>
							<td class="right"><?php echo $overview['visitor_type']['registered']; ?>&nbsp</td>
							<td class="right"><?php echo $this->Number->toPercentage($overview['visitor_type']['registered_percentage']); ?>&nbsp</td>
						</tr>
						<tr>
							<td><?php echo __('Public Users'); ?></td>
							<td class="right"><?php echo $overview['visitor_type']['public']; ?>&nbsp</td>
							<td class="right"><?php echo $this->Number->toPercentage($overview['visitor_type']['public_percentage']); ?></td>
						</tr>
					</tbody>
				</table>
			<?php
		}
	?>
</div>
