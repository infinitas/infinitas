<div class="dashboard half">
	<?php
		echo sprintf(
			__('<h1>%s<small>Data between %s and %s</small></h1>', true),
			__('Load for the last six months', true),
			$this->Time->niceShort($lastSixMonths['start_date']),
			$this->Time->niceShort($lastSixMonths['end_date'])
		);
		
		if(empty($lastSixMonths['totals'])){
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
					'data' => $lastSixMonths['totals'],
					'labels' => $lastSixMonths['months'],
					'axis_type' => array('x', 'y'),
					'size' => '450,130',
					'html' => array(
						'class' => 'chart'
					)
				)
			);
		}
	?>
</div>
