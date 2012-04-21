<?php
	class AccessController extends UsersAppController {
		public $uses = false;
		
		public function beforeFilter() {
			parent::beforeFilter();
			
			$this->notice(
				__d('users', 'ACL is not currently in use'), 
				array(
					'level' => 'warning',
					'redirect' => true
				)
			);
		}
	}