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

	$viewStats = ClassRegistry::init('ViewCounter.ViewCount')->getGlobalStats();
	if(empty($viewStats)){
		return;
	}

	foreach($viewStats as $stats){
		if(empty($stats['top_rows'])){
			continue;
		}

		?>
			<div class="dashboard half grid_8">
				<h1><?php echo sprintf(__('Popular %s %s', true), prettyName($stats['plugin']), prettyName($stats['model'])); ?></h1>
				<?php
					$counts = Set::extract('/'.$stats['model'].'/views', $stats['top_rows']);

					$a = 'A';
					$count_loop = count($counts);
					$labels = array();
					for($i = 0; $i < $count_loop; ++$i){
						$labels[] = $a++;
					}
					$counts[] = $stats['total_views'] - array_sum($counts);
					$labels[] = 'Z';
					$this->Chart->cache = false;

					echo $this->Chart->display(
						'pie3d',
						array(
							'data' => $counts,
							'labels' => $labels,
							'size' => '250,130',
							'html' => array(
								'class' => 'chart'
							)
						)
					);
				?>
				<ul class="info">
					<?php
						foreach($stats['top_rows'] as $i => $row){
							?>
								<li>
									<b><?php echo $labels[$i]; ?>) </b>
									<?php
											$link = $this->Event->trigger(Inflector::underscore($stats['plugin']).'.slugUrl', $row);
											echo $this->Html->link(
												$row[$stats['model']][$stats['displayField']],
												array_merge(array('admin' => 0, 'prefix' => false), $link['slugUrl'][Inflector::underscore($stats['plugin'])]),
												array(
													'title' => sprintf(__('%s views', true), $counts[$i])
												)
											);
										?>
								</li>
							<?php
						}
					?>
					<li><b>Z) </b><?php echo __('Others', true); ?></li>
				</ul>
			</div>
		<?php
	}
?>