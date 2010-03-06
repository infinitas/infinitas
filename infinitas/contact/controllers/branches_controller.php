<?php
	/**
	 * Contact branches controller.
	 *
	 * controller to manage branches in the company.
	 *
	 * Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 *
	 * @filesource
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package contact
	 * @subpackage contact.controllers.branches
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.7a
	 *
	 * @author Carl Sutton ( dogmatic69 )
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */

	class BranchesController extends ContactAppController{
		var $name = 'Branches';

		var $helpers = array(
			'Filter.Filter'
		);

		function index(){
			$this->Branch->recursive = 0;

			$branches = $this->paginate(
				null,
				$this->Filter->filter
			);

			if (empty($branches)) {
				$this->Session->setFlash(__('There are no contact details available', true));
				$this->redirect($this->referer());
			}

			if (count($branches) == 1) {
				$this->redirect(array('action' => 'view', 'slug' => $branches[0]['Branch']['slug'], 'id' => $branches[0]['Branch']['id']));
			}

			$filterOptions = $this->Filter->filterOptions;
			$filterOptions['fields'] = array(
				'name'
			);

			$this->set(compact('branches','filterOptions'));
		}

		function view(){
			if (!isset($this->params['slug'])) {
				$this->Session->setFlash( __('A problem occured', true) );
				$this->redirect($this->referer());
			}

			$branch = $this->Branch->find(
				'first',
				array(
					'conditions' => array(
						'Branch.slug' => $this->params['slug'],
						'Branch.active' => 1
					),
					'contain' => array(
						'Address' => array(
							'fields' => array(
								'Address.name',
								'Address.street',
								'Address.city',
								'Address.postal',
								'Address.province'
							),
							'Country' => array(
								'fields' => array(
									'Country.name'
								)
							)
						),
						'Contact'
					)
				)
			);

			if (empty($branch)) {
				$this->Session->setFlash( __('The branch does not exsit', true) );
				$this->redirect($this->referer());
			}

			$this->set(compact('branch'));
		}

		function admin_index(){
			$this->Branch->recursive = 0;

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
				$this->Branch->create();
				if ($this->Branch->save($this->data)) {
					$this->Session->setFlash('Your branch has been saved.');
					$this->redirect(array('action' => 'index'));
				}
			}

			//$timeZones = $this->Branch->TimeZone->find('list');
			$this->set(compact('timeZones'));
		}

		function admin_edit($id = null){
			if (!$id) {
				$this->Session->setFlash(__('That branch could not be found', true), true);
				$this->redirect($this->referer());
			}

			if (!empty($this->data)) {
				if ($this->Branch->save($this->data)) {
					$this->Session->setFlash(__('Your branch has been saved.', true));
					$this->redirect(array('action' => 'index'));
				}

				$this->Session->setFlash(__('Your branch could not be saved.', true));
			}

			if ($id && empty($this->data)) {
				$this->data = $this->Branch->read(null, $id);
			}

			//$timeZones = $this->Branch->TimeZone->find('list');
			$this->set(compact('timeZones'));
		}
	}
?>