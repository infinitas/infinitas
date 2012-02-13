<?php
	/**
	 * @brief Contact plugin Branches controller.
	 *
	 * controller to manage branches in the company.
	 *
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package Infinitas.Contact.controllers
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.7a
	 *
	 * @author dogmatic69
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */

	class BranchesController extends ContactAppController{
		/**
		 * The name of the class
		 *
		 * @var string
		 * @access public
		 */
		public $name = 'Branches';

		/**
		 * The Branch model
		 * 
		 * @var Branch
		 * @access public
		 */
		public $Branch;

		/**
		 * @todo remove recursive 0
		 */
		public function index(){
			$this->Branch->recursive = 0;

			$branches = $this->paginate(
				null,
				$this->Filter->filter
			);

			if (empty($branches)) {
				$this->notice(
					__('There are no contact details available'),
					array(
						'redirect' => true
					)
				);
			}

			if (count($branches) == 1) {
				$this->redirect(array('action' => 'view', 'slug' => $branches[0]['Branch']['slug'], 'id' => $branches[0]['Branch']['id']));
			}

			$filterOptions = $this->Filter->filterOptions;
			$filterOptions['fields'] = array(
				'name'
			);

			$this->set(compact('branches', 'filterOptions'));
		}

		public function view(){
			if (!isset($this->params['slug'])) {
				$this->Infinitas->noticeInvalidRecord();
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
				$this->notice(
					__('The branch does not exsit'),
					array(
						'redirect' => true
					)
				);
			}

			$this->set('title_for_layout', sprintf(__('Contact us at %s'), $branch['Branch']['name']));
			$this->set(compact('branch'));
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

			$this->set(compact('branches', 'filterOptions'));
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