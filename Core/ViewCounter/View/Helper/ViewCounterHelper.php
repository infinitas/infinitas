<?php
class ViewCounterHelper extends AppHelper {
	public $helpers = array(
		'Time'
	);

	public function header($type = null, $data = null) {
		switch($type) {
			case 'year_on_year':
				$header = __d('view_counter', 'Traffic year on year');
				break;

			case 'month_on_month':
				$header = __d('view_counter', 'Traffic month on month');
				break;

			case 'day_of_month':
				$header = __d('view_counter', 'Traffic by day of month');
				break;

			case 'day_of_week':
				$header = __d('view_counter', 'Traffic by day of week');
				break;

			case 'hour_on_hour':
				$header = __d('view_counter', 'Traffic by hour of day');
				break;

			case 'week_on_week':
				$header = __d('view_counter', 'Traffic week on week');
				break;

			case 'world_map':
				$header = __d('view_counter', 'Traffic by region');
				break;

			case 'overview':
				$header = __d('view_counter', 'General Overview');
				break;

			default:
				$header = __d('view_counter', 'Nothing selected');
				break;
		}

		if(!isset($data['stats']['total'])) {
			$data['stats']['total'] = $data['total_views'];
		}

		return sprintf(
			__d('view_counter', '<h1>%s<small>%d views<br/>Between %s and %s</small></h1>'),
			$header,
			$data['stats']['total'],
			$this->Time->niceShort($data['start_date']),
			$this->Time->niceShort($data['end_date'])
		);
	}

	public function noData() {
		return __d('view_counter', 'There is not enough data to display this graph');
	}

}
