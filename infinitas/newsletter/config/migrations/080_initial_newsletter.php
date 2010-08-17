<?php
class M4c6a938676a4465a8e9c11e86318cd70 extends CakeMigration {

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
			'create_table' => array(
				'newsletter_campaigns' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
					'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50),
					'description' => array('type' => 'text', 'null' => false, 'default' => NULL),
					'active' => array('type' => 'boolean', 'null' => false, 'default' => '1'),
					'newsletter_count' => array('type' => 'integer', 'null' => false, 'default' => '0'),
					'template_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'locked' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'locked_by' => array('type' => 'integer', 'null' => true, 'default' => NULL),
					'locked_since' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'deleted' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 1),
					'deleted_date' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
				'newsletter_newsletters' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
					'campaign_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'key' => 'index'),
					'template_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'from' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 150),
					'reply_to' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 150),
					'subject' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
					'html' => array('type' => 'text', 'null' => false, 'default' => NULL),
					'text' => array('type' => 'text', 'null' => false, 'default' => NULL),
					'active' => array('type' => 'boolean', 'null' => false, 'default' => NULL),
					'sent' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'views' => array('type' => 'integer', 'null' => false, 'default' => '0'),
					'sends' => array('type' => 'integer', 'null' => false, 'default' => '0'),
					'last_sent' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'locked' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'locked_by' => array('type' => 'integer', 'null' => true, 'default' => NULL),
					'locked_since' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'created_by' => array('type' => 'integer', 'null' => true, 'default' => NULL),
					'modified_by' => array('type' => 'integer', 'null' => true, 'default' => NULL),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
						'campaign_id' => array('column' => 'campaign_id', 'unique' => 0),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
				'newsletter_newsletters_users' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
					'user_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'newsletter_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
					'sent' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'key' => 'index'),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
						'newsletter_sent' => array('column' => 'sent', 'unique' => 0),
						'newsletter_newsletter_id' => array('column' => 'newsletter_id', 'unique' => 0),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
				'newsletter_templates' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
					'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50, 'key' => 'unique'),
					'header' => array('type' => 'text', 'null' => true, 'default' => NULL),
					'footer' => array('type' => 'text', 'null' => true, 'default' => NULL),
					'locked' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'locked_by' => array('type' => 'integer', 'null' => true, 'default' => NULL),
					'locked_since' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'delete' => array('type' => 'boolean', 'null' => false, 'default' => NULL),
					'deleted_date' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
						'name' => array('column' => 'name', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
			),
		),
		'down' => array(
			'drop_table' => array(
				'newsletter_campaigns', 'newsletter_newsletters', 'newsletter_newsletters_users', 'newsletter_templates'
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