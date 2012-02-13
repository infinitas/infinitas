<?php
/* CoreLog Fixture generated on: 2010-08-17 14:08:32 : 1282055132 */
class CoreLogFixture extends CakeTestFixture {
	var $name = 'CoreLog';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
		'title' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'description' => array('type' => 'text', 'null' => true, 'default' => NULL),
		'model' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100),
		'model_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'action' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50),
		'user_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'change' => array('type' => 'text', 'null' => true, 'default' => NULL),
		'version_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
	);
}
?>