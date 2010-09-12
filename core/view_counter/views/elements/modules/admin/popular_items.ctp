<?php
	$viewStats = ClassRegistry::init('ViewCounter.ViewCount')->getGlobalStats();
	if(empty($viewStats)){
		return;
	}

	foreach($viewStats as $stats){
		if(empty($stats['top_rows'])){
			continue;
		}

		?>
			<div class="dashboard small">
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
							'size' => '280,160'
						)
					);
				?>
				<ul>
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