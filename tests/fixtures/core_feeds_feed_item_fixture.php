<?php
/* CoreFeedsFeedItem Fixture generated on: 2010-08-17 14:08:24 : 1282055124 */
class CoreFeedsFeedItemFixture extends CakeTestFixture {
	var $name = 'CoreFeedsFeedItem';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'feed_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'feed_item_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
	);
}
?>