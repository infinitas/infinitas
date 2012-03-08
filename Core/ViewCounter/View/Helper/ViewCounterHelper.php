<?php
	class ViewCounterHelper extends AppHelper{
		public $helpers = array(
			'Time'
		);

		public function header($type = null, $data = null){
			switch($type){
				case 'year_on_year':
					$header = __('Traffic year on year');
					break;

				case 'month_on_month':
					$header = __('Traffic month on month');
					break;

				case 'day_of_month':
					$header = __('Traffic by day of month');
					break;

				case 'day_of_week':
					$header = __('Traffic by day of week');
					break;

				case 'hour_on_hour':
					$header = __('Traffic by hour of day');
					break;

				case 'week_on_week':
					$header = __('Traffic week on week');
					break;

				case 'world_map':
					$header = __('Traffic by region');
					break;

				case 'overview':
					$header = __('General Overview');
					break;

				default:
					$header = __('Nothing selected');
					break;
			}

			if(!isset($data['stats']['total'])){
				$data['stats']['total'] = $data['total_views'];
			}
			
			return sprintf(
				__('<h1>%s<small>%d views<br/>Between %s and %s</small></h1>'),
				$header,
				$data['stats']['total'],
				$this->Time->niceShort($data['start_date']),
				$this->Time->niceShort($data['end_date'])
			);
		}

		public function noData(){
			return __('There is not enough data to display this graph');
		}
	}
