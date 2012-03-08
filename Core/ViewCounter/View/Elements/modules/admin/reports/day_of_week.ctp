<div class="dashboard half">
	<?php
		echo $this->ViewCounter->header('day_of_week', $dayOfWeek);
		if(empty($dayOfWeek['sub_total'])){
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
					'data' => array($dayOfWeek['sub_total']),
					'axes' => array(
						'x' => $dayOfWeek['day_of_week'],
						'y' => true
					),
					'size' => array('width' => 450, 'height' => 130),
					'extra' => array('html' => array('class' => 'chart')),
					'spacing' => array(
						'padding' => 2,
						'grouping' => 10,
						'width' => 30,
						'type' => 'absolute'
					),
					'color' => array(
						'series' => array(
							array('0d5c05')
						)
					),
					'legend' => array(
						'position' => 'right',
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