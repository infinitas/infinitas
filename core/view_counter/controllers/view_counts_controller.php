<?php
	class ViewCountsController extends ViewCounterAppController{
		public $name = 'ViewCounts';

		public $helpers = array(
			'Filter.Filter'
		);

		public function admin_custom(){
			$filterOptions = $this->Filter->filterOptions;
			$filterOptions['fields'] = array(
				'start_date',
				'end_date',
				'model' => $this->ViewCount->uniqueList('model'),
				'year' => $this->ViewCount->uniqueList('year'),
				'month' => $this->ViewCount->uniqueList('month'),
				'day' => $this->ViewCount->uniqueList('day'),
				'hour' => $this->ViewCount->uniqueList('hour'),
				'continent_code' => $this->ViewCount->uniqueList('continent_code'),
				'country_code' => $this->ViewCount->uniqueList('country_code'),
				'country' => $this->ViewCount->uniqueList('country'),
				'city'
			);

			$this->set(compact('filterOptions'));
		}

		/**
		 * Generate reports on views
		 *
		 * Create pretty graphs of all the data collected for the
		 */
		public function admin_reports(){
			$byYear = $byMonth = $byWeek = $byDay = $relatedModel = $byRegion = $foreignKeys = $allModels = null;
			$conditions = array();
			
			
			if(isset($this->params['named']['ViewCount.foreign_key'])){
				$conditions['ViewCount.foreign_key'] = $this->params['named']['ViewCount.foreign_key'];
			}

			if(isset($this->params['named']['ViewCount.model'])){
				$conditions['ViewCount.model'] = $this->params['named']['ViewCount.model'];
			}

			$overview = $this->ViewCount->reportOverview($conditions);

			$yearOnYear = $this->ViewCount->reportYearOnYear($conditions);
			$monthOnMonth = $this->ViewCount->reportMonthOnMonth($conditions);
			$weekOnWeek = $this->ViewCount->reportWeekOnWeek($conditions);
			$byDay  = $this->ViewCount->reportByDay($conditions);
			$dayOfWeek = $this->ViewCount->reportDayOfWeek($conditions);
			$hourOnHour = $this->ViewCount->reportHourOnHour($conditions);
			$byRegion = $this->ViewCount->reportByRegion($conditions);
			
			$this->set(compact('overview', 'yearOnYear', 'monthOnMonth', 'weekOnWeek', 'byWeek', 'byDay', 'dayOfWeek', 'hourOnHour', 'byRegion'));

			if(isset($this->params['named']['ViewCount.model']) && isset($this->params['named']['ViewCount.foreign_key'])){
				$relatedModel = $this->ViewCount->reportPopularRows($conditions, $this->params['named']['ViewCount.model']);
				if(isset($relatedModel[0])){
					$relatedModel = $relatedModel[0];
				}

				$byRegion = $this->ViewCount->reportByRegion($conditions);
				$this->set(compact('relatedModel', 'byRegion'));
			}

			else if(isset($this->params['named']['ViewCount.model']) && !isset($this->params['named']['ViewCount.foreign_key'])){
				$foreignKeys = $this->ViewCount->reportPopularRows($conditions, $this->params['named']['ViewCount.model']);
				$this->set(compact('foreignKeys', 'byRegion'));
			}
			
			else if(!isset($this->params['named']['ViewCount.model']) && !isset($this->params['named']['ViewCount.foreign_key'])){
				$allModels = $this->ViewCount->reportPopularModels();
				$this->set(compact('allModels'));
			}
		}
	}