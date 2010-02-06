<?php
	/**
	 * Users controller
	 *
	 * This is for the management of users.
	 *
	 * Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 *
	 * @filesource
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package management
	 * @subpackage management.controllers.users
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.7alpha
	 *
	 * @author Carl Sutton (dogmatic69)
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */

	class UsersController extends ManagementAppController{
		var $name = 'Users';

		var $helpers = array('Libs.Wysiwyg');

		/**
		 * Login method.
		 *
		 * Cake magic
		 *
		 * @access public
		 */
		function login(){

		}

		/**
		 * Logout method.
		 *
		 * Cake magic
		 *
		 * @access public
		 */
		function logout(){
			$this->redirect($this->Auth->logout());
		}

		function register(){
			if (!empty($this->data)) {
				$this->User->create();
				if ($this->User->saveAll($this->data)) {
					$this->Session->setFlash(__('Thank you, your registration was completed'));
					$this->redirect('/');
				}
			}
		}


		function admin_login(){

		}

		function admin_logout(){
			$this->redirect($this->Auth->logout());
		}

		function admin_index(){
			$this->User->recursive = 0;
			$users = $this->paginate(null, $this->Filter->filter);

			$filterOptions = $this->Filter->filterOptions;
			$filterOptions['fields'] = array(
				'name',
				'email'
			);

			$this->set(compact('users','filterOptions'));
		}

		function admin_add(){
			if (!empty($this->data)) {
				$this->User->create();
				if ($this->User->saveAll($this->data)) {
					$this->Session->setFlash('The user has been saved.');
					$this->redirect(array('action' => 'index'));
				}
			}

			$groups = $this->User->Group->find('list');
			$this->set(compact('groups'));
		}

		function admin_edit($id = null) {
			if (!$id) {
				$this->Session->setFlash(__('That user could not be found', true), true);
				$this->redirect($this->referer());
			}

			if (!empty($this->data)) {
				if ( $this->data['User']['password'] == Security::hash('', null, true)) {
					unset($this->data['User']['password']);
					unset($this->data['User']['confirm_password']);
				}

				if ($this->User->saveAll($this->data)) {
					$this->Session->setFlash(__('The user has been saved.', true));
					$this->redirect(array('action' => 'index'));
				}

				$this->Session->setFlash(__('The user could not be saved.', true));
			}

			if ($id && empty($this->data)) {
				$this->data = $this->User->read(null, $id);
			}

			$groups = $this->User->Group->find('list');
			$this->set(compact('groups'));
		}

		function admin_initDB() {
			$group =& $this->User->Group;
			//Allow admins to everything
			$group->id = 1;
			$this->Acl->allow($group, 'controllers');

			//allow managers to posts and widgets
			$group->id = 2;
			//$this->Acl->deny($group, 'controllers');
		}

	}
?>