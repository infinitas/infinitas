<?php
/**
 * BranchesController
 *
 * @package Infinitas.Contact.Controller
 */

/**
 * BranchesController
 *
 * controller to manage branches in the company.
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Contact.Controller
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.7a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

class BranchesController extends ContactAppController {
/**
 * View all available branch records
 *
 * @todo remove recursive 0
 *
 * @return void
 */
	public function index() {
		$this->Branch->recursive = 0;

		$branches = $this->Paginator->paginate(
			null,
			$this->Filter->filter
		);

		if (empty($branches)) {
			$this->notice(
				__('There are no contact details available'),
				array(
					'redirect' => true
				)
			);
		}

		if (count($branches) == 1) {
			$this->redirect(array(
				'action' => 'view',
				'slug' => $branches[0]['Branch']['slug'],
				'id' => $branches[0]['Branch']['id']
			));
		}

		$filterOptions = $this->Filter->filterOptions;
		$filterOptions['fields'] = array(
			'name'
		);

		$this->set(compact('branches', 'filterOptions'));
	}

/**
 * View a branch record
 *
 * @return void
 */
	public function view() {
		if (!isset($this->request->params['slug'])) {
			$this->notice('invalid');
		}

		$branch = $this->Branch->find('first', array(
			'conditions' => array(
				'Branch.slug' => $this->request->params['slug'],
				'Branch.active' => 1
			),
			'contain' => array(
				'ContactAddress' => array(
					'fields' => array(
						'ContactAddress.address'
					),
					'Country' => array(
						'fields' => array(
							'Country.name'
						)
					)
				),
				'Contact'
			)
		));

		if (empty($branch)) {
			$this->notice(
				__('The branch does not exsit'),
				array(
					'redirect' => true
				)
			);
		}

		$this->set('title_for_layout', sprintf(__('Contact us at %s'), $branch['Branch']['name']));
		$this->set(compact('branch'));
	}

/**
 * list all branch records
 *
 * @return void
 */
	public function admin_index() {
		$branches = $this->Paginator->paginate(
			null,
			$this->Filter->filter
		);

		$filterOptions = $this->Filter->filterOptions;
		$filterOptions['fields'] = array(
			'name',
			'active' => (array)Configure::read('CORE.active_options')
		);

		$this->set(compact('branches', 'filterOptions'));
	}

/**
 * Create a new branch record
 *
 * @return void
 */
	public function admin_add() {
		parent::admin_add();

		//$timeZones = $this->Branch->TimeZone->find('list');
		$this->set(compact('timeZones'));
	}

/**
 * Edit a branch record
 *
 * @param string $id the record id
 *
 * @return void
 */
	public function admin_edit($id = null) {
		parent::admin_edit();

		//$timeZones = $this->Branch->TimeZone->find('list');
		$this->set(compact('timeZones'));
	}

}