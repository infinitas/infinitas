<?php
	/**
	 * The Branch model.
	 *
	 * for database of contact branches.
	 *
	 * Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 *
	 * @filesource
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package contact
	 * @subpackage contact.models.app_model
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.7a
	 *
	 * @author Carl Sutton ( dogmatic69 )
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */

	class Branch extends ContactAppModel{
		var $name = 'Branch';

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
					'validations' => array(
						'Empty' => array(
						)
					),

						'Empty' => array(
						)
				)
	        ),
			'Libs.Sluggable' => array(
				'label' => array(
					'name'
				)
			)
	    );

		var $hasMany = array(
			'Contact.Contact'
		);

		var $belongsTo = array(
			'Management.Address'
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
				'name' => array(
					'notEmpty' => array(
						'rule' => 'notEmpty',
						'message' => __('Please enter the name of your branch', true)
					),
				),
				'address' => array(
					'notEmpty' => array(
						'rule' => 'notEmpty',
						'message' => __('Please enter the branches address', true)
					)
				),
				'phone' => array(
					'notEmpty' => array(
						'rule' => 'notEmpty',
						'message' => __('Please enter some text for the body', true)
					),
					'phone' => array(
						'rule' => array('phone', '/\D?(\d{3})\D?\D?(\d{3})\D?(\d{4})$/'), //Configure::read('Website.phone_regex')),
						'message' => __('The number does not seem to be valid', true)
					)
				),
				'fax' => array(
					'phone' => array(
						'rule' => array('phone', '/\D?(\d{3})\D?\D?(\d{3})\D?(\d{4})$/'), //Configure::read('Website.phone_regex')),
						'message' => __('Please enter a valid fax number', true),
						'allowEmpty' =>  true
					)
				),
				'map' => array(
					'map' => array(
						'rule' => 'url',
						'message' => __('Please enter a valid url for the map', true),
						'allowEmpty' =>  true
					)
				),
				'time_zone_id' => array(
					'comparison' => array(
						'rule' => array('comparison', '>', 0),
						'message' => __('Please select your time zone', true)
					)
				)
			);
		}

		function beforeFind($queryData){
			$this->bindModel(
				array(
					'belongsTo' => array(
						//'Management.TimeZone'
					)
				)
			);

			return true;
		}
	}
?>