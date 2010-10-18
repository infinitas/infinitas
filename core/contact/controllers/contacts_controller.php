<?php
	/**
	 * Contacts controller
	 *
	 * Used for managing contacts at the company of the site
	 *
	 * Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 *
	 * @filesource
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package contact
	 * @subpackage contact.controllers.contacts
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.7a
	 *
	 * @author Carl Sutton ( dogmatic69 )
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */

	class ContactsController extends ContactAppController{
		public $name = 'Contacts';

		public function view(){
			if (!isset($this->params['slug'])) {
				$this->Session->setFlash( __('A problem occured', true) );
				$this->redirect($this->referer());
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
				$this->Session->setFlash(__('No contact found', true));
				$this->redirect($this->referer());
			}

			$title_for_layout = sprintf(__('Contact details for %s %s', true), $contact['Contact']['first_name'], $contact['Contact']['last_name']);

			$this->set(compact('contact', 'title_for_layout'));
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
				'branch_id' => array(null => __('All branches', true)) + $this->Contact->Branch->find('list'),
				'active' => (array)Configure::read('CORE.active_options')
			);

			$this->set(compact('contacts','filterOptions'));
		}

		public function admin_add(){
			parent::admin_add();
			
			$branches = $this->Contact->Branch->find('list');
			if(empty($branches)){
				$this->notice(__('Please add a branch first', true), array('level' => 'notice','redirect' => array('controller' => 'branches')));
			}
			$this->set(compact('branches'));
		}

		public function admin_edit($id = null){
			parent::admin_edit($id);

			$branches = $this->Contact->Branch->find('list');
			$this->set(compact('branches'));
		}
	}