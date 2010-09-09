<?php
	/* CoreTicket Fixture generated on: 2010-08-17 01:08:09 : 1282003869 */
	class TicketFixture extends CakeTestFixture {
		var $name = 'Ticket';

		var $table = 'core_tickets';

		var $fields = array(
			'id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'key' => 'primary'),
			'data' => array('type' => 'text', 'null' => false, 'default' => NULL),
			'expires' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
			'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
			'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
		);

		var $records = array(
		);
	}