<?php
	if(!empty($config)) {
		extract($config);
	}
	
	if(empty($chartData)) {
		if(empty($model) || empty($foreignKey)) {
			throw new CakeException('Missing model or foreignKey');
		}
		
		$chartData = ClassRegistry::init('ViewCounter.ViewCounterView')->reportLastTwoWeeks(
			array(
				'model' => $model, 
				'foreign_key' => $foreignKey
			)
		);
	}
	
	$defaultSize = array('width' => 150, 'height' => 50);
	if(!empty($size) && is_array($size)) {
		$size = array_merge($defaultSize, $size);
	}
	
	else{
		$size = $defaultSize;
	}
	
	echo $this->Charts->draw(
		'line',
		array(
			'data' => array(
				$chartData['sub_total']
			),
			'size' => $size,
			'color' => array(
				'series' => array(
					'0d5c05'
				),
				'fill' => array(
					array('color' => '0d5c05', 'type' => 'solid'),
				)
			)
		)
	);
	echo sprintf('<p>%s views per day</p>', round($chartData['stats']['median'], 2));