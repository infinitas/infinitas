<?php
/**
 * CakePHP Tags Plugin
 *
 * Copyright 2009 - 2010, Cake Development Corporation
 *						1785 E. Sahara Avenue, Suite 490-423
 *						Las Vegas, Nevada 89104
 *
 *
 *
 *
 * @copyright 2009 - 2010, Cake Development Corporation (http://cakedc.com)
 * @link	  http://github.com/CakeDC/Tags
 * @package   plugins.tags
 * @license   MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * Content article fixtures
 */

class ContentArticleFixture extends CakeTestFixture {
	public $table = 'articles';
/**
 * fields property
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer'),
		'title' => array('type' => 'string', 'null' => false)
	);

/**
 * records property
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'title' => 'First Article'),
		array(
			'id' => 2,
			'title' => 'Second Article'),
		array(
			'id' => 3,
			'title' => 'Third Article')
	);

}
?>