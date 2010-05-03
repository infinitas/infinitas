<?php
	class ProductsController extends ShopAppController{
		var $name = 'Products';

		var $helpers = array(
			'Filter.Filter'
		);

		function dashboard(){
			$specials = $this->Product->Special->getSpecials();

			$this->set(compact('specials'));
		}

		function index(){

		}

		function view(){

		}

		function admin_dashboard(){

		}

		function admin_index(){
			$this->paginate = array(
				'fields' => array(
					'Product.id',
					'Product.name',
					'Product.slug',
					'Product.active',
					'Product.views',
					'Product.rating',
					'Product.rating_count',
					'Product.cost',
					'Product.retail',
					'Product.price',
					'Product.modified',
					'Product.supplier_id',
					'Product.unit_id',
					'Product.image_id',
				),
				'contain' => array(
					'Image' => array(
						'fields' => array(
							'Image.id',
							'Image.image'
						)
					),
					'Unit' => array(
						'fields' => array(
							'Unit.id',
							'Unit.name',
							'Unit.slug'
						)
					),
					'Supplier' => array(
						'fields' => array(
							'Supplier.id',
							'Supplier.name',
							'Supplier.slug'
						)
					),
					'ProductCategory' => array(
						'fields' => array(
							'ProductCategory.id',
							'ProductCategory.name',
							'ProductCategory.slug'
						)
					),
					'ShopBranch' => array(
						'fields' => array(
							'ShopBranch.id',
							'ShopBranch.branch_id',
						),
						'BranchDetail' => array(
							'fields' => array(
								'BranchDetail.id',
								'BranchDetail.name',
							)
						)
					),
					'Special' => array(
						'fields' => array(
							'Special.id',
							'Special.product_id',
							'Special.image_id',
							'Special.discount',
							'Special.amount',
							'Special.start_date',
							'Special.start_time',
							'Special.end_date',
							'Special.end_time',
							'Special.active'
						),
						'Image' => array(
							'fields' => array(
								'Image.id',
								'Image.image'
							)
						)
					)
				)
			);

			$products = $this->paginate(
				null,
				$this->Filter->filter
			);

			$filterOptions = $this->Filter->filterOptions;
			$filterOptions['fields'] = array(
				'name',
				'category_id' => $this->Product->ProductCategory->generatetreelist(null, null, null, '_'),
				'supplier_id' => $this->Product->Supplier->find('list'),
				'unit_id' => $this->Product->Unit->find('list'),
				'active' => (array)Configure::read('CORE.active_options')
			);
			$this->set(compact('products','filterOptions'));
		}

		function admin_add(){
			if (!empty($this->data)) {
				$this->Product->create();
				if ($this->Product->saveAll($this->data)) {
					$this->Session->setFlash('Your product has been saved.');
					$this->redirect(array('action' => 'index'));
				}
			}

			$productCategories = $this->Product->ProductCategory->generatetreelist(null, null, null, '_');
			$units = $this->Product->Unit->find('list');
			$suppliers = $this->Product->Supplier->find('list');
			$shopBranches = $this->Product->ShopBranch->getList();
			$images = $this->Product->Image->find('list');
			$this->set(compact('productCategories', 'units', 'suppliers', 'shopBranches', 'images'));
		}

		function admin_edit($id = null){
			if (!$id) {
				$this->Session->setFlash(__('That product could not be found', true), true);
				$this->redirect($this->referer());
			}

			if (!empty($this->data)) {
				if ($this->Product->saveAll($this->data)) {
					$this->Session->setFlash('Your product has been saved.');
					$this->redirect(array('action' => 'index'));
				}
			}

			if ($id && empty($this->data)) {
				$this->data = $this->Product->read(null, $id);
			}

			$productCategories = $this->Product->ProductCategory->generatetreelist(null, null, null, '_');
			$units = $this->Product->Unit->find('list');
			$suppliers = $this->Product->Supplier->find('list');
			$shopBranches = $this->Product->ShopBranch->getList();
			$images = $this->Product->Image->find('list');
			$this->set(compact('productCategories', 'units', 'suppliers', 'shopBranches', 'images'));
		}
	}