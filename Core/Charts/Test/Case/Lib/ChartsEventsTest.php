<?php
App::uses('InfinitasEventTestCase', 'Events.Test/Lib');

class ChartsEventsTest extends InfinitasEventTestCase {

/**
 * test that the manipulation libs are available
 */
	public function testLibIsLoaded() {
		$this->assertTrue(class_exists('ChartDataManipulation'));
	}
}