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

	$viewStats = ClassRegistry::init('ViewCounter.ViewCount')->getGlobalTotalCount();
	if(empty($viewStats)){
		return;
	}
		?>
			<div class="dashboard half">
				<h1><?php echo sprintf(__('Overall Usage', true)); ?></h1>
				<?php
					$a = 'A';
					$labels = array();
					$count = count($viewStats);
					for($i = 0; $i < $count; $i++){
						$labels[] = $a++;
					}
					
					echo $this->Chart->display(
						array(
							'name' => 'bar',
							'type' => 'vertical',
							'bar' => 'vertical'
						),
						array(
							'data' => array_values($viewStats),
							'labels' => $labels,
							'size' => '280,130',
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
				<ul class="info">
					<?php
						$i = 0;
						foreach($viewStats as $model => $count){
							$model = prettyName(str_replace('.', '', Inflector::singularize($model)));
							?><li><?php echo sprintf(__('%s: %s %s views', true), $labels[$i], $count, $model); ?></li><?php
							++$i;
						}
					?>
					<li><?php echo sprintf(__('%s views in total', true), array_sum($viewStats)); ?></li>
				</ul>
			</div>
		<?php
?>