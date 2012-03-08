<div class="dashboard grid_16">
	<?php
		echo $this->ViewCounter->header('day_of_month', $byDay);
		if(empty($byDay['sub_total'])){
			echo $this->ViewCounter->noData();
		}
		else{
			echo $this->Charts->draw(
				array(
					'bar' => array(
						'type' => 'vertical_group'
					)
				),
				array(
					'data' => array($byDay['sub_total']),
					'axes' => array(
						'x' => $byDay['day'],
						'y' => true
					),
					'size' => array('width' => 930, 'height' => 130),
					'extra' => array('html' => array('class' => 'chart')),
					'spacing' => array(
						'padding' => 2,
						'grouping' => 9,
						'width' => 20,
						'type' => 'absolute'
					),
					'color' => array(
						'series' => array(
							array('0d5c05')
						)
					),
					'legend' => array(
						'position' => 'top',
						'order' => 'default',
						'labels' => array(
							__('Views')
						)
					),
				)
			);
		}
	?>
</div>
