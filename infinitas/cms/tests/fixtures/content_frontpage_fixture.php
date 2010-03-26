<?php
/* ContentFrontpage Fixture generated on: 2009-12-13 19:12:29 : 1260726929 */
class ContentFrontpageFixture extends CakeTestFixture {
	var $name = 'ContentFrontpage';

	var $fields = array(
		'content_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'key' => 'primary'),
		'ordering' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'indexes' => array('PRIMARY' => array('column' => 'content_id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM')
	);

	var $records = array(
		array(
			'content_id' => 1,
			'ordering' => 1
		),
	);
}
?>