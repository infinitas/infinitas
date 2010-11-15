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
			$plugin = pluginSplit($foreignKey['ViewCount']['model']);
			$icons[] = array(
				'name' => sprintf('%s #%d', $plugin[1], $foreignKey[$model]['id']),
				'description' => sprintf('%d views for %s', $foreignKey['ViewCount']['sub_total'], $foreignKey[$model][ClassRegistry::init($model)->displayField]),
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

	echo $this->element('modules/admin/reports/year_on_year',   array('yearOnYear'   => $yearOnYear));
	echo $this->element('modules/admin/reports/month_on_month', array('monthOnMonth' => $monthOnMonth));
	echo $this->element('modules/admin/reports/week_on_week',   array('weekOnWeek'   => $weekOnWeek));
	echo $this->element('modules/admin/reports/day_of_week',    array('dayOfWeek'    => $dayOfWeek));
	echo $this->element('modules/admin/reports/hour_on_hour',   array('hourOnHour'   => $hourOnHour));
	echo $this->element('modules/admin/reports/day_of_month',   array('byDay'        => $byDay));
	$byRegion = isset($byRegion) ? $byRegion : array();
	echo $this->element('modules/admin/reports/world_map',      array('byRegion'     => $byRegion));