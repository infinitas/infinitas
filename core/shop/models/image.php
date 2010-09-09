<?php
	class Image extends ShopAppModel{
		var $name = 'Image';

		var $virtualFields = array(
			'full_path' => 'CONCAT("/infinitas/img/content/shop/global/", Image.image)'
		);

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
				'className' => 'Shop.ShopCategory'
			)
		);

		function getImagePaths(){
			$images = Cache::read('images', 'shop');
			if($images !== false){
				return $images;
			}

			$images = $this->find(
				'list',
				array(
					'fields' =>
						array(
							'Image.id', 'full_path'
						)
					)
			);

			Cache::write('images', $images, 'shop');

			return $images;
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

			Cache::delete('images', 'shop');

			foreach($files[1] as $file){
				$shouldDelete =
					strstr($file, 'products') ||
					strstr($file, 'specials') ||
					strstr($file, 'spotlights');

				if($shouldDelete != false){
					Cache::delete($file, 'shop');
				}
			}

			return true;
		}
	}