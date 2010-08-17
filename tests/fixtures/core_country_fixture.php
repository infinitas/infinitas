<?php
/* CoreCountry Fixture generated on: 2010-08-17 14:08:17 : 1282055117 */
class CoreCountryFixture extends CakeTestFixture {
	var $name = 'CoreCountry';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50),
		'code' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 5),
		'continent_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 1,
			'name' => 'South Africa',
			'code' => 'ZAF',
			'continent_id' => 0
		),
	);
}
?>