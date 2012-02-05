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
	 *
	 * Short description for class.
	 *
	 * @package		plugins.tags
	 * @subpackage	plugins.tags.models
	 */

	class GlobalTag extends ContentsAppModel {

		/**
		 * Name
		 *
		 * @var string $name
		 * @access public
		 */
		public $name = 'GlobalTag';

		/**
		 * hasMany associations
		 *
		 * @var array
		 * @access public
		 */
		public $hasMany = array(
			'GlobalTagged' => array(
				'className' => 'Contents.GlobalTagged',
				'foreignKey' => 'tag_id'
			)
		);

		public function  __construct($id = false, $table = null, $ds = null) {
			parent::__construct($id, $table, $ds);

			$this->validate = array(
				'name' => array(
					'notEmpty' => array(
						'rule' => 'notEmpty',
						'message' => __('Please enter a tag', true)
					)
				),
				'keyname' => array(
					'rule' => 'notEmpty',
					'message' => __('Please enter the key name', true)
				)
			);
		}

		public function getViewData($tag = null) {
			$return = $this->find(
				'first',
				array(
					'conditions' => array(
						$this->alias . '.keyname' => str_replace(' ', '', strtolower($tag))
					)
				)
			);

			return $return;
		}
	}