<?php
/**
 * InfinitasPaymentLogs controller
 *
 * @brief Add some documentation for InfinitasPaymentLogs controller.
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

class InfinitasPaymentLogsController extends InfinitasPaymentsAppController {

/**
 * @brief the index method
 *
 * Show a paginated list of InfinitasPaymentLog records.
 *
 * @todo update the documentation
 *
 * @return void
 */
	public function admin_index() {
		$this->Paginator->settings = array(
			'contain' => array(
				'InfinitasPaymentMethod'
			)
		);

		$infinitasPaymentLogs = $this->Paginator->paginate(null, $this->Filter->filter);

		$filterOptions = $this->Filter->filterOptions;
		$filterOptions['fields'] = array(
			'id',
		);

		$this->set(compact('infinitasPaymentLogs', 'filterOptions'));
	}

/**
 * @brief admin create action
 *
 * Adding new InfinitasPaymentLog records.
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
 * Edit old InfinitasPaymentLog records.
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