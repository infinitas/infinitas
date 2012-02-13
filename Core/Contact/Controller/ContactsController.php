<?php
	/**
	 * @brief Contacts controller for managing contacts
	 *
	 * Used for managing contacts at the company of the application
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

	class ContactsController extends ContactAppController {
		public function view(){
			if (!isset($this->params['slug'])) {
				$this->Infinitas->noticeInvalidRecord();
			}

			$contact = $this->Contact->find(
				'first',
				array(
					'conditions' => array(
						'Contact.slug' => $this->params['slug'],
						'Contact.active' => 1
					),
					'contain' => array(
						'Branch' => array(
							'fields' => array(
								'id',
								'name',
								'slug',
								'active'
							),
							'Address' => array(
								'Country'
							)
						)
					)
				)
			);

			if (!$contact['Branch']['active']) {
				$this->Infinitas->noticeInvalidRecord();
			}

			$this->set(
				'title_for_layout',
				sprintf(__('Contact details for %s %s'), $contact['Contact']['first_name'], $contact['Contact']['last_name'])
			);
			$this->set(compact('contact'));
		}

		public function admin_index(){
			$this->paginate = array(
				'contain' => array(
					'Branch'
				)
			);
			
			$contacts = $this->paginate(
				null,
				$this->Filter->filter
			);

			$filterOptions = $this->Filter->filterOptions;
			$filterOptions['fields'] = array(
				'name',
				'branch_id' => array(null => __('All branches')) + $this->Contact->Branch->find('list'),
				'active' => (array)Configure::read('CORE.active_options')
			);

			$this->set(compact('contacts', 'filterOptions'));
		}

		public function admin_add(){
			parent::admin_add();
			
			$branches = $this->Contact->Branch->find('list');
			if(empty($branches)){
				$this->notice(__('Please add a branch first'), array('level' => 'notice','redirect' => array('controller' => 'branches')));
			}
			$this->set(compact('branches'));
		}

		public function admin_edit($id = null){
			parent::admin_edit($id);

			$branches = $this->Contact->Branch->find('list');
			$this->set(compact('branches'));
		}
	}