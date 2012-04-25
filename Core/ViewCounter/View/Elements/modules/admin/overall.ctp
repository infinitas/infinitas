<?php
	/**
	 * Generats a graph for overall view stas.
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

	if(empty($viewStats)) {
		$viewStats = ClassRegistry::init('ViewCounter.ViewCounterView')->getGlobalTotalCount();
		if(empty($viewStats)) {
			return;
		}
	}
?>
<div class="dashboard half">
	<h1><?php echo sprintf(__('Overall Usage [%d views]'), array_sum($viewStats)); ?></h1>
	<?php
		$a = 'A';
		$labels = array();
		$count = count($viewStats);
		for($i = 0; $i < $count; $i++){
			$labels[] = $a++;
		}


		$i = 0;
		$legend = array();
		foreach($viewStats as $model => $count) {
			list($plugin, $model) = pluginSplit($model);
			$model = prettyName(str_replace($plugin, '', Inflector::singularize($model)));
			$legend[] = sprintf(__('%s: %s %s views'), $labels[$i], $count, $model);
			++$i;
		}

		echo $this->Charts->draw(
			array(
				'pie' => array(
					'type' => '3d'
				)
			),
			array(
				'data' => array_values($viewStats),
				'axes' => array(
					'x' => $labels,
					'y' => array('0', 100)
				),
				'size' => array(
					450,
					130
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
				),
				'legend' => array(
					'position' => 'bottom',
					'order' => 'default',
					'labels' => $legend
				)
			)
		);
	?>
</div>