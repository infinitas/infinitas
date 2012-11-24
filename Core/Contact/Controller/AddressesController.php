<?php
/**
 * ContactAddressesController
 *
 * @package Infinitas.Contact.Controller
 */

/**
 * ContactAddressesController
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Contact.Controller
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.6a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */
class ContactAddressesController extends ContactAppController {
/**
 * Add new contact address
 *
 * @return void
 */
	public function add() {
		if(!$this->Auth->user('id')) {
			$this->notice(
				__d('contact', 'You must be logged in to do that'),
				array(
					'redirect' => true
				)
			);
		}

		if (!empty($this->request->data)) {
			$this->{$this->modelClass}->create();
			if ($this->{$this->modelClass}->saveAll($this->request->data)) {
				$this->Infintias->noticeSaved();
				$this->redirect('/');
			}
		}

		$this->request->data[$this->modelClass]['plugin'] = 'management';
		$this->request->data[$this->modelClass]['model'] = 'user';
		$this->request->data[$this->modelClass]['foreign_key'] = $this->Auth->user('id');

		$countries = $this->{$this->modelClass}->Country->find('list');
		$continents = array(0 => 'Other', 1 => 'Africa');
		$this->set(compact('referer', 'countries', 'continents'));
	}

/**
 * Edit contact address
 *
 * @param string $id the contact id to edit
 *
 * @return void
 */
	public function edit($id = null) {
		parent::edit($id);

		$countries = $this->{$this->modelClass}->Country->find('list');
		$continents = array(0 => 'Other', 1 => 'Africa');
		$this->set(compact('countries', 'continents'));
	}

/**
 * List all addresses
 *
 * @return void
 */
	public function admin_index() {
		$this->Paginator->settings = array(
			'contain' => array(
				'Country'
			)
		);

		$addresses = $this->Paginator->paginate(null, $this->Filter->filter);

		$filterOptions = $this->Filter->filterOptions;
		$filterOptions['fields'] = array(
			'name',
			'street',
			'city',
			'province',
			'postal',
			'country_id' => $this->{$this->modelClass}->Country->find('list'),
			'continent' => Configure::read('Contact.continents'),
			'active' => (array)Configure::read('CORE.active_options')
		);

		$this->set(compact('addresses', 'filterOptions'));
	}

}