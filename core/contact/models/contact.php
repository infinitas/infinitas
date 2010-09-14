<?php
	/**
	 *
	 *
	 */
	class Contact extends ContactAppModel{
		public $name = 'Contact';

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

		public $belongsTo = array(
			'Branch' => array(
				'className' => 'Contact.Branch',
				'counterCache' =>  'user_count',
				'counterScope' =>  array('Contact.active' => 1)
			)
		);

		/**
		 * Construct for validation.
		 *
		 * This is used to make the validation messages run through __()
		 *
		 * @param mixed $id
		 * @param mixed $table
		 * @param mixed $ds
		 */
		public function __construct($id = false, $table = null, $ds = null) {
			parent::__construct($id, $table, $ds);

			$this->validate = array(
				'first_name' => array(
					'notEmpty' => array(
						'rule' => 'notEmpty',
						'message' => __('Please enter the contacts first name', true)
					),
				),
				'last_name' => array(
					'notEmpty' => array(
						'rule' => 'notEmpty',
						'message' => __('Please enter the contacts last name', true)
					),
				),
				'phone' => array(
					'phone' => array(
						'rule' => array('phone', '/\D?(\d{3})\D?\D?(\d{3})\D?(\d{4})$/'), //Configure::read('Website.phone_regex')),
						'message' => __('The number does not seem to be valid', true),
						'allowEmpty' =>  true
					)
				),
				'mobile' => array(
					'phone' => array(
						'rule' => array('phone', '/\D?(\d{3})\D?\D?(\d{3})\D?(\d{4})$/'), //Configure::read('Website.phone_regex')),
						'message' => __('Please enter a valid mobile number', true),
						'allowEmpty' =>  true
					)
				),
				'email' => array(
					'notEmpty' => array(
						'rule' => 'notEmpty',
						'message' => __('Please enter an email address', true)
					),
					'email' => array(
						'rule' => array('email', true),
						'message' => __('That email address does not seem valid', true)
					)
				),
				'branch_id' => array(
					'rule' => array('comparison', '>', 0),
					'message' => __('Please select a branch', true)
				)
			);
		}
	}