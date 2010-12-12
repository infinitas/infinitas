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
			echo $this->Charts->draw(
				'line',
				array(
					'data' => $byHour['totals'],
					'axes' => array(
						'x' => $byHour['hours'],
						'y' => true
					),
					'size' => array(
						'width' => 930,
						'height' => 130
					),
					'extra' => array(
						'class' => 'chart',
						'scale' => 'relative'
					)
				)
			);
		}
	?>
</div>
