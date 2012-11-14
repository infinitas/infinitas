<?php
/**
 * TrashController
 *
 * @package Infinitas.Trash.Controller
 */

/**
 * TrashController
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Trash.Controller
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.7a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

class TrashController extends TrashAppController {
/**
 * BeforeFilter callback
 *
 * @return boolean
 */
	public function beforeFilter() {
		parent::beforeFilter();
		if(isset($this->request->params['form']['action']) && $this->request->params['form']['action'] == 'cancel') {
			unset($this->request->params['form']['action']);
			$this->redirect(array_merge(array('action' => 'list_items'), $this->request->params['named']));
		}
		return true;
	}

/**
 * List all table with deleted in the schema
 *
 * @return void
 */
	public function admin_index() {
		$this->Paginator->settings = array(
			'contain' => array(
				'User'
			)
		);
		$trashed = $this->Paginator->paginate(null, $this->Filter->filter);

		$filterOptions = $this->Filter->filterOptions;
		$filterOptions['fields'] = array(
			'name',
			'type',
			'deleted_by' => $this->Trash->User->find('list')
		);

		$this->set(compact('trashed', 'filterOptions'));
	}

/**
 * Restor trash items
 *
 * @param array $ids list of ids to restore
 *
 * @return void
 */
	public function __massActionRestore($ids) {
		if($this->Trash->restore($ids)) {
			$this->notice(
				__('The items have been restored'),
				array(
					'redirect' => true
				)
			);
		}

		$this->notice(
			__('The items could not be restored'),
			array(
				'level' => 'error',
				'redirect' => true
			)
		);
	}

}