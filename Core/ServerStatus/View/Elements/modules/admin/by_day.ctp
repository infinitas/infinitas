<?php
	if(!isset($byDay) || empty($byDay)) {
		return false;
	}
?>
<div class="page-header span12">
	<?php
		echo sprintf(
			__d('server_status', '<h1>%s<small>Data between %s and %s</small></h1>'),
			__d('server_status', 'Server load average by day'),
			$this->Time->niceShort($byDay['start_date']),
			$this->Time->niceShort($byDay['end_date'])
		);

		if(empty($byDay['day'])) {
			echo $this->ViewCounter->noData();
		}

		else{
			echo $this->Charts->draw(
				'line',
				array(
					'data' => array($byDay['max_load'], $byDay['average_load']),
					'axes' => array('x' => $byDay['day'], 'y' => true),
					'size' => array('width' => 930, 'height' => 130),
					'color' => array('series' => array('0d5c05', '03348a')),
					'extra' => array('html' => array('class' => 'chart'), 'scale' => 'relative'),
					'legend' => array(
						'position' => 'top',
						'labels' => array(
							__d('server_status', 'Max Load'),
							__d('server_status', 'Ave Load')
						)
					),
				)
			);
		}
	?>
</div>
