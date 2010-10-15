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
			
			$this->set('bouncedMail', $this->BouncedMail->read(null, $id));
		}
	}
