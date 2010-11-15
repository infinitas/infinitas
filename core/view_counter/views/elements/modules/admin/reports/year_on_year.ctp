<div class="dashboard half">
	<?php
		echo $this->ViewCounter->header('year_on_year', $yearOnYear);
		if(empty($yearOnYear['totals'])){
			echo $this->ViewCounter->noData();
		}
		else{
			echo $this->Chart->display(
				'pie3d',
				array(
					'data' => $yearOnYear['totals'],
					'labels' => $yearOnYear['years'],
					'axis_type' => array('x', 'y'),
					'size' => '450,130',
					'spacing' => array(26, 25),
					'html' => array(
						'class' => 'chart'
					)
				)
			);
		}
	?>
</div>
