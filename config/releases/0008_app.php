<?php
class R4c8fac209a28441b8b324a796318cd70 extends CakeRelease {

/**
 * Migration description
 *
 * @var string
 * @access public
 */
	public $description = 'Migration for App version 0.8';

/**
 * Plugin name
 *
 * @var string
 * @access public
 */
	public $plugin = 'App';

/**
 * Actions to be performed
 *
 * @var array $migration
 * @access public
 */
	public $migration = array(
		'up' => array(
			'create_table' => array(
				'acos' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
					'parent_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10),
					'model' => array('type' => 'string', 'null' => true, 'default' => NULL),
					'foreign_key' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10),
					'alias' => array('type' => 'string', 'null' => true, 'default' => NULL, 'key' => 'index'),
					'lft' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10),
					'rght' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
						'mptt_alias' => array('column' => array('alias', 'lft', 'rght'), 'unique' => 0),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
				'aros' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
					'parent_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10),
					'model' => array('type' => 'string', 'null' => true, 'default' => NULL),
					'foreign_key' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10),
					'alias' => array('type' => 'string', 'null' => true, 'default' => NULL),
					'lft' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10),
					'rght' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
				'aros_acos' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
					'aro_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'index'),
					'aco_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10),
					'_create' => array('type' => 'string', 'null' => false, 'default' => '0', 'length' => 2),
					'_read' => array('type' => 'string', 'null' => false, 'default' => '0', 'length' => 2),
					'_update' => array('type' => 'string', 'null' => false, 'default' => '0', 'length' => 2),
					'_delete' => array('type' => 'string', 'null' => false, 'default' => '0', 'length' => 2),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
						'ARO_ACO_KEY' => array('column' => array('aro_id', 'aco_id'), 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
				'sessions' => array(
					'id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 40, 'key' => 'primary'),
					'data' => array('type' => 'text', 'null' => true, 'default' => NULL),
					'expires' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'key' => 'index'),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
						'id_unique' => array('column' => 'id', 'unique' => 1),
						'expires_index' => array('column' => 'expires', 'unique' => 0),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
			),
		),
		'down' => array(
			'drop_table' => array(
				'acos', 'aros', 'aros_acos', 'sessions'
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
		'Aco' => array(
			array(
				'id' => 1,
				'parent_id' => 0,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'controllers',
				'lft' => 1,
				'rght' => 992
			),
			array(
				'id' => 2,
				'parent_id' => 1,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'Pages',
				'lft' => 2,
				'rght' => 21
			),
			array(
				'id' => 3,
				'parent_id' => 2,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_index',
				'lft' => 3,
				'rght' => 4
			),
			array(
				'id' => 4,
				'parent_id' => 2,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_add',
				'lft' => 5,
				'rght' => 6
			),
			array(
				'id' => 5,
				'parent_id' => 2,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_edit',
				'lft' => 7,
				'rght' => 8
			),
			array(
				'id' => 6,
				'parent_id' => 2,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getPlugins',
				'lft' => 9,
				'rght' => 10
			),
			array(
				'id' => 7,
				'parent_id' => 2,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getControllers',
				'lft' => 11,
				'rght' => 12
			),
			array(
				'id' => 8,
				'parent_id' => 2,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getActions',
				'lft' => 13,
				'rght' => 14
			),
			array(
				'id' => 9,
				'parent_id' => 2,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_mass',
				'lft' => 15,
				'rght' => 16
			),
			array(
				'id' => 10,
				'parent_id' => 2,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_reorder',
				'lft' => 17,
				'rght' => 18
			),
			array(
				'id' => 11,
				'parent_id' => 2,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_commentPurge',
				'lft' => 19,
				'rght' => 20
			),
			array(
				'id' => 12,
				'parent_id' => 1,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'Blog',
				'lft' => 22,
				'rght' => 51
			),
			array(
				'id' => 13,
				'parent_id' => 12,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'Posts',
				'lft' => 23,
				'rght' => 50
			),
			array(
				'id' => 14,
				'parent_id' => 13,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'index',
				'lft' => 24,
				'rght' => 25
			),
			array(
				'id' => 15,
				'parent_id' => 13,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'view',
				'lft' => 26,
				'rght' => 27
			),
			array(
				'id' => 16,
				'parent_id' => 13,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_dashboard',
				'lft' => 28,
				'rght' => 29
			),
			array(
				'id' => 17,
				'parent_id' => 13,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_index',
				'lft' => 30,
				'rght' => 31
			),
			array(
				'id' => 18,
				'parent_id' => 13,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_add',
				'lft' => 32,
				'rght' => 33
			),
			array(
				'id' => 19,
				'parent_id' => 13,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_edit',
				'lft' => 34,
				'rght' => 35
			),
			array(
				'id' => 20,
				'parent_id' => 13,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_view',
				'lft' => 36,
				'rght' => 37
			),
			array(
				'id' => 21,
				'parent_id' => 13,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getPlugins',
				'lft' => 38,
				'rght' => 39
			),
			array(
				'id' => 22,
				'parent_id' => 13,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getControllers',
				'lft' => 40,
				'rght' => 41
			),
			array(
				'id' => 23,
				'parent_id' => 13,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getActions',
				'lft' => 42,
				'rght' => 43
			),
			array(
				'id' => 24,
				'parent_id' => 13,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_mass',
				'lft' => 44,
				'rght' => 45
			),
			array(
				'id' => 25,
				'parent_id' => 13,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_reorder',
				'lft' => 46,
				'rght' => 47
			),
			array(
				'id' => 26,
				'parent_id' => 13,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_commentPurge',
				'lft' => 48,
				'rght' => 49
			),
			array(
				'id' => 27,
				'parent_id' => 1,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'Cms',
				'lft' => 52,
				'rght' => 139
			),
			array(
				'id' => 28,
				'parent_id' => 27,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'Contents',
				'lft' => 53,
				'rght' => 78
			),
			array(
				'id' => 29,
				'parent_id' => 28,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'index',
				'lft' => 54,
				'rght' => 55
			),
			array(
				'id' => 30,
				'parent_id' => 28,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'view',
				'lft' => 56,
				'rght' => 57
			),
			array(
				'id' => 31,
				'parent_id' => 28,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_index',
				'lft' => 58,
				'rght' => 59
			),
			array(
				'id' => 32,
				'parent_id' => 28,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_view',
				'lft' => 60,
				'rght' => 61
			),
			array(
				'id' => 33,
				'parent_id' => 28,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_add',
				'lft' => 62,
				'rght' => 63
			),
			array(
				'id' => 34,
				'parent_id' => 28,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_edit',
				'lft' => 64,
				'rght' => 65
			),
			array(
				'id' => 35,
				'parent_id' => 28,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getPlugins',
				'lft' => 66,
				'rght' => 67
			),
			array(
				'id' => 36,
				'parent_id' => 28,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getControllers',
				'lft' => 68,
				'rght' => 69
			),
			array(
				'id' => 37,
				'parent_id' => 28,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getActions',
				'lft' => 70,
				'rght' => 71
			),
			array(
				'id' => 38,
				'parent_id' => 28,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_mass',
				'lft' => 72,
				'rght' => 73
			),
			array(
				'id' => 39,
				'parent_id' => 28,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_reorder',
				'lft' => 74,
				'rght' => 75
			),
			array(
				'id' => 40,
				'parent_id' => 28,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_commentPurge',
				'lft' => 76,
				'rght' => 77
			),
			array(
				'id' => 41,
				'parent_id' => 27,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'Features',
				'lft' => 79,
				'rght' => 98
			),
			array(
				'id' => 42,
				'parent_id' => 41,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'index',
				'lft' => 80,
				'rght' => 81
			),
			array(
				'id' => 43,
				'parent_id' => 41,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_index',
				'lft' => 82,
				'rght' => 83
			),
			array(
				'id' => 44,
				'parent_id' => 41,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_add',
				'lft' => 84,
				'rght' => 85
			),
			array(
				'id' => 45,
				'parent_id' => 41,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getPlugins',
				'lft' => 86,
				'rght' => 87
			),
			array(
				'id' => 46,
				'parent_id' => 41,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getControllers',
				'lft' => 88,
				'rght' => 89
			),
			array(
				'id' => 47,
				'parent_id' => 41,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getActions',
				'lft' => 90,
				'rght' => 91
			),
			array(
				'id' => 48,
				'parent_id' => 41,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_mass',
				'lft' => 92,
				'rght' => 93
			),
			array(
				'id' => 49,
				'parent_id' => 41,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_reorder',
				'lft' => 94,
				'rght' => 95
			),
			array(
				'id' => 50,
				'parent_id' => 41,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_commentPurge',
				'lft' => 96,
				'rght' => 97
			),
			array(
				'id' => 51,
				'parent_id' => 27,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'Frontpages',
				'lft' => 99,
				'rght' => 118
			),
			array(
				'id' => 52,
				'parent_id' => 51,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'index',
				'lft' => 100,
				'rght' => 101
			),
			array(
				'id' => 53,
				'parent_id' => 51,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_index',
				'lft' => 102,
				'rght' => 103
			),
			array(
				'id' => 54,
				'parent_id' => 51,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_add',
				'lft' => 104,
				'rght' => 105
			),
			array(
				'id' => 55,
				'parent_id' => 51,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getPlugins',
				'lft' => 106,
				'rght' => 107
			),
			array(
				'id' => 56,
				'parent_id' => 51,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getControllers',
				'lft' => 108,
				'rght' => 109
			),
			array(
				'id' => 57,
				'parent_id' => 51,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getActions',
				'lft' => 110,
				'rght' => 111
			),
			array(
				'id' => 58,
				'parent_id' => 51,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_mass',
				'lft' => 112,
				'rght' => 113
			),
			array(
				'id' => 59,
				'parent_id' => 51,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_reorder',
				'lft' => 114,
				'rght' => 115
			),
			array(
				'id' => 60,
				'parent_id' => 51,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_commentPurge',
				'lft' => 116,
				'rght' => 117
			),
			array(
				'id' => 61,
				'parent_id' => 27,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'Layouts',
				'lft' => 119,
				'rght' => 138
			),
			array(
				'id' => 62,
				'parent_id' => 61,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_index',
				'lft' => 120,
				'rght' => 121
			),
			array(
				'id' => 63,
				'parent_id' => 61,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_add',
				'lft' => 122,
				'rght' => 123
			),
			array(
				'id' => 64,
				'parent_id' => 61,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_edit',
				'lft' => 124,
				'rght' => 125
			),
			array(
				'id' => 65,
				'parent_id' => 61,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getPlugins',
				'lft' => 126,
				'rght' => 127
			),
			array(
				'id' => 66,
				'parent_id' => 61,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getControllers',
				'lft' => 128,
				'rght' => 129
			),
			array(
				'id' => 67,
				'parent_id' => 61,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getActions',
				'lft' => 130,
				'rght' => 131
			),
			array(
				'id' => 68,
				'parent_id' => 61,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_mass',
				'lft' => 132,
				'rght' => 133
			),
			array(
				'id' => 69,
				'parent_id' => 61,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_reorder',
				'lft' => 134,
				'rght' => 135
			),
			array(
				'id' => 70,
				'parent_id' => 61,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_commentPurge',
				'lft' => 136,
				'rght' => 137
			),
			array(
				'id' => 71,
				'parent_id' => 1,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'Contact',
				'lft' => 140,
				'rght' => 187
			),
			array(
				'id' => 72,
				'parent_id' => 71,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'Branches',
				'lft' => 141,
				'rght' => 164
			),
			array(
				'id' => 73,
				'parent_id' => 72,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'index',
				'lft' => 142,
				'rght' => 143
			),
			array(
				'id' => 74,
				'parent_id' => 72,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'view',
				'lft' => 144,
				'rght' => 145
			),
			array(
				'id' => 75,
				'parent_id' => 72,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_index',
				'lft' => 146,
				'rght' => 147
			),
			array(
				'id' => 76,
				'parent_id' => 72,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_add',
				'lft' => 148,
				'rght' => 149
			),
			array(
				'id' => 77,
				'parent_id' => 72,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_edit',
				'lft' => 150,
				'rght' => 151
			),
			array(
				'id' => 78,
				'parent_id' => 72,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getPlugins',
				'lft' => 152,
				'rght' => 153
			),
			array(
				'id' => 79,
				'parent_id' => 72,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getControllers',
				'lft' => 154,
				'rght' => 155
			),
			array(
				'id' => 80,
				'parent_id' => 72,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getActions',
				'lft' => 156,
				'rght' => 157
			),
			array(
				'id' => 81,
				'parent_id' => 72,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_mass',
				'lft' => 158,
				'rght' => 159
			),
			array(
				'id' => 82,
				'parent_id' => 72,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_reorder',
				'lft' => 160,
				'rght' => 161
			),
			array(
				'id' => 83,
				'parent_id' => 72,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_commentPurge',
				'lft' => 162,
				'rght' => 163
			),
			array(
				'id' => 84,
				'parent_id' => 71,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'Contacts',
				'lft' => 165,
				'rght' => 186
			),
			array(
				'id' => 85,
				'parent_id' => 84,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_index',
				'lft' => 166,
				'rght' => 167
			),
			array(
				'id' => 86,
				'parent_id' => 84,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'view',
				'lft' => 168,
				'rght' => 169
			),
			array(
				'id' => 87,
				'parent_id' => 84,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_add',
				'lft' => 170,
				'rght' => 171
			),
			array(
				'id' => 88,
				'parent_id' => 84,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_edit',
				'lft' => 172,
				'rght' => 173
			),
			array(
				'id' => 89,
				'parent_id' => 84,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getPlugins',
				'lft' => 174,
				'rght' => 175
			),
			array(
				'id' => 90,
				'parent_id' => 84,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getControllers',
				'lft' => 176,
				'rght' => 177
			),
			array(
				'id' => 91,
				'parent_id' => 84,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getActions',
				'lft' => 178,
				'rght' => 179
			),
			array(
				'id' => 92,
				'parent_id' => 84,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_mass',
				'lft' => 180,
				'rght' => 181
			),
			array(
				'id' => 93,
				'parent_id' => 84,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_reorder',
				'lft' => 182,
				'rght' => 183
			),
			array(
				'id' => 94,
				'parent_id' => 84,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_commentPurge',
				'lft' => 184,
				'rght' => 185
			),
			array(
				'id' => 95,
				'parent_id' => 1,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'Feed',
				'lft' => 188,
				'rght' => 229
			),
			array(
				'id' => 96,
				'parent_id' => 95,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'Feeds',
				'lft' => 189,
				'rght' => 208
			),
			array(
				'id' => 97,
				'parent_id' => 96,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_index',
				'lft' => 190,
				'rght' => 191
			),
			array(
				'id' => 98,
				'parent_id' => 96,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_add',
				'lft' => 192,
				'rght' => 193
			),
			array(
				'id' => 99,
				'parent_id' => 96,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_edit',
				'lft' => 194,
				'rght' => 195
			),
			array(
				'id' => 100,
				'parent_id' => 96,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getPlugins',
				'lft' => 196,
				'rght' => 197
			),
			array(
				'id' => 101,
				'parent_id' => 96,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getControllers',
				'lft' => 198,
				'rght' => 199
			),
			array(
				'id' => 102,
				'parent_id' => 96,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getActions',
				'lft' => 200,
				'rght' => 201
			),
			array(
				'id' => 103,
				'parent_id' => 96,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_mass',
				'lft' => 202,
				'rght' => 203
			),
			array(
				'id' => 104,
				'parent_id' => 96,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_reorder',
				'lft' => 204,
				'rght' => 205
			),
			array(
				'id' => 105,
				'parent_id' => 96,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_commentPurge',
				'lft' => 206,
				'rght' => 207
			),
			array(
				'id' => 106,
				'parent_id' => 95,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'FeedItems',
				'lft' => 209,
				'rght' => 228
			),
			array(
				'id' => 107,
				'parent_id' => 106,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_index',
				'lft' => 210,
				'rght' => 211
			),
			array(
				'id' => 108,
				'parent_id' => 106,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_add',
				'lft' => 212,
				'rght' => 213
			),
			array(
				'id' => 109,
				'parent_id' => 106,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_edit',
				'lft' => 214,
				'rght' => 215
			),
			array(
				'id' => 110,
				'parent_id' => 106,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getPlugins',
				'lft' => 216,
				'rght' => 217
			),
			array(
				'id' => 111,
				'parent_id' => 106,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getControllers',
				'lft' => 218,
				'rght' => 219
			),
			array(
				'id' => 112,
				'parent_id' => 106,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getActions',
				'lft' => 220,
				'rght' => 221
			),
			array(
				'id' => 113,
				'parent_id' => 106,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_mass',
				'lft' => 222,
				'rght' => 223
			),
			array(
				'id' => 114,
				'parent_id' => 106,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_reorder',
				'lft' => 224,
				'rght' => 225
			),
			array(
				'id' => 115,
				'parent_id' => 106,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_commentPurge',
				'lft' => 226,
				'rght' => 227
			),
			array(
				'id' => 116,
				'parent_id' => 1,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'Filemanager',
				'lft' => 230,
				'rght' => 251
			),
			array(
				'id' => 117,
				'parent_id' => 116,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'FileManager',
				'lft' => 231,
				'rght' => 250
			),
			array(
				'id' => 118,
				'parent_id' => 117,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_index',
				'lft' => 232,
				'rght' => 233
			),
			array(
				'id' => 119,
				'parent_id' => 117,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_view',
				'lft' => 234,
				'rght' => 235
			),
			array(
				'id' => 120,
				'parent_id' => 117,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_download',
				'lft' => 236,
				'rght' => 237
			),
			array(
				'id' => 121,
				'parent_id' => 117,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getPlugins',
				'lft' => 238,
				'rght' => 239
			),
			array(
				'id' => 122,
				'parent_id' => 117,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getControllers',
				'lft' => 240,
				'rght' => 241
			),
			array(
				'id' => 123,
				'parent_id' => 117,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getActions',
				'lft' => 242,
				'rght' => 243
			),
			array(
				'id' => 124,
				'parent_id' => 117,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_mass',
				'lft' => 244,
				'rght' => 245
			),
			array(
				'id' => 125,
				'parent_id' => 117,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_reorder',
				'lft' => 246,
				'rght' => 247
			),
			array(
				'id' => 126,
				'parent_id' => 117,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_commentPurge',
				'lft' => 248,
				'rght' => 249
			),
			array(
				'id' => 127,
				'parent_id' => 1,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'Installer',
				'lft' => 252,
				'rght' => 311
			),
			array(
				'id' => 128,
				'parent_id' => 127,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'Install',
				'lft' => 253,
				'rght' => 268
			),
			array(
				'id' => 129,
				'parent_id' => 128,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'index',
				'lft' => 254,
				'rght' => 255
			),
			array(
				'id' => 130,
				'parent_id' => 128,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'licence',
				'lft' => 256,
				'rght' => 257
			),
			array(
				'id' => 131,
				'parent_id' => 128,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'database',
				'lft' => 258,
				'rght' => 259
			),
			array(
				'id' => 132,
				'parent_id' => 128,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'install',
				'lft' => 260,
				'rght' => 261
			),
			array(
				'id' => 133,
				'parent_id' => 128,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'siteConfig',
				'lft' => 262,
				'rght' => 263
			),
			array(
				'id' => 134,
				'parent_id' => 128,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'done',
				'lft' => 264,
				'rght' => 265
			),
			array(
				'id' => 135,
				'parent_id' => 128,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'path',
				'lft' => 266,
				'rght' => 267
			),
			array(
				'id' => 136,
				'parent_id' => 127,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'Releases',
				'lft' => 269,
				'rght' => 292
			),
			array(
				'id' => 137,
				'parent_id' => 136,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_index',
				'lft' => 270,
				'rght' => 271
			),
			array(
				'id' => 138,
				'parent_id' => 136,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_update_core',
				'lft' => 272,
				'rght' => 273
			),
			array(
				'id' => 139,
				'parent_id' => 136,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_update_sample',
				'lft' => 274,
				'rght' => 275
			),
			array(
				'id' => 140,
				'parent_id' => 136,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_core_data',
				'lft' => 276,
				'rght' => 277
			),
			array(
				'id' => 141,
				'parent_id' => 136,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_sample_data',
				'lft' => 278,
				'rght' => 279
			),
			array(
				'id' => 142,
				'parent_id' => 136,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getPlugins',
				'lft' => 280,
				'rght' => 281
			),
			array(
				'id' => 143,
				'parent_id' => 136,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getControllers',
				'lft' => 282,
				'rght' => 283
			),
			array(
				'id' => 144,
				'parent_id' => 136,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getActions',
				'lft' => 284,
				'rght' => 285
			),
			array(
				'id' => 145,
				'parent_id' => 136,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_mass',
				'lft' => 286,
				'rght' => 287
			),
			array(
				'id' => 146,
				'parent_id' => 136,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_reorder',
				'lft' => 288,
				'rght' => 289
			),
			array(
				'id' => 147,
				'parent_id' => 136,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_commentPurge',
				'lft' => 290,
				'rght' => 291
			),
			array(
				'id' => 148,
				'parent_id' => 127,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'Upgrade',
				'lft' => 293,
				'rght' => 310
			),
			array(
				'id' => 149,
				'parent_id' => 148,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_index',
				'lft' => 294,
				'rght' => 295
			),
			array(
				'id' => 150,
				'parent_id' => 148,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_update',
				'lft' => 296,
				'rght' => 297
			),
			array(
				'id' => 151,
				'parent_id' => 148,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getPlugins',
				'lft' => 298,
				'rght' => 299
			),
			array(
				'id' => 152,
				'parent_id' => 148,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getControllers',
				'lft' => 300,
				'rght' => 301
			),
			array(
				'id' => 153,
				'parent_id' => 148,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getActions',
				'lft' => 302,
				'rght' => 303
			),
			array(
				'id' => 154,
				'parent_id' => 148,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_mass',
				'lft' => 304,
				'rght' => 305
			),
			array(
				'id' => 155,
				'parent_id' => 148,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_reorder',
				'lft' => 306,
				'rght' => 307
			),
			array(
				'id' => 156,
				'parent_id' => 148,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_commentPurge',
				'lft' => 308,
				'rght' => 309
			),
			array(
				'id' => 157,
				'parent_id' => 1,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'Management',
				'lft' => 312,
				'rght' => 635
			),
			array(
				'id' => 158,
				'parent_id' => 157,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'Acos',
				'lft' => 313,
				'rght' => 334
			),
			array(
				'id' => 159,
				'parent_id' => 158,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_index',
				'lft' => 314,
				'rght' => 315
			),
			array(
				'id' => 160,
				'parent_id' => 158,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_view',
				'lft' => 316,
				'rght' => 317
			),
			array(
				'id' => 161,
				'parent_id' => 158,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_add',
				'lft' => 318,
				'rght' => 319
			),
			array(
				'id' => 162,
				'parent_id' => 158,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_edit',
				'lft' => 320,
				'rght' => 321
			),
			array(
				'id' => 163,
				'parent_id' => 158,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getPlugins',
				'lft' => 322,
				'rght' => 323
			),
			array(
				'id' => 164,
				'parent_id' => 158,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getControllers',
				'lft' => 324,
				'rght' => 325
			),
			array(
				'id' => 165,
				'parent_id' => 158,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getActions',
				'lft' => 326,
				'rght' => 327
			),
			array(
				'id' => 166,
				'parent_id' => 158,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_mass',
				'lft' => 328,
				'rght' => 329
			),
			array(
				'id' => 167,
				'parent_id' => 158,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_reorder',
				'lft' => 330,
				'rght' => 331
			),
			array(
				'id' => 168,
				'parent_id' => 158,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_commentPurge',
				'lft' => 332,
				'rght' => 333
			),
			array(
				'id' => 169,
				'parent_id' => 157,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'Backlinks',
				'lft' => 335,
				'rght' => 350
			),
			array(
				'id' => 170,
				'parent_id' => 169,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_index',
				'lft' => 336,
				'rght' => 337
			),
			array(
				'id' => 171,
				'parent_id' => 169,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getPlugins',
				'lft' => 338,
				'rght' => 339
			),
			array(
				'id' => 172,
				'parent_id' => 169,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getControllers',
				'lft' => 340,
				'rght' => 341
			),
			array(
				'id' => 173,
				'parent_id' => 169,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getActions',
				'lft' => 342,
				'rght' => 343
			),
			array(
				'id' => 174,
				'parent_id' => 169,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_mass',
				'lft' => 344,
				'rght' => 345
			),
			array(
				'id' => 175,
				'parent_id' => 169,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_reorder',
				'lft' => 346,
				'rght' => 347
			),
			array(
				'id' => 176,
				'parent_id' => 169,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_commentPurge',
				'lft' => 348,
				'rght' => 349
			),
			array(
				'id' => 177,
				'parent_id' => 157,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'Backups',
				'lft' => 351,
				'rght' => 366
			),
			array(
				'id' => 178,
				'parent_id' => 177,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_backup',
				'lft' => 352,
				'rght' => 353
			),
			array(
				'id' => 179,
				'parent_id' => 177,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getPlugins',
				'lft' => 354,
				'rght' => 355
			),
			array(
				'id' => 180,
				'parent_id' => 177,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getControllers',
				'lft' => 356,
				'rght' => 357
			),
			array(
				'id' => 181,
				'parent_id' => 177,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getActions',
				'lft' => 358,
				'rght' => 359
			),
			array(
				'id' => 182,
				'parent_id' => 177,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_mass',
				'lft' => 360,
				'rght' => 361
			),
			array(
				'id' => 183,
				'parent_id' => 177,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_reorder',
				'lft' => 362,
				'rght' => 363
			),
			array(
				'id' => 184,
				'parent_id' => 177,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_commentPurge',
				'lft' => 364,
				'rght' => 365
			),
			array(
				'id' => 185,
				'parent_id' => 157,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'Comments',
				'lft' => 367,
				'rght' => 388
			),
			array(
				'id' => 186,
				'parent_id' => 185,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_index',
				'lft' => 368,
				'rght' => 369
			),
			array(
				'id' => 187,
				'parent_id' => 185,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_toggle',
				'lft' => 370,
				'rght' => 371
			),
			array(
				'id' => 188,
				'parent_id' => 185,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_reply',
				'lft' => 372,
				'rght' => 373
			),
			array(
				'id' => 189,
				'parent_id' => 185,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_perge',
				'lft' => 374,
				'rght' => 375
			),
			array(
				'id' => 190,
				'parent_id' => 185,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_commentPurge',
				'lft' => 376,
				'rght' => 377
			),
			array(
				'id' => 191,
				'parent_id' => 185,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getPlugins',
				'lft' => 378,
				'rght' => 379
			),
			array(
				'id' => 192,
				'parent_id' => 185,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getControllers',
				'lft' => 380,
				'rght' => 381
			),
			array(
				'id' => 193,
				'parent_id' => 185,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getActions',
				'lft' => 382,
				'rght' => 383
			),
			array(
				'id' => 194,
				'parent_id' => 185,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_mass',
				'lft' => 384,
				'rght' => 385
			),
			array(
				'id' => 195,
				'parent_id' => 185,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_reorder',
				'lft' => 386,
				'rght' => 387
			),
			array(
				'id' => 196,
				'parent_id' => 157,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'Configs',
				'lft' => 389,
				'rght' => 408
			),
			array(
				'id' => 197,
				'parent_id' => 196,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_index',
				'lft' => 390,
				'rght' => 391
			),
			array(
				'id' => 198,
				'parent_id' => 196,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_add',
				'lft' => 392,
				'rght' => 393
			),
			array(
				'id' => 199,
				'parent_id' => 196,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_edit',
				'lft' => 394,
				'rght' => 395
			),
			array(
				'id' => 200,
				'parent_id' => 196,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getPlugins',
				'lft' => 396,
				'rght' => 397
			),
			array(
				'id' => 201,
				'parent_id' => 196,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getControllers',
				'lft' => 398,
				'rght' => 399
			),
			array(
				'id' => 202,
				'parent_id' => 196,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getActions',
				'lft' => 400,
				'rght' => 401
			),
			array(
				'id' => 203,
				'parent_id' => 196,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_mass',
				'lft' => 402,
				'rght' => 403
			),
			array(
				'id' => 204,
				'parent_id' => 196,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_reorder',
				'lft' => 404,
				'rght' => 405
			),
			array(
				'id' => 205,
				'parent_id' => 196,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_commentPurge',
				'lft' => 406,
				'rght' => 407
			),
			array(
				'id' => 206,
				'parent_id' => 157,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'IpAddresses',
				'lft' => 409,
				'rght' => 428
			),
			array(
				'id' => 207,
				'parent_id' => 206,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_index',
				'lft' => 410,
				'rght' => 411
			),
			array(
				'id' => 208,
				'parent_id' => 206,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_add',
				'lft' => 412,
				'rght' => 413
			),
			array(
				'id' => 209,
				'parent_id' => 206,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_edit',
				'lft' => 414,
				'rght' => 415
			),
			array(
				'id' => 210,
				'parent_id' => 206,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getPlugins',
				'lft' => 416,
				'rght' => 417
			),
			array(
				'id' => 211,
				'parent_id' => 206,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getControllers',
				'lft' => 418,
				'rght' => 419
			),
			array(
				'id' => 212,
				'parent_id' => 206,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getActions',
				'lft' => 420,
				'rght' => 421
			),
			array(
				'id' => 213,
				'parent_id' => 206,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_mass',
				'lft' => 422,
				'rght' => 423
			),
			array(
				'id' => 214,
				'parent_id' => 206,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_reorder',
				'lft' => 424,
				'rght' => 425
			),
			array(
				'id' => 215,
				'parent_id' => 206,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_commentPurge',
				'lft' => 426,
				'rght' => 427
			),
			array(
				'id' => 216,
				'parent_id' => 157,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'Locks',
				'lft' => 429,
				'rght' => 446
			),
			array(
				'id' => 217,
				'parent_id' => 216,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_index',
				'lft' => 430,
				'rght' => 431
			),
			array(
				'id' => 218,
				'parent_id' => 216,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_unlock',
				'lft' => 432,
				'rght' => 433
			),
			array(
				'id' => 219,
				'parent_id' => 216,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getPlugins',
				'lft' => 434,
				'rght' => 435
			),
			array(
				'id' => 220,
				'parent_id' => 216,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getControllers',
				'lft' => 436,
				'rght' => 437
			),
			array(
				'id' => 221,
				'parent_id' => 216,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getActions',
				'lft' => 438,
				'rght' => 439
			),
			array(
				'id' => 222,
				'parent_id' => 216,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_mass',
				'lft' => 440,
				'rght' => 441
			),
			array(
				'id' => 223,
				'parent_id' => 216,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_reorder',
				'lft' => 442,
				'rght' => 443
			),
			array(
				'id' => 224,
				'parent_id' => 216,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_commentPurge',
				'lft' => 444,
				'rght' => 445
			),
			array(
				'id' => 225,
				'parent_id' => 157,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'Management',
				'lft' => 447,
				'rght' => 462
			),
			array(
				'id' => 226,
				'parent_id' => 225,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_dashboard',
				'lft' => 448,
				'rght' => 449
			),
			array(
				'id' => 227,
				'parent_id' => 225,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getPlugins',
				'lft' => 450,
				'rght' => 451
			),
			array(
				'id' => 228,
				'parent_id' => 225,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getControllers',
				'lft' => 452,
				'rght' => 453
			),
			array(
				'id' => 229,
				'parent_id' => 225,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getActions',
				'lft' => 454,
				'rght' => 455
			),
			array(
				'id' => 230,
				'parent_id' => 225,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_mass',
				'lft' => 456,
				'rght' => 457
			),
			array(
				'id' => 231,
				'parent_id' => 225,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_reorder',
				'lft' => 458,
				'rght' => 459
			),
			array(
				'id' => 232,
				'parent_id' => 225,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_commentPurge',
				'lft' => 460,
				'rght' => 461
			),
			array(
				'id' => 233,
				'parent_id' => 157,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'Menus',
				'lft' => 463,
				'rght' => 482
			),
			array(
				'id' => 234,
				'parent_id' => 233,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_index',
				'lft' => 464,
				'rght' => 465
			),
			array(
				'id' => 235,
				'parent_id' => 233,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_add',
				'lft' => 466,
				'rght' => 467
			),
			array(
				'id' => 236,
				'parent_id' => 233,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_edit',
				'lft' => 468,
				'rght' => 469
			),
			array(
				'id' => 237,
				'parent_id' => 233,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getPlugins',
				'lft' => 470,
				'rght' => 471
			),
			array(
				'id' => 238,
				'parent_id' => 233,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getControllers',
				'lft' => 472,
				'rght' => 473
			),
			array(
				'id' => 239,
				'parent_id' => 233,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getActions',
				'lft' => 474,
				'rght' => 475
			),
			array(
				'id' => 240,
				'parent_id' => 233,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_mass',
				'lft' => 476,
				'rght' => 477
			),
			array(
				'id' => 241,
				'parent_id' => 233,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_reorder',
				'lft' => 478,
				'rght' => 479
			),
			array(
				'id' => 242,
				'parent_id' => 233,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_commentPurge',
				'lft' => 480,
				'rght' => 481
			),
			array(
				'id' => 243,
				'parent_id' => 157,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'MenuItems',
				'lft' => 483,
				'rght' => 504
			),
			array(
				'id' => 244,
				'parent_id' => 243,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_index',
				'lft' => 484,
				'rght' => 485
			),
			array(
				'id' => 245,
				'parent_id' => 243,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_add',
				'lft' => 486,
				'rght' => 487
			),
			array(
				'id' => 246,
				'parent_id' => 243,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getParents',
				'lft' => 488,
				'rght' => 489
			),
			array(
				'id' => 247,
				'parent_id' => 243,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_edit',
				'lft' => 490,
				'rght' => 491
			),
			array(
				'id' => 248,
				'parent_id' => 243,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getPlugins',
				'lft' => 492,
				'rght' => 493
			),
			array(
				'id' => 249,
				'parent_id' => 243,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getControllers',
				'lft' => 494,
				'rght' => 495
			),
			array(
				'id' => 250,
				'parent_id' => 243,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getActions',
				'lft' => 496,
				'rght' => 497
			),
			array(
				'id' => 251,
				'parent_id' => 243,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_mass',
				'lft' => 498,
				'rght' => 499
			),
			array(
				'id' => 252,
				'parent_id' => 243,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_reorder',
				'lft' => 500,
				'rght' => 501
			),
			array(
				'id' => 253,
				'parent_id' => 243,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_commentPurge',
				'lft' => 502,
				'rght' => 503
			),
			array(
				'id' => 254,
				'parent_id' => 157,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'Modules',
				'lft' => 505,
				'rght' => 524
			),
			array(
				'id' => 255,
				'parent_id' => 254,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_index',
				'lft' => 506,
				'rght' => 507
			),
			array(
				'id' => 256,
				'parent_id' => 254,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_add',
				'lft' => 508,
				'rght' => 509
			),
			array(
				'id' => 257,
				'parent_id' => 254,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_edit',
				'lft' => 510,
				'rght' => 511
			),
			array(
				'id' => 258,
				'parent_id' => 254,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getPlugins',
				'lft' => 512,
				'rght' => 513
			),
			array(
				'id' => 259,
				'parent_id' => 254,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getControllers',
				'lft' => 514,
				'rght' => 515
			),
			array(
				'id' => 260,
				'parent_id' => 254,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getActions',
				'lft' => 516,
				'rght' => 517
			),
			array(
				'id' => 261,
				'parent_id' => 254,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_mass',
				'lft' => 518,
				'rght' => 519
			),
			array(
				'id' => 262,
				'parent_id' => 254,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_reorder',
				'lft' => 520,
				'rght' => 521
			),
			array(
				'id' => 263,
				'parent_id' => 254,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_commentPurge',
				'lft' => 522,
				'rght' => 523
			),
			array(
				'id' => 264,
				'parent_id' => 157,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'Pages',
				'lft' => 525,
				'rght' => 544
			),
			array(
				'id' => 265,
				'parent_id' => 264,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_index',
				'lft' => 526,
				'rght' => 527
			),
			array(
				'id' => 266,
				'parent_id' => 264,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_add',
				'lft' => 528,
				'rght' => 529
			),
			array(
				'id' => 267,
				'parent_id' => 264,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_edit',
				'lft' => 530,
				'rght' => 531
			),
			array(
				'id' => 268,
				'parent_id' => 264,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getPlugins',
				'lft' => 532,
				'rght' => 533
			),
			array(
				'id' => 269,
				'parent_id' => 264,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getControllers',
				'lft' => 534,
				'rght' => 535
			),
			array(
				'id' => 270,
				'parent_id' => 264,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getActions',
				'lft' => 536,
				'rght' => 537
			),
			array(
				'id' => 271,
				'parent_id' => 264,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_mass',
				'lft' => 538,
				'rght' => 539
			),
			array(
				'id' => 272,
				'parent_id' => 264,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_reorder',
				'lft' => 540,
				'rght' => 541
			),
			array(
				'id' => 273,
				'parent_id' => 264,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_commentPurge',
				'lft' => 542,
				'rght' => 543
			),
			array(
				'id' => 274,
				'parent_id' => 157,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'Routes',
				'lft' => 545,
				'rght' => 564
			),
			array(
				'id' => 275,
				'parent_id' => 274,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_index',
				'lft' => 546,
				'rght' => 547
			),
			array(
				'id' => 276,
				'parent_id' => 274,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_add',
				'lft' => 548,
				'rght' => 549
			),
			array(
				'id' => 277,
				'parent_id' => 274,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_edit',
				'lft' => 550,
				'rght' => 551
			),
			array(
				'id' => 278,
				'parent_id' => 274,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getPlugins',
				'lft' => 552,
				'rght' => 553
			),
			array(
				'id' => 279,
				'parent_id' => 274,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getControllers',
				'lft' => 554,
				'rght' => 555
			),
			array(
				'id' => 280,
				'parent_id' => 274,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getActions',
				'lft' => 556,
				'rght' => 557
			),
			array(
				'id' => 281,
				'parent_id' => 274,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_mass',
				'lft' => 558,
				'rght' => 559
			),
			array(
				'id' => 282,
				'parent_id' => 274,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_reorder',
				'lft' => 560,
				'rght' => 561
			),
			array(
				'id' => 283,
				'parent_id' => 274,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_commentPurge',
				'lft' => 562,
				'rght' => 563
			),
			array(
				'id' => 284,
				'parent_id' => 157,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'Themes',
				'lft' => 565,
				'rght' => 584
			),
			array(
				'id' => 285,
				'parent_id' => 284,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_index',
				'lft' => 566,
				'rght' => 567
			),
			array(
				'id' => 286,
				'parent_id' => 284,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_add',
				'lft' => 568,
				'rght' => 569
			),
			array(
				'id' => 287,
				'parent_id' => 284,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_edit',
				'lft' => 570,
				'rght' => 571
			),
			array(
				'id' => 288,
				'parent_id' => 284,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getPlugins',
				'lft' => 572,
				'rght' => 573
			),
			array(
				'id' => 289,
				'parent_id' => 284,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getControllers',
				'lft' => 574,
				'rght' => 575
			),
			array(
				'id' => 290,
				'parent_id' => 284,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getActions',
				'lft' => 576,
				'rght' => 577
			),
			array(
				'id' => 291,
				'parent_id' => 284,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_mass',
				'lft' => 578,
				'rght' => 579
			),
			array(
				'id' => 292,
				'parent_id' => 284,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_reorder',
				'lft' => 580,
				'rght' => 581
			),
			array(
				'id' => 293,
				'parent_id' => 284,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_commentPurge',
				'lft' => 582,
				'rght' => 583
			),
			array(
				'id' => 294,
				'parent_id' => 157,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'Trash',
				'lft' => 585,
				'rght' => 600
			),
			array(
				'id' => 295,
				'parent_id' => 294,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_index',
				'lft' => 586,
				'rght' => 587
			),
			array(
				'id' => 296,
				'parent_id' => 294,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getPlugins',
				'lft' => 588,
				'rght' => 589
			),
			array(
				'id' => 297,
				'parent_id' => 294,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getControllers',
				'lft' => 590,
				'rght' => 591
			),
			array(
				'id' => 298,
				'parent_id' => 294,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getActions',
				'lft' => 592,
				'rght' => 593
			),
			array(
				'id' => 299,
				'parent_id' => 294,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_mass',
				'lft' => 594,
				'rght' => 595
			),
			array(
				'id' => 300,
				'parent_id' => 294,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_reorder',
				'lft' => 596,
				'rght' => 597
			),
			array(
				'id' => 301,
				'parent_id' => 294,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_commentPurge',
				'lft' => 598,
				'rght' => 599
			),
			array(
				'id' => 302,
				'parent_id' => 157,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'Users',
				'lft' => 601,
				'rght' => 634
			),
			array(
				'id' => 303,
				'parent_id' => 302,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'login',
				'lft' => 602,
				'rght' => 603
			),
			array(
				'id' => 304,
				'parent_id' => 302,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'logout',
				'lft' => 604,
				'rght' => 605
			),
			array(
				'id' => 305,
				'parent_id' => 302,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'register',
				'lft' => 606,
				'rght' => 607
			),
			array(
				'id' => 306,
				'parent_id' => 302,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_login',
				'lft' => 608,
				'rght' => 609
			),
			array(
				'id' => 307,
				'parent_id' => 302,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_logout',
				'lft' => 610,
				'rght' => 611
			),
			array(
				'id' => 308,
				'parent_id' => 302,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_index',
				'lft' => 612,
				'rght' => 613
			),
			array(
				'id' => 309,
				'parent_id' => 302,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_logged_in',
				'lft' => 614,
				'rght' => 615
			),
			array(
				'id' => 310,
				'parent_id' => 302,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_add',
				'lft' => 616,
				'rght' => 617
			),
			array(
				'id' => 311,
				'parent_id' => 302,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_edit',
				'lft' => 618,
				'rght' => 619
			),
			array(
				'id' => 312,
				'parent_id' => 302,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_initDB',
				'lft' => 620,
				'rght' => 621
			),
			array(
				'id' => 313,
				'parent_id' => 302,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getPlugins',
				'lft' => 622,
				'rght' => 623
			),
			array(
				'id' => 314,
				'parent_id' => 302,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getControllers',
				'lft' => 624,
				'rght' => 625
			),
			array(
				'id' => 315,
				'parent_id' => 302,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getActions',
				'lft' => 626,
				'rght' => 627
			),
			array(
				'id' => 316,
				'parent_id' => 302,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_mass',
				'lft' => 628,
				'rght' => 629
			),
			array(
				'id' => 317,
				'parent_id' => 302,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_reorder',
				'lft' => 630,
				'rght' => 631
			),
			array(
				'id' => 318,
				'parent_id' => 302,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_commentPurge',
				'lft' => 632,
				'rght' => 633
			),
			array(
				'id' => 319,
				'parent_id' => 1,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'Newsletter',
				'lft' => 636,
				'rght' => 723
			),
			array(
				'id' => 320,
				'parent_id' => 319,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'Campaigns',
				'lft' => 637,
				'rght' => 658
			),
			array(
				'id' => 321,
				'parent_id' => 320,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_index',
				'lft' => 638,
				'rght' => 639
			),
			array(
				'id' => 322,
				'parent_id' => 320,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_add',
				'lft' => 640,
				'rght' => 641
			),
			array(
				'id' => 323,
				'parent_id' => 320,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_edit',
				'lft' => 642,
				'rght' => 643
			),
			array(
				'id' => 324,
				'parent_id' => 320,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_toggle',
				'lft' => 644,
				'rght' => 645
			),
			array(
				'id' => 325,
				'parent_id' => 320,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getPlugins',
				'lft' => 646,
				'rght' => 647
			),
			array(
				'id' => 326,
				'parent_id' => 320,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getControllers',
				'lft' => 648,
				'rght' => 649
			),
			array(
				'id' => 327,
				'parent_id' => 320,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getActions',
				'lft' => 650,
				'rght' => 651
			),
			array(
				'id' => 328,
				'parent_id' => 320,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_mass',
				'lft' => 652,
				'rght' => 653
			),
			array(
				'id' => 329,
				'parent_id' => 320,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_reorder',
				'lft' => 654,
				'rght' => 655
			),
			array(
				'id' => 330,
				'parent_id' => 320,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_commentPurge',
				'lft' => 656,
				'rght' => 657
			),
			array(
				'id' => 331,
				'parent_id' => 319,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'Newsletters',
				'lft' => 659,
				'rght' => 696
			),
			array(
				'id' => 332,
				'parent_id' => 331,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'track',
				'lft' => 660,
				'rght' => 661
			),
			array(
				'id' => 333,
				'parent_id' => 331,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'sendNewsletters',
				'lft' => 662,
				'rght' => 663
			),
			array(
				'id' => 334,
				'parent_id' => 331,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_dashboard',
				'lft' => 664,
				'rght' => 665
			),
			array(
				'id' => 335,
				'parent_id' => 331,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_report',
				'lft' => 666,
				'rght' => 667
			),
			array(
				'id' => 336,
				'parent_id' => 331,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_index',
				'lft' => 668,
				'rght' => 669
			),
			array(
				'id' => 337,
				'parent_id' => 331,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_add',
				'lft' => 670,
				'rght' => 671
			),
			array(
				'id' => 338,
				'parent_id' => 331,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_edit',
				'lft' => 672,
				'rght' => 673
			),
			array(
				'id' => 339,
				'parent_id' => 331,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_view',
				'lft' => 674,
				'rght' => 675
			),
			array(
				'id' => 340,
				'parent_id' => 331,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_preview',
				'lft' => 676,
				'rght' => 677
			),
			array(
				'id' => 341,
				'parent_id' => 331,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_delte',
				'lft' => 678,
				'rght' => 679
			),
			array(
				'id' => 342,
				'parent_id' => 331,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_toggleSend',
				'lft' => 680,
				'rght' => 681
			),
			array(
				'id' => 343,
				'parent_id' => 331,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_stopAll',
				'lft' => 682,
				'rght' => 683
			),
			array(
				'id' => 344,
				'parent_id' => 331,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getPlugins',
				'lft' => 684,
				'rght' => 685
			),
			array(
				'id' => 345,
				'parent_id' => 331,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getControllers',
				'lft' => 686,
				'rght' => 687
			),
			array(
				'id' => 346,
				'parent_id' => 331,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getActions',
				'lft' => 688,
				'rght' => 689
			),
			array(
				'id' => 347,
				'parent_id' => 331,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_mass',
				'lft' => 690,
				'rght' => 691
			),
			array(
				'id' => 348,
				'parent_id' => 331,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_reorder',
				'lft' => 692,
				'rght' => 693
			),
			array(
				'id' => 349,
				'parent_id' => 331,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_commentPurge',
				'lft' => 694,
				'rght' => 695
			),
			array(
				'id' => 350,
				'parent_id' => 319,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'Templates',
				'lft' => 697,
				'rght' => 722
			),
			array(
				'id' => 351,
				'parent_id' => 350,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_index',
				'lft' => 698,
				'rght' => 699
			),
			array(
				'id' => 352,
				'parent_id' => 350,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_add',
				'lft' => 700,
				'rght' => 701
			),
			array(
				'id' => 353,
				'parent_id' => 350,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_edit',
				'lft' => 702,
				'rght' => 703
			),
			array(
				'id' => 354,
				'parent_id' => 350,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_view',
				'lft' => 704,
				'rght' => 705
			),
			array(
				'id' => 355,
				'parent_id' => 350,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_export',
				'lft' => 706,
				'rght' => 707
			),
			array(
				'id' => 356,
				'parent_id' => 350,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_preview',
				'lft' => 708,
				'rght' => 709
			),
			array(
				'id' => 357,
				'parent_id' => 350,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getPlugins',
				'lft' => 710,
				'rght' => 711
			),
			array(
				'id' => 358,
				'parent_id' => 350,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getControllers',
				'lft' => 712,
				'rght' => 713
			),
			array(
				'id' => 359,
				'parent_id' => 350,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getActions',
				'lft' => 714,
				'rght' => 715
			),
			array(
				'id' => 360,
				'parent_id' => 350,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_mass',
				'lft' => 716,
				'rght' => 717
			),
			array(
				'id' => 361,
				'parent_id' => 350,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_reorder',
				'lft' => 718,
				'rght' => 719
			),
			array(
				'id' => 362,
				'parent_id' => 350,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_commentPurge',
				'lft' => 720,
				'rght' => 721
			),
			array(
				'id' => 363,
				'parent_id' => 1,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'Shop',
				'lft' => 724,
				'rght' => 973
			),
			array(
				'id' => 364,
				'parent_id' => 363,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'Branches',
				'lft' => 725,
				'rght' => 748
			),
			array(
				'id' => 365,
				'parent_id' => 364,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'index',
				'lft' => 726,
				'rght' => 727
			),
			array(
				'id' => 366,
				'parent_id' => 364,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'view',
				'lft' => 728,
				'rght' => 729
			),
			array(
				'id' => 367,
				'parent_id' => 364,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_index',
				'lft' => 730,
				'rght' => 731
			),
			array(
				'id' => 368,
				'parent_id' => 364,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_add',
				'lft' => 732,
				'rght' => 733
			),
			array(
				'id' => 369,
				'parent_id' => 364,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_edit',
				'lft' => 734,
				'rght' => 735
			),
			array(
				'id' => 370,
				'parent_id' => 364,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getPlugins',
				'lft' => 736,
				'rght' => 737
			),
			array(
				'id' => 371,
				'parent_id' => 364,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getControllers',
				'lft' => 738,
				'rght' => 739
			),
			array(
				'id' => 372,
				'parent_id' => 364,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getActions',
				'lft' => 740,
				'rght' => 741
			),
			array(
				'id' => 373,
				'parent_id' => 364,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_mass',
				'lft' => 742,
				'rght' => 743
			),
			array(
				'id' => 374,
				'parent_id' => 364,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_reorder',
				'lft' => 744,
				'rght' => 745
			),
			array(
				'id' => 375,
				'parent_id' => 364,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_commentPurge',
				'lft' => 746,
				'rght' => 747
			),
			array(
				'id' => 376,
				'parent_id' => 363,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'Carts',
				'lft' => 749,
				'rght' => 770
			),
			array(
				'id' => 377,
				'parent_id' => 376,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'index',
				'lft' => 750,
				'rght' => 751
			),
			array(
				'id' => 378,
				'parent_id' => 376,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'adjust',
				'lft' => 752,
				'rght' => 753
			),
			array(
				'id' => 379,
				'parent_id' => 376,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'change_shipping_method',
				'lft' => 754,
				'rght' => 755
			),
			array(
				'id' => 380,
				'parent_id' => 376,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_index',
				'lft' => 756,
				'rght' => 757
			),
			array(
				'id' => 381,
				'parent_id' => 376,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getPlugins',
				'lft' => 758,
				'rght' => 759
			),
			array(
				'id' => 382,
				'parent_id' => 376,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getControllers',
				'lft' => 760,
				'rght' => 761
			),
			array(
				'id' => 383,
				'parent_id' => 376,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getActions',
				'lft' => 762,
				'rght' => 763
			),
			array(
				'id' => 384,
				'parent_id' => 376,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_mass',
				'lft' => 764,
				'rght' => 765
			),
			array(
				'id' => 385,
				'parent_id' => 376,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_reorder',
				'lft' => 766,
				'rght' => 767
			),
			array(
				'id' => 386,
				'parent_id' => 376,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_commentPurge',
				'lft' => 768,
				'rght' => 769
			),
			array(
				'id' => 387,
				'parent_id' => 363,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'Categories',
				'lft' => 771,
				'rght' => 792
			),
			array(
				'id' => 388,
				'parent_id' => 387,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'index',
				'lft' => 772,
				'rght' => 773
			),
			array(
				'id' => 389,
				'parent_id' => 387,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_index',
				'lft' => 774,
				'rght' => 775
			),
			array(
				'id' => 390,
				'parent_id' => 387,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_add',
				'lft' => 776,
				'rght' => 777
			),
			array(
				'id' => 391,
				'parent_id' => 387,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_edit',
				'lft' => 778,
				'rght' => 779
			),
			array(
				'id' => 392,
				'parent_id' => 387,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getPlugins',
				'lft' => 780,
				'rght' => 781
			),
			array(
				'id' => 393,
				'parent_id' => 387,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getControllers',
				'lft' => 782,
				'rght' => 783
			),
			array(
				'id' => 394,
				'parent_id' => 387,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getActions',
				'lft' => 784,
				'rght' => 785
			),
			array(
				'id' => 395,
				'parent_id' => 387,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_mass',
				'lft' => 786,
				'rght' => 787
			),
			array(
				'id' => 396,
				'parent_id' => 387,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_reorder',
				'lft' => 788,
				'rght' => 789
			),
			array(
				'id' => 397,
				'parent_id' => 387,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_commentPurge',
				'lft' => 790,
				'rght' => 791
			),
			array(
				'id' => 398,
				'parent_id' => 363,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'Images',
				'lft' => 793,
				'rght' => 812
			),
			array(
				'id' => 399,
				'parent_id' => 398,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_index',
				'lft' => 794,
				'rght' => 795
			),
			array(
				'id' => 400,
				'parent_id' => 398,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_add',
				'lft' => 796,
				'rght' => 797
			),
			array(
				'id' => 401,
				'parent_id' => 398,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_edit',
				'lft' => 798,
				'rght' => 799
			),
			array(
				'id' => 402,
				'parent_id' => 398,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getPlugins',
				'lft' => 800,
				'rght' => 801
			),
			array(
				'id' => 403,
				'parent_id' => 398,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getControllers',
				'lft' => 802,
				'rght' => 803
			),
			array(
				'id' => 404,
				'parent_id' => 398,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getActions',
				'lft' => 804,
				'rght' => 805
			),
			array(
				'id' => 405,
				'parent_id' => 398,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_mass',
				'lft' => 806,
				'rght' => 807
			),
			array(
				'id' => 406,
				'parent_id' => 398,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_reorder',
				'lft' => 808,
				'rght' => 809
			),
			array(
				'id' => 407,
				'parent_id' => 398,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_commentPurge',
				'lft' => 810,
				'rght' => 811
			),
			array(
				'id' => 408,
				'parent_id' => 363,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'Products',
				'lft' => 813,
				'rght' => 842
			),
			array(
				'id' => 409,
				'parent_id' => 408,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'dashboard',
				'lft' => 814,
				'rght' => 815
			),
			array(
				'id' => 410,
				'parent_id' => 408,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'index',
				'lft' => 816,
				'rght' => 817
			),
			array(
				'id' => 411,
				'parent_id' => 408,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'view',
				'lft' => 818,
				'rght' => 819
			),
			array(
				'id' => 412,
				'parent_id' => 408,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_dashboard',
				'lft' => 820,
				'rght' => 821
			),
			array(
				'id' => 413,
				'parent_id' => 408,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_index',
				'lft' => 822,
				'rght' => 823
			),
			array(
				'id' => 414,
				'parent_id' => 408,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_statistics',
				'lft' => 824,
				'rght' => 825
			),
			array(
				'id' => 415,
				'parent_id' => 408,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_add',
				'lft' => 826,
				'rght' => 827
			),
			array(
				'id' => 416,
				'parent_id' => 408,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_edit',
				'lft' => 828,
				'rght' => 829
			),
			array(
				'id' => 417,
				'parent_id' => 408,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getPlugins',
				'lft' => 830,
				'rght' => 831
			),
			array(
				'id' => 418,
				'parent_id' => 408,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getControllers',
				'lft' => 832,
				'rght' => 833
			),
			array(
				'id' => 419,
				'parent_id' => 408,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getActions',
				'lft' => 834,
				'rght' => 835
			),
			array(
				'id' => 420,
				'parent_id' => 408,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_mass',
				'lft' => 836,
				'rght' => 837
			),
			array(
				'id' => 421,
				'parent_id' => 408,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_reorder',
				'lft' => 838,
				'rght' => 839
			),
			array(
				'id' => 422,
				'parent_id' => 408,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_commentPurge',
				'lft' => 840,
				'rght' => 841
			),
			array(
				'id' => 423,
				'parent_id' => 363,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'Specials',
				'lft' => 843,
				'rght' => 866
			),
			array(
				'id' => 424,
				'parent_id' => 423,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'index',
				'lft' => 844,
				'rght' => 845
			),
			array(
				'id' => 425,
				'parent_id' => 423,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_index',
				'lft' => 846,
				'rght' => 847
			),
			array(
				'id' => 426,
				'parent_id' => 423,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_add',
				'lft' => 848,
				'rght' => 849
			),
			array(
				'id' => 427,
				'parent_id' => 423,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_edit',
				'lft' => 850,
				'rght' => 851
			),
			array(
				'id' => 428,
				'parent_id' => 423,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getPrices',
				'lft' => 852,
				'rght' => 853
			),
			array(
				'id' => 429,
				'parent_id' => 423,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getPlugins',
				'lft' => 854,
				'rght' => 855
			),
			array(
				'id' => 430,
				'parent_id' => 423,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getControllers',
				'lft' => 856,
				'rght' => 857
			),
			array(
				'id' => 431,
				'parent_id' => 423,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getActions',
				'lft' => 858,
				'rght' => 859
			),
			array(
				'id' => 432,
				'parent_id' => 423,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_mass',
				'lft' => 860,
				'rght' => 861
			),
			array(
				'id' => 433,
				'parent_id' => 423,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_reorder',
				'lft' => 862,
				'rght' => 863
			),
			array(
				'id' => 434,
				'parent_id' => 423,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_commentPurge',
				'lft' => 864,
				'rght' => 865
			),
			array(
				'id' => 435,
				'parent_id' => 363,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'Spotlights',
				'lft' => 867,
				'rght' => 888
			),
			array(
				'id' => 436,
				'parent_id' => 435,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'index',
				'lft' => 868,
				'rght' => 869
			),
			array(
				'id' => 437,
				'parent_id' => 435,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_index',
				'lft' => 870,
				'rght' => 871
			),
			array(
				'id' => 438,
				'parent_id' => 435,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_add',
				'lft' => 872,
				'rght' => 873
			),
			array(
				'id' => 439,
				'parent_id' => 435,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_edit',
				'lft' => 874,
				'rght' => 875
			),
			array(
				'id' => 440,
				'parent_id' => 435,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getPlugins',
				'lft' => 876,
				'rght' => 877
			),
			array(
				'id' => 441,
				'parent_id' => 435,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getControllers',
				'lft' => 878,
				'rght' => 879
			),
			array(
				'id' => 442,
				'parent_id' => 435,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getActions',
				'lft' => 880,
				'rght' => 881
			),
			array(
				'id' => 443,
				'parent_id' => 435,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_mass',
				'lft' => 882,
				'rght' => 883
			),
			array(
				'id' => 444,
				'parent_id' => 435,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_reorder',
				'lft' => 884,
				'rght' => 885
			),
			array(
				'id' => 445,
				'parent_id' => 435,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_commentPurge',
				'lft' => 886,
				'rght' => 887
			),
			array(
				'id' => 446,
				'parent_id' => 363,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'Stocks',
				'lft' => 889,
				'rght' => 910
			),
			array(
				'id' => 447,
				'parent_id' => 446,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_index',
				'lft' => 890,
				'rght' => 891
			),
			array(
				'id' => 448,
				'parent_id' => 446,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_add',
				'lft' => 892,
				'rght' => 893
			),
			array(
				'id' => 449,
				'parent_id' => 446,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_edit',
				'lft' => 894,
				'rght' => 895
			),
			array(
				'id' => 450,
				'parent_id' => 446,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_valuation',
				'lft' => 896,
				'rght' => 897
			),
			array(
				'id' => 451,
				'parent_id' => 446,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getPlugins',
				'lft' => 898,
				'rght' => 899
			),
			array(
				'id' => 452,
				'parent_id' => 446,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getControllers',
				'lft' => 900,
				'rght' => 901
			),
			array(
				'id' => 453,
				'parent_id' => 446,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getActions',
				'lft' => 902,
				'rght' => 903
			),
			array(
				'id' => 454,
				'parent_id' => 446,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_mass',
				'lft' => 904,
				'rght' => 905
			),
			array(
				'id' => 455,
				'parent_id' => 446,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_reorder',
				'lft' => 906,
				'rght' => 907
			),
			array(
				'id' => 456,
				'parent_id' => 446,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_commentPurge',
				'lft' => 908,
				'rght' => 909
			),
			array(
				'id' => 457,
				'parent_id' => 363,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'Suppliers',
				'lft' => 911,
				'rght' => 930
			),
			array(
				'id' => 458,
				'parent_id' => 457,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_index',
				'lft' => 912,
				'rght' => 913
			),
			array(
				'id' => 459,
				'parent_id' => 457,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_add',
				'lft' => 914,
				'rght' => 915
			),
			array(
				'id' => 460,
				'parent_id' => 457,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_edit',
				'lft' => 916,
				'rght' => 917
			),
			array(
				'id' => 461,
				'parent_id' => 457,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getPlugins',
				'lft' => 918,
				'rght' => 919
			),
			array(
				'id' => 462,
				'parent_id' => 457,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getControllers',
				'lft' => 920,
				'rght' => 921
			),
			array(
				'id' => 463,
				'parent_id' => 457,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getActions',
				'lft' => 922,
				'rght' => 923
			),
			array(
				'id' => 464,
				'parent_id' => 457,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_mass',
				'lft' => 924,
				'rght' => 925
			),
			array(
				'id' => 465,
				'parent_id' => 457,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_reorder',
				'lft' => 926,
				'rght' => 927
			),
			array(
				'id' => 466,
				'parent_id' => 457,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_commentPurge',
				'lft' => 928,
				'rght' => 929
			),
			array(
				'id' => 467,
				'parent_id' => 363,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'Units',
				'lft' => 931,
				'rght' => 950
			),
			array(
				'id' => 468,
				'parent_id' => 467,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_index',
				'lft' => 932,
				'rght' => 933
			),
			array(
				'id' => 469,
				'parent_id' => 467,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_add',
				'lft' => 934,
				'rght' => 935
			),
			array(
				'id' => 470,
				'parent_id' => 467,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_edit',
				'lft' => 936,
				'rght' => 937
			),
			array(
				'id' => 471,
				'parent_id' => 467,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getPlugins',
				'lft' => 938,
				'rght' => 939
			),
			array(
				'id' => 472,
				'parent_id' => 467,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getControllers',
				'lft' => 940,
				'rght' => 941
			),
			array(
				'id' => 473,
				'parent_id' => 467,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getActions',
				'lft' => 942,
				'rght' => 943
			),
			array(
				'id' => 474,
				'parent_id' => 467,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_mass',
				'lft' => 944,
				'rght' => 945
			),
			array(
				'id' => 475,
				'parent_id' => 467,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_reorder',
				'lft' => 946,
				'rght' => 947
			),
			array(
				'id' => 476,
				'parent_id' => 467,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_commentPurge',
				'lft' => 948,
				'rght' => 949
			),
			array(
				'id' => 477,
				'parent_id' => 363,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'Wishlists',
				'lft' => 951,
				'rght' => 972
			),
			array(
				'id' => 478,
				'parent_id' => 477,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'index',
				'lft' => 952,
				'rght' => 953
			),
			array(
				'id' => 479,
				'parent_id' => 477,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'adjust',
				'lft' => 954,
				'rght' => 955
			),
			array(
				'id' => 480,
				'parent_id' => 477,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'move',
				'lft' => 956,
				'rght' => 957
			),
			array(
				'id' => 481,
				'parent_id' => 477,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_index',
				'lft' => 958,
				'rght' => 959
			),
			array(
				'id' => 482,
				'parent_id' => 477,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getPlugins',
				'lft' => 960,
				'rght' => 961
			),
			array(
				'id' => 483,
				'parent_id' => 477,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getControllers',
				'lft' => 962,
				'rght' => 963
			),
			array(
				'id' => 484,
				'parent_id' => 477,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getActions',
				'lft' => 964,
				'rght' => 965
			),
			array(
				'id' => 485,
				'parent_id' => 477,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_mass',
				'lft' => 966,
				'rght' => 967
			),
			array(
				'id' => 486,
				'parent_id' => 477,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_reorder',
				'lft' => 968,
				'rght' => 969
			),
			array(
				'id' => 487,
				'parent_id' => 477,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_commentPurge',
				'lft' => 970,
				'rght' => 971
			),
			array(
				'id' => 488,
				'parent_id' => 1,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'Db Admin',
				'lft' => 974,
				'rght' => 991
			),
			array(
				'id' => 489,
				'parent_id' => 488,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'Listings',
				'lft' => 975,
				'rght' => 990
			),
			array(
				'id' => 490,
				'parent_id' => 489,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_index',
				'lft' => 976,
				'rght' => 977
			),
			array(
				'id' => 491,
				'parent_id' => 489,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getPlugins',
				'lft' => 978,
				'rght' => 979
			),
			array(
				'id' => 492,
				'parent_id' => 489,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getControllers',
				'lft' => 980,
				'rght' => 981
			),
			array(
				'id' => 493,
				'parent_id' => 489,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_getActions',
				'lft' => 982,
				'rght' => 983
			),
			array(
				'id' => 494,
				'parent_id' => 489,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_mass',
				'lft' => 984,
				'rght' => 985
			),
			array(
				'id' => 495,
				'parent_id' => 489,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_reorder',
				'lft' => 986,
				'rght' => 987
			),
			array(
				'id' => 496,
				'parent_id' => 489,
				'model' => '',
				'foreign_key' => 0,
				'alias' => 'admin_commentPurge',
				'lft' => 988,
				'rght' => 989
			),
		),
		'Aro' => array(
			array(
				'id' => 1,
				'parent_id' => 0,
				'model' => 'Group',
				'foreign_key' => 1,
				'alias' => '',
				'lft' => 1,
				'rght' => 2
			),
			array(
				'id' => 2,
				'parent_id' => 0,
				'model' => 'User',
				'foreign_key' => 1,
				'alias' => '',
				'lft' => 35,
				'rght' => 36
			),
			array(
				'id' => 3,
				'parent_id' => 0,
				'model' => 'User',
				'foreign_key' => 2,
				'alias' => '',
				'lft' => 3,
				'rght' => 4
			),
			array(
				'id' => 4,
				'parent_id' => 0,
				'model' => 'User',
				'foreign_key' => 3,
				'alias' => '',
				'lft' => 5,
				'rght' => 6
			),
			array(
				'id' => 5,
				'parent_id' => 0,
				'model' => 'User',
				'foreign_key' => 4,
				'alias' => '',
				'lft' => 7,
				'rght' => 8
			),
			array(
				'id' => 6,
				'parent_id' => 0,
				'model' => 'User',
				'foreign_key' => 5,
				'alias' => '',
				'lft' => 9,
				'rght' => 10
			),
			array(
				'id' => 7,
				'parent_id' => 0,
				'model' => 'User',
				'foreign_key' => 6,
				'alias' => '',
				'lft' => 11,
				'rght' => 12
			),
			array(
				'id' => 8,
				'parent_id' => 0,
				'model' => 'User',
				'foreign_key' => 7,
				'alias' => '',
				'lft' => 13,
				'rght' => 14
			),
			array(
				'id' => 9,
				'parent_id' => 0,
				'model' => 'User',
				'foreign_key' => 8,
				'alias' => '',
				'lft' => 15,
				'rght' => 16
			),
			array(
				'id' => 10,
				'parent_id' => 0,
				'model' => 'User',
				'foreign_key' => 9,
				'alias' => '',
				'lft' => 17,
				'rght' => 18
			),
			array(
				'id' => 11,
				'parent_id' => 0,
				'model' => 'User',
				'foreign_key' => 10,
				'alias' => '',
				'lft' => 19,
				'rght' => 20
			),
			array(
				'id' => 12,
				'parent_id' => 0,
				'model' => 'User',
				'foreign_key' => 11,
				'alias' => '',
				'lft' => 21,
				'rght' => 22
			),
			array(
				'id' => 13,
				'parent_id' => 0,
				'model' => 'User',
				'foreign_key' => 12,
				'alias' => '',
				'lft' => 23,
				'rght' => 24
			),
			array(
				'id' => 14,
				'parent_id' => 0,
				'model' => 'User',
				'foreign_key' => 13,
				'alias' => '',
				'lft' => 25,
				'rght' => 26
			),
			array(
				'id' => 15,
				'parent_id' => 0,
				'model' => 'User',
				'foreign_key' => 14,
				'alias' => '',
				'lft' => 27,
				'rght' => 28
			),
			array(
				'id' => 16,
				'parent_id' => 0,
				'model' => 'User',
				'foreign_key' => 15,
				'alias' => '',
				'lft' => 29,
				'rght' => 30
			),
			array(
				'id' => 17,
				'parent_id' => 0,
				'model' => 'User',
				'foreign_key' => 16,
				'alias' => '',
				'lft' => 31,
				'rght' => 32
			),
			array(
				'id' => 18,
				'parent_id' => 0,
				'model' => 'User',
				'foreign_key' => 18,
				'alias' => '',
				'lft' => 33,
				'rght' => 34
			),
		),
		'ArosAco' => array(
			array(
				'id' => 1,
				'aro_id' => 1,
				'aco_id' => 1,
				'_create' => '1',
				'_read' => '1',
				'_update' => '1',
				'_delete' => '1'
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