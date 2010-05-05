<?php
	class CategoriesController extends ShopAppController{
		var $name = 'Categories';

		var $helpers = array(
			'Filter.Filter'
		);

		function beforeFilter(){
			parent::beforeFilter();
		}

		function index(){
			$conditions = array(
				'Category.active' => 1,
				'Category.parent_id IS NULL'
			);

			$category_id = null;
			if (isset($this->params['slug']) && !empty($this->params['slug'])) {
				$id = $this->Category->find(
					'first',
					array(
						'conditions' => array(
							'Category.slug' => $this->params['slug']
						),
						'fields' => array(
							'Category.id',
							'Category.name',
							'Category.slug',
							'Category.parent_id'
						),
						'contain' => array(
							'Parent'
						)
					)
				);

				$parent = Set::extract('/Parent', $id);
				$currentCategory['Category'] = isset($parent[0]['Parent']) ? $parent[0]['Parent'] : null;

				$category_id = isset($id['Category']['id']) ? $id['Category']['id'] : null;

				if($id){
					$conditions = array(
						'Category.parent_id' => $category_id
					);
				}

			}

			$this->paginate = array(
				'fields' => array(
					'Category.id',
					'Category.name',
					'Category.slug',
					'Category.keywords',
					'Category.image_id',
				),
				'conditions' => $conditions,
				'contain' => array(
					'Image'
				)
			);

			$categories = $this->paginate('Category');
			$products = $this->Category->Product->find(
				'all',
				array(
					'conditions' => array(
						'Product.id' => $this->Category->Product->getActiveProducts($category_id)
					),
					'contain' => array(
						'ProductCategory',
						'Image',
						'Special',
						'Spotlight'
					),
					'limit' => 10
				)
			);

			$specials = $this->Category->Product->Special->getSpecials(5);
			$spotlights = $this->Category->Product->Spotlight->getSpotlights(5);
			$this->set(compact('categories', 'products', 'currentCategory', 'specials', 'spotlights'));
		}

		function admin_index(){
			$this->paginate = array(
				'fields' => array(
					'Category.id',
					'Category.name',
					'Category.slug',
					'Category.image_id',
					'Category.active',
					'Category.lft',
					'Category.rght',
					'Category.parent_id',
					'Category.modified'
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
				'parent_id' => $this->Category->generatetreelist(null, null, null, '_')
			);
			$this->set(compact('categories','filterOptions'));
		}

		function admin_add(){
			if (!empty($this->data)) {
				$this->Category->create();
				if ($this->Category->saveAll($this->data)) {
					$this->Session->setFlash('Your category has been saved.');
					$this->redirect(array('action' => 'index'));
				}
			}

			$parents = $this->Category->generatetreelist(null, null, null, '_');
			$images = $this->Category->Image->find('list');
			$branches = $this->Category->ShopBranch->find('list');
			$this->set(compact('parents', 'images', 'branches'));
		}

		function admin_edit($id = null){
			if (!$id) {
				$this->Session->setFlash(__('That category could not be found', true), true);
				$this->redirect($this->referer());
			}

			if (!empty($this->data)) {
				if ($this->Category->saveAll($this->data)) {
					$this->Session->setFlash('Your category has been saved.');
					$this->redirect(array('action' => 'index'));
				}
			}

			if ($id && empty($this->data)) {
				$this->data = $this->Category->read(null, $id);
			}

			$parents = $this->Category->generatetreelist(null, null, null, '_');
			$images = $this->Category->Image->find('list');
			$shopBranches = $this->Category->ShopBranch->find('list');
			$this->set(compact('parents', 'images', 'shopBranches'));
		}
	}