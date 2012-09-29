<?php
App::uses('Feed', 'Feed.Model');

/**
 * Feed Test Case
 *
 */
class FeedTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.feed.feed',
		'plugin.feed.feeds_feed',

		'plugin.users.group',
		'plugin.view_counter.view_counter_view',
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Feed = ClassRegistry::init('Feed.Feed');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Feed);

		parent::tearDown();
	}

/**
 * testListFeeds method
 *
 * @return void
 */
	public function testListFeeds() {
	}

/**
 * testGetFeed method
 *
 * @return void
 */
	public function testGetFeed() {
	}

/**
 * testFeedArrayFormat method
 *
 * @return void
 */
	public function testFeedArrayFormat() {
	}

}
