<div class="dashboard grid_16">
	<?php
		echo sprintf(
			__('<h1>%s<small>Data between %s and %s</small></h1>', true),
			__('Server load average by day', true),
			$this->Time->niceShort($byDay['start_date']),
			$this->Time->niceShort($byDay['end_date'])
		);
		
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
