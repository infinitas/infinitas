<?php
/**
 * fixture file for Campaign tests.
 *
 * @package Newsletter.Fixture
 * @since 0.9b1
 */
class NewsletterCampaignFixture extends CakeTestFixture {

	public $fields = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'slug' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'description' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'active' => array('type' => 'boolean', 'null' => false, 'default' => '1'),
		'newsletter_count' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'newsletter_subscription_count' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'newsletter_template_id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	public $records = array(
		array(
			'id' => 'campaign-1',
			'name' => 'campaign-1',
			'slug' => 'campaign-1',
			'description' => 'campaign-1',
			'active' => 1,
			'newsletter_count' => 0,
			'newsletter_subscription_count' => 0,
			'newsletter_template_id' => 'template-1',
			'created' => '2009-12-12 12:47:53',
			'modified' => '2009-12-21 16:28:38',
		),
		array(
			'id' => 'campaign-inactive',
			'name' => 'campaign-inactive',
			'slug' => 'campaign-inactive',
			'description' => 'campaign-inactive',
			'active' => 0,
			'newsletter_count' => 0,
			'newsletter_subscription_count' => 0,
			'newsletter_template_id' => 'template-1',
			'created' => '2009-12-12 12:47:53',
			'modified' => '2009-12-21 16:28:38',
		),
	);
}