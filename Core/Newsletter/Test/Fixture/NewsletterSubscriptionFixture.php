<?php
/**
 * @brief fixture file for NewsletterSubscription tests.
 *
 * @package .Fixture
 * @since 0.9b1
 */
class NewsletterSubscriptionFixture extends CakeTestFixture {
	public $name = 'NewsletterSubscription';

	public $fields = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'newsletter_subscriber_id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'newsletter_campaign_id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'index', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'sent' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'key' => 'index'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'newsletter_sent' => array('column' => 'sent', 'unique' => 0),
			'newsletter_newsletter_id' => array('column' => 'newsletter_campaign_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	public $records = array(
		array(
			'id' => '50f80dc9-1e74-4bc4-82d1-25856318cd70',
			'newsletter_subscriber_id' => 'Lorem ipsum dolor sit amet',
			'newsletter_campaign_id' => 'Lorem ipsum dolor sit amet',
			'sent' => 1,
			'created' => '2013-01-17 14:42:17',
			'modified' => '2013-01-17 14:42:17'
		),
		array(
			'id' => '50f80dc9-8080-4f27-8235-25856318cd70',
			'newsletter_subscriber_id' => 'Lorem ipsum dolor sit amet',
			'newsletter_campaign_id' => 'Lorem ipsum dolor sit amet',
			'sent' => 1,
			'created' => '2013-01-17 14:42:17',
			'modified' => '2013-01-17 14:42:17'
		),
		array(
			'id' => '50f80dc9-cfcc-47b9-80df-25856318cd70',
			'newsletter_subscriber_id' => 'Lorem ipsum dolor sit amet',
			'newsletter_campaign_id' => 'Lorem ipsum dolor sit amet',
			'sent' => 1,
			'created' => '2013-01-17 14:42:17',
			'modified' => '2013-01-17 14:42:17'
		),
		array(
			'id' => '50f80dc9-1dec-4001-a250-25856318cd70',
			'newsletter_subscriber_id' => 'Lorem ipsum dolor sit amet',
			'newsletter_campaign_id' => 'Lorem ipsum dolor sit amet',
			'sent' => 1,
			'created' => '2013-01-17 14:42:17',
			'modified' => '2013-01-17 14:42:17'
		),
		array(
			'id' => '50f80dc9-6a18-4c14-b94c-25856318cd70',
			'newsletter_subscriber_id' => 'Lorem ipsum dolor sit amet',
			'newsletter_campaign_id' => 'Lorem ipsum dolor sit amet',
			'sent' => 1,
			'created' => '2013-01-17 14:42:17',
			'modified' => '2013-01-17 14:42:17'
		),
		array(
			'id' => '50f80dc9-b9c8-45ac-bf9a-25856318cd70',
			'newsletter_subscriber_id' => 'Lorem ipsum dolor sit amet',
			'newsletter_campaign_id' => 'Lorem ipsum dolor sit amet',
			'sent' => 1,
			'created' => '2013-01-17 14:42:17',
			'modified' => '2013-01-17 14:42:17'
		),
		array(
			'id' => '50f80dc9-06bc-4058-a5ba-25856318cd70',
			'newsletter_subscriber_id' => 'Lorem ipsum dolor sit amet',
			'newsletter_campaign_id' => 'Lorem ipsum dolor sit amet',
			'sent' => 1,
			'created' => '2013-01-17 14:42:17',
			'modified' => '2013-01-17 14:42:17'
		),
		array(
			'id' => '50f80dc9-566c-4325-a040-25856318cd70',
			'newsletter_subscriber_id' => 'Lorem ipsum dolor sit amet',
			'newsletter_campaign_id' => 'Lorem ipsum dolor sit amet',
			'sent' => 1,
			'created' => '2013-01-17 14:42:17',
			'modified' => '2013-01-17 14:42:17'
		),
		array(
			'id' => '50f80dc9-a360-4a6c-923a-25856318cd70',
			'newsletter_subscriber_id' => 'Lorem ipsum dolor sit amet',
			'newsletter_campaign_id' => 'Lorem ipsum dolor sit amet',
			'sent' => 1,
			'created' => '2013-01-17 14:42:17',
			'modified' => '2013-01-17 14:42:17'
		),
		array(
			'id' => '50f80dc9-f248-4a10-8e43-25856318cd70',
			'newsletter_subscriber_id' => 'Lorem ipsum dolor sit amet',
			'newsletter_campaign_id' => 'Lorem ipsum dolor sit amet',
			'sent' => 1,
			'created' => '2013-01-17 14:42:17',
			'modified' => '2013-01-17 14:42:17'
		),
	);
}