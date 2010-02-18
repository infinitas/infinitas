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
		var $name = 'Contacts';

		function admin_index(){
			$this->Contact->recursive = 0;

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

		function admin_add(){
			if (!empty($this->data)) {
				$this->Contact->create();
				if ($this->Contact->save($this->data)) {
					$this->Session->setFlash('Your contact has been saved.');
					$this->redirect(array('action' => 'index'));
				}
			}

			$branches = $this->Contact->Branch->find('list');
			$this->set(compact('branches'));
		}

		function admin_edit($id = null){
			if (!$id) {
				$this->Session->setFlash(__('That contact could not be found', true), true);
				$this->redirect($this->referer());
			}

			if (!empty($this->data)) {
				if ($this->Contact->save($this->data)) {
					$this->Session->setFlash(__('Your contact has been saved.', true));
					$this->redirect(array('action' => 'index'));
				}

				$this->Session->setFlash(__('Your contact could not be saved.', true));
			}

			if ($id && empty($this->data)) {
				$this->data = $this->Contact->read(null, $id);
			}

			$branches = $this->Contact->Branch->find('list');
			$this->set(compact('branches'));
		}
	}
?>