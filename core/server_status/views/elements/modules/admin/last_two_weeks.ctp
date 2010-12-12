<div class="dashboard half">
	<?php
		echo sprintf(
			__('<h1>Last two weeks<small>Data between %s and %s</small></h1>', true),
			$this->Time->niceShort($lastTwoWeeks['start_date']),
			$this->Time->niceShort($lastTwoWeeks['end_date'])
		);
		
		if(empty($lastTwoWeeks['day'])){
			echo $this->ViewCounter->noData();
		}
		
		else{
			echo $this->Charts->draw(
				'line',
				array(
					'data' => array($lastTwoWeeks['max_load'], $lastTwoWeeks['average_load']),
					'axes' => array('x' => $lastTwoWeeks['day'], 'y' => true),
					'size' => array('width' => 450, 'height' => 130),
					'color' => array('series' => array('0d5c05', '03348a')),
					'extra' => array('scale' => 'relative', 'html' => array('class' => 'chart')),
					'legend' => array(
						'position' => 'top',
						'order' => 'default',
						'labels' => array(
							__('Max Load', true),
							__('Ave Load', true)
						)
					)
				)
			);
		}
	?>
</div>
