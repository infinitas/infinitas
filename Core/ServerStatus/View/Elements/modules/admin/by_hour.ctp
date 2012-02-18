<div class="dashboard grid_16">
	<?php
		if(empty($byHour)) {
			$byHour = ClassRegistry::init('ServerStatus.ServerStatus')->reportByHour();
		}
		
		echo sprintf(
			__('<h1>Server load average by hour<small>Data between %s and %s</small></h1>'),
			$this->Time->niceShort($byHour['start_date']),
			$this->Time->niceShort($byHour['end_date'])
		);

		if(empty($byHour['hour'])){
			echo $this->ViewCounter->noData();
		}
		
		else{
			echo $this->Charts->draw(
				'line',
				array(
					'data' => array($byHour['max_load'], $byHour['average_load']),
					'axes' => array('x' => $byHour['hour'], 'y' => true),
					'size' => array('width' => 930,'height' => 130),
					'color' => array('series' => array('0d5c05', '03348a')),
					'extra' => array('html' => array('class' => 'chart'), 'scale' => 'relative'),
					'legend' => array(
						'position' => 'top',
						'labels' => array(
							__('Max Load'),
							__('Ave Load')
						)
					),
				)
			);
		}
	?>
</div>
