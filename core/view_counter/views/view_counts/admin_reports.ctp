<?php
	if(isset($allModels)){
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
	else if(isset($foreignKeys)){
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
	else if(isset($relatedModel)){ ?>
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
?>

<?php
	$text = sprintf(
		__('%d views from %s to %s', true),
		array_sum($byMonth['totals']),
		date('M-d \'y', strtotime(sprintf('Y%sW%s', date('Y'), min($byWeek['weeks'])))),
		date('M-d \'y', strtotime(sprintf('Y%sW%s', date('Y'), max($byWeek['weeks']) + 1)))
	);
?>
<div class="dashboard grid_16">
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
				'size' => '700,130',
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
<div class="dashboard grid_16">
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
				'size' => '700,130',
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
<div class="dashboard grid_16">
	<h1>
		<?php echo __('Views per day', true); ?>
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
				'data' => $byDay['totals'],
				'labels' => $byDay['days'],
				'size' => '700,130',
				'colors' => array(
					'#101A4D',
					'#4D81A8'
				),
				'html' => array(
					'class' => 'chart'
				)
			)
		);
	?>
</div>