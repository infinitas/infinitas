<?php
/**
 * CakePHP Tags Plugin
 *
 * Copyright 2009 - 2010, Cake Development Corporation
 *						1785 E. Sahara Avenue, Suite 490-423
 *						Las Vegas, Nevada 89104
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright 2009 - 2010, Cake Development Corporation (http://cakedc.com)
 * @link	  http://github.com/CakeDC/Tags
 * @package   plugins.tags
 * @license   MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * Short description for class.
 *
 * @package		plugins.tags
 * @subpackage	plugins.tags.tests.fixtures
 */

class GlobalTagFixture extends CakeTestFixture {
/**
 * Table
 *
 * @var string $table
 * @access public
 */
	public $table = 'global_tags';

/**
 * Fields
 *
 * @var array $fields
 * @access public
 */
	public $fields = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'key' => 'primary'),
		'identifier' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 30, 'key' => 'index'),
		'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 30),
		'keyname' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 30),
		'description' => array('type' => 'text', 'null' => false, 'default' => NULL),
		'weight' => array('type' => 'integer', 'null' => false, 'default' => 0, 'length' => 2),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'UNIQUE_TAG' => array('column' => array('identifier', 'keyname'), 'unique' => 1)
		)
	);

/**
 * Records
 *
 * @var array $records
 * @access public
 */
	public $records = array(
		array(
			'id'  => '1',
			'identifier'  => null,
			'name'  => 'CakePHP',
			'keyname'  => 'cakephp',
			'weight' => '2',
			'created'  => '2008-06-02 18:18:11',
			'modified'  => '2008-06-02 18:18:37',
			'description' => 'nice'),
		array(
			'id'  => '2',
			'identifier'  => null,
			'name'  => 'CakeDC',
			'keyname'  => 'cakedc',
			'weight' => '2',
			'created'  => '2008-06-01 18:18:15',
			'modified'  => '2008-06-01 18:18:15',
			'description' => 'as'),
		array(
			'id'  => '3',
			'identifier'  => null,
			'name'  => 'CakeDC',
			'keyname'  => 'cakedc',
			'weight' => '2',
			'created'  => '2008-06-01 18:18:15',
			'modified'  => '2008-06-01 18:18:15',
			'description' => 'well'));
}
?>