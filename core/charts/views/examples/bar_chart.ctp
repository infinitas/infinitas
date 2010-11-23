<?php
	echo $this->Charts->draw(
		array(
			'bar' => array(
				'type' => 'vertical',
				'bar' => 'vertical'
			)
		),
		array(
			'data' => array(10, 15, 20, 5, 10, 40),
			'axes' => array(
				'x' => array('a', 'b', 'c', 'd', 'e', 'f'),
				'y' => ''
			),
			'size' => array(
				900,
				150
			),
			'color' => array(
				'background' => 'FFFFFF',
				'fill' => 'FFCC33',
				'text' => '989898',
				'lines' => '989898',
			),
			'spacing' => array(
				'padding' => 6
			),
			'tooltip' => 'Something Cool :: figure1: %s<br/>figure1: %s<br/>figure3: %s',
			'html' => array(
				'class' => 'chart'
			)
		)
	);