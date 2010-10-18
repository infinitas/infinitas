<div class="dashboard grid_16">
	<h1><?php echo __('Views per Week', true); ?></h1>
	<?php
		echo $this->Chart->display(
			array(
				'name' => 'bar',
				'type' => 'vertical',
				'bar' => 'vertical'
			),
			array(
				'data' => $byWeek['totals'],
				'labels' => $byWeek['weeks'],
				'size' => '600,130',
				'colors' => array(
					'#001A4D',
					'#4D81A8'
				),
				'html' => array(
					'class' => 'chart'
				)
			)
		);
	?>
</div>
<div class="dashboard grid_16">
	<h1><?php echo __('Views per day', true); ?></h1>
	<?php
		echo $this->Chart->display(
			array(
				'name' => 'bar',
				'type' => 'vertical',
				'bar' => 'vertical'
			),
			array(
				'data' => $byDay['totals'],
				'labels' => $byDay['weeks'],
				'size' => '600,130',
				'colors' => array(
					'#001A4D',
					'#4D81A8'
				),
				'html' => array(
					'class' => 'chart'
				)
			)
		);
	?>
</div>