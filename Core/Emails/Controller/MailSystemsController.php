<?php
	class MailSystemsController extends EmailsAppController {
		public function beforeFilter(){
			parent::beforeFilter();

			$this->helpers[] = 'Emails.EmailAttachments';
			return true;
		}

		public function admin_dashboard(){
			$accounts = ClassRegistry::init('Emails.EmailAccount')->getMyAccounts($this->Auth->user('id'));

			if(empty($accounts)){
				$this->notice('You do not have any accounts set up', 'notice');
			}
			
			$this->set(compact('accounts'));
		}

		public function admin_index(){
			if(!$this->request->params['account']){
				$this->notice(__('Please select an account'), 'notice', 0, null, true);
			}

			$this->Paginator->settings = array(
				'conditions' => array(
					'MailSystem.account' => $this->request->params['account']
				),
				'order' => array(
					'date' => 'desc'
				)
			);

			$mails = $this->Paginator->paginate();

			$filterOptions = $this->Filter->filterOptions;
			$filterOptions['fields'] = array(
				'name',
				'description'
			);

			$this->set(compact('mails', 'filterOptions'));
		}

		public function admin_view(){
			if(!$this->request->params['email']){
				$this->notice(__('Please select an email to view'), 'error', 0, true);
			}

			$mail = $this->MailSystem->find(
				'first',
				array(
					'conditions' => array(
						'MailSystem.account' => $this->request->params['account'],
						'MailSystem.id' => $this->request->params['email']
					)
				)
			);

			$this->set(compact('mail'));
		}

		public function admin_get_mail(){
			if(!$this->request->params['email']){
				$this->notice(__('Please select an email to view'), 'error', 0, true);
			}

			$this->layout = 'ajax';
			Configure::write('debug', 0);

			$mail = $this->MailSystem->find(
				'first',
				array(
					'conditions' => array(
						'MailSystem.account' => $this->request->params['account'],
						'MailSystem.id' => $this->request->params['email']
					)
				)
			);

			$this->set(compact('mail'));
		}

		public function admin_mass(){
			$massAction = $this->MassAction->getAction($this->request->params['form']);

			switch($massAction){
				case 'back':
					$this->redirect(array('action' => 'index'));
					break;

				default:
					parent::admin_mass();
					break;
			}
		}
	}