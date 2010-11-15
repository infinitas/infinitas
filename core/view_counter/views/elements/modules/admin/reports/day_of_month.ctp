<div class="dashboard grid_16">
	<?php
		echo $this->ViewCounter->header('day_of_month', $byDay);
		if(count($byDay['days']) > 1){
			echo $this->Chart->display(
				'line',
				array(
					'data' => $byDay['totals'],
					'labels' => $byDay['days'],
					'axis_type' => array('x', 'y'),
					'size' => '930,130',
					'html' => array(
						'class' => 'chart'
					)
				)
			);
		}
		else{
			?><span class="chart"><?php echo __('Not enough data collected', true); ?></span><?php
		}
	?>
</div>