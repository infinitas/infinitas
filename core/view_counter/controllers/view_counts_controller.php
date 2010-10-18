<?php
	class ViewCountsController extends ViewCounterAppController{
		public $name = 'ViewCounts';

		public function admin_dashboard(){
			$this->ViewCount->virtualFields = array(
				'sub_total' => 'COUNT(ViewCount.id)'
			);
			
			$models = $this->ViewCount->find(
				'all',
				array(
					'fields' => array(
						'ViewCount.id',
						'ViewCount.model',
						'sub_total'
					),
					'group' => array(
						'ViewCount.model'
					)
				)
			);

			$this->set(compact('models'));
		}

		public function admin_index(){
			$conditions = $this->Filter->filter;
			$conditions['month >= '] = date('m') - 2;

			$this->ViewCount->virtualFields = array(
				'sub_total' => 'COUNT(ViewCount.id)',
				'day' => 'DAYOFYEAR(ViewCount.created)',
				'week' => 'WEEK(ViewCount.created)',
				'month' => 'MONTH(ViewCount.created)'
			);

			$viewCountsByWeek = $this->ViewCount->find(
				'all',
				array(
					'fields' => array(
						'ViewCount.id',
						'ViewCount.model',
						'week',
						'month',
						'sub_total'
					),
					'conditions' => $conditions,
					'group' => array(
						'week'
					)
				)
			);
			$byWeek = array();
			if(!empty($viewCountsByWeek)){
				$byWeek['model'] = $viewCountsByWeek[0]['ViewCount']['model'];
				$byWeek['totals'] = Set::extract('/ViewCount/sub_total', $viewCountsByWeek);
				$byWeek['weeks'] = Set::extract('/ViewCount/week', $viewCountsByWeek);
				unset($viewCountsByWeek);
			}

			$viewCountsByDay = $this->ViewCount->find(
				'all',
				array(
					'fields' => array(
						'ViewCount.id',
						'ViewCount.model',
						'day',
						'month',
						'sub_total'
					),
					'conditions' => $conditions,
					'group' => array(
						'day'
					)
				)
			);
			$byDay = array();
			if(!empty($viewCountsByDay)){
				$byDay['model'] = $viewCountsByDay[0]['ViewCount']['model'];
				$byDay['totals'] = Set::extract('/ViewCount/sub_total', $viewCountsByDay);
				$byDay['weeks'] = Set::extract('/ViewCount/week', $viewCountsByDay);
				unset($viewCountsByDay);
			}
			
			$this->set(compact('byWeek', 'byDay'));
		}
	}