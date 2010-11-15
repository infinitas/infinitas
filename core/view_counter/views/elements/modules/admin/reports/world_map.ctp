<div class="dashboard grid_16">
	<?php
		echo $this->ViewCounter->header('world_map', $byRegion);
		if(!empty($byRegion['country_codes'])){
			echo $this->Chart->display(
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
			foreach($byRegion['countries'] as $k => $country){
				if(count($list) > 24){
					break;
				}
				$list[] = sprintf(__('%s - %s view(s)', true), __($country, true), $byRegion['totals'][$k]);
			}

			echo $this->Design->arrayToList($list, 'country-data');
		}
		else{
			?><span class="chart"><?php echo __('Not enough data collected', true); ?></span><?php
		}
	?>
</div>