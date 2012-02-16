<?php
	class EmailAccountsController extends EmailsAppController {
		public function admin_index(){
			$emailAccounts = $this->paginate(null, $this->Filter->filter);

			$filterOptions = $this->Filter->filterOptions;

			$filterOptions['fields'] = array(
				'name',
				'username',
				'email',
				'type' => $this->EmailAccount->types
			);

			$this->set(compact('emailAccounts', 'filterOptions'));
		}

		public function admin_add(){
			parent::admin_add();

			if(empty($this->request->data)){
				$this->request->data['EmailAccount'] = array(
					'name' => sprintf(__('%s\'s mail'), $this->Auth->user('username')),
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

		public function admin_edit($id){
			parent::admin_edit($id);

			$this->set('users', $this->EmailAccount->User->getSiteRelatedList());
			$this->set('types', $this->EmailAccount->types);
		}
	}
