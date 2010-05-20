<?php
	class ProductsController extends ShopAppController{
		var $name = 'Products';

		var $helpers = array(
			'Filter.Filter'
		);

		function dashboard(){
			$specials = $this->Product->Special->getSpecials();
			$mostViewedProducts = $this->Product->getMostViewed();
			$spotlights = $this->Product->Spotlight->getSpotlights();
			$newest = $this->Product->getNewest();

			$this->set(compact('specials', 'mostViewedProducts', 'spotlights', 'newest'));
		}

		function index(){
			$this->paginate = array(
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
				'conditions' => array(
					'Product.id' => $this->Product->getActiveProducts(),
				),
				'contain' => array(
					'Image',
					'ShopCategory',
					'Special' => array(
						'Image'
					)
				),
				'limit' => 1
			);

			$products = $this->paginate('Product');

			$spotlights = $this->Product->Spotlight->getSpotlights(5);
			$specials = $this->Product->Special->getSpecials(5);
			$this->set(compact('products', 'specials', 'spotlights'));
		}

		function search(){
			if(!isset($this->data['Search']['search'])){
				$this->Session->setFlash(__('Please enter your search term', true));
				$this->redirect($this->referer());
			}

			$this->paginate = array(
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
				'conditions' => array(
					'Product.id' => $this->Product->getActiveProducts(),
					'or' => array(
						'Product.name LIKE ' => '%'. $this->data['Search']['search'] .'%',
						'Product.description LIKE ' => '%'. $this->data['Search']['search'] .'%',
						'Product.specifications LIKE ' => '%'. $this->data['Search']['search'] .'%'
					)
				),
				'contain' => array(
					'Image',
					'ShopCategory',
					'Special' => array(
						'Image'
					)
				),
				'limit' => 1
			);

			$products = $this->paginate('Product');

			$spotlights = $this->Product->Spotlight->getSpotlights(5);
			$specials = $this->Product->Special->getSpecials(5);
			$this->set(compact('products', 'specials', 'spotlights'));
			$this->render('index');
		}

		function view(){
			if (!isset($this->params['slug'])) {
				$this->Session->setFlash( __('The product could not be found', true) );
				$this->redirect($this->referer());
			}

			$conditions = array(
				'Product.id' => $this->Product->getActiveProducts(),
				'Product.slug' => $this->params['slug']
			);

			$product = $this->Product->find(
				'first',
				array(
					'fields' => array(
					),
					'conditions' => $conditions,
					'contain' => array(
						'ShopCategory' => array(
							'Parent'
						),
						'Image',
						'ProductImage',
						'Special' => array(
							'Image'
						),
						'Spotlight' => array(
							'Image'
						),
						'Unit',
						'Supplier',
						'ShopBranch'
					)
				)
			);

			if(empty($product)){
				$this->Session->setFlash( __('You have selected an invalid product', true) );
				$this->redirect($this->referer());
			}

			$tabs = array(
				'description' => '/Product/description',
				'comments' => 'comments',
				'specifications' => '/Product/specifications'
			);

			$eventData = $this->Event->trigger('Shop.setupTabs', $tabs);
			if (isset($eventData)){
			}

			$neighbors = $this->Product->find(
				'neighbors',
				array(
					'fields' => array(
						'Product.id',
						'Product.name',
						'Product.slug',
						'Product.image_id'
					),
					'contain' => array(
						'Image'
					)
				)
			);

			$specials = $this->Product->Special->getSpecials(5);
			$spotlights = $this->Product->Spotlight->getSpotlights(5);
			$this->set(compact('product', 'tabs', 'neighbors', 'specials', 'spotlights'));
		}

		function admin_dashboard(){

		}

		function admin_index(){
			$conditions = array();
			if(isset($this->Filter->filter['Product.category_id'])){
				$category_id  = $this->Filter->filter['Product.category_id'];
				unset($this->Filter->filter['Product.category_id']);
			}
			if(isset($category_id)){
				$conditions = array(
					'Product.id' => $this->Product->getActiveProducts($category_id, array(0,1))
				);
			}

			$this->paginate = array(
				'fields' => array(
					'Product.id',
					'Product.name',
					'Product.slug',
					'Product.active',
					'Product.cost',
					'Product.retail',
					'Product.price',
					'Product.modified',
					'Product.supplier_id',
					'Product.unit_id',
					'Product.image_id'
				),
				'conditions' => $conditions,
				'contain' => array(
					'Image',
					'Unit',
					'Supplier',
					'ShopCategory',
					'ShopBranch' => array(
						'BranchDetail',
					),
					'Special' => array(
						'Image'
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
				'category_id' => $this->Product->ShopCategory->generatetreelist(null, null, null, '_'),
				'supplier_id' => $this->Product->Supplier->find('list'),
				'unit_id' => $this->Product->Unit->find('list'),
				'active' => (array)Configure::read('CORE.active_options')
			);
			$this->set(compact('products','filterOptions'));
		}

		function admin_statistics(){
			$this->paginate = array(
				'fields' => array(
					'Product.id',
					'Product.name',
					'Product.active',
					'Product.views',
					'Product.rating',
					'Product.rating_count',
					'Product.sales',
					'Product.modified',
					'Product.supplier_id',
					'Product.unit_id',
					'Product.image_id',
					'Product.added_to_cart',
					'Product.added_to_wishlist'
				),
				'contain' => array(
					'Image',
					'Supplier',
					'ShopCategory',
					'ShopBranch' => array(
						'BranchDetail',
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
				'category_id' => $this->Product->ShopCategory->generatetreelist(null, null, null, '_'),
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

			$shopCategories = $this->Product->ShopCategory->generatetreelist(null, null, null, '_');
			$units = $this->Product->Unit->find('list');
			$suppliers = $this->Product->Supplier->find('list');
			$shopBranches = $this->Product->ShopBranch->getList();
			$images = $this->Product->Image->getImagePaths();
			$this->set(compact('shopCategories', 'units', 'suppliers', 'shopBranches', 'images'));
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
				$this->data = $this->Product->find(
					'first',
					array(
						'conditions' => array(
							'Product.id' => $id
						),
						'contain' => array(
							'ShopBranch',
							'ShopCategory'
						)
					)
				);
			}

			$units          = $this->Product->Unit->find('list');
			$suppliers      = $this->Product->Supplier->find('list');
			$images         = $this->Product->Image->getImagePaths();
			$shopBranches   = $this->Product->ShopBranch->getList();
			$shopCategories = $this->Product->ShopCategory->generatetreelist(null, null, null, '_');
			$this->set(compact('shopCategories', 'units', 'suppliers', 'shopBranches', 'images'));
		}
	}