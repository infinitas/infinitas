<?php
	class Category extends ShopAppModel{
		var $name = 'Category';

		var $order = array(
			'Category.name' => 'ASC'
		);

		var $actsAs = array(
			'Libs.Sluggable' => array(
				'label' => array(
					'name'
				)
			)
		);

		var $belongsTo = array(
			'Parent' => array(
				'className' => 'Shop.Category',
				'foreignKey' => 'parent_id',
				'fields' => array(),
				'conditions' => array(),
				'order' => array(
					'Parent.name' => 'ASC'
				)
			),
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
			)
		);

		var $hasAndBelongsToMany = array(
			'Product' => array(
				'className' => 'Shop.Product',
				'foreignKey' => 'product_id',
				'associationForeignKey' => 'category_id',
				'with' => 'Shop.CategoriesProduct',
				'unique' => true,
				'conditions' => '',
				'fields' => array(
					'Product.id',
					'Product.name',
					'Product.cost'
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
				'associationForeignKey' => 'category_id',
				'with' => 'Shop.BranchesCategory',
				'unique' => true,
				'conditions' => '',
				'fields' => array(
					'Branch.id',
					'Branch.name'
				),
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => ''
			),
		);

		function getCategories($category_id = null){
			$conditions = array(
				'Category.parent_id IS NULL'
			);

			if((int)$category_id > 0){
				$conditions = array(
					'Category.parent_id' => $category_id
				);
			}

			$categories = $this->find(
				'all',
				array(
					'conditions' => array(
						$conditions
					),
					'fields' => array(
						'Category.id',
						'Category.parent_id',
						'Category.name',
						'Category.slug',
						'Category.product_count'
					)
				)
			);

			return $categories;
		}

		function getActiveCategories(){
			$category_ids = $this->find(
				'list',
				array(
					'fields' => array(
						'Category.id','Category.id'
					),
					'conditions' => array(
						'Category.active' => 1
					)
				)
			);

			return $category_ids;
		}
	}