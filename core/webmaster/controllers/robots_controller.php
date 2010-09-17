<?php
	/**
	 *
	 *
	 */
	class RobotsController extends WebmasterAppController{
		public $name = 'Robots';

		public $uses = array();

		public function admin_edit(){
			Configure::write('Wysiwyg.editor', 'text');
			$File = new File(APP . 'webroot' . DS . 'robots.txt');

			if(!empty($this->data)){
				if($File->write($this->data['Robot']['robots'])){
					$this->Session->setFlash(__('Robots file updated', true));
					$this->redirect(array('controller' => 'webmaster', 'action' => 'dashboard'));
				}

				$this->Session->write(__('There was a problem updating the robots file', true));
			}
			
			if(!isset($this->data['Robot']['robots'])){
				$this->data['Robot']['robots'] = $File->read();
			}
		}
	}