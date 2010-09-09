<?php
	class ShopCategory extends ShopAppModel{
		var $name = 'ShopCategory';

		var $order = array(
			'ShopCategory.lft' => 'ASC'
		);

		var $belongsTo = array(
			'Parent' => array(
				'className' => 'Shop.ShopCategory',
				'foreignKey' => 'parent_id',
				'fields' => array(
					'Parent.id',
					'Parent.name',
					'Parent.slug',
					'Parent.lft',
					'Parent.rght',
					'Parent.parent_id'
				),
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
					'ShopBranch.id',
					'ShopBranch.branch_id',
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
				'ShopCategory.parent_id IS NULL'
			);

			if((int)$category_id > 0){
				$conditions = array(
					'ShopCategory.parent_id' => $category_id
				);
			}

			$cacheName = cacheName('categories', $conditions);
			$categories = Cache::read($cacheName, 'shop');
			if($categories !== false){
				return $categories;
			}

			$categories = $this->find(
				'all',
				array(
					'conditions' => array(
						$conditions
					),
					'fields' => array(
						'ShopCategory.id',
						'ShopCategory.parent_id',
						'ShopCategory.name',
						'ShopCategory.slug',
						'ShopCategory.product_count'
					)
				)
			);

			Cache::write($cacheName, $categories, 'shop');

			return $categories;
		}

		function getActiveCategories(){
			$cacheName = cacheName('categories_active', $conditions);
			$category_ids = Cache::read($cacheName, 'shop');
			if($category_ids !== false){
				return $category_ids;
			}

			$category_ids = $this->find(
				'list',
				array(
					'fields' => array(
						'ShopCategory.id','ShopCategory.id'
					),
					'conditions' => array(
						'ShopCategory.active' => 1
					)
				)
			);

			Cache::write($cacheName, $category_ids, 'shop');

			return $category_ids;
		}

		function afterSave($created){
			return $this->dataChanged('afterSave');
		}

		function afterDelete(){
			return $this->dataChanged('afterDelete');
		}

		function dataChanged($from){
			App::import('Folder');
			$Folder = new Folder(CACHE . 'shop');
			$files = $Folder->read();

			foreach($files[1] as $file){
				if(strstr($file, 'categories_') != false){
					Cache::delete($file, 'shop');
				}
			}

			return true;
		}
	}