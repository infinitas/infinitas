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
	 * @package Infinitas.Contents.Model
	 * @license   MIT License (http://www.opensource.org/licenses/mit-license.php)
	 *
	 * Short description for class.
	 *
	 * @package Infinitas.Contents.Model
	 */

	class GlobalTag extends ContentsAppModel {
		/**
		 * hasMany associations
		 *
		 * @var array
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
						'message' => __d('contents', 'Please enter a tag')
					)
				),
				'keyname' => array(
					'rule' => 'notEmpty',
					'message' => __d('contents', 'Please enter the key name')
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