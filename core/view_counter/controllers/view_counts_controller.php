<?php
	class ViewCountsController extends ViewCounterAppController{
		public $name = 'ViewCounts';

		public function admin_reports(){
			$conditions = $this->Filter->filter;
			$conditions['month >= '] = date('Y-m', mktime(0, 0, 0, date('m') - 12));
			$byMonth = $this->ViewCount->reportByMonth($conditions);

			$conditions['month >= '] = date('m') -3;
			$byWeek = $this->ViewCount->reportByWeek($conditions);
			$byDay  = $this->ViewCount->reportByDay($conditions);
			
			$this->set(compact('byMonth', 'byWeek', 'byDay'));

			if(!isset($this->params['named']['ViewCount.model']) && !isset($this->params['named']['ViewCount.foreign_key'])){
				$allModels = $this->ViewCount->reportPopularModels();
				$this->set(compact('allModels'));
			}
			else if(isset($this->params['named']['ViewCount.model']) && !isset($this->params['named']['ViewCount.foreign_key'])){
				$foreignKeys = $this->ViewCount->reportPopularRows($conditions, $this->params['named']['ViewCount.model']);
				$this->set(compact('foreignKeys'));
			}
			else if(isset($this->params['named']['ViewCount.model']) && isset($this->params['named']['ViewCount.foreign_key'])){
				$relatedModel = $this->ViewCount->reportPopularRows($conditions, $this->params['named']['ViewCount.model']);
				if(isset($relatedModel[0])){
					$relatedModel = $relatedModel[0];
				}
				$this->set(compact('relatedModel'));
			}
		}
	}