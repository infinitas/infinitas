<?php
/* CoreShortUrl Fixture generated on: 2010-12-13 17:12:57 : 1292262477 */
class ShortUrlFixture extends CakeTestFixture {
	var $name = 'ShortUrl';

	var $table = 'core_short_urls';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'url' => array('type' => 'text', 'null' => false, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'views' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 9),
		'dead' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 2,
			'url' => 'http://site.com/whatever/again',
			'views' => 0,
			'dead' => 0,
			'created' => '2010-10-17 19:39:35',
			'modified' => '2010-10-19 13:39:25'
		),
	);
}
?>