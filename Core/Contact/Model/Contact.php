<?php
	/**
	 * @brief The Contact model handles the CRUD for user details.
	 *
	 * @todo link up the contacts to users in the User table
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

	class Contact extends ContactAppModel{
		/**
		 * the models name
		 * @var string
		 */
		public $name = 'Contact';

		/**
		 * Behaviors that are attached to this model
		 * @var string
		 */
		public $actsAs = array(
			'MeioUpload.MeioUpload' => array(
				'image' => array(
					'dir' => 'img{DS}content{DS}contact{DS}{ModelName}',
					'create_directory' => true,
					'allowed_mime' => array(
						'image/jpeg',
						'image/pjpeg',
						'image/png'
					),
					'allowed_ext' => array(
						'.jpg',
						'.jpeg',
						'.png'
					),

				)
			),
			'Libs.Sequence' => array(
				'group_fields' => array(
					'branch_id'
				)
			),
			'Libs.Sluggable' => array(
				'label' => array(
					'first_name', 'last_name'
				),
				'length' => 255,
				'overwrite' => true
			)
		);

		/**
		 * Relations for this model
		 * @var array
		 */
		public $belongsTo = array(
			'Branch' => array(
				'className' => 'Contact.Branch',
				'counterCache' =>  'user_count',
				'counterScope' =>  array('Contact.active' => 1)
			)
		);

		/**
		 * The branch model
		 * 
		 * @var Branch
		 */
		public $Branch;

		/**
		 * @copydoc AppModel::__construct()
		 */
		public function __construct($id = false, $table = null, $ds = null) {
			parent::__construct($id, $table, $ds);

			$this->validate = array(
				'first_name' => array(
					'notEmpty' => array(
						'rule' => 'notEmpty',
						'message' => __('Please enter the contacts first name')
					),
				),
				'last_name' => array(
					'notEmpty' => array(
						'rule' => 'notEmpty',
						'message' => __('Please enter the contacts last name')
					),
				),
				'phone' => array(
					'phone' => array(
						'rule' => array('phone', '/\D?(\d{3})\D?\D?(\d{3})\D?(\d{4})$/'), //Configure::read('Website.phone_regex')),
						'message' => __('The number does not seem to be valid'),
						'allowEmpty' =>  true
					)
				),
				'mobile' => array(
					'phone' => array(
						'rule' => array('phone', '/\D?(\d{3})\D?\D?(\d{3})\D?(\d{4})$/'), //Configure::read('Website.phone_regex')),
						'message' => __('Please enter a valid mobile number'),
						'allowEmpty' =>  true
					)
				),
				'email' => array(
					'notEmpty' => array(
						'rule' => 'notEmpty',
						'message' => __('Please enter an email address')
					),
					'email' => array(
						'rule' => array('email', true),
						'message' => __('That email address does not seem valid')
					)
				),
				'branch_id' => array(
					'rule' => array('comparison', '>', 0),
					'message' => __('Please select a branch')
				)
			);
		}
	}