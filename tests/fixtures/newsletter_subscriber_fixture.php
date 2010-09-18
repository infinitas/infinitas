<?php
/* NewsletterSubscriber Fixture generated on: 2010-08-17 14:08:18 : 1282055178 */
class NewsletterSubscriberFixture extends CakeTestFixture {
	var $name = 'NewsletterSubscriber';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'active' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 1,
			'user_id' => 1,
			'active' => 1,
			'created' => '2009-12-13 01:49:32',
			'modified' => '2009-12-13 01:49:32'
		),
	);
}
?>