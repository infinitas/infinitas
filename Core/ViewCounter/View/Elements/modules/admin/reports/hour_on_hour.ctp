<div class="dashboard half">
	<?php
		echo $this->ViewCounter->header('hour_on_hour', $hourOnHour);
		if(empty($hourOnHour['sub_total'])){
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
					'data' => array($hourOnHour['sub_total']),
					'axes' => array(
						'x' => $hourOnHour['hour'],
						'y' => true
					),
					'size' => array('width' => 450, 'height' => 130),
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
