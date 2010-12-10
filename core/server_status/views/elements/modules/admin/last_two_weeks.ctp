<div class="dashboard half">
	<?php
		echo sprintf(
			__('<h1>%s<small>Data between %s and %s</small></h1>', true),
			__('Load for the last two weeks', true),
			$this->Time->niceShort($lastTwoWeeks['start_date']),
			$this->Time->niceShort($lastTwoWeeks['end_date'])
		);
		
		if(empty($lastTwoWeeks['totals'])){
			echo $this->ViewCounter->noData();
		}
		
		else{
			echo $this->Chart->display(
				'line',
				array(
					'data' => $lastTwoWeeks['totals'],
					'labels' => $lastTwoWeeks['days'],
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
