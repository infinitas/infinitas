<?php
	/**
	 * @brief The Branch model.
	 *
	 * CRUD for database of Contact branches
	 *
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package Infinitas.Contact.models
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.7a
	 *
	 * @author dogmatic69
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */

	class Branch extends ContactAppModel {
		/**
		 * hasMany related models
		 *
		 * @var array
		 * @access public
		 */
		public $hasMany = array(
			'Contact.Contact'
		);

		/**
		 * belongsTo related models
		 *
		 * @var array
		 * @access public
		 */
		public $belongsTo = array(
			'Contact.Address'
		);

		/**
		 * @copydoc AppModel::__construct()
		 */
		public function __construct($id = false, $table = null, $ds = null) {
			parent::__construct($id, $table, $ds);

			$this->validate = array(
				'name' => array(
					'notEmpty' => array(
						'rule' => 'notEmpty',
						'message' => __('Please enter the name of your branch')
					),
				),
				'address' => array(
					'notEmpty' => array(
						'rule' => 'notEmpty',
						'message' => __('Please enter the branches address')
					)
				),
				'phone' => array(
					'notEmpty' => array(
						'rule' => 'notEmpty',
						'message' => __('Please enter some text for the body')
					),
					'phone' => array(
						'rule' => array('phone', '/\D?(\d{3})\D?\D?(\d{3})\D?(\d{4})$/'), //Configure::read('Website.phone_regex')),
						'message' => __('The number does not seem to be valid')
					)
				),
				'fax' => array(
					'phone' => array(
						'rule' => array('phone', '/\D?(\d{3})\D?\D?(\d{3})\D?(\d{4})$/'), //Configure::read('Website.phone_regex')),
						'message' => __('Please enter a valid fax number'),
						'allowEmpty' =>  true
					)
				),
				'time_zone_id' => array(
					'comparison' => array(
						'rule' => array('comparison', '>', 0),
						'message' => __('Please select your time zone')
					)
				)
			);
		}

		/**
		 * @todo list all the time zones so that the current time can be shown
		 * of different branches.
		 *
		 * @param array $queryData the find data
		 * 
		 * @return bool
		 */
		public function beforeFind($queryData){
			return true;
		}
	}