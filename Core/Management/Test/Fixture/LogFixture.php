<?php
/**
 * @brief fixture file for Log tests.
 *
 * @package Management.Fixture
 * @since 0.9b1
 */
class LogFixture extends CakeTestFixture {
	public $name = 'Log';
	public $table = 'core_logs';

	public $fields = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'title' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'description' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'model' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'model_id' => array('type' => 'integer', 'null' => true, 'default' => null),
		'action' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'user_id' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 36),
		'change' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'version_id' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 36),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	public $records = array(
		array(
			'id' => 1,
			'title' => 'Config (73)',
			'description' => 'Config (73) added by Management.User \\"1\\" (1).',
			'model' => 'Config',
			'model_id' => 73,
			'action' => 'add',
			'user_id' => 1,
			'change' => 'key () => (CORE.locked_options), value () => ({\\"0\\":\\"Unlocked\\",\\"1\\":\\"Locked\\"}), type () => (array), core () => (0), description () => (<p>\\r\\n	&nbsp;</p>)',
			'version_id' => null,
			'created' => '2010-03-05 14:38:45'
		),
		array(
			'id' => 2,
			'title' => 'Config (4)',
			'description' => 'Config (4) updated by Management.User \\"1\\" (1).',
			'model' => 'Config',
			'model_id' => 4,
			'action' => 'edit',
			'user_id' => 1,
			'change' => 'description (Application wide charset encoding) => (<p>\\r\\n	Application wide charset encoding</p>)',
			'version_id' => null,
			'created' => '2010-03-05 14:40:19'
		),
		array(
			'id' => 3,
			'title' => 'Config (72)',
			'description' => 'Config (72) updated by Management.User \\"1\\" (1).',
			'model' => 'Config',
			'model_id' => 72,
			'action' => 'edit',
			'user_id' => 1,
			'change' => 'value (blog) => (cms)',
			'version_id' => null,
			'created' => '2010-03-05 15:03:07'
		),
	);
}