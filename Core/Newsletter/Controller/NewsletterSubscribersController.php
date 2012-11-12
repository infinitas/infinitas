<?php
	/**
	 * Comment Template.
	 *
	 * @todo Implement .this needs to be sorted out.
	 *
	 * Copyright (c) 2009 Carl Sutton ( dogmatic69 )
	 *
	 *
	 *
	 * @filesource
	 * @copyright Copyright (c) 2009 Carl Sutton ( dogmatic69 )
	 * @link http://infinitas-cms.org
	 * @package Infinitas.Newsletter.Controller
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.5a
	 */

	class NewsletterSubscribersController extends NewsletterAppController {
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

		public function admin_add() {
			parent::admin_add();

			$users = $this->NewsletterSubscriber->User->find('list');
			$this->set(compact('users'));
		}

		public function admin_edit($id) {
			parent::admin_edit($id);

			$users = $this->NewsletterSubscriber->User->find('list');
			$this->set(compact('users'));
		}
	}