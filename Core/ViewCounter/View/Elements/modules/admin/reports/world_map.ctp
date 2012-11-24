<div class="dashboard span6">
	<?php
		echo $this->ViewCounter->header('world_map', $byRegion);
		if (!empty($byRegion['country_codes'])) {
			echo $this->Charts->draw(
				'map',
				array(
					'data' => $byRegion['totals'],
					'places' => $byRegion['country_codes'],
					'size' => '600,400',
					'colors' => array(
						'DBDBDB', // background
						'BFF7AA', // from
						'1A5903'  // to
					),
					'html' => array(
						'class' => 'chart'
					)
				)
			);

			$list = array();
			foreach ($byRegion['countries'] as $k => $country) {
				if (count($list) > 24) {
					break;
				}
				$list[] = sprintf(__d('view_counter', '%s - %s view(s)'), __d('view_counter', $country), $byRegion['totals'][$k]);
			}

			echo $this->Design->arrayToList($list, 'country-data');
		}
		else{
			?><span class="chart"><?php echo __d('view_counter', 'Not enough data collected'); ?></span><?php
		}
	?>
</div>