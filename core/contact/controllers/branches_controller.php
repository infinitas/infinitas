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
		public $name = 'Branches';

		public $helpers = array(
			'Filter.Filter'
		);

		public function index(){
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

		public function view(){
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
								'Address.address'
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

			$title_for_layout = sprintf(__('Contact us at %s', true), $branch['Branch']['name']);

			$this->set(compact('branch', 'title_for_layout'));
		}

		public function admin_index(){
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

		public function admin_add(){
			parent::admin_add();

			//$timeZones = $this->Branch->TimeZone->find('list');
			$this->set(compact('timeZones'));
		}

		public function admin_edit($id = null){
			parent::admin_edit();

			//$timeZones = $this->Branch->TimeZone->find('list');
			$this->set(compact('timeZones'));
		}
	}