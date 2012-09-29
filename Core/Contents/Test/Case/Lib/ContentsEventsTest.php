<?php
/**
 * @brief ContentsEventsTest
 *
 * These tests are extended from InfinitasEventTestCase which does most of the
 * automated testing for simple events
 */

App::uses('InfinitasEventTestCase', 'Events.Test/Lib');

class ContentsEventsTest extends InfinitasEventTestCase {
/**
 * @brief test behaviors are attached correctly
 */
	public function testAttachBehaviors() {

	}

/**
 * @brief test site map building
 */
	public function testSiteMapRebuild() {

	}

/**
 * @brief test hard coded routes
 */
	public function testSetupRoutes() {
		
	}

/**
 * @brief test slugged urls
 *
 * @dataProvider slugUrlsDataProvider
 */
	public function testSlugUrls($data, $expected) {
		$result = $this->Event->trigger($this->ViewObject, 'Assets.slugUrl', $data);
		//$this->assertEquals($expected, $result['slugUrl']);
	}

/**
 * @brief data provider for url data
 *
 * @return array
 */
	public function slugUrlsDataProvider() {
		return array(
			array(
				array(
					'type' =>  'category',
					'data' => array(
						'plugin' => 'foo',
						'controller' => 'foo',
						'action' => 'foo',
						'id' => 123,
						'slug' => 'abc'
					)
				),
				array('asd')
			)
		);
	}

/**
 * @brief test route parsing
 */
	public function testRouteParse() {

	}
}