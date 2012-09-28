<?php
App::uses('ViewCounterView', 'ViewCounter.Model');

/**
 * ViewCounterView Test Case
 *
 */
class ViewCounterViewTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.view_counter.view_counter_view'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->ViewCounterView = ClassRegistry::init('ViewCounter.ViewCounterView');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->ViewCounterView);

		parent::tearDown();
	}

/**
 * testGetToalViews method
 *
 * @return void
 */
	public function testGetToalViews() {
	}

/**
 * testGetGlobalStats method
 *
 * @return void
 */
	public function testGetGlobalStats() {
	}

/**
 * testGetGlobalTotalCount method
 *
 * @return void
 */
	public function testGetGlobalTotalCount() {
	}

/**
 * testGetAverage method
 *
 * @return void
 */
	public function testGetAverage() {
	}

/**
 * testGetUniqueModels method
 *
 * @return void
 */
	public function testGetUniqueModels() {
	}

/**
 * testReportOverview method
 *
 * @return void
 */
	public function testReportOverview() {
	}

/**
 * testReportYearOnYear method
 *
 * @return void
 */
	public function testReportYearOnYear() {
	}

/**
 * testReportMonthOnMonth method
 *
 * @return void
 */
	public function testReportMonthOnMonth() {
	}

/**
 * testReportWeekOnWeek method
 *
 * @return void
 */
	public function testReportWeekOnWeek() {
	}

/**
 * testReportByDayOfMonth method
 *
 * @return void
 */
	public function testReportByDayOfMonth() {
	}

/**
 * testReportDayOfWeek method
 *
 * @return void
 */
	public function testReportDayOfWeek() {
	}

/**
 * testReportLastTwoWeeks method
 *
 * @return void
 */
	public function testReportLastTwoWeeks() {
	}

/**
 * testReportHourOnHour method
 *
 * @return void
 */
	public function testReportHourOnHour() {
	}

/**
 * testReportPopularRows method
 *
 * @return void
 */
	public function testReportPopularRows() {
	}

/**
 * testReportPopularModels method
 *
 * @return void
 */
	public function testReportPopularModels() {
	}

/**
 * testReportByRegion method
 *
 * @return void
 */
	public function testReportByRegion() {
	}

/**
 * testGetCountryForUnknown method
 *
 * @return void
 */
	public function testGetCountryForUnknown() {
	}

/**
 * testGetCityForUnknown method
 *
 * @return void
 */
	public function testGetCityForUnknown() {
	}

/**
 * testGetDatePartsFromCreated method
 *
 * @return void
 */
	public function testGetDatePartsFromCreated() {
	}

/**
 * testClearLocalhost method
 *
 * @return void
 */
	public function testClearLocalhost() {
	}

}
