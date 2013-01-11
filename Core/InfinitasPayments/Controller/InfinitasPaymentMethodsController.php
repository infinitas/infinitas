<?php
/**
 * InfinitasPaymentMethods
 *
 * @copyright Copyright (c) 2009 Carl Sutton (dogmatic69)
 *
 * @link http://infinitas-cms.org/InfinitasPayments
 * @package InfinitasPayments.Controller
 * @license http://infinitas-cms.org/mit-license The MIT License
 * @since 0.9b1
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

class InfinitasPaymentMethodsController extends InfinitasPaymentsAppController {

	public function admin_dashboard() {
		
	}

/**
 * @brief the index method
 *
 * Show a paginated list of InfinitasPaymentMethod records.
 *
 * @todo update the documentation
 *
 * @return void
 */
	public function admin_index() {
		$this->Paginator->settings = array(
			'contain' => array(
			)
		);

		$infinitasPaymentMethods = $this->Paginator->paginate(null, $this->Filter->filter);

		$filterOptions = $this->Filter->filterOptions;
		$filterOptions['fields'] = array(
			'name',
			'active' => (array)Configure::read('CORE.active_options'),
		);

		$this->set(compact('infinitasPaymentMethods', 'filterOptions'));
	}

/**
 * @brief view method for a single row
 *
 * Show detailed information on a single InfinitasPaymentMethod
 *
 * @todo update the documentation
 * @param mixed $id int or string uuid or the row to find
 *
 * @return void
 */
	public function admin_view($id = null) {
		if (!$id) {
			$this->Infinitas->noticeInvalidRecord();
		}

		$infinitasPaymentMethod = $this->InfinitasPaymentMethod->getViewData(
			array($this->InfinitasPaymentMethod->alias . '.' . $this->InfinitasPaymentMethod->primaryKey => $id)
		);

		$this->set(compact('infinitasPaymentMethod'));
	}

/**
 * @brief admin create action
 *
 * Adding new InfinitasPaymentMethod records.
 *
 * @todo update the documentation
 *
 * @return void
 */
	public function admin_add() {
		parent::admin_add();

	}

/**
 * @brief admin edit action
 *
 * Edit old InfinitasPaymentMethod records.
 *
 * @todo update the documentation
 * @param mixed $id int or string uuid or the row to edit
 *
 * @return void
 */
	public function admin_edit($id = null) {
		parent::admin_edit($id);

	}
}