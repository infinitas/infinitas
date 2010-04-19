<?php
	class Branch extends ShopAppModel{
		var $name = 'Branch';

		var $actsAs = array(
			'Libs.SoftDeletable',
			'Libs.Feedable'
		);

		var $belongsTo = array(
			'BranchDetails' => array(

			)
		);

		var $hasAndBelongsToMany = array(
			'SCategory' => array(
				'className' => 'Shop.Category',
				'foreignKey' => 'category_id',
				'associationForeignKey' => 'branch_id',
				'with' => 'Shop.BranchesCategory',
				'unique' => true,
				'conditions' => '',
				'fields' => array('ShopCategory.id', 'ShopCategory.name'),
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => ''
			),
			'Product' => array(
				'className' => 'Shop.Product',
				'foreignKey' => 'product_id',
				'associationForeignKey' => 'branch_id',
				'with' => 'Shop.BranchesProduct',
				'unique' => true,
				'conditions' => '',
				'fields' => array('Product.id', 'Product.name'),
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => ''
			),
			'Special' => array(
				'className' => 'Shop.Category',
				'foreignKey' => 'special_id',
				'associationForeignKey' => 'branch_id',
				'with' => 'Shop.BranchesSpecial',
				'unique' => true,
				'conditions' => '',
				'fields' => array('Special.id', 'Special.name'),
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => ''
			),
			'Spotlight' => array(
				'className' => 'Shop.Spotlight',
				'foreignKey' => 'spotlight_id',
				'associationForeignKey' => 'branch_id',
				'with' => 'Shop.BranchesSpotlight',
				'unique' => true,
				'conditions' => '',
				'fields' => array('Spotlight.id', 'Spotlight.name'),
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => ''
			)
		);
	}