<?php
/**
 * @brief fixture file for FeedsFeed tests.
 *
 * @package Feed.Fixture
 * @since 0.9b1
 */
class FeedsFeedFixture extends CakeTestFixture {
	public $name = 'FeedsFeed';

	public $fields = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary'),
		'main_feed_id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36),
		'sub_feed_id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	public $records = array(
	);
}