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
					'Unit.name',
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
			),
			'Spotlight' => array(
				'className' => 'Shop.Spotlight'
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
					'ProductImage.image',
					'ProductImage.width',
					'ProductImage.height',
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
					'ShopBranch.id'
				),
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => ''
			),
			'Stock' => array(
				'className' => 'Shop.Stock',
				'foreignKey' => 'branch_id',
				'associationForeignKey' => 'stock_id',
				'with' => 'Shop.Stock',
				'unique' => true,
				'conditions' => '',
				'fields' => array(
					'Stock.id',
					'Stock.branch_id',
					'Stock.branch_id',
					'Stock.stock',
				),
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => ''
			)
		);

		function getMostViewed($limit = 10){
			$cacheName = cacheName('products_mostViewed', $limit);
			$products = Cache::read($cacheName, 'shop');
			if(!empty($products)){
				return $products;
			}

			$products = $this->find(
				'all',
				array(
					'fields' => array(
						'Product.id',
						'Product.name',
						'Product.slug',
						'Product.cost',
						'Product.price',
						'Product.image_id',
					),
					'conditions' => array(
						'Product.id' => $this->getActiveProducts()
					),
					'limit' => (int)$limit,
					'order' => array(
						'Product.views' => 'DESC'
					),
					'contain' => array(
						'ProductCategory',
						'Image',
						'Special' => array(
							'Image'
						)
					)
				)
			);

			Cache::write($cacheName, $products, 'shop');

			return $products;
		}

		function getNewest($limit = 10){
			$cacheName = cacheName('products_newest', $limit);
			$products = Cache::read($cacheName, 'shop');
			if(!empty($products)){
				return $products;
			}

			$products = $this->find(
				'all',
				array(
					'fields' => array(
						'Product.id',
						'Product.name',
						'Product.slug',
						'Product.cost',
						'Product.price',
						'Product.image_id',
					),
					'conditions' => array(
						'Product.id' => $this->getActiveProducts()
					),
					'limit' => (int)$limit,
					'order' => array(
						'Product.created' => 'DESC'
					),
					'contain' => array(
						'ProductCategory',
						'Image',
						'Special' => array(
							'Image'
						)
					)
				)
			);

			Cache::write($cacheName, $products, 'shop');

			return $products;
		}

		function getActiveProducts($category_id = null){
			$conditions = array(
				'ProductCategory.active' => 1
			);

			if ($category_id){
				$conditions = array(
					'ProductCategory.active' => 1,
					'ProductCategory.id' => $category_id
				);
			}

			$cacheName = cacheName('products_active', $conditions);
			$products = Cache::read($cacheName, 'shop');
			if(!empty($products)){
				return $products;
			}

			$products = $this->ProductCategory->find(
				'all',
				array(
					'fields' => array(
						'ProductCategory.id'
					),
					'conditions' => $conditions,
					'order' => false,
					'contain' => array(
						'Product' => array(
							'fields' => array(
								'Product.id', 'Product.id'
							),
							'conditions' => array(
								'Product.active' => 1
							)
						)
					)
				)
			);

			$products = Set::extract('/Product/id', $products);

			Cache::write($cacheName, $products, 'shop');

			return $products;
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
				if(strstr($file, 'products_') != false){
					Cache::delete($file, 'shop');
				}
			}

			return true;
		}
	}