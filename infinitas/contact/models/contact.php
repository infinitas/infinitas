<?php
	/**
	 *
	 *
	 */
	class Contact extends ContactAppModel{
		var $name = 'Contact';

		var $actsAs = array(
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
			)
		);

		var $belongsTo = array(
			'Contact.Branch'
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
		function __construct($id = false, $table = null, $ds = null) {
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
					'notEmpty' => array(
						'rule' => 'notEmpty',
						'message' => __('Please enter some text for the body', true)
					),
					'phone' => array(
						'rule' => 'phone',
						'message' => __('The number does not seem to be valid', true)
					)
				),
				'mobile' => array(
					'rule' => 'phone',
					'message' => __('Please enter a valid mobile number', true),
					'allowEmpty' =>  true
				),
				'branch_id' => array(
					'rule' => array('comparison', '>', 0),
					'message' => __('Please select a branch', true)
				)
			);
		}
	}
?>