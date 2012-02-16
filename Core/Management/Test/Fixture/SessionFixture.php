<?php
/* Session Fixture generated on: 2010-03-11 23:03:10 : 1268341930 */
class SessionFixture extends CakeTestFixture {
	var $name = 'Session';

	var $table = 'sessions';

	var $fields = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 40, 'key' => 'primary'),
		'data' => array('type' => 'text', 'null' => true, 'default' => NULL),
		'expires' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 't4l44f3r33rp83ptmcn1gc73s1',
			'data' => 'Config|a:3:{s:9:\"userAgent\";s:32:\"9faf3285fc820d5ba33c6c83f6a87384\";s:4:\"time\";i:1268353628;s:7:\"timeout\";i:10;}Infinitas|a:1:{s:8:\"Security\";a:1:{s:10:\"ip_checked\";b:1;}}_Token|s:179:\"a:5:{s:3:\"key\";s:40:\"4b26dbbf37a3b73e501e7ed91d7adb6c700a42c8\";s:7:\"expires\";i:1268343220;s:18:\"allowedControllers\";a:0:{}s:14:\"allowedActions\";a:0:{}s:14:\"disabledFields\";a:0:{}}\";Message|a:0:{}',
			'expires' => 1268353628
		),
	);
}
?>