<?php
	/**
	 * @page Contact-Plugin Contact plugin
	 *
	 * @section contact-overview What is it
	 *
	 * The Contact plugin provides a way to list and show all the contacts in the
	 * company per branch. You are also able add and list the different branches or
	 * departments.
	 *
	 * The plugin can be configured to show contact forms that users will be able
	 * to use to contact the inderviduals and/or branch that they need.
	 *
	 * @section contact-usage How to use it
	 *
	 * Set up a Route to the Contact plugin in the format that you wish. Branches
	 * are by default routed to /contact/branches/index and contacts are in
	 * /contact/contacts/index. Its recomened to create routes for each, something
	 * like /contact for branches, /contact/:branch to show the branch and
	 * /contact/:branch/:contact for the contacts.
	 *
	 * After you have created the Routes you can go ahead and start creating the
	 * branches and contacts that you require. If you have a small company with
	 * only one branch you still need to enter the basic information, users will
	 * be automatically redirected to that branch when attempting to view the list
	 * of branches.
	 *
	 * @section contact-code How it works
	 *
	 * Each Branch can have many Contac and the Contact belongs to a particular
	 * Branch. The Contact form integrates with the EmailerComponent to send
	 * emails from the contact forms to the Contact in question.
	 *
	 * There is the VcfHelper which generates vCards for users to download and
	 * save to their favorite contacts application like outlook and gmail etc
	 *
	 * Other helpers include the ContactsHelper. This is used to generate card
	 * like elements in the backend. All the helpers can be used throughout your
	 * application should you need some of its functionality
	 *
	 * @image html sql_contact_plugin.png "Contact Plugin table structure"
	 *
	 * @section contact-see-also Also see
	 * @ref ContactsHelper
	 * @ref VcfHelper
	 * @ref Contact
	 * @ref Branch
	 */

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