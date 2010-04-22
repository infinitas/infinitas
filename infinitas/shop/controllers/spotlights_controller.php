<?php
	class SpotlightsController extends ShopAppController{
		var $name = 'Spotlights';

		var $helpers = array(
			'Filter.Filter'
		);

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
					'Spotlight.modified',
				),
				'contain' => array(
					'Product' => array(
						'fields' => array(
							'Product.id',
							'Product.name',
							'Product.image_id',
							'Product.cost',
							'Product.retail',
							'Product.price',
							'Product.active',
							'Product.image_id',
						),
						'Image' => array(
							'fields' => array(
								'Image.id',
								'Image.image'
							)
						)
					),
					'Image' => array(
						'fields' => array(
							'Image.id',
							'Image.image'
						)
					)
				)
			);

			$spotlights = $this->paginate(
				null,
				$this->Filter->filter
			);

			$filterOptions = $this->Filter->filterOptions;
			$filterOptions['fields'] = array(
				'active' => (array)Configure::read('CORE.active_options'),
				'product_id' => $this->Spotlight->Product->find('list')
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

			$products = $this->Spotlight->Product->find('list');
			$images = $this->Spotlight->Image->find('list');
			$this->set(compact('products', 'images'));
		}

		function admin_edit($id = null){
			if (!$id) {
				$this->Session->setFlash(__('That spotlight could not be found', true), true);
				$this->redirect($this->referer());
			}

			if (!empty($this->data)) {
				if ($this->Spotlight->saveAll($this->data)) {
					$this->Spotlight->setFlash('Your spotlight has been saved.');
					$this->redirect(array('action' => 'index'));
				}
			}

			if ($id && empty($this->data)) {
				$this->data = $this->Spotlight->read(null, $id);
			}

			$products = $this->Spotlight->Product->find('list');
			$images = $this->Spotlight->Image->find('list');
			$this->set(compact('products', 'images'));
		}
	}