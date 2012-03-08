<div class="dashboard grid_16">
	<?php
		echo $this->ViewCounter->header('week_on_week', $weekOnWeek);
		if(empty($weekOnWeek['sub_total'])){
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
					'data' => array($weekOnWeek['sub_total']),
					'axes' => array(
						'x' => $weekOnWeek['week_of_year'],
						'y' => true
					),
					'size' => array('width' => 930, 'height' => 130),
					'extra' => array('html' => array('class' => 'chart')),
					'spacing' => array(
						'padding' => 2,
						'grouping' => 6,
						'width' => 11,
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

