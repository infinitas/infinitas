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
	if($this->plugin == 'view_counter' || $this->plugin == 'management'){
		$conditions['month >= '] = date('m') - 1;
		$byWeek = ClassRegistry::init('ViewCounter.ViewCount')->reportByWeek($conditions);
		$byWeek['model'] = pluginSplit($byWeek['model']);
		$header = __('Popular content this month', true);
	}
	else{
		$conditions['month >= '] = date('m') - 1;
		$conditions[] = 'ViewCount.model LIKE %' . $this->plugin . '%';
		$byWeek = ClassRegistry::init('ViewCounter.ViewCount')->reportByWeek($conditions);
		$byWeek['model'] = pluginSplit($byWeek['model']);
		$header = sprintf(__('Popular %s %s', true), $byWeek['model'][0], $byWeek['model'][1]);
	} ?>

	<div class="dashboard half">

		<h1><?php echo $header; ?></h1>
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
					'size' => '400,130',
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