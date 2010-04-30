<?php
	class Product extends ShopAppModel{
		var $name = 'Product';

		var $belongsTo = array(
			'Image' => array(
				'className' => 'Shop.Image',
				'fields' => array(
					'Image.id',
					'Image.image',
					'Image.width',
					'Image.height'
				)
			),
			'Unit' => array(
				'className' => 'Shop.Unit',
				'counterCache' =>  true,
				'fields' => array(
					'Unit.id',
					'Unit.symbol',
					'Unit.description'
				)
			),
			'Supplier' => array(
				'className' => 'Shop.Supplier',
				'counterCache' =>  true,
				'fields' => array(
					'Supplier.id',
					'Supplier.name',
					'Supplier.slug'
				)
			)
		);

		var $hasMany = array(
			'Special' => array(
				'className' => 'Shop.Special'
			)
		);

		var $hasAndBelongsToMany = array(
			'ProductImage' => array(
				'className' => 'Shop.Image',
				'foreignKey' => 'image_id',
				'associationForeignKey' => 'product_id',
				'with' => 'Shop.ImagesProduct',
				'unique' => true,
				'conditions' => '',
				'fields' => array(
					'ProductImage.id',
					'ProductImage.name'
				),
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => ''
			),
			'ProductCategory' => array(
				'className' => 'Shop.Category',
				'foreignKey' => 'category_id',
				'associationForeignKey' => 'product_id',
				'with' => 'Shop.CategoriesProduct',
				'unique' => true,
				'conditions' => '',
				'fields' => array(
					'ProductCategory.id',
					'ProductCategory.name',
					'ProductCategory.slug',
					'ProductCategory.active',
					'ProductCategory.image_id',
					'ProductCategory.parent_id'
				),
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => ''
			),
			'ShopBranch' => array(
				'className' => 'Shop.ShopBranch',
				'foreignKey' => 'branch_id',
				'associationForeignKey' => 'product_id',
				'with' => 'Shop.BranchesProduct',
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
			)
		);
	}