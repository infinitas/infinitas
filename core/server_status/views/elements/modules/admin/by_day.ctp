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
			echo $this->Charts->draw(
				'line',
				array(
					'data' => $byDay['totals'],
					'axes' => array(
						'x' => $byDay['days'],
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
