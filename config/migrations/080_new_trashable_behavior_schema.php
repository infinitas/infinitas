<?php
class M4bcb4c1525f448d78c492dd46318cd70 extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 * @access public
 */
	public $description = '';

/**
 * Actions to be performed
 *
 * @var array $migration
 * @access public
 */
	public $migration = array(
		'up' => array(
			'drop_field' => array(
				'cms_content_layouts' => array('deleted', 'deleted_date',),
				'cms_contents' => array('deleted', 'deleted_date',),
				'contact_branches' => array('deleted', 'deleted_date',),
				'contact_contacts' => array('deleted', 'deleted_date',),
				'core_addresses' => array('deleted', 'deleted_date',),
				'core_comments' => array('deleted', 'deleted_date',),
				'core_feed_items' => array('indexes' => array('id')),
				'core_feeds' => array('indexes' => array('id')),
				'core_ip_addresses' => array('deleted', 'deleted_date',),
				'core_menu_items' => array('deleted', 'deleted_date',),
				'core_modules' => array('deleted', 'deleted_date',),
				'core_routes' => array('deleted', 'deleted_date',),
				'core_themes' => array('deleted', 'deleted_date',),
				'core_users' => array('deleted', 'deleted_date',),
				'global_categories' => array('deleted', 'deleted_date',),
				'newsletter_campaigns' => array('deleted', 'deleted_date',),
				'newsletter_newsletters' => array('deleted', 'deleted_date',),
				'newsletter_templates' => array('deleted', 'deleted_date',),
			),
			'create_table' => array(
				'core_trash' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
					'model' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
					'foreign_key' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36),
					'name' => array('type' => 'string', 'null' => false, 'default' => NULL),
					'data' => array('type' => 'text', 'null' => false, 'default' => NULL),
					'deleted' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
					'deleted_by' => array('type' => 'integer', 'null' => true, 'default' => NULL),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM'),
				),
			),
		),
		'down' => array(
			'create_field' => array(
				'cms_content_layouts' => array(
					'deleted' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'deleted_date' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
				),
				'cms_contents' => array(
					'deleted' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'deleted_date' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
				),
				'contact_branches' => array(
					'deleted' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'deleted_date' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
				),
				'contact_contacts' => array(
					'deleted' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'deleted_date' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
				),
				'core_addresses' => array(
					'deleted' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'deleted_date' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
				),
				'core_comments' => array(
					'deleted' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'deleted_date' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
				),
				'core_feed_items' => array(
					'indexes' => array(
						'id' => array('column' => 'id', 'unique' => 1),
					),
				),
				'core_feeds' => array(
					'indexes' => array(
						'id' => array('column' => 'id', 'unique' => 1),
					),
				),
				'core_ip_addresses' => array(
					'deleted' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'deleted_date' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
				),
				'core_menu_items' => array(
					'deleted' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'deleted_date' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
				),
				'core_modules' => array(
					'deleted' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'deleted_date' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
				),
				'core_routes' => array(
					'deleted' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'deleted_date' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
				),
				'core_themes' => array(
					'deleted' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'deleted_date' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
				),
				'core_users' => array(
					'deleted' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'deleted_date' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
				),
				'global_categories' => array(
					'deleted' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'deleted_date' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
				),
				'newsletter_campaigns' => array(
					'deleted' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'deleted_date' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
				),
				'newsletter_newsletters' => array(
					'deleted' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'deleted_date' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
				),
				'newsletter_templates' => array(
					'deleted' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'deleted_date' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
				),
			),
			'drop_table' => array(
				'core_trash'
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