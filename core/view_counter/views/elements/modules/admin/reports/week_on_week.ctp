<div class="dashboard grid_16">
	<?php
		echo $this->ViewCounter->header('week_on_week', $weekOnWeek);
		if(empty($weekOnWeek['totals'])){
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
					'data' => $weekOnWeek['totals'],
					'labels' => $weekOnWeek['week_of_years'],
					'axis_type' => array('x', 'y'),
					'size' => '930,130',
					'spacing' => array(11, 6),
					'html' => array(
						'class' => 'chart'
					)
				)
			);
		}
	?>
</div>
