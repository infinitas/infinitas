<?php
/**
 * WebmasterRedirects controller
 *
 * @brief Add some documentation for WebmasterRedirects controller.
 *
 * @copyright Copyright (c) 2009 Carl Sutton (dogmatic69)
 *
 * @link		  http://infinitas-cms.org/Webmaster
 * @package	   Webmaster.Controller
 * @license	   http://infinitas-cms.org/mit-license The MIT License
 * @since 0.9b1
 *
 * @author dogmatic69
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 */

class WebmasterRedirectsController extends WebmasterAppController {

/**
 * @brief the index method
 *
 * Show a paginated list of WebmasterRedirect records.
 *
 * @todo update the documentation
 *
 * @return void
 */
	public function admin_index() {
		$this->Paginator->settings = array(
			'contain' => array(
			),
			'order' => array(
				'empty_redirect' => 'desc',
				$this->modelClass . '.error_count' => 'desc'
			),
			'conditions' => array(
				$this->modelClass . '.ignore' => 0
			)
		);

		$webmasterRedirects = $this->Paginator->paginate(null, $this->Filter->filter);

		$filterOptions = $this->Filter->filterOptions;
		$filterOptions['fields'] = array(
			'url',
		);

		$this->set(compact('webmasterRedirects', 'filterOptions'));
	}

/**
 * @brief view method for a single row
 *
 * Show detailed information on a single WebmasterRedirect
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

		$webmasterRedirect = $this->WebmasterRedirect->getViewData(
			array($this->WebmasterRedirect->alias . '.' . $this->WebmasterRedirect->primaryKey => $id)
		);

		$this->set(compact('webmasterRedirect'));
	}

/**
 * @brief admin create action
 *
 * Adding new WebmasterRedirect records.
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
 * Edit old WebmasterRedirect records.
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