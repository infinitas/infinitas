<div class="dashboard half">
	<?php
		echo $this->ViewCounter->header('hour_on_hour', $hourOnHour);
		if(count($hourOnHour['hours']) > 1){
			echo $this->Chart->display(
				array(
					'name' => 'bar',
					'type' => 'vertical',
					'bar' => 'vertical'
				),
				array(
					'data' => $hourOnHour['totals'],
					'labels' => $hourOnHour['hours'],
					'axis_type' => array('x', 'y'),
					'size' => '450,130',
					'spacing' => array(11, 6),
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