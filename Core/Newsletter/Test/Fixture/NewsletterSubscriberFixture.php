<?php
/**
 * fixture file for NewsletterSubscriber tests.
 *
 * @package Newsletter.Fixture
 * @since 0.9b1
 */
class NewsletterSubscriberFixture extends CakeTestFixture {

	public $fields = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary'),
		'user_id' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 36),
		'prefered_name' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 150),
		'email' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 150),
		'active' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'confirmed' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'newsletter_subscription_count' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	public $records = array(
		array(
			'id' => 'subscriber-non-user',
			'user_id' => null,
			'prefered_name' => 'subscriber-non-user',
			'email' => 'non-user@subscriber.com',
			'active' => 1,
			'confirmed' => 1,
			'newsletter_subscription_count' => 0,
			'created' => '2009-12-13 01:49:32',
			'modified' => '2009-12-13 01:49:32'
		),
		array(
			'id' => 'subscriber-inactive',
			'user_id' => null,
			'prefered_name' => 'subscriber-inactive',
			'email' => 'inactive@subscriber.com',
			'active' => 0,
			'confirmed' => 1,
			'newsletter_subscription_count' => 0,
			'created' => '2009-12-13 01:49:32',
			'modified' => '2009-12-13 01:49:32'
		),
		array(
			'id' => 'subscriber-pending',
			'user_id' => null,
			'prefered_name' => 'subscriber-pending',
			'email' => 'pending@subscriber.com',
			'active' => 1,
			'confirmed' => 0,
			'newsletter_subscription_count' => 0,
			'created' => '2009-12-13 01:49:32',
			'modified' => '2009-12-13 01:49:32'
		),
		array(
			'id' => 'subscriber-user',
			'user_id' => 1,
			'prefered_name' => 'subscriber-user',
			'email' => 'user@subscriber.com',
			'active' => 1,
			'confirmed' => 1,
			'newsletter_subscription_count' => 0,
			'created' => '2009-12-13 01:49:32',
			'modified' => '2009-12-13 01:49:32'
		),
	);
}