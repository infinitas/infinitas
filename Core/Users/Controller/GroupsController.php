<?php
/**
 * GroupsController
 *
 * @package Infinitas.Users.Controller
 */

/**
 * GroupsController
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Users.Controller
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.9b1
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

class GroupsController extends UsersAppController {
/**
 * Show a paginated list of Group records.
 *
 * @return void
 */
	public function admin_index() {
		$groups = $this->Paginator->paginate(null, $this->Filter->filter);

		$filterOptions = $this->Filter->filterOptions;
		$filterOptions['fields'] = array(
			'name',
		);

		$this->set(compact('groups', 'filterOptions'));
	}

/**
 * view method for a single row
 *
 * Show detailed information on a single Group
 *
 * @param string $id id of the record to view
 *
 * @return void
 */
	public function admin_view($id = null) {
		if (!$id) {
			$this->notice('invalid');
		}

		$group = $this->Group->getViewData(
			array($this->Group->alias . '.' . $this->Group->primaryKey => $id)
		);

		$this->set(compact('group'));
	}

}