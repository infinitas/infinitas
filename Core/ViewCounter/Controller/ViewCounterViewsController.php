<?php
/**
 * ViewCounterViewsController
 *
 * @package Infinitas.ViewCounter.Controller
 */

/**
 * ViewCounterViewsController
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.ViewCounter.Controller
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.7a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */
class ViewCounterViewsController extends ViewCounterAppController {
/**
 * Helpers to load
 *
 * @var array
 */
	public $helpers = array(
		'Charts.Charts' => array(
			'Google.GoogleStatic'
		)
	);

/**
 * ViewCounter dashboard
 *
 * @return void
 */
	public function admin_dashboard() {
		$this->set('viewCount', $this->ViewCounterView->find('count'));
	}

/**
 * Custom reporting
 *
 * @return void
 */
	public function admin_custom() {
		$filterOptions = $this->Filter->filterOptions;
		$filterOptions['fields'] = array(
			'start_date',
			'end_date',
			'model' => $this->ViewCounterView->uniqueList('model'),
			'year' => $this->ViewCounterView->uniqueList('year'),
			'month' => $this->ViewCounterView->uniqueList('month'),
			'day' => $this->ViewCounterView->uniqueList('day'),
			'hour' => $this->ViewCounterView->uniqueList('hour'),
			'continent_code' => $this->ViewCounterView->uniqueList('continent_code'),
			'country_code' => $this->ViewCounterView->uniqueList('country_code'),
			'country' => $this->ViewCounterView->uniqueList('country'),
			'city'
		);

		$this->set(compact('filterOptions'));
	}

/**
 * List referers
 *
 * @return void
 */
	public function admin_referers() {
		$filterOptions = $this->Filter->filterOptions;
		$filterOptions['fields'] = array(
			'start_date',
			'end_date',
			'model' => $this->ViewCounterView->uniqueList('model'),
			'year' => $this->ViewCounterView->uniqueList('year'),
			'month' => $this->ViewCounterView->uniqueList('month'),
			'day' => $this->ViewCounterView->uniqueList('day'),
			'hour' => $this->ViewCounterView->uniqueList('hour'),
			'continent_code' => $this->ViewCounterView->uniqueList('continent_code'),
			'country_code' => $this->ViewCounterView->uniqueList('country_code'),
			'country' => $this->ViewCounterView->uniqueList('country'),
			'city'
		);

		$this->Paginator->settings = array(
			'conditions' => array(
				$this->modelClass . '.referer IS NOT NULL'
			),
			'group' => array(
				$this->modelClass . '.referer'
			)
		);

		$this->ViewCounterView->virtualFields['referer_count'] = 'COUNT(' . $this->modelClass . '.id)';
		$this->ViewCounterView->virtualFields['external'] = 'LOCATE("http", '. $this->modelClass . '.referer) >= 1';

		$this->Paginator->whitelist[] = 'order';

		$this->Paginator->settings = array(
			'fields' => array(
				$this->modelClass . '.id',
				$this->modelClass . '.referer',
				'referer_count',
				'external'
			),
			'conditions' => array(
				$this->modelClass . '.referer IS NOT NULL'
			),
			'order' => array(
				$this->modelClass . '__referer_count' => 'desc'
			),
			'group' => array(
				$this->modelClass . '.referer'
			)
		);
		$views = $this->Paginator->paginate();

		$this->set(compact('filterOptions', 'views'));
	}

/**
 * Generate reports on views
 *
 * Create pretty graphs of all the data collected for the
 *
 * @return void
 */
	public function admin_reports() {
		if(!$this->ViewCounterView->find('count')) {
			$this->notice(
				__d('view_counter', 'There is no view data yet, try again later'),
				array(
					'level' => 'warning',
					'redirect' => true
				)
			);
		}

		$overview = $yearOnYear = $monthOnMonth = $weekOnWeek = $byDay =
		$dayOfWeek = $hourOnHour = $relatedModel = $byRegion = $foreignKeys =
		$allModels = null;

		$conditions = array();


		if(isset($this->request->params['named']['ViewCounterView.foreign_key'])) {
			$conditions['ViewCounterView.foreign_key'] = $this->request->params['named']['ViewCounterView.foreign_key'];
		}

		if(isset($this->request->params['named']['ViewCounterView.model'])) {
			$conditions['ViewCounterView.model'] = $this->request->params['named']['ViewCounterView.model'];
		}

		$overview = $this->ViewCounterView->reportOverview($conditions);

		$yearOnYear = $this->ViewCounterView->reportYearOnYear($conditions);
		$monthOnMonth = $this->ViewCounterView->reportMonthOnMonth($conditions);
		$weekOnWeek = $this->ViewCounterView->reportWeekOnWeek($conditions);
		$byDay  = $this->ViewCounterView->reportByDayOfMonth($conditions);
		$dayOfWeek = $this->ViewCounterView->reportDayOfWeek($conditions);
		$hourOnHour = $this->ViewCounterView->reportHourOnHour($conditions);
		$byRegion = $this->ViewCounterView->reportByRegion($conditions);

		$this->set(compact('overview', 'yearOnYear', 'monthOnMonth', 'weekOnWeek', 'byWeek', 'byDay', 'dayOfWeek', 'hourOnHour', 'byRegion'));

		if(isset($this->request->params['named']['ViewCounterView.model']) && isset($this->request->params['named']['ViewCounterView.foreign_key'])) {
			$relatedModel = $this->ViewCounterView->reportPopularRows($conditions, $this->request->params['named']['ViewCounterView.model']);
			if(isset($relatedModel[0])) {
				$relatedModel = $relatedModel[0];
			}

			$byRegion = $this->ViewCounterView->reportByRegion($conditions);
			$this->set(compact('relatedModel', 'byRegion'));
		}

		else if(isset($this->request->params['named']['ViewCounterView.model']) && !isset($this->request->params['named']['ViewCounterView.foreign_key'])) {
			$foreignKeys = $this->ViewCounterView->reportPopularRows($conditions, $this->request->params['named']['ViewCounterView.model']);
			$this->set(compact('foreignKeys', 'byRegion'));
		}

		else if(!isset($this->request->params['named']['ViewCounterView.model']) && !isset($this->request->params['named']['ViewCounterView.foreign_key'])) {
			$allModels = $this->ViewCounterView->reportPopularModels();
			$this->set(compact('allModels'));
		}
	}

}