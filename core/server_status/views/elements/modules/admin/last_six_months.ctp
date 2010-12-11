<div class="dashboard half">
	<?php
		echo sprintf(
			__('<h1>%s<small>Data between %s and %s</small></h1>', true),
			__('Load for the last six months', true),
			$this->Time->niceShort($lastSixMonths['average']['start_date']),
			$this->Time->niceShort($lastSixMonths['average']['end_date'])
		);
		
		if(empty($lastSixMonths['average']['totals'])){
			echo $this->ViewCounter->noData();
		}
		
		else{
			echo $this->Charts->draw(
				array(
					'bar' => array(
						'type' => 'vertical'
					)
				),
				array(
					'data' => $lastSixMonths['average']['totals'],
					'axes' => array(
						'x' => $lastSixMonths['average']['months'],
						'y' => true
					),
					'size' => array(
						'width' => 200,
						'height' => 130
					),
					'extra' => array(
						'class' => 'chart'
					),
					'spacing' => array(
						'padding' => 5,
						'width' => 15,
						'type' => 'absolute'
					),
					'color' => array(
						'series' => array(
							array('00FF00'),
							array('FFFF00'),
							array('FF0000')
						)
					),
				)
			);

			$average = round(array_sum($lastSixMonths['average']['totals']) / count($lastSixMonths['average']['totals']), 3);
			$average = round(($average / 10) * 100);

			$max = round(max($lastSixMonths['max']['totals']), 3);
			$max = round(($max / 10) * 100);

			echo $this->Charts->draw(
				'gauge',
				array(
					'data' => array(
						$average,
						$max
					),
					'normalize' => false,
					'axes' => array(
						'x' => array('Average', 'Max'),
						'y' => array(
							'0.0 Low',
							'Medium',
							'High 10.0'
						)
					),
					'color' => array(
						'series' => array(
							'00FF00',
							'FFFF00',
							'FF0000'
						)
					),
					'size' => array(
						'width' => 250,
						'height' => 100
					),
					'extra' => array(
						'class' => 'chart'
					)
				)
			);
		}
	?>
</div>
