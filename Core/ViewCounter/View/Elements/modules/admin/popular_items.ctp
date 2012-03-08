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
	if(ClassRegistry::init('ViewCounter.ViewCounterView')->find('count') == 0){
		return;
	}
	
	if($this->plugin == 'view_counter' || $this->plugin == 'management'){
		$conditions['month >= '] = date('m') - 1;
		$byWeek = ClassRegistry::init('ViewCounter.ViewCounterView')->reportByDayOfMonth($conditions);
		if(isset($byWeek['model'])){
			$byWeek['model'] = pluginSplit($byWeek['model']);
		}
		$header = __('Popular content this month');
	}

	else{
		$conditions['month >= '] = date('m') -3;
		if(isset($model)){
			$conditions['ViewCounterView.model'] = $model;
		}
		$byWeek = ClassRegistry::init('ViewCounter.ViewCounterView')->reportByDayOfMonth($conditions);
		if(!isset($byWeek['totals'])){
			$byWeek['totals'] = array();
		}
		$header = sprintf(__('Views for the last few weeks'));
	}

	if($this->plugin != 'view_counter'){
		$link = $this->Html->link(
			__('More reports'),
			array(
				'plugin' => 'view_counter',
				'controller' => 'view_counter_views',
				'action' => 'reports',
				'ViewCounterView.model' => isset($class) ? $class : ''
			),
			array(
				'class' => 'chart'
			)
		);
	}
?>
<div class="dashboard half">
	<h1><?php echo sprintf('%s<small>%s<br/>Total: %s</small>', $header, $link, $byWeek['stats']['total']); ?></h1>
	<?php
		if(!isset($byWeek['sub_total']) || empty($byWeek['sub_total'])){
			?><span class="chart"><?php echo __('Not enough data collected'); ?></span><?php
		}
		else{
			echo $this->Charts->draw(
				'line',
				array(
					'data' => array($byWeek['sub_total']),
					'axes' => array('x' => $byWeek['day'], 'y' => true),
					'size' => array('width' => 430,'height' => 130),
					'color' => array('series' => array('0d5c05', '03348a')),
					'extra' => array('html' => array('class' => 'chart')),
					'legend' => array(
						'position' => 'top',
						'labels' => array(
							__('Views'),
						)
					),
				)
			);
		}
	?>
</div>
