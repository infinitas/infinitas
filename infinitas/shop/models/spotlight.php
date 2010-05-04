<?php
	class Spotlight extends ShopAppModel{
		var $name = 'Spotlight';

		var $belongsTo = array(
			'Image' => array(
				'className' => 'Shop.Image',
				'foreignKey' => 'image_id',
				'fields' => array(
					'Image.id',
					'Image.image',
					'Image.width',
					'Image.height'
				),
				'conditions' => array(),
				'order' => array(
					'Image.image' => 'ASC'
				)
			),
			'Product' => array(
				'className' => 'Shop.Product',
				'foreignKey' => 'product_id',
				'fields' => array(
					'Product.id',
					'Product.name',
					'Product.slug',
					'Product.image_id',
					'Product.cost',
					'Product.retail',
					'Product.price',
					'Product.active',
					'Product.image_id',
				),
				'conditions' => array(),
				'order' => array(
					'Product.name' => 'ASC'
				)
			)
		);

		var $hasAndBelongsToMany = array(
			'ShopBranch' => array(
				'className' => 'Shop.ShopBranch',
				'foreignKey' => 'branch_id',
				'associationForeignKey' => 'spotlight_id',
				'with' => 'Shop.BranchesSpotlight',
				'unique' => true,
				'conditions' => '',
				'fields' => array(
					'ShopBranch.id',
					'ShopBranch.name'
				),
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => ''
			),
		);

		function getSpotlights($limit = 10){
			$spotlights = $this->find(
				'all',
				array(
					'fields' => array(
						'Spotlight.id',
						'Spotlight.image_id',
						'Spotlight.start_date',
						'Spotlight.end_date'
					),
					'conditions' => array(
						'Spotlight.active' => 1,
						'and' => array(
							'CONCAT(`Spotlight`.`start_date`, " ", `Spotlight`.`start_time`) <= ' => date('Y-m-d H:i:s'),
							'CONCAT(`Spotlight`.`end_date`, " ", `Spotlight`.`end_time`) >= ' => date('Y-m-d H:i:s')
						)
					),
					'contain' => array(
						'Product' => array(
							'fields' => array(
								'Product.id',
								'Product.name',
								'Product.slug',
								'Product.price',
								'Product.retail',
								'Product.description'
							),
							'Image',
							'ProductCategory'
						),
						'Image'
					),
					'limit' => $limit
				)
			);

			return $spotlights;
		}
	}