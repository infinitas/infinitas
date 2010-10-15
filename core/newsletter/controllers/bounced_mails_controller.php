<?php
	class BouncedMailsController extends NewsletterAppController{
		public $name = 'BouncedMails';

		public function admin_index(){
			$this->paginate = array(
				'order' => array(
					'date' => 'desc'
				)
			);
			
			$bouncedMails = $this->paginate();
			
			$filterOptions = $this->Filter->filterOptions;
			$filterOptions['fields'] = array(
				'name',
				'description'
			);
			
			$this->set(compact('bouncedMails', 'filterOptions'));
		}

		public function admin_view($id = null){
			if(!$id){
				$this->Session->setFlash(__('Please select an email to view', true));
				$this-> redirect($this->referer());
			}
			
			$bouncedMail = $this->BouncedMail->find(
				'first',
				array(
					'conditions' => array(
						'BouncedMail.id' => $id
					)
				)
			);
			
			$this->set(compact('bouncedMail'));
		}

		public function admin_mass(){
			$massAction = $this->MassAction->getAction($this->params['form']);

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
