<?php
App::uses('ShortUrl', 'ShortUrls.Model');

/**
 * ShortUrl Test Case
 *
 */
class ShortUrlTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.short_urls.short_url',
		'plugin.short_urls.view_counter_view'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->ShortUrl = ClassRegistry::init('ShortUrls.ShortUrl');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->ShortUrl);

		parent::tearDown();
	}

/**
 * testSomeTypeOfUrl method
 *
 * @return void
 */
	public function testSomeTypeOfUrl() {
	}

/**
 * testShorten method
 *
 * @return void
 */
	public function testShorten() {
	}

/**
 * testGetUrl method
 *
 * @return void
 */
	public function testGetUrl() {
	}

}
