<?php
/* CoreComment Fixture generated on: 2010-08-17 14:08:13 : 1282055113 */
class CoreCommentFixture extends CakeTestFixture {
	var $name = 'CoreComment';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
		'class' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 128),
		'foreign_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50),
		'email' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
		'website' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'comment' => array('type' => 'text', 'null' => false, 'default' => NULL),
		'active' => array('type' => 'boolean', 'null' => false, 'default' => NULL),
		'rating' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'points' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'status' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 1,
			'class' => 'Content',
			'foreign_id' => 6,
			'name' => 'bob',
			'email' => 'bob@bob.com',
			'website' => 'www.something.com',
			'comment' => 'asdjaksldj asldka ',
			'active' => 1,
			'rating' => 2,
			'points' => 5,
			'status' => 'actvie',
			'created' => '2010-04-02 22:25:01'
		),
	);
}
?>