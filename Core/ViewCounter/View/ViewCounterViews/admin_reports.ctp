<?php
	if(isset($allModels) && $allModels) {
		$icons = array();
		foreach($allModels as $model) {
			$plugin = pluginSplit($model['ViewCounterView']['model']);
			$icons[] = array(
				'name' => __(Inflector::pluralize(implode(' ', $plugin))),
				'description' => sprintf('%s views in total', $model['ViewCounterView']['sub_total']),
				'icon' => '/' . $plugin[0] . '/img/icon.png',
				'dashboard' => array(
					'ViewCounterView.model' => $model['ViewCounterView']['model']
				)
			);
		}
		$icons = $this->Menu->builDashboardLinks($icons, 'view_counts_totals'); ?>
		<div class="dashboard grid_16">
			<h1>
				<?php echo __('Totals per model'); ?>
			</h1>
			<ul class="icons"><li><?php echo implode('</li><li>', current((array)$icons)); ?></li></ul>
		</div><?php
	}

	else if(isset($foreignKeys) && $foreignKeys) {
		$icons = array();
		foreach($foreignKeys as $foreignKey) {
			$model = str_replace('.', '', $foreignKey['ViewCounterView']['model']);
			$plugin = pluginSplit($foreignKey['ViewCounterView']['model']);
			$icons[] = array(
				'name' => sprintf('%s #%d', $plugin[1], $foreignKey[$model]['id']),
				'description' => sprintf('%d views for %s', $foreignKey['ViewCounterView']['sub_total'], $foreignKey[$model][ClassRegistry::init($model)->displayField]),
				'icon' => '/view_counter/img/row.png',
				'dashboard' => array(
					'ViewCounterView.model' => $foreignKey['ViewCounterView']['model'],
					'ViewCounterView.foreign_key' => $foreignKey['ViewCounterView']['foreign_key']
				)
			);
		}

		$icons = $this->Menu->builDashboardLinks($icons, 'view_counts_totals_' . $model); ?>
		<div class="dashboard grid_16">
			<h1>
				<?php echo __('Views by Row'); ?>
			</h1>
			<ul class="icons"><li><?php echo implode('</li><li>', current((array)$icons)); ?></li></ul>
		</div><?php
	}

	else if(isset($relatedModel) && $relatedModel) { ?>
		<div class="dashboard grid_16">
			<h1>
				<?php
					echo sprintf(
						__('Showing data for "%s", row 3%d'),
						$relatedModel[str_replace('.', '', $relatedModel['ViewCounterView']['model'])]['title'],
						$relatedModel[str_replace('.', '', $relatedModel['ViewCounterView']['model'])]['id']
					);
				?>
			</h1>
		</div><?php
	}

	echo $this->ModuleLoader->loadDirect('ViewCounter.reports/overview',       array('overview'     => $overview));
	echo $this->ModuleLoader->loadDirect('ViewCounter.reports/year_on_year',   array('yearOnYear'   => $yearOnYear));
	echo $this->ModuleLoader->loadDirect('ViewCounter.reports/month_on_month', array('monthOnMonth' => $monthOnMonth));
	echo $this->ModuleLoader->loadDirect('ViewCounter.reports/week_on_week',   array('weekOnWeek'   => $weekOnWeek));
	echo $this->ModuleLoader->loadDirect('ViewCounter.reports/day_of_week',    array('dayOfWeek'    => $dayOfWeek));
	echo $this->ModuleLoader->loadDirect('ViewCounter.reports/hour_on_hour',   array('hourOnHour'   => $hourOnHour));
	echo $this->ModuleLoader->loadDirect('ViewCounter.reports/day_of_month',   array('byDay'        => $byDay));
	$byRegion = isset($byRegion) ? $byRegion : array();
	//echo $this->ModuleLoader->loadDirect('ViewCounter.reports/world_map',      array('byRegion'     => $byRegion));