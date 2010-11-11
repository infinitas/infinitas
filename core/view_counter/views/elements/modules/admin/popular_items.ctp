<?php
	/**
	 * Display some pretty stats on page views.
	 *
	 * This gets the data from the views table and generates some graphs and stats
	 * for the most popular contentS
	 *
	 * Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 *
	 * @filesource
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package Infinitas.view_counter
	 * @subpackage Infinitas.view_counter.modules
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.8a
	 *
	 * @author dogmatic69
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */
	$header = $link = '';
	$byWeek = $conditions = array();

	if($this->plugin == 'view_counter' || $this->plugin == 'management'){
		$conditions['month >= '] = date('m') - 1;
		$byWeek = ClassRegistry::init('ViewCounter.ViewCount')->reportByWeek($conditions);
		if(isset($byWeek['model'])){
			$byWeek['model'] = pluginSplit($byWeek['model']);
		}
		$header = __('Popular content this month', true);
	}

	else{
		$conditions['month >= '] = date('m') -3;
		if(isset($model)){
			$conditions['ViewCount.model'] = $model;
		}
		$byWeek = ClassRegistry::init('ViewCounter.ViewCount')->reportByWeek($conditions);
		$header = sprintf(__('Views for the last few weeks<small>Total: %s</small>', true), array_sum($byWeek['totals']));
	}


	if($this->plugin != 'view_counter'){
		$link = $this->Html->link(
			__('More reports', true),
			array(
				'plugin' => 'view_counter',
				'controller' => 'view_counts',
				'action' => 'reports',
				'ViewCount.model' => isset($class) ? $class : ''
			),
			array(
				'class' => 'chart'
			)
		);
	}
?>
<div class="dashboard half">
	<h1><?php echo sprintf('%s<small>%s</small>', $header, $link); ?></h1>
	<?php
		if(!isset($byWeek['totals']) || empty($byWeek['totals'])){
			?><span class="chart"><?php echo __('Not enough data collected'); ?></span><?php
		}
		else{
			echo $this->Chart->display(
				'line',
				array(
					'data' => $byWeek['totals'],
					'labels' => $byWeek['weeks'],
					'size' => '430,130',
					'html' => array(
						'class' => 'chart'
					)
				)
			);
		}
	?>
</div>
