<?php
/* Session Fixture generated on: 2010-08-17 14:08:40 : 1282055200 */
class SessionFixture extends CakeTestFixture {
	var $name = 'Session';

	var $fields = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 40, 'key' => 'primary'),
		'data' => array('type' => 'text', 'null' => true, 'default' => NULL),
		'expires' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'key' => 'index'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'id_unique' => array('column' => 'id', 'unique' => 1), 'expires_index' => array('column' => 'expires', 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 'akvsfv601fbku949o3jt6p1vq4',
			'data' => 'Wizard|a:1:{s:6:\"config\";a:4:{s:12:\"wizardAction\";s:6:\"index/\";s:5:\"steps\";a:4:{i:0;s:7:\"welcome\";i:1;s:8:\"database\";i:2;s:7:\"install\";i:3;s:10:\"admin_user\";}s:12:\"expectedStep\";s:7:\"welcome\";s:10:\"activeStep\";s:7:\"welcome\";}}Config|a:3:{s:9:\"userAgent\";s:32:\"ce5e6723591a25d7cd632420df64e024\";s:4:\"time\";i:1282049678;s:7:\"timeout\";i:10;}Infinitas|a:1:{s:8:\"Security\";a:1:{s:10:\"ip_checked\";b:1;}}_Token|s:179:\"a:5:{s:3:\"key\";s:40:\"9622a70ece29d5ddb5935b550927a25ba74b77ec\";s:7:\"expires\";i:1282043675;s:18:\"allowedControllers\";a:0:{}s:14:\"allowedActions\";a:0:{}s:14:\"disabledFields\";a:0:{}}\";',
			'expires' => 1282049679
		),
	);
}
?>