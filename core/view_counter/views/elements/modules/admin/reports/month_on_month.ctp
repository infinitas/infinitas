<div class="dashboard half">
	<?php
		echo $this->ViewCounter->header('month_on_month', $monthOnMonth);
		if(empty($monthOnMonth['totals'])){
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
					'data' => $monthOnMonth['totals'],
					'labels' => $monthOnMonth['months'],
					'axis_type' => array('x', 'y'),
					'size' => '450,130',
					'spacing' => array(25, 9),
					'html' => array(
						'class' => 'chart'
					)
				)
			);
		}
	?>
</div>
