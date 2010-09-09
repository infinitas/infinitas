<?php
/* CoreLog Fixture generated on: 2010-03-13 11:03:58 : 1268472118 */
class LogFixture extends CakeTestFixture {
	var $name = 'Log';

	var $table = 'core_logs';

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
		array(
			'id' => 1,
			'title' => 'Config (73)',
			'description' => 'Config (73) added by Management.User \"1\" (1).',
			'model' => 'Config',
			'model_id' => 73,
			'action' => 'add',
			'user_id' => 1,
			'change' => 'key () => (CORE.locked_options), value () => ({\"0\":\"Unlocked\",\"1\":\"Locked\"}), type () => (array), core () => (0), description () => (<p>\r\n	&nbsp;</p>)',
			'version_id' => NULL,
			'created' => '2010-03-05 14:38:45'
		),
		array(
			'id' => 2,
			'title' => 'Config (4)',
			'description' => 'Config (4) updated by Management.User \"1\" (1).',
			'model' => 'Config',
			'model_id' => 4,
			'action' => 'edit',
			'user_id' => 1,
			'change' => 'description (Application wide charset encoding) => (<p>\r\n	Application wide charset encoding</p>)',
			'version_id' => NULL,
			'created' => '2010-03-05 14:40:19'
		),
		array(
			'id' => 3,
			'title' => 'Config (72)',
			'description' => 'Config (72) updated by Management.User \"1\" (1).',
			'model' => 'Config',
			'model_id' => 72,
			'action' => 'edit',
			'user_id' => 1,
			'change' => 'value (blog) => (cms)',
			'version_id' => NULL,
			'created' => '2010-03-05 15:03:07'
		),
	);
}
?>