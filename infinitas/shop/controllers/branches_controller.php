<?php
	/**
	 * Controller for shop branches
	 *
	 * manage the branches in the shop
	 *
	 * Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 *
	 * @filesource
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package shop
	 * @subpackage shop.controllers.shopBranches
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.8a
	 *
	 * @author Carl Sutton ( dogmatic69 )
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */

	class BranchesController extends ShopAppController{
		var $name = 'Branches';

		var $uses = array('Shop.ShopBranch');

		var $helpers = array('Filter.Filter');

		function admin_index(){
			$this->paginate = array(
				'fields' => array(
					'ShopBranch.branch_id',
					'ShopBranch.manager_id',
					'ShopBranch.ordering',
					'ShopBranch.active',
					'ShopBranch.modified',
				),
				'contain' => array(
					'BranchDetail' => array(
						'fields' => array(
							'BranchDetail.id',
							'BranchDetail.name',
							'BranchDetail.slug',
							'BranchDetail.image',
							'BranchDetail.phone',
							'BranchDetail.fax',
							'BranchDetail.address_id'
						)
					),
					'Manager',
					'ShopCategory',
					'Product',
					'Special',
					'Spotlight'
				)
			);
			$branches = $this->paginate(
				null,
				$this->Filter->filter
			);

			$filterOptions = $this->Filter->filterOptions;
			$filterOptions['fields'] = array(
				'name',
				'active' => (array)Configure::read('CORE.active_options')
			);

			$this->set(compact('branches','filterOptions'));
		}

		function admin_add(){
			if (!empty($this->data)) {
				$this->ShopBranch->create();
				if ($this->ShopBranch->save($this->data)) {
					$this->Session->setFlash('Your branch has been saved.');
					$this->redirect(array('action' => 'index'));
				}
			}

			$branchDetails = $this->_checkCanAddEdit();
			$managers = $this->ShopBranch->getManagers();
			$this->set(compact('branchDetails', 'managers'));
		}

		function admin_edit($id = null){
			if (!$id) {
				$this->Session->setFlash(__('That branch could not be found', true), true);
				$this->redirect($this->referer());
			}

			if (!empty($this->data)) {
				$this->ShopBranch->create();
				if ($this->ShopBranch->save($this->data)) {
					$this->Session->setFlash('Your branch has been saved.');
					$this->redirect(array('action' => 'index'));
				}
			}

			$branchDetails = $this->ShopBranch->BranchDetail->find('list');
			$managers = $this->ShopBranch->getManagers();
			$this->set(compact('branchDetails', 'managers'));

			if ($id && empty($this->data)) {
				$this->data = $this->ShopBranch->read(null, $id);
			}
		}

		function _checkCanAddEdit(){
			$branchDetails = $this->ShopBranch->_getAvailableBranches();
			if (empty($branchDetails)){
				$this->Session->setFlash(__('Current branches are setup, add more in contacts first', true));
				$this->redirect($this->referer());
			}

			return $branchDetails;
		}
	}