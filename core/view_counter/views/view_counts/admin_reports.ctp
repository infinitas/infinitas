<?php
	if(isset($allModels) && $allModels){
		$icons = array();
		foreach($allModels as $model){
			$plugin = pluginSplit($model['ViewCount']['model']);
			$icons[] = array(
				'name' => __(Inflector::pluralize(implode(' ', $plugin)), true),
				'description' => sprintf('%s views in total', $model['ViewCount']['sub_total']),
				'icon' => '/' . $plugin[0] . '/img/icon.png',
				'dashboard' => array(
					'ViewCount.model' => $model['ViewCount']['model']
				)
			);
		}
		$icons = $this->Menu->builDashboardLinks($icons, 'view_counts_totals'); ?>
		<div class="dashboard grid_16">
			<h1>
				<?php echo __('Totals per model', true); ?>
			</h1>
			<ul class="icons"><li><?php echo implode('</li><li>', current((array)$icons)); ?></li></ul>
		</div><?php
	}
	
	else if(isset($foreignKeys) && $foreignKeys){
		$icons = array();
		foreach($foreignKeys as $foreignKey){
			$model = str_replace('.', '', $foreignKey['ViewCount']['model']);
			$icons[] = array(
				'name' => sprintf('%s #%d', $model, $foreignKey[$model]['id']),
				'description' => sprintf('%d views for %s', $foreignKey['ViewCount']['sub_total'], $foreignKey[$model]['title']),
				'icon' => '/view_counter/img/row.png',
				'dashboard' => array(
					'ViewCount.model' => $foreignKey['ViewCount']['model'],
					'ViewCount.foreign_key' => $foreignKey['ViewCount']['foreign_key']
				)
			);
		}

		$icons = $this->Menu->builDashboardLinks($icons, 'view_counts_totals_' . $model); ?>
		<div class="dashboard grid_16">
			<h1>
				<?php echo __('Views by Row', true); ?>
			</h1>
			<ul class="icons"><li><?php echo implode('</li><li>', current((array)$icons)); ?></li></ul>
		</div><?php
	}
	
	else if(isset($relatedModel) && $relatedModel){ ?>
		<div class="dashboard grid_16">
			<h1>
				<?php
					echo sprintf(
						__('Showing data for "%s", row 3%d', true),
						$relatedModel[str_replace('.', '', $relatedModel['ViewCount']['model'])]['title'],
						$relatedModel[str_replace('.', '', $relatedModel['ViewCount']['model'])]['id']
					);
				?>
			</h1>
		</div><?php
	}
	
	$text = sprintf(
		__('%d views from %s to %s', true),
		array_sum($byMonth['totals']),
		date('M-d \'y', strtotime(sprintf('Y%sW%s', date('Y'), min($byWeek['weeks'])))),
		date('M-d \'y', strtotime(sprintf('Y%sW%s', date('Y'), max($byWeek['weeks']) + 1)))
	);
?>
<div class="dashboard half">
	<h1>
		<?php echo __('Views per Month', true); ?>
		<small><?php echo $text; ?></small>
	</h1>
	<?php
		echo $this->Chart->display(
			array(
				'name' => 'bar',
				'type' => 'vertical',
				'bar' => 'vertical'
			),
			array(
				'data' => $byMonth['totals'],
				'labels' => $byMonth['months'],
				'axis_type' => array('x', 'y'),
				'size' => '400,130',
				'spacing' => array(26, 25),
				'html' => array(
					'class' => 'chart'
				)
			)
		);
	?>
</div>
<div class="dashboard half">
	<h1>
		<?php echo __('Views per Week', true); ?>
		<small><?php echo $text; ?></small>
	</h1>
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
				'axis_type' => array('x', 'y'),
				'size' => '400,130',
				'html' => array(
					'class' => 'chart'
				)
			)
		);
	?>
</div>
<div class="dashboard grid_16">
	<h1>
		<?php echo __('Views per day', true); ?>
		<small><?php echo $text; ?></small>
	</h1>
	<?php
		echo $this->Chart->display(
			'line',
			array(
				'data' => $byDay['totals'],
				'labels' => $byDay['days'],
				'axis_type' => array('x', 'y'),
				'spacing' => array(20, 2),
				'size' => '900,130',
				'html' => array(
					'class' => 'chart'
				)
			)
		);
	?>
</div>
<?php
	if(!isset($byRegion)){
		return;
	}
?>
<div class="dashboard grid_16">
	<h1>
		<?php echo __('Views by region', true); ?>
		<small><?php echo $text; ?></small>
	</h1>
	<?php
		echo $this->Chart->display(
			'map',
			array(
				'data' => Set::extract('/total', $byRegion),
				'places' => Set::extract('/country_code', $byRegion),
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
		foreach($byRegion as $region){
			$list[] = sprintf(__('%s - %s view(s)', true), __($region['country'], true), $region['total']);
		}

		echo $this->Design->arrayToList($list, 'country-data');
	?>
</div>