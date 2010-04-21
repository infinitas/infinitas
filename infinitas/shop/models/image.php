<?php
	class Image extends ShopAppModel{
		var $name = 'Image';

		var $displayField = 'image';

		var $order = array(
			'Image.image' => 'ASC'
		);

		var $actsAs = array(
	        'MeioUpload.MeioUpload' => array(
	        	'image' => array(
		        	'dir' => 'img{DS}content{DS}shop{DS}global',
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
						'check' => false
					)
				)
	        )
		);

		var $hasMany = array(
			'Category' => array(
				'className' => 'Shop.Category'
			)
		);
	}