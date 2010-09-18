<?php
/* ShopBranchesSpotlight Fixture generated on: 2010-08-17 14:08:49 : 1282055209 */
class ShopBranchesSpotlightFixture extends CakeTestFixture {
	var $name = 'ShopBranchesSpotlight';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'branch_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'spotlight_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM')
	);

	var $records = array(
		array(
			'id' => 6,
			'branch_id' => 2,
			'spotlight_id' => 3
		),
		array(
			'id' => 7,
			'branch_id' => 3,
			'spotlight_id' => 3
		),
	);
}
?>