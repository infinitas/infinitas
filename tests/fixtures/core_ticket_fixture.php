<?php
/* CoreTicket Fixture generated on: 2010-08-17 14:08:57 : 1282055157 */
class CoreTicketFixture extends CakeTestFixture {
	var $name = 'CoreTicket';

	var $fields = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'key' => 'primary'),
		'data' => array('type' => 'text', 'null' => false, 'default' => NULL),
		'expires' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM')
	);

	var $records = array(
	);
}
?>