<?php
	App::uses('InfinitasComponent', 'Libs/Component');
	
	class InfinitasSecurityComponent extends InfinitasComponent {
		public function initialize($Controller) {
			parent::initialize($Controller);

			$this->__setupAuth();
			$this->__setupSecurity();
		}

		/**
		 * Set up Auth.
		 *
		 * Define some things that auth needs to work
		 */
		private function __setupAuth() {
			//$this->Controller->Auth->allow();
			$this->Controller->Auth->allow('display');

			if (!isset($this->Controller->request->params['prefix']) || $this->Controller->request->params['prefix'] != 'admin') {
				$this->Controller->Auth->allow();
			}

			//$this->Controller->Auth->authorize	= array('Actions' => array('actionPath' => 'controllers/'));
			$this->Controller->Auth->loginAction  = array('plugin' => 'users', 'controller' => 'users', 'action' => 'login');

			if(Configure::read('Website.login_type') == 'email'){
				$this->Controller->fields = array('username' => 'email', 'password' => 'password');
			}

			$this->Controller->Auth->loginRedirect = '/';

			if (isset($this->Controller->params['prefix']) && $this->Controller->params['prefix'] == 'admin') {
				$this->Controller->Auth->loginRedirect = '/admin';
			}

			$this->Controller->Auth->logoutRedirect = '/';
			$this->Controller->Auth->userModel = 'Users.User';

			$this->Controller->Auth->userScope = array('User.active' => 1);
		}

		/**
		 * setup Security
		 *
		 * settings for security
		 */
		private function __setupSecurity() {
			$this->Controller->Security->blackHoleCallback = 'blackHole';
			$this->Controller->Security->validatePost = false;
		}
	}