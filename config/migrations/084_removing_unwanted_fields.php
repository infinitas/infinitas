<?php
class M4c936fa3e3c840d790bc21056318cd70 extends CakeMigration {

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
				'blog_posts' => array('locked', 'locked_by', 'locked_since',),
				'cms_contents' => array('locked', 'locked_since', 'locked_by'),
				'core_modules' => array('locked', 'locked_by', 'locked_since',),
				'global_categories' => array('locked', 'locked_since', 'locked_by'),
				'newsletter_campaigns' => array('locked', 'locked_by', 'locked_since',),
				'newsletter_newsletters' => array('locked', 'locked_by', 'locked_since',),
				'newsletter_templates' => array('locked', 'locked_by', 'locked_since',),
				'shop_images' => array('deleted', 'deleted_date',),
				'shop_products' => array('deleted', 'deleted_date',),
				'shop_shop_branches' => array('deleted', 'deleted_date',),
				'shop_shop_categories' => array('deleted', 'deleted_date',),
				'shop_specials' => array('deleted', 'deleted_date',),
				'shop_spotlights' => array('deleted', 'deleted_date',),
				'shop_suppliers' => array('deleted', 'deleted_date',),
				'shop_units' => array('deleted', 'deleted_date',),
				'shop_wishlists' => array('deleted', 'deleted_date',),
			),
			'create_table' => array(
				'global_locks' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
					'class' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 128, 'key' => 'index', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'foreign_key' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'user_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 8),
					'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
						'lock' => array('column' => array('class', 'foreign_key'), 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
			),
		),
		'down' => array(
			'create_field' => array(
				'blog_posts' => array(
					'locked' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'locked_by' => array('type' => 'integer', 'null' => true, 'default' => NULL),
					'locked_since' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
				),
				'cms_contents' => array(
					'locked' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'key' => 'index'),
					'locked_since' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'locked_by' => array('type' => 'integer', 'null' => true, 'default' => NULL),					
				),
				'core_modules' => array(
					'locked' => array('type' => 'boolean', 'null' => false, 'default' => NULL),
					'locked_by' => array('type' => 'integer', 'null' => true, 'default' => NULL),
					'locked_since' => array('type' => 'integer', 'null' => true, 'default' => NULL),
				),
				'global_categories' => array(
					'locked' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'key' => 'index'),
					'locked_since' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'locked_by' => array('type' => 'integer', 'null' => true, 'default' => NULL),					
				),
				'newsletter_campaigns' => array(
					'locked' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'locked_by' => array('type' => 'integer', 'null' => true, 'default' => NULL),
					'locked_since' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
				),
				'newsletter_newsletters' => array(
					'locked' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'locked_by' => array('type' => 'integer', 'null' => true, 'default' => NULL),
					'locked_since' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
				),
				'newsletter_templates' => array(
					'locked' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'locked_by' => array('type' => 'integer', 'null' => true, 'default' => NULL),
					'locked_since' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
				),
				'shop_images' => array(
					'deleted' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'deleted_date' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
				),
				'shop_products' => array(
					'deleted' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'deleted_date' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
				),
				'shop_shop_branches' => array(
					'deleted' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'deleted_date' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
				),
				'shop_shop_categories' => array(
					'deleted' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'deleted_date' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
				),
				'shop_specials' => array(
					'deleted' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'deleted_date' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
				),
				'shop_spotlights' => array(
					'deleted' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'deleted_date' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
				),
				'shop_suppliers' => array(
					'deleted' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'deleted_date' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
				),
				'shop_units' => array(
					'deleted' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'deleted_date' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
				),
				'shop_wishlists' => array(
					'deleted' => array('type' => 'integer', 'null' => false, 'default' => '0'),
					'deleted_date' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
				),
			),
			'drop_table' => array(
				'global_locks',
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