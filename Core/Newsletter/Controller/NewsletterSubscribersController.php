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
	public function beforeFilter() {
		parent::beforeFilter();

		$this->notice['already_subscribed'] = array(
			'redirect' => true,
			'level' => 'success',
			'message' => __d('newsletter', 'You are already subscribed')
		);

		$this->notice['subscribed'] = array(
			'redirect' => true,
			'level' => 'success',
			'message' => __d('newsletter', 'Your subscription has been saved, please check your email')
		);

		$this->notice['not_subscribed'] = array(
			'redirect' => false,
			'level' => 'warning',
			'message' => __d('newsletter', 'There was a problem saving your subscription')
		);
	}

	public function subscribe() {
		if (!$this->request->is('post')) {
			$this->notice(__d('newsletter', 'No direct access allowed'), array(
				'level' => 'error',
				'redirect' => true
			));
		}

		if (empty($this->request->data[$this->modelClass])) {
			$this->notice(__d('newsletter', 'Details not found'), array(
				'level' => 'error',
				'redirect' => true
			));
		}

		if ($this->{$this->modelClass}->isSubscriber($this->request->data)) {
			return $this->notice('already_subscribed');
		}

		if ($this->{$this->modelClass}->subscribe($this->request->data)) {
			// send out confirmation email
			return $this->notice('subscribed');
		}

		return $this->notice('not_subscribed');
	}

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