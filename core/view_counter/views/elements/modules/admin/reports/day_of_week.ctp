<div class="dashboard half">
	<?php
		echo $this->ViewCounter->header('day_of_week', $dayOfWeek);
		if(count($dayOfWeek['day_of_weeks']) > 1){
			echo $this->Chart->display(
				'line',
				array(
					'data' => $dayOfWeek['totals'],
					'labels' => $dayOfWeek['day_of_weeks'],
					'axis_type' => array('x', 'y'),
					'size' => '450,130',
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