<?php
/* CoreBackup Fixture generated on: 2010-08-17 14:08:10 : 1282055110 */
class CoreBackupFixture extends CakeTestFixture {
	var $name = 'CoreBackup';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
		'plugin' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50),
		'model' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50),
		'last_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'data' => array('type' => 'text', 'null' => false, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
	);
}
?>