<?php
	App::uses('InfinitasComponent', 'Libs/Component');
	
	class InfinitasSecurityComponent extends InfinitasComponent {
		public function initialize($Controller) {
			parent::initialize($Controller);

			$this->__checkBadLogins();
			$this->__ipBlocker();
			
			$this->__setupAuth();
			$this->__setupSecurity();
		}

		/**
		 * @brief look for bots fillilng out honey traps and stop them from being a pain
		 *
		 * Bots are redirected to /?spam=true in which case you can use your web
		 * server to block them or redirect them to another site.
		 *
		 * should look into this http://www.projecthoneypot.org/httpbl_implementations.php
		 */
		private function __detectBot() {
			if(!empty($this->Controller->request->data[$this->Controller->modelClass]['om_nom_nom'])) {
				$this->Controller->Session->write('Spam.bot', true);
				$this->Controller->Session->write('Spam.detected', time());

				$this->redirect('/?spam=true');
			}

			else if($this->Controller->Session->read('Spam.bot')) {
				if((time() - 3600) > $this->Controller->Session->read('Spam.detected')) {
					$this->Controller->Session->write('Spam', null);
				}
			}
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



		/**
		 * Block people.
		 *
		 * Will get a list of ip addresses that are saved to be blocked and
		 * if the user matches that address they will be black holed.
		 *
		 * If the user is allowed it is saved to their session so that the test
		 * is not done on every request.
		 */
		private function __ipBlocker(){
			if ($this->Controller->Session->read('Infinitas.Security.ip_checked')) {
				return true;
			}

			$ips = Cache::read('blocked_ips', 'core');
			if (!$ips) {
				$ips = ClassRegistry::init('Management.IpAddress')->getBlockedIpAddresses();
			}

			$currentIp = $this->Controller->RequestHandler->getClientIp();

			if(in_array($currentIp, $ips)) {
				$this->Controller->Security->blackHole($this->Controller, 'ipAddressBlocked');
			}

			else {
				foreach($ips as $ip) {
					if(eregi($ip, $currentIp)) {
						$this->Controller->Security->blackHole($this->Controller, 'ipAddressBlocked');
					}
				}
			}

			$this->Controller->Session->write('Infinitas.Security.ip_checked', true);

			return true;
		}

		/**
		 * Record bad logins.
		 *
		 * This will record each time a user tries to log in with the incorect
		 * username / password combination.
		 *
		 * @param array $data the username and password form the login atempt.
		 * @return true
		 */
		public function badLoginAttempt($data){
			$old = (array)$this->Controller->Session->read('Infinitas.Security.loginAttempts');
			$old[] = $data;
			$this->Controller->Session->write('Infinitas.Security.loginAttempts', $old);
			$this->Controller->Session->delete('Infinitas.Security.ip_checked');
			return true;
		}

		/**
		 * Check the bad logins.
		 *
		 * If the bad logins are more than the system allows the user will be band.
		 *
		 * @return true or blackHole;
		 */
		private function __checkBadLogins(){
			return true;

			if($this->Controller->Auth->user('id')) {
				return true;
			}

			$old = $this->Controller->Session->read('Infinitas.Security.loginAttempts');

			if (count($old) > 0) {
				$this->risk = ClassRegistry::init('Management.IpAddress')->findSimmilarAttempts(
					$this->Controller->RequestHandler->getClientIp(),
					$this->Controller->data['User']['username']
				);
			}

			if (count($old) >= Configure::read('Security.login_attempts')) {

				ClassRegistry::init('Management.IpAddress')->blockIp(
					$this->Controller->RequestHandler->getClientIp(),
					$this->Controller->Session->read('Infinitas.Security.loginAttempts'),
					$this->risk
				);

				$this->Controller->Security->blackHole($this->Controller, 'invalidLogin');
			}

			return true;
		}
	}