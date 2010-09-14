<?php
	/**
	 * CakePHP Tags Plugin
	 *
	 * Copyright 2009 - 2010, Cake Development Corporation
	 *                        1785 E. Sahara Avenue, Suite 490-423
	 *                        Las Vegas, Nevada 89104
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 *
	 * @copyright 2009 - 2010, Cake Development Corporation (http://cakedc.com)
	 * @link      http://github.com/CakeDC/Tags
	 * @package   plugins.tags
	 * @license   MIT License (http://www.opensource.org/licenses/mit-license.php)
	 *
	 * Short description for class.
	 *
	 * @package		plugins.tags
	 * @subpackage	plugins.tags.models
	 */

	class Tag extends TagsAppModel {

		/**
		 * Name
		 *
		 * @var string $name
		 * @access public
		 */
		public $name = 'Tag';

		/**
		 * hasMany associations
		 *
		 * @var array
		 * @access public
		 */
		public $hasMany = array(
			'Tagged' => array(
				'className' => 'Tags.Tagged',
				'foreignKey' => 'tag_id'
			)
		);

		/**
		 * Validation rules
		 *
		 * @var array
		 * @access public
		 */
		public $validate = array(
			'name' => array('rule' => 'notEmpty'),
			'keyname' => array('rule' => 'notEmpty')
		);
	}