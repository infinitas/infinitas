<?php
class R4c8e68c4de2448378d8138ba6318cd70 extends CakeRelease {

/**
 * Migration description
 *
 * @var string
 * @access public
 */
	public $description = 'Migration for Tags version 0.8';

/**
 * Plugin name
 *
 * @var string
 * @access public
 */
	public $plugin = 'Tags';

/**
 * Actions to be performed
 *
 * @var array $migration
 * @access public
 */
	public $migration = array(
		'up' => array(
			'create_table' => array(
				'tagged' => array(
					'id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'key' => 'primary'),
					'foreign_key' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36),
					'tag_id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36),
					'model' => array('type' => 'string', 'null' => false, 'default' => NULL, 'key' => 'index'),
					'language' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 6, 'key' => 'index'),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
						'UNIQUE_TAGGING' => array('column' => array('model', 'foreign_key', 'tag_id', 'language'), 'unique' => 1),
						'INDEX_TAGGED' => array('column' => 'model', 'unique' => 0),
						'INDEX_LANGUAGE' => array('column' => 'language', 'unique' => 0),
					),
					'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM'),
				),
				'tags' => array(
					'id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'key' => 'primary'),
					'identifier' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 30, 'key' => 'index'),
					'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 30),
					'keyname' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 30),
					'weight' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 2),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
						'UNIQUE_TAG' => array('column' => array('identifier', 'keyname'), 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM'),
				),
			),
		),
		'down' => array(
			'drop_table' => array(
				'tagged', 'tags'
			),
		),
	);

/**
 * Fixtures for data
 *
 * @var array $fixtures
 * @access public
 */
	public $fixtures = array(
	'core' => array(
		'Tagged' => array(
		),
		'Tag' => array(
			array(
				'id' => '4bcf76bb-c0bc-4b64-a586-23006318cd70',
				'identifier' => NULL,
				'name' => 'sdaf',
				'keyname' => 'sdaf',
				'weight' => 0,
				'created' => '2010-04-22 01:05:47',
				'modified' => '2010-04-22 01:05:47'
			),
		),
		),
	);
	
/**
 * Before migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @return boolean Should process continue
 * @access public
 */
	public function before($direction) {
		return true;
	}

/**
 * After migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @return boolean Should process continue
 * @access public
 */
	public function after($direction) {
		return true;
	}
}
?>