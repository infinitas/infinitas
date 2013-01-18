<?php
	/**
	 * Infinitas Releas
	 *
	 * Auto generated database update
	 */
	 
	class R50f8ab444a88404080d205ef6318cd70 extends CakeRelease {

	/**
	* Migration description
	*
	* @var string
	* @access public
	*/
		public $description = 'Migration for Newsletter version 0.9.1';

	/**
	* Plugin name
	*
	* @var string
	* @access public
	*/
		public $plugin = 'Newsletter';

	/**
	* Actions to be performed
	*
	* @var array $migration
	* @access public
	*/
		public $migration = array(
			'up' => array(
			'create_field' => array(
				'newsletter_campaigns' => array(
					'slug' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8', 'after' => 'name'),
					'newsletter_subscription_count' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 10, 'after' => 'newsletter_count'),
					'newsletter_template_id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8', 'after' => 'newsletter_subscription_count'),
				),
				'newsletter_newsletters' => array(
					'slug' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8', 'after' => 'id'),
					'newsletter_campaign_id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'key' => 'index', 'collate' => 'utf8_general_ci', 'charset' => 'utf8', 'after' => 'slug'),
					'newsletter_template_id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8', 'after' => 'newsletter_campaign_id'),
					'indexes' => array(
						'campaign_id' => array('column' => 'newsletter_campaign_id', 'unique' => 0),
					),
				),
				'newsletter_subscribers' => array(
					'newsletter_subscription_count' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 8, 'after' => 'active'),
					'confirmed' => array('type' => 'datetime', 'null' => true, 'default' => NULL, 'after' => 'newsletter_subscription_count'),
				),
				'newsletter_templates' => array(
					'newsletter_count' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 8, 'after' => 'footer'),
				),
			),
			'drop_field' => array(
				'newsletter_campaigns' => array('template_id', 'deleted', 'deleted_date',),
				'newsletter_newsletters' => array('campaign_id', 'template_id', 'created_by', 'modified_by', 'indexes' => array('campaign_id')),
				'newsletter_subscribers' => array('subscription_count',),
				'newsletter_templates' => array('delete', 'deleted_date',),
			),
			'alter_field' => array(
				'newsletter_campaigns' => array(
					'id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
				),
				'newsletter_newsletters' => array(
					'id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'active' => array('type' => 'boolean', 'null' => false, 'default' => '1'),
				),
				'newsletter_subscribers' => array(
					'active' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'length' => 3),
				),
				'newsletter_templates' => array(
					'id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
				),
			),
			'create_table' => array(
				'newsletter_subscriptions' => array(
					'id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'newsletter_subscriber_id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8', 'after' => 'id'),
					'newsletter_campaign_id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'key' => 'index', 'collate' => 'utf8_general_ci', 'charset' => 'utf8', 'after' => 'newsletter_subscriber_id'),
					'sent' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'key' => 'index', 'after' => 'newsletter_campaign_id'),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL, 'after' => 'sent'),
					'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL, 'after' => 'created'),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
						'newsletter_sent' => array('column' => 'sent', 'unique' => 0),
						'newsletter_newsletter_id' => array('column' => 'newsletter_campaign_id', 'unique' => 0),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
			),
			'drop_table' => array(
				'newsletter_newsletters_users'
			),
		),
		'down' => array(
			'drop_field' => array(
				'newsletter_campaigns' => array('slug', 'newsletter_subscription_count', 'newsletter_template_id',),
				'newsletter_newsletters' => array('slug', 'newsletter_campaign_id', 'newsletter_template_id', 'indexes' => array('campaign_id')),
				'newsletter_subscribers' => array('newsletter_subscription_count', 'confirmed',),
				'newsletter_templates' => array('newsletter_count',),
			),
			'create_field' => array(
				'newsletter_campaigns' => array(
					'template_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'deleted' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 1),
					'deleted_date' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
				),
				'newsletter_newsletters' => array(
					'campaign_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'key' => 'index'),
					'template_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'created_by' => array('type' => 'integer', 'null' => true, 'default' => NULL),
					'modified_by' => array('type' => 'integer', 'null' => true, 'default' => NULL),
					'indexes' => array(
						'campaign_id' => array(),
					),
				),
				'newsletter_subscribers' => array(
					'subscription_count' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 3),
				),
				'newsletter_templates' => array(
					'delete' => array('type' => 'boolean', 'null' => false, 'default' => NULL),
					'deleted_date' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
				),
			),
			'alter_field' => array(
				'newsletter_campaigns' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
				),
				'newsletter_newsletters' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
					'active' => array('type' => 'boolean', 'null' => false, 'default' => NULL),
				),
				'newsletter_subscribers' => array(
					'active' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 3),
				),
				'newsletter_templates' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
				),
			),
			'drop_table' => array(
				'newsletter_subscriptions'
			),
			'create_table' => array(
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