<?php
/**
 * EmailAccountsController
 *
 * @package Infinitas.Emails.Controller
 */

/**
 * EmailAccountsController
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Emails.Controller
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.7a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */
class EmailAccountsController extends EmailsAppController {
/**
 * List email account information
 *
 * @return void
 */
	public function admin_index() {
		$emailAccounts = $this->Paginator->paginate(null, $this->Filter->filter);

		$filterOptions = $this->Filter->filterOptions;

		$filterOptions['fields'] = array(
			'name',
			'username',
			'email',
			'type' => $this->EmailAccount->types
		);

		$this->set(compact('emailAccounts', 'filterOptions'));
	}

/**
 * Add an email account
 *
 * This will attempt to connect to the email server to validate the connection
 * details are correct.
 *
 * @return void
 */
	public function admin_add() {
		parent::admin_add();

		if (empty($this->request->data)) {
			$this->request->data['EmailAccount'] = array(
				'name' => __d('emails', '%s\'s mail', $this->Auth->user('username')),
				'server' => sprintf('mail.%s', env('HTTP_HOST')),
				'username' => sprintf('%s@%s', $this->Auth->user('username'), env('HTTP_HOST')),
				'email' => $this->Auth->user('email'),
				'type' => 'imap',
				'port' => 143,
				'readonly' => 0,
				'user_id' => $this->Auth->user('id')
			);
		}

		$this->set('users', $this->EmailAccount->User->getSiteRelatedList());
		$this->set('types', $this->EmailAccount->types);
	}

/**
 * Edit an email account
 *
 * @param string $id the account id
 *
 * @return void
 */
	public function admin_edit($id) {
		parent::admin_edit($id);

		$this->set('users', $this->EmailAccount->User->getSiteRelatedList());
		$this->set('types', $this->EmailAccount->types);
	}

}