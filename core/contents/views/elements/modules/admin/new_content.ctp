<?php
	if(empty($newContent)) {
		$newContent = ClassRegistry::init('Contents.GlobalContent')->getNewContentByMonth();
	}
?>
<div class="dashboard full">
	<h1><?php __d('contents', 'New Content by month'); ?></h1>
	<?php
		echo $this->Charts->draw(
			array(
				'bar' => array(
					'type' => 'vertical_group'
				)
			),
			array(
				'data' => array(
					$newContent['new'],
					$newContent['updated']
				),
				'axes' => array(
					'x' => $newContent['labels'],
					'y' => true
				),
				'size' => array('width' => 930, 'height' => 175),
				'extra' => array('html' => array('class' => 'chart')),
				'spacing' => array(
					'padding' => 2,
					'grouping' => 4,
					'width' => 13,
					'type' => 'absolute'
				),
				'color' => array(
					'series' => array(
						'0d5c05',
						'ffea00'
					),
					'fill' => array(
						array('color' => '0d5c05', 'type' => 'solid'),
						array('color' => 'ffea00', 'type' => 'solid')
					)
				),
				'legend' => array(
					'position' => 'right',
					'order' => 'default',
					'labels' => array(
						__('New', true),
						__('Updated', true),
					)
				)
			)
		);
	?>
</div>