<?php
/**
 * NewsletterSubscribersController
 *
 * @package Infinitas.Newsletter.Controller
 */

/**
 * NewsletterSubscribersController
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Newsletter.Controller
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.5a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

class NewsletterSubscribersController extends NewsletterAppController {
/**
 * List all subscribers
 *
 * @return void
 */
	public function admin_index() {
		$this->Paginator->settings = array('paginated');

		$newsletterSubscribers = $this->Paginator->paginate('NewsletterSubscriber', $this->Filter->filter);

		$filterOptions = $this->Filter->filterOptions;
		$filterOptions['fields'] = array(
			'prefered_name',
			'email'
		);

		$this->set(compact('newsletterSubscribers', 'filterOptions'));
	}

/**
 * Add new subscriber details
 *
 * @return void
 */
	public function admin_add() {
		parent::admin_add();

		$users = $this->NewsletterSubscriber->User->find('list');
		$this->set(compact('users'));
	}

/**
 * Edit a newsletter subscriber
 *
 * @param string $id the subscirber id
 *
 * @return void
 */
	public function admin_edit($id) {
		parent::admin_edit($id);

		$users = $this->NewsletterSubscriber->User->find('list');
		$this->set(compact('users'));
	}

}