<?php
	class ShopCategoriesController extends ShopAppController{
		var $name = 'ShopCategories';
		//var $uses = array('Shop.ShopCategory');

		var $helpers = array(
			'Filter.Filter'
		);

		function beforeFilter(){
			parent::beforeFilter();
		}

		function index(){
			$conditions = array(
				'ShopCategory.active' => 1,
				'ShopCategory.parent_id IS NULL'
			);

			$category_id = null;
			if (isset($this->params['slug']) && !empty($this->params['slug'])) {
				$id = $this->ShopCategory->find(
					'first',
					array(
						'conditions' => array(
							'ShopCategory.slug' => $this->params['slug']
						),
						'fields' => array(
							'ShopCategory.id',
							'ShopCategory.name',
							'ShopCategory.slug',
							'ShopCategory.parent_id'
						),
						'contain' => array(
							'Parent'
						)
					)
				);

				$parent = Set::extract('/Parent', $id);
				$currentCategory['ShopCategory'] = isset($parent[0]['Parent']) ? $parent[0]['Parent'] : null;

				$category_id = isset($id['ShopCategory']['id']) ? $id['ShopCategory']['id'] : null;

				if($id){
					$conditions = array(
						'ShopCategory.parent_id' => $category_id
					);
				}

			}

			$this->paginate = array(
				'fields' => array(
					'ShopCategory.id',
					'ShopCategory.name',
					'ShopCategory.slug',
					'ShopCategory.keywords',
					'ShopCategory.image_id',
				),
				'conditions' => $conditions,
				'contain' => array(
					'Image'
				)
			);

			$categories = $this->paginate('ShopCategory');
			$products = $this->ShopCategory->Product->find(
				'all',
				array(
					'conditions' => array(
						'Product.id' => $this->ShopCategory->Product->getActiveProducts($category_id)
					),
					'contain' => array(
						'ShopCategory',
						'Image',
						'Special',
						'Spotlight'
					),
					'limit' => 10
				)
			);

			$specials = $this->ShopCategory->Product->Special->getSpecials(5);
			$spotlights = $this->ShopCategory->Product->Spotlight->getSpotlights(5);
			$this->set(compact('categories', 'products', 'currentCategory', 'specials', 'spotlights'));
		}

		function admin_index(){
			$this->paginate = array(
				'fields' => array(
					'ShopCategory.id',
					'ShopCategory.name',
					'ShopCategory.slug',
					'ShopCategory.image_id',
					'ShopCategory.active',
					'ShopCategory.lft',
					'ShopCategory.rght',
					'ShopCategory.parent_id',
					'ShopCategory.modified'
				),
				'contain' => array(
					'Parent',
					'Image',
					'ShopBranch' => array(
						'BranchDetail'
					)
				)
			);

			$categories = $this->paginate(
				null,
				$this->Filter->filter
			);

			$filterOptions = $this->Filter->filterOptions;
			$filterOptions['fields'] = array(
				'name',
				'active' => (array)Configure::read('CORE.active_options'),
				'parent_id' => $this->ShopCategory->generatetreelist(null, null, null, '_')
			);
			$this->set(compact('categories','filterOptions'));
		}

		function admin_add(){
			if (!empty($this->data)) {
				$this->ShopCategory->create();
				if ($this->ShopCategory->saveAll($this->data)) {
					$this->Session->setFlash('Your category has been saved.');
					$this->redirect(array('action' => 'index'));
				}
			}

			$parents = $this->ShopCategory->generatetreelist(null, null, null, '_');
			$images = $this->ShopCategory->Image->getImagePaths();
			$branches = $this->ShopCategory->ShopBranch->find('list');
			$this->set(compact('parents', 'images', 'branches'));
		}

		function admin_edit($id = null){
			if (!$id) {
				$this->Session->setFlash(__('That category could not be found', true), true);
				$this->redirect($this->referer());
			}

			if (!empty($this->data)) {
				if ($this->ShopCategory->saveAll($this->data)) {
					$this->Session->setFlash('Your category has been saved.');
					$this->redirect(array('action' => 'index'));
				}
			}

			if ($id && empty($this->data)) {
				$this->data = $this->ShopCategory->read(null, $id);
			}

			$parents = $this->ShopCategory->generatetreelist(null, null, null, '_');
			$images = $this->ShopCategory->Image->getImagePaths();
			$shopBranches = $this->ShopCategory->ShopBranch->find('list');
			$this->set(compact('parents', 'images', 'shopBranches'));
		}
	}