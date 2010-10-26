<?php
	class EmailAccountsController extends EmailsAppController{
		public $name = 'EmailAccounts';

		public function admin_index(){
			$emailAccounts = $this->paginate(null, $this->Filter->filter);

			$filterOptions = $this->Filter->filterOptions;

			$filterOptions['fields'] = array(
				'name',
				'username',
				'email',
				'type' => $this->EmailAccount->types
			);

			$this->set(compact('emailAccounts','filterOptions'));
		}

		public function admin_add(){
			parent::admin_add();

			if(empty($this->data)){
				$this->data['EmailAccount'] = array(
					'name' => sprintf(__('%s\'s mail', true), $this->Session->read('Auth.User.username')),
					'server' => sprintf('mail.%s', env('HTTP_HOST')),
					'username' => sprintf('%s@%s', $this->Session->read('Auth.User.username'), env('HTTP_HOST')),
					'email' => $this->Session->read('Auth.User.email'),
					'type' => 'imap',
					'port' => 143,
					'readonly' => 0,
					'user_id' => $this->Session->read('Auth.User.id')
				);
			}

			$this->set('users', $this->EmailAccount->User->getSiteRelatedList());
			$this->set('types', $this->EmailAccount->types);
		}

		public function admin_edit($id){
			parent::admin_edit($id);

			$this->set('users', $this->EmailAccount->User->getSiteRelatedList());
			$this->set('types', $this->EmailAccount->types);
		}
	}
