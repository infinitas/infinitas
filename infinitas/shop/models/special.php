<?php
	class Special extends ShopAppModel{
		var $name = 'Special';

		var $virtualFields = array(
			'start' => 'CONCAT(Special.start_date, " ", Special.start_time)',
			'end'   => 'CONCAT(Special.end_date, " ", Special.end_time)'
		);

		var $order = array(
			'end' => 'ASC'
		);

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
					'Product.description',
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
				'associationForeignKey' => 'special_id',
				'with' => 'Shop.BranchesSpecial',
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

		function getSpecials($limit = 10){
			$cacheName = cacheName('products_specials', $limit);
			$specials = Cache::read($cacheName, 'shop');
			if($specials !== false){
				return $specials;
			}
			$specials = $this->find(
				'all',
				array(
					'fields' => array(
						'Special.id',
						'Special.image_id',
						'Special.amount',
						'Special.active',
						'Special.start_date',
						'Special.end_date'
					),
					'conditions' => array(
						'Special.active' => 1,
						'and' => array(
							'start <= ' => date('Y-m-d H:i:s'),
							'end >= ' => date('Y-m-d H:i:s')
						)
					),
					'contain' => array(
						'Product' => array(
							'Image',
							'ShopCategory'
						),
						'Image'
					),
					'limit' => $limit
				)
			);

			Cache::write($cacheName, $specials, 'shop');

			return $specials;
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
				if(strstr($file, 'products_specials') != false){
					Cache::delete($file, 'shop');
				}
			}

			return true;
		}
	}