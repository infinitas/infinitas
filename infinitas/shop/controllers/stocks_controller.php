<?php
	class StocksController extends ShopAppController{
		var $name = 'Stocks';

		var $helpers = array(
			'Filter.Filter'
		);

		function admin_index(){
			$this->paginate = array(
				'contain' => array(
					'Product' => array(
						'ShopCategory'
					),
					'ShopBranch' => array(
						'BranchDetail'
					)
				)
			);

			$filterOptions = $this->Filter->filterOptions;
			$filterOptions['fields'] = array(
				'branch_id' => $this->Stock->ShopBranch->branchList()
			);

			$stocks = $this->paginate('Stock');
			$this->set(compact('stocks', 'filterOptions'));
		}

		function admin_add(){
			if (!empty($this->data)) {
				$current = $this->Stock->find(
					'first',
					array(
						'conditions' => array(
							'Stock.branch_id' => $this->data['Stock']['branch_id'],
							'Stock.product_id' => $this->data['Stock']['product_id']
						)
					)
				);

				if(isset($current['Stock']['stock'])){
					$this->data['Stock']['id'] = $current['Stock']['id'];
					$this->data['Stock']['stock'] += $current['Stock']['stock'];
				}
				else{
					$this->Stock->create();
				}

				if ($this->Stock->save($this->data)) {
					$this->Session->setFlash('The stock has been updated.');
					$this->redirect(array('action' => 'index'));
				}
			}

			$branches = $this->Stock->ShopBranch->branchList();
			$products = $this->Stock->Product->find('list');
			$this->set(compact('branches', 'products'));
		}

		function admin_edit(){

		}

		function admin_valuation($branch_id = null){

		}
	}