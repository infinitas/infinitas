<?php
	class UnitsController extends ShopAppController{
		var $name = 'Units';

		var $helpers = array(
			'Filter.Filter'
		);

		function admin_index(){
			$this->paginate = array(
				'fields' => array(
					'Unit.id',
					'Unit.name',
					'Unit.slug',
					'Unit.symbol',
					'Unit.description',
					'Unit.product_count',
					'Unit.active',
					'Unit.modified',
				),
				'contain' => false
			);

			$units = $this->paginate(
				null,
				$this->Filter->filter
			);

			$filterOptions = $this->Filter->filterOptions;
			$filterOptions['fields'] = array(
				'name'
			);
			$this->set(compact('units','filterOptions'));
		}

		function admin_add(){
			if (!empty($this->data)) {
				$this->Unit->create();
				if ($this->Unit->saveAll($this->data)) {
					$this->Session->setFlash('The unit has been saved.');
					$this->redirect(array('action' => 'index'));
				}
			}
		}

		function admin_edit($id = null){
			if (!$id) {
				$this->Session->setFlash(__('That unnit could not be found', true), true);
				$this->redirect($this->referer());
			}

			if (!empty($this->data)) {
				if ($this->Unit->save($this->data)) {
					$this->Session->setFlash('Your unit has been saved.');
					$this->redirect(array('action' => 'index'));
				}
			}

			if ($id && empty($this->data)) {
				$this->data = $this->Unit->read(null, $id);
			}
		}
	}