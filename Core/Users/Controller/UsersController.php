<?php
	/**
	 * Users controller
	 *
	 * This is for the management of users.
	 *
	 * Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 *
	 * @filesource
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package management
	 * @subpackage management.controllers.users
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.7alpha
	 *
	 * @author Carl Sutton (dogmatic69)
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */
	 App::uses('UsersAppController', 'Users.Controller');

	class UsersController extends UsersAppController {
		public function beforeFilter() {
			parent::beforeFilter();
			$this->Auth->allow(
				'login', 'logout', 'register',
				'forgot_password', 'reset_password'
			);
		}

		public function beforeRender() {
			parent::beforeRender();

			if(!strstr($this->request->params['action'], 'login') && !strstr($this->request->params['action'], 'register')) {
				return;
			}

			if($this->request->params['admin']) {
				$this->layout = 'admin_login';
			}

			else if($this->theme && file_exists(APP . 'View' . DS . 'Themed' . DS . $this->theme . DS . 'Layouts' . DS . 'front_login.ctp')) {
				$this->layout = 'front_login';
			}
		}

		public function view(){
			if(!$this->Auth->user('id')){
				$this->notice(
					__('You must be logged in to view your profile'),
					array(
						'redirect' => array('action' => 'login'),
						'level' => 'warning'
					)
				);
			}

			$user = $this->User->find(
				'first',
				array(
					'conditions' => array(
						'User.id' => $this->Auth->user('id')
					)
				)
			);

			if(empty($user)){
				$this->notice(
					__('Please login to view your profile'),
					array(
						'redirect' => array('action' => 'login'),
						'level' => 'warning'
					)
				);
			}

			$this->set(compact('user'));
		}

		public function profile(){
			if(!$this->Auth->user('id')){
				$this->notice(
					__('You must be logged in to edit your profile'),
					array(
						'redirect' => array('action' => 'login'),
						'level' => 'warning'
					)
				);
			}
			
			if(!empty($this->request->data)) {
				if($this->request->data[$this->modelClass]['id'] == $this->Auth->user('id')) {
					if(isset($this->request->data[$this->modelClass]['prefered_name']) && empty($this->request->data[$this->modelClass]['prefered_name'])) {
						$this->request->data[$this->modelClass]['prefered_name'] = $this->request->data[$this->modelClass]['username'];
					}
					
					if(empty($this->request->data[$this->modelClass]['password'])) {
						unset($this->request->data[$this->modelClass]['password'], $this->request->data[$this->modelClass]['confirm_password']);
					}
					try{
						if($this->{$this->modelClass}->save($this->request->data)) {
							$this->notice('saved');
						}
						
						$this->notice('not_saved');
					}
					
					catch(Exception $e) {
						$this->notice(
							$e->getMessage(),
							array(
								'redirect' => '',
								'level' => 'warning'
							)
						);
					}
				}
			}

			if($this->Auth->user('id') && !$this->request->data) {
				$this->request->data = $this->User->find(
					'first',
					array(
						'conditions' => array(
							'User.id' => $this->Auth->user('id'),
							'User.active' => 1
						)
					)
				);
			}

			if(empty($this->request->data)){
				$this->notice(
					__('Please login to edit your profile'),
					array(
						'redirect' => array('action' => 'login'),
						'level' => 'warning'
					)
				);
			}

			$this->set(compact('user'));
			$this->saveRedirectMarker();
		}

		/**
		 * Login method.
		 *
		 * Cake magic
		 *
		 * @access public
		 */
		public function login() {
			if (!Configure::read('Website.allow_login')) {
				$this->notice(
					__('Login is disabled'),
					array(
						'redirect' => '/',
						'level' => 'warning'
					)
				);
			}

			$this->_createCookie();

			if($this->Auth->login()){
				$this->User->recursive = -1;

				$lastLogon = $this->User->getLastLogon($this->Auth->user('id'));
				$data = $this->_getUserData();

				if ($this->User->save($data)) {
					$currentUser = $this->Auth->user();

					$this->Session->write('Auth.User', array_merge($data['User'], $currentUser));
					$this->notice(
						sprintf(
							__('Welcome back %s, your last login was from %s, %s on %s. (%s)'),
							$currentUser['username'],
							$lastLogon['User']['country'],
							$lastLogon['User']['city'],
							$lastLogon['User']['last_login'],
							$lastLogon['User']['ip_address']
						)
					);
				}

				$this->Event->trigger('userLogin', $currentUser);
				unset($lastLogon, $data);
				$this->redirect($this->Auth->redirect());
			}

			if (!(empty($this->request->data)) && !$this->Auth->user()) {
				$this->InfinitasSecurity->badLoginAttempt($this->request->data['User']);
				$this->notice(__('Your login details have not been recognised'), array('level' => 'warning'));
			}
		}

		/**
		 * get some info about the user when they log in
		 *
		 * @return array
		 */
		public function _getUserData(){
			$data['User']['id']			   = $this->Auth->user('id');
			$data['User']['last_login']	   = date('Y-m-d H:i:s');
			$data['User']['modified']		 = false;
			$data['User']['browser']		  = $this->Infinitas->getBrowser();
			$data['User']['operating_system'] = $this->Infinitas->getOperatingSystem();

			//$data['User'] = array_merge($data['User'], $this->Session->read('GeoLocation'));
			$data['User']['is_mobile']		= $this->RequestHandler->isMobile();
			return $data;
			$location = $this->Event->trigger('getLocation');
			$data['User'] = array_merge($data['User'], current($location['getLocation']));

			return $data;
		}

		/**
		 * Check if there is a cookie to log the user in with
		 */
		public function _checkCookie(){
			if (!empty($this->request->data)) {
				$cookie = $this->Cookie->read('Auth.User');
				if (!is_null($cookie)) {
					if ($this->Auth->login($cookie)) {
						$this->Session->del('Message.auth');
					}
				}
			}
		}

		/**
		 * Create a remember me cookie
		 */
		public function _createCookie(){
			return;
			if ($this->Auth->user()) {
				if (!empty($this->request->data['User']['remember_me'])) {
					$cookie = array();
					$cookie['username'] = $this->request->data['User']['username'];
					$cookie['password'] = $this->request->data['User']['password'];
					$this->Cookie->write('Auth.User', $cookie, true, '+2 weeks');
					unset($this->request->data['User']['remember_me']);
				}
			}
		}

		/**
		 * Logout method.
		 *
		 * Cake magic
		 *
		 * @access public
		 */
		public function logout(){
			$this->Event->trigger('beforeUserLogout', array('user' => $this->Auth->user()));
			//@todo if this is false dont logout.

			$this->Session->destroy();
			$this->Event->trigger('afterUserLogout');
			$this->redirect($this->Auth->logout());
		}

		/**
		 * register a new user
		 *
		 * Only works when you have allowed registrations. When the email validation
		 * is on the user will be sent an email to confirm the registration
		 */
		public function register(){
			if (!Configure::read('Website.allow_registration')) {
				$this->notice(
					__('Registration is disabled'),
					array(
						'redirect' => '/',
						'level' => 'warning'
					)
				);
			}

			if (!empty($this->request->data)) {
				$this->request->data['User']['active'] = 1;

				if (Configure::read('Website.email_validation') === true) {
					$this->request->data['User']['active'] = 0;
				}
				$this->request->data['User']['group_id'] = 2;

				$this->User->create();

				if ($this->User->saveAll($this->request->data)) {
					if (!$this->request->data['User']['active']) {
						$ticket = $this->User->createTicket($this->User->id);

						$urlToActivateUser = ClassRegistry::init('ShortUrlsShortUrl')->newUrl(
							Router::url(array('action' => 'activate', $ticket), true)
						);

						$this->Emailer->sendDirectMail(
							array(
								$this->request->data['User']['email']
							),
							array(
								'subject' => Configure::read('Website.name').' '.__('Confirm your registration'),
								'body' => $urlToActivateUser,
								'template' => 'User - Activate'
							)
						);
					}
					else{
						$this->Emailer->sendDirectMail(
							array(
								$this->request->data['User']['email']
							),
							array(
								'subject' => __('Welcome to ').' '.Configure::read('Website.name'),
								'body' => '',
								'template' => 'User - Registration'
							)
						);
					}

					$flashMessage = __('Thank you, your registration was completed');
					if (Configure::read('Website.email_validation') === true) {
						$flashMessage = __('Thank you, please check your email to complete your registration');
					}

					$this->notice($flashMessage, array('redirect' => '/'));
				}
			}
		}

		/**
		 * Activate a registration
		 *
		 * When this was set in registration  the user needs to click the link
		 * from an email. the code is then checked and if found it will activate
		 * that user. aims to stop spam
		 *
		 * @param string $hash
		 */
		public function activate($hash = null) {
			if (!$hash){
				$this->notice('invalid');
			}

			$this->User->id = $this->User->getTicket($hash);

			if ($this->User->saveField('active', 1, null, true)){
				$user = $this->User->read('email', $this->User->id);

				$this->Emailer->sendDirectMail(
					array(
						$user['User']['email']
					),
					array(
						'subject' => __('Welcome to ') .' '. Configure::read('Website.name'),
						'body' => '',
						'template' => 'User - Registration'
					)
				);

				$this->notice(
					__('Your account is now active, you may log in'),
					array(
						'redirect' => array('action' => 'login')
					)
				);
			}

			$this->notice(
				__('There was a problem activating your account, please try again'),
				array(
					'level' => 'error',
					'redirect' => '/'
				)
			);
		}

		/**
		 * If the user has forgotten the pw they can reset it using this form.
		 * An email will be sent if they supply the correct details which they
		 * will need to click the link to be taken to the reset page.
		 */
		public function forgot_password(){
			if (!empty($this->request->data)){
				$theUser = $this->User->find(
					'first',
					array(
						'conditions' => array(
							'User.email' => $this->request->data['User']['email']
						)
					)
				);

				if (is_array( $theUser['User']) && ($ticket = $this->User->createTicket($theUser['User']['email']) !== false)){
					$urlToRessetPassword = ClassRegistry::init('ShortUrls.ShortUrl')->newUrl(
						Router::url(array('action' => 'reset_password', $ticket), true)
					);

					// @todo send a email with a link to reset.
					$this->notice(
						__('An email has been sent to your address with instructions to reset your password'),
						array(
							'redirect' => '/'
						)
					);
				}

				else{
					$this->notice(
						__('That does not seem to be a valid user'),
						array(
							'level' => 'warning',
							'redirect' => '/'
						)
					);
				}
			}
		}

		/**
		 * After the forgot pw page and they have entered the correct details
		 * they will recive an email with a link to this page. they can then
		 * enter a new pw and it will be reset.
		 *
		 * @param string $hash the hash of the reset request.
		 */
		public function reset_password($hash = null) {
			if (!$hash){
				$this->notice(
					__('Reset request timed out, please try again'),
					array(
						'level' => 'warning',
						'redirect' => '/'
					)
				);
			}

			if (!empty($this->request->data)){
				$this->User->id = $this->request->data['User']['id'];

				if ($this->User->saveField('password', Security::hash($this->request->data['User']['new_password'], null, true))) {
					$this->notice(
						__('Your new password was saved. You may now login'),
						array(
							'redirect' => array(
								'action' => 'login'
							)
						)
					);
				}

				else {
					$this->notice(
						__('User could not be saved'),
						array(
							'level' => 'warning',
							'redirect' => array(
								'action' => 'forgot_password'
							)
						)
					);
				}
			}

			$email = $this->User->getTicket($hash);

			if (!$email){
				$this->notice(
					__('Your ticket has expired, please request a new password'),
					array(
						'level' => 'error',
						'redirect' => array(
							'action' => 'forgot_password'
						)
					)
				);
			}

			$this->request->data = $this->User->find(
				'first',
				array(
					'conditions' => array(
						'User.email' => $email
					)
				)
			);
		}

		public function admin_login(){
			$this->layout = 'admin_login';

			$this->_createCookie();

			if($this->request->data) {
				if($this->Auth->login()) {
					$lastLogon = $this->User->getLastLogon($this->Auth->user('id'));
					$data = $this->_getUserData();

					if ($this->User->save($data)) {
						$currentUser = $this->Auth->user();

						// there is something wrong
						if ($lastLogon === false) {
							$this->redirect('/logout');
						}

						$this->Session->write('Auth.User', array_merge($data['User'], $currentUser));
						$this->notice(
							sprintf(
								__('Welcome back %s, your last login was from %s, %s on %s. (%s)'),
								$currentUser['username'],
								$lastLogon['User']['country'],
								$lastLogon['User']['city'],
								$lastLogon['User']['last_login'],
								$lastLogon['User']['ip_address']
							)
						);
					}
					$this->redirect($this->Auth->redirect());
				}

				// bad login
				$this->InfinitasSecurity->badLoginAttempt($this->request->data['User']);
			}

			if($this->Auth->user()) {
				$this->redirect('/admin');
			}
		}

		public function admin_logout(){
			$this->Session->destroy();
			$this->redirect(array('action' => 'login'));
		}

		public function admin_dashboard(){

		}

		public function admin_index(){
			$this->User->recursive = 0;
			$users = $this->Paginator->paginate(null, $this->Filter->filter);

			$filterOptions = $this->Filter->filterOptions;
			$filterOptions['fields'] = array(
				'full_name',
				'username',
				'email',
				'group_id' => $this->User->Group->find('list'),
				'active' => Configure::read('CORE.active_options')
			);

			$this->set(compact('users', 'filterOptions'));
		}

		public function admin_logged_in(){
			$this->Paginator->settings =array(
				'conditions' => array(
					'User.last_login > ' => date('Y-m-d H:i:s', strtotime('-30 min'))
				),
				'order' => array(
					'User.last_login' => 'desc'
				)
			);
			$users = $this->Paginator->paginate(null, $this->Filter->filter);

			$filterOptions = $this->Filter->filterOptions;
			$filterOptions['fields'] = array(
				'name',
				'email'
			);

			$this->set(compact('users', 'filterOptions'));
			$this->render('admin_index');
		}

		public function admin_add(){
			parent::admin_add();

			$groups = $this->User->Group->find('list');
			$this->set(compact('groups'));
		}

		public function admin_edit($id = null) {
			if (!$id) {
				$this->notice('invalid');
			}

			if (!empty($this->request->data)) {
				if ($this->request->data['User']['password'] == Security::hash('', null, true)) {
					unset($this->request->data['User']['password']);
					unset($this->request->data['User']['confirm_password']);
				}

				if ($this->User->saveAll($this->request->data)) {
					$this->notice('saved');
				}

				$this->notice('not_saved');
			}

			if ($id && empty($this->request->data)) {
				$this->request->data = $this->User->read(null, $id);
			}

			$groups = $this->User->Group->find('list');
			$this->set(compact('groups'));
		}

		/**
		 * for acl, should be removed.
		 */
		public function admin_initDB() {
			$group = $this->User->Group;
			//Allow admins to everything
			$group->id = 1;
			$this->Acl->allow($group, 'controllers');

			//allow managers to posts and widgets
			$group->id = 2;
			//$this->Acl->deny($group, 'controllers');
		}
	}