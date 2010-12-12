<div class="dashboard half">
	<?php
		echo sprintf(
			__('<h1>%s<small>Data between %s and %s</small></h1>', true),
			__('Load for the last two weeks', true),
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
					'data' => array(
						$lastTwoWeeks['max_load'],
						$lastTwoWeeks['ave_load']
					),
					'axes' => array(
						'x' => $lastTwoWeeks['day'],
						'y' => true
					),
					'size' => array(
						'width' => 450,
						'height' => 130
					),
					'extra' => array(
						'scale' => 'relative',
						'class' => 'chart'
					)
				)
			);
		}
	?>
</div>
