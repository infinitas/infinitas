<?php
class M4b8beff7199c482fb0e011f16318cd70 extends CakeMigration {

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
			'create_field' => array(
				'blog_categories' => array(
					'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'deleted' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'deleted_date' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
				),
				'blog_posts' => array(
					'deleted' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'deleted_date' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
				),
				'cms_categories' => array(
					'deleted' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'deleted_date' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
				),
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
			'drop_field' => array(
				'blog_categories' => array('modeified',),
			),
		),
		'down' => array(
			'drop_field' => array(
				'blog_categories' => array('modified', 'deleted', 'deleted_date',),
				'blog_posts' => array('deleted', 'deleted_date',),
				'cms_categories' => array('deleted', 'deleted_date',),
				'cms_content_layouts' => array('deleted', 'deleted_date',),
				'cms_contents' => array('deleted', 'deleted_date',),
				'contact_branches' => array('deleted', 'deleted_date',),
				'contact_contacts' => array('deleted', 'deleted_date',),
				'core_addresses' => array('deleted', 'deleted_date',),
				'core_comments' => array('deleted', 'deleted_date',),
				'core_ip_addresses' => array('deleted', 'deleted_date',),
				'core_menu_items' => array('deleted', 'deleted_date',),
				'core_modules' => array('deleted', 'deleted_date',),
				'core_routes' => array('deleted', 'deleted_date',),
				'core_themes' => array('deleted', 'deleted_date',),
				'core_users' => array('deleted', 'deleted_date',),
				'newsletter_campaigns' => array('deleted', 'deleted_date',),
				'newsletter_newsletters' => array('deleted', 'deleted_date',),
				'newsletter_templates' => array('deleted', 'deleted_date',),
			),
			'create_field' => array(
				'blog_categories' => array(
					'modeified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
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