<div class="dashboard grid_16">
	<?php
		echo $this->ViewCounter->header('day_of_month', $byDay);
		if(empty($byDay['totals'])){
			echo $this->ViewCounter->noData();
		}
		else{
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
	?>
</div>
