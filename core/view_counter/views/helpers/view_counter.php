<?php
	class ViewCounterHelper extends AppHelper{
		public $helpers = array(
			'Time'
		);

		public function header($type = null, $data = null){
			switch($type){
				case 'year_on_year':
					$header = __('Traffic year on year', true);
					break;

				case 'month_on_month':
					$header = __('Traffic month on month', true);
					break;

				case 'day_of_month':
					$header = __('Traffic by day of month', true);
					break;

				case 'day_of_week':
					$header = __('Traffic by day of week', true);
					break;

				case 'hour_on_hour':
					$header = __('Traffic by hour of day', true);
					break;

				case 'week_on_week':
					$header = __('Traffic week on week', true);
					break;

				case 'world_map':
					$header = __('Traffic by region', true);
					break;
			}

			return sprintf(
				__('<h1>%s<small>%d views<br/>Between %s and %s</small></h1>', true),
				$header,
				$data['total_views'],
				$this->Time->niceShort($data['start_date']),
				$this->Time->niceShort($data['end_date'])
			);
		}

		public function noData(){
			return __('There is not enough data to display this graph', true);
		}
	}
