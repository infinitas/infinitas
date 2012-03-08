<div class="dashboard half">
	<?php
		echo $this->ViewCounter->header('year_on_year', $yearOnYear);
		if(empty($yearOnYear['totals'])){
			echo $this->ViewCounter->noData();
		}
		else{
			echo $this->Charts->draw(
				array(
					'pie' => array(
						'type' => '3d'
					)
				),
				array(
					'data' => $yearOnYear['totals'],
					'axis_type' => array('x', 'y'),
					'size' => '450,130',
					'spacing' => array(26, 25),
					'html' => array(
						'class' => 'chart'
					),
					'color' => array(
						'series' => 'FFCC33,989898'
					),
					'legend' => array(
						'position' => 'right',
						'order' => 'default',
						'labels' => $yearOnYear['years']
					),
				)
			);
		}
	?>
</div>
