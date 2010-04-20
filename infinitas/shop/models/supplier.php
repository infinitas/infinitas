<?php
	class Supplier extends ShopAppModel{
		var $name = 'Supplier';

		var $actsAs = array(
	        'MeioUpload.MeioUpload' => array(
	        	'logo' => array(
		        	'dir' => 'img{DS}content{DS}shop{DS}{ModelName}',
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
			'Libs.SoftDeletable',
			'Libs.Sluggable' => array(
				'label' => array(
					'name'
				)
			)
		);

		var $hasMany = array(
			'Product' => array(
				'className' => 'Shop.Product'
			)
		);

		var $belongsTo = array(
			'Address' => array(
				'className' => 'Management.Address'
			)
		);

		function __construct($id = false, $table = null, $ds = null) {
			parent::__construct($id, $table, $ds);

			$this->validate = array(
				'name' => array(
					'notEmpty' => array(
						'rule' => 'notEmpty',
						'message' => __('Please enter the suppliers name', true)
					),
					'isUnique' => array(
						'rule' => 'isUnique',
						'message' => __('That supplier already exsits', true)
					)
				),
				'email' => array(
					'email' => array(
						'rule' => array('email', true),
						'message' => __('Please enter a valid email address', true)
					)
				)
			);
		}
	}