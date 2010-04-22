<?php
	class SpecialsController extends ShopAppController{
		var $name = 'Specials';

		var $helpers = array(
			'Filter.Filter',
			'Number'
		);

		function admin_index(){
			$this->paginate = array(
				'fields' => array(
					'Special.id',
					'Special.product_id',
					'Special.image_id',
					'Special.discount',
					'Special.amount',
					'Special.start',
					'Special.end',
					'Special.modified',
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

			$specials = $this->paginate(
				null,
				$this->Filter->filter
			);

			$filterOptions = $this->Filter->filterOptions;
			$filterOptions['fields'] = array(
				'active' => (array)Configure::read('CORE.active_options'),
				'product_id' => $this->Special->Product->fnd('list')
			);
			$this->set(compact('specials','filterOptions'));
		}

		function admin_add(){
			if (!empty($this->data)) {
				$this->Special->create();
				if ($this->Special->saveAll($this->data)) {
					$this->Session->setFlash('Your special has been saved.');
					$this->redirect(array('action' => 'index'));
				}
			}

			$products = $this->Special->Product->find('list');
			$images = $this->Special->Image->find('list');
			$this->set(compact('products', 'images'));
		}

		function admin_edit($id = null){
			if (!$id) {
				$this->Session->setFlash(__('That special could not be found', true), true);
				$this->redirect($this->referer());
			}

			if (!empty($this->data)) {
				if ($this->Special->saveAll($this->data)) {
					$this->Session->setFlash('Your special has been saved.');
					$this->redirect(array('action' => 'index'));
				}
			}

			if ($id && empty($this->data)) {
				$this->data = $this->Special->read(null, $id);
			}

			$products = $this->Special->Product->find('list');
			$images = $this->Special->Image->find('list');
			$this->set(compact('products', 'images'));
		}
	}