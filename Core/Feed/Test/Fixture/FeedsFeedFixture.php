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
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'main_feed_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'sub_feed_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	public $records = array(
	);
}