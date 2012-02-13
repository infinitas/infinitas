<?php
	if(empty($userRegistrations)) {
		$userRegistrations = ClassRegistry::init('Users.User')->getRegistrationChartData();
	}
?>
<div class="dashboard full">
	<h1><?php echo __d('users', 'Registrations per month'); ?></h1>
	<?php
		$labels = array();
		foreach(array_keys($userRegistrations) as $k => $v) {
			if($k % 2 == 0) {
				$labels[] = $v;
			}
			else {
				$labels[] = '';
			}
		}
		echo $this->Charts->draw(
			array(
				'bar' => array(
					'type' => 'vertical_group'
				)
			),
			array(
				'data' => array_values($userRegistrations),
				'axes' => array(
					'x' => $labels,
					'y' => true
				),
				'size' => array('width' => 930, 'height' => 175),
				'extra' => array('html' => array('class' => 'chart')),
				'spacing' => array(
					'padding' => 2,
					'grouping' => 9,
					'width' => 22,
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
						__('New Users')
					)
				),
			)
		);
	?>
</div>