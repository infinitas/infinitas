<?php
/**
 * Display some pretty stats on page views.
 *
 * This gets the data from the views table and generates some graphs and stats
 * for the most popular contentS
 *
 * Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.ViewCounter.View
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.8a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

	$header = $link = '';
	$byWeek = $conditions = array();
	if (ClassRegistry::init('ViewCounter.ViewCounterView')->find('count') == 0) {
		return;
	}

	if ($this->plugin == 'view_counter' || $this->plugin == 'management') {
		$conditions['month >= '] = date('m') - 1;
		$byWeek = ClassRegistry::init('ViewCounter.ViewCounterView')->reportByDayOfMonth($conditions);
		if (isset($byWeek['model'])) {
			$byWeek['model'] = pluginSplit($byWeek['model']);
		}
		$header = __d('view_counter', 'Popular content this month');
	} else {
		$conditions['month >= '] = date('m') -3;
		if (isset($model)) {
			$conditions['ViewCounterView.model'] = $model;
		}

		$byWeek = ClassRegistry::init('ViewCounter.ViewCounterView')->reportByDayOfMonth($conditions);
		if (!isset($byWeek['totals'])) {
			$byWeek['totals'] = array();
		}
		$header = sprintf(__d('view_counter', 'Views for the last few weeks'));
	}

	if ($this->plugin != 'view_counter') {
		$url = array(
			'plugin' => 'view_counter',
			'controller' => 'view_counter_views',
			'action' => 'reports'
		);

		if (!empty($class)) {
			$url['ViewCounterView.model'] = $class;
		}

		$link = $this->Html->link(
			__d('view_counter', 'More reports'),
			$url,
			array(
				'class' => 'chart'
			)
		);
	}
?>
<div class="dashboard span6">
	<?php
		echo $this->Html->tag('h1', $header . $this->Html->tag('small', sprintf('%s<br/>Total: %s', $link, $byWeek['stats']['total'])));
		if (!isset($byWeek['sub_total']) || empty($byWeek['sub_total'])) {
			echo $this->Html->tag('span', __d('view_counter', 'Not enough data collected'), array(
				'class' => 'chart'
			));
		} else {
			echo $this->Charts->draw('line', array(
				'data' => array($byWeek['sub_total']),
				'axes' => array('x' => $byWeek['day'], 'y' => true),
				'size' => array('width' => 430,'height' => 130),
				'color' => array('series' => array('0d5c05', '03348a')),
				'extra' => array('html' => array('class' => 'chart')),
				'legend' => array(
					'position' => 'top',
					'labels' => array(
						__d('view_counter', 'Views'),
					)
				),
			));
		}
	?>
</div>
