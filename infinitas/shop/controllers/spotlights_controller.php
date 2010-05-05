<?php
	class SpotlightsController extends ShopAppController{
		var $name = 'Spotlights';

		var $helpers = array(
			'Filter.Filter'
		);

		function index(){
			$this->paginate = array(
				'fields' => array(
					'Spotlight.id',
					'Spotlight.image_id',
					'Spotlight.active',
					'Spotlight.start_date',
					'Spotlight.end_date'
				),
				'conditions' => array(
					'Spotlight.active' => 1,
					'and' => array(
						'start <= ' => date('Y-m-d H:i:s'),
						'end >= '   => date('Y-m-d H:i:s')
					)
				),
				'contain' => array(
					'Image',
					'Product' => array(
						'Image',
						'ProductCategory'
					)
				)
			);

			$spotlights = $this->paginate('Spotlight');

			$specials = $this->Spotlight->Product->Special->getSpecials(5);
			$this->set(compact('spotlights', 'specials'));
		}

		function admin_index(){
			$this->paginate = array(
				'fields' => array(
					'Spotlight.id',
					'Spotlight.product_id',
					'Spotlight.image_id',
					'Spotlight.start_date',
					'Spotlight.end_date',
					'Spotlight.start_time',
					'Spotlight.end_time',
					'Spotlight.active',
					'Spotlight.modified',
				),
				'contain' => array(
					'Product' => array(
						'Image',
						'Special'
					),
					'Image'
				)
			);

			$spotlights = $this->paginate(
				null,
				$this->Filter->filter
			);

			$filterOptions = $this->Filter->filterOptions;
			$filterOptions['fields'] = array(
				'product_id' => $this->Spotlight->Product->find('list'),
				'active' => (array)Configure::read('CORE.active_options')
			);
			$this->set(compact('spotlights','filterOptions'));
		}

		function admin_add(){
			if (!empty($this->data)) {
				$this->Spotlight->create();
				if ($this->Spotlight->saveAll($this->data)) {
					$this->Session->setFlash('Your spotlight has been saved.');
					$this->redirect(array('action' => 'index'));
				}
			}

			$shopBranches = $this->Spotlight->ShopBranch->getList();
			$products = $this->Spotlight->Product->find('list');
			$images = $this->Spotlight->Image->find('list');
			$this->set(compact('shopBranches', 'products', 'images'));
		}

		function admin_edit($id = null){
			if (!$id) {
				$this->Session->setFlash(__('That spotlight could not be found', true), true);
				$this->redirect($this->referer());
			}

			if (!empty($this->data)) {
				if ($this->Spotlight->saveAll($this->data)) {
					$this->Session->setFlash('Your spotlight has been saved.');
					$this->redirect(array('action' => 'index'));
				}
			}

			if ($id && empty($this->data)) {
				$this->data = $this->Spotlight->read(null, $id);
			}

			$shopBranches = $this->Spotlight->ShopBranch->getList();
			$products = $this->Spotlight->Product->find('list');
			$images = $this->Spotlight->Image->find('list');
			$this->set(compact('shopBranches', 'products', 'images'));
		}
	}