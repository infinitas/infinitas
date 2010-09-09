<?php
	class SuppliersController extends ShopAppController{
		var $name = 'Suppliers';

		var $helpers = array(
			'Filter.Filter'
		);

		function admin_index(){
			$this->paginate = array(
				'fields' => array(
					'Supplier.id',
					'Supplier.name',
					'Supplier.slug',
					'Supplier.phone',
					'Supplier.fax',
					'Supplier.logo',
					'Supplier.product_count',
					'Supplier.terms',
					'Supplier.active',
					'Supplier.modified'
				),
				'contain' => false
			);

			$suppliers = $this->paginate(
				null,
				$this->Filter->filter
			);

			$filterOptions = $this->Filter->filterOptions;
			$filterOptions['fields'] = array(
				'name',
				'terms' => (array)Configure::read('Shop.payment_terms'),
				'active' => (array)Configure::read('CORE.active_options')
			);
			$this->set(compact('suppliers','filterOptions'));
		}

		function admin_add(){
			if (!empty($this->data)) {
				$this->Supplier->create();
				if ($this->Supplier->save($this->data)) {
					$this->Session->setFlash('Your supplier has been saved.');
					$this->redirect(array('action' => 'index'));
				}
			}

			$addresses = $this->Supplier->Address->find('list');
			$this->set(compact('addresses'));
		}

		function admin_edit($id = null){
			if (!$id) {
				$this->Session->setFlash(__('That supplier could not be found', true), true);
				$this->redirect($this->referer());
			}

			if (!empty($this->data)) {
				$this->Supplier->create();
				if ($this->Supplier->save($this->data)) {
					$this->Session->setFlash('Your supplier has been saved.');
					$this->redirect(array('action' => 'index'));
				}
			}

			if ($id && empty($this->data)) {
				$this->data = $this->Supplier->read(null, $id);
			}

			$addresses = $this->Supplier->Address->find('list');
			$this->set(compact('addresses'));
		}
	}