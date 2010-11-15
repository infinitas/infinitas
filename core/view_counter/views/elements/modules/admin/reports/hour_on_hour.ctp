<div class="dashboard half">
	<?php
		echo $this->ViewCounter->header('hour_on_hour', $hourOnHour);
		if(empty($hourOnHour['totals'])){
			echo $this->ViewCounter->noData();
		}
		else{
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
	?>
</div>
