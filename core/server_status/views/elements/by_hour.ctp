<div class="dashboard grid_16">
	<?php
		echo sprintf(
			__('<h1>%s<small>Data between %s and %s</small></h1>', true),
			__('Server load average by hour', true),
			$this->Time->niceShort($byHour['start_date']),
			$this->Time->niceShort($byHour['end_date'])
		);

		if(empty($byHour['totals'])){
			echo $this->ViewCounter->noData();
		}
		
		else{
			echo $this->Chart->display(
				'line',
				array(
					'data' => $byHour['totals'],
					'labels' => $byHour['hours'],
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
