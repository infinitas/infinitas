<?php
	class CategoriesController extends ShopAppController{
		var $name = 'Categories';

		var $helpers = array(
			'Filter.Filter'
		);

		function beforeFilter(){
			parent::beforeFilter();

			if(isset($this->data['Image']['image']['name']) && empty($this->data['Image']['image']['name'])){
				unset($this->data['Image']);
			}
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
					'Parent' => array(
						'fields' => array(
							'Parent.id',
							'Parent.name',
							'Parent.slug',
							'Parent.lft',
							'Parent.rght',
							'Parent.parent_id'
						)
					),
					'Image' => array(
						'fields' => array(
							'Image.id',
							'Image.image'
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
				pr($this->data);
				exit;
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