<?php
/**
 * @brief fixture file for NewslettersUser tests.
 *
 * @package Newsletter.Fixture
 * @since 0.9b1
 */
class NewslettersUserFixture extends CakeTestFixture {
	public $name = 'NewslettersUser';
	public $table = 'newsletter_newsletters_users';

	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'primary'),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'newsletter_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'index'),
		'sent' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'key' => 'index'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'newsletter_sent' => array('column' => 'sent', 'unique' => 0),
			'newsletter_newsletter_id' => array('column' => 'newsletter_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	public $records = array(
	);
}