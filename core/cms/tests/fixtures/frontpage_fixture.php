<?php
	/* ContentFrontpage Fixture generated on: 2009-12-13 19:12:29 : 1260726929 */
	class FrontpageFixture extends CakeTestFixture {
		var $name = 'Frontpage';

		var $fields = array(
			'id' => array('type' => 'integer', 'null' => false, 'key' => 'primary'),
			'content_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
			'ordering' => array('type' => 'integer', 'null' => false, 'default' => '0'),
			'order_id' => array('type' => 'integer', 'null' => false, 'default' => 1),
			'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
			'modified' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
			'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
			'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
		);

		var $records = array(
			array(
				'id' => 1,
				'content_id' => 1,
				'ordering' => 1,
				'order_id' => 1,
				'created' => '2010-01-01 00:00:00',
				'created' => '2010-01-01 00:00:00'
			),
		);
	}