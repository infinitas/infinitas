<?php
	/**
	 * Groups controller
	 *
	 * Add some documentation for Groups controller.
	 *
	 * @copyright Copyright (c) 2009 Carl Sutton (dogmatic69)
	 *
	 * @link		  http://infinitas-cms.org/Users
	 * @package Infinitas.Users.Controller
	 * @license	   http://infinitas-cms.org/mit-license The MIT License
	 * @since 0.9b1
	 *
	 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
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
		 * the index method
		 *
		 * Show a paginated list of Group records.
		 *
		 * @todo update the documentation
		 *
		 * @return void
		 */
		function admin_index() {
			$groups = $this->Paginator->paginate(null, $this->Filter->filter);

			$filterOptions = $this->Filter->filterOptions;
			$filterOptions['fields'] = array(
				'name',
			);

			$this->set(compact('groups','filterOptions'));
		}

		/**
		 * view method for a single row
		 *
		 * Show detailed information on a single Group
		 *
		 * @todo update the documentation
		 * @param mixed $id int or string uuid or the row to find

		 *
		 * @return void
		 */
		function admin_view($id = null) {
			if(!$id) {
				$this->notice('invalid');
			}

			$group = $this->Group->getViewData(
				array($this->Group->alias . '.' . $this->Group->primaryKey => $id)
			);

			$this->set(compact('group'));
		}

		/**
		 * admin create action
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
		 * admin edit action
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