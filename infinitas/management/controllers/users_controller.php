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
	}
?>