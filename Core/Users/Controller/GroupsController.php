<?php
	/**
	 * Groups controller
	 *
	 * @brief Add some documentation for Groups controller.
	 *
	 * @copyright Copyright (c) 2009 Carl Sutton (dogmatic69)
	 *
	 * @link		  http://infinitas-cms.org/Users
	 * @package	   Users.controllers.Groups
	 * @license	   http://infinitas-cms.org/mit-license The MIT License
	 * @since 0.9b1
	 *
	 * @author dogmatic69
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */

	class GroupsController extends UsersAppController {
		/**
		 * The helpers linked to this controller
		 *
		 * @access public
		 * @var string
		 */
		public $helpers = array(
			//'Users.Users', // uncoment this for a custom plugin controller
			//'Libs.Design',
			//'Libs.Gravatar',
		);

		/**
		 * @brief the index method
		 *
		 * Show a paginated list of Group records.
		 *
		 * @todo update the documentation
		 *
		 * @return void
		 */
		function admin_index() {
			$groups = $this->paginate(null, $this->Filter->filter);

			$filterOptions = $this->Filter->filterOptions;
			$filterOptions['fields'] = array(
				'name',
			);

			$this->set(compact('groups','filterOptions'));
		}

		/**
		 * @brief view method for a single row
		 *
		 * Show detailed information on a single Group
		 *
		 * @todo update the documentation 
		 * @param mixed $id int or string uuid or the row to find

		 *
		 * @return void
		 */
		function admin_view($id = null) {
			if(!$id){
				$this->Infinitas->noticeInvalidRecord();
			}

			$group = $this->Group->getViewData(
				array($this->Group->alias . '.' . $this->Group->primaryKey => $id)
			);

			$this->set(compact('group'));
		}

		/**
		 * @brief admin create action
		 *
		 * Adding new Group records.
		 *
		 * @todo update the documentation
		 *
		 * @return void
		 */
		function admin_add() {
			parent::admin_add();

		}

		/**
		 * @brief admin edit action
		 *
		 * Edit old Group records.
		 *
		 * @todo update the documentation
		 * @param mixed $id int or string uuid or the row to edit
		 *
		 * @return void
		 */
		function admin_edit($id = null) {
			parent::admin_edit($id);

		}
	}