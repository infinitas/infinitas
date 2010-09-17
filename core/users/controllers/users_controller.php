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

	class UsersController extends UsersAppController{
		public $name = 'Users';

		public function beforeFilter(){
			parent::beforeFilter();
			$this->Auth->allow(
				'login', 'logout', 'register',
				'forgot_password', 'reset_password'
			);
		}

		/**
		 * Login method.
		 *
		 * Cake magic
		 *
		 * @access public
		 */
		public function login(){
			if (!Configure::read('Website.allow_login')) {
				$this->Session->setFlash(__('Login is disabled', true));
				$this->redirect('/');
			}

			$this->_createCookie();

			if(!(empty($this->data)) && $this->Auth->user()){
				$this->User->recursive = -1;

				$lastLogon = $this->User->getLastLogon($this->Auth->user('id'));
				$data = $this->_getUserData();

				if ($this->User->save($data)) {
					$currentUser = $this->Session->read('Auth.User');


					// there is something wrong
					if ($lastLogon === false) {
						$this->redirect('/logout');
					}

					$this->Session->write('Auth.User', array_merge($data['User'], $currentUser));
					$this->Session->setFlash(
						sprintf(
							__('Welcome back %s, your last login was from %s, %s on %s. (%s)', true),
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
			if (!(empty($this->data)) && !$this->Auth->user()) {
				$this->Infinitas->badLoginAttempt($this->data['User']);
			}
		}

		public function _getUserData(){
			$data['User']['id']               = $this->Auth->user('id');
			$data['User']['ip_address']       = $this->Session->read('GeoLocation.ip_address');
			$data['User']['last_login']       = date('Y-m-d H:i:s');
			$data['User']['modified']         = false;
			$data['User']['browser']          = $this->Infinitas->getBrowser();
			$data['User']['operating_system'] = $this->Infinitas->getOperatingSystem();

			$data['User']['country']          = $this->Session->read('GeoLocation.country');
			$data['User']['city']             = $this->Session->read('GeoLocation.city');
			$data['User']['is_mobile']        = $this->RequestHandler->isMobile();

			return $data;
		}

		public function _checkCookie(){
			if (!empty($this->data)) {
				$cookie = $this->Cookie->read('Auth.User');
				if (!is_null($cookie)) {
					if ($this->Auth->login($cookie)) {
						$this->Session->del('Message.auth');
					}
				}
			}
		}

		public function _createCookie(){
			if ($this->Auth->user()) {
				if (!empty($this->data['User']['remember_me'])) {
					$cookie = array();
					$cookie['username'] = $this->data['User']['username'];
					$cookie['password'] = $this->data['User']['password'];
					$this->Cookie->write('Auth.User', $cookie, true, '+2 weeks');
					unset($this->data['User']['remember_me']);
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
			$this->Event->trigger('beforeUserLogout', array('user' => $this->Session->read('Auth.User')));
			//@todo if this is false dont logout.

			$this->Session->destroy();
			$this->Event->trigger('afterUserLogout');
			$this->redirect($this->Auth->logout());
		}

		public function register(){
			if (!Configure::read('Website.allow_registration')) {
				$this->Session->setFlash(__('Registration is disabled', true));
				$this->redirect('/');
			}

			if (!empty($this->data)) {
				$this->data['User']['active'] = 1;

				if (Configure::read('Website.email_validation') === true) {
					$this->data['User']['active'] = 0;
				}
				$this->data['User']['group_id'] = 2;

				$this->User->create();

				if ($this->User->saveAll($this->data)) {
					if (!$this->data['User']['active']) {
						$ticket = $this->User->createTicket($this->User->id);

						$urlToActivateUser = ClassRegistry::init('ShortUrlsShortUrl')->newUrl(
			            	'http://'.env('SERVER_NAME').$this->webroot.'management/users/activate/'.$ticket
			            );

						$this->Emailer->sendDirectMail(
							array(
								$this->data['User']['email']
							),
							array(
								'subject' => Configure::read('Website.name').' '.__('Confirm your registration', true),
								'body' => $urlToActivateUser,
								'template' => 'User - Activate'
							)
						);
					}
					else{
						$this->Emailer->sendDirectMail(
							array(
								$this->data['User']['email']
							),
							array(
								'subject' => __('Welcome to ', true).' '.Configure::read('Website.name'),
								'body' => '',
								'template' => 'User - Registration'
							)
						);
					}

					if (Configure::read('Website.email_validation') === true) {
						$this->Session->setFlash(__('Thank you, please check your email to complete your registration', true));
					}
					else{
						$this->Session->setFlash(__('Thank you, your registration was completed', true));
					}

					$this->redirect('/');
				}
			}
		}

		public function activate($hash = null){
	        if (!$hash){
	            $this->Session->setFlash(__('Invalid address', true));
	            $this->redirect('/');
	        }

	        $this->User->id = $this->User->getTicket($hash);

            if ($this->User->saveField('active', 1, null, true)){
            	$user = $this->User->read('email', $this->User->id);

				$this->Emailer->sendDirectMail(
					array(
						$user['User']['email']
					),
					array(
						'subject' => __('Welcome to ', true).' '.Configure::read('Website.name'),
						'body' => '',
						'template' => 'User - Registration'
					)
				);

                $this->Session->setFlash(__('Your account is now active, you may log in', true));
                $this->redirect(array('plugin' => 'management', 'controller' => 'users', 'action' => 'login'));
            }
            else{
                $this->Session->setFlash('There was a problem activating your account, please try again');
                $this->redirect('/');
            }
		}


	    public function forgot_password(){
	        if (!empty($this->data)){
	            $theUser = $this->User->find(
	            	'first',
	            	array(
	            		'conditions' => array(
	            			'User.email' => $this->data['User']['email']
	            		)
	            	)
	            );

	            if (is_array( $theUser['User']) && ($ticket = $this->User->createTicket($theUser['User']['email']) !== false)){
	            	$urlToRessetPassword = ClassRegistry::init('ShortUrls.ShortUrl')->newUrl(
	            		'http://'.env('SERVER_NAME').$this->webroot.'management/users/reset_password/'.$ticket
	            	);

	            	// @todo send a email with a link to reset.
                    $this->Session->setFlash(__('An email has been sent to your address with instructions to reset your password', true));
	            }

	            else{
	                // no user found for adress
	                $this->Session->setFlash(__('That does not seem to be a valid user', true));
	            }
	        }
	    }

	    public function reset_password($hash = null){
	        if (!$hash){
	            $this->Session->setFlash(__('Reset request timed out, please try again', true));
	            $this->redirect('/');
	        }

	        if (!empty($this->data)){
	            $this->User->id = $this->data['User']['id'];

	            if ( $this->User->saveField('password', Security::hash($this->data['User']['new_password'], null, true))){
	                $this->Session->setFlash(__('Your new password was saved. You may now login', true));
	                $this->redirect(array('plugin' => 'management', 'controller' => 'users', 'action' => 'login'));
	            }
	            else{
	                $this->Session->setFlash('User could not be saved');
	                $this->redirect(array('plugin' => 'management', 'controller' => 'users', 'action' => 'forgot_password'));
	            }
	        }

	        $email = $this->User->getTicket($hash);

	        if (!$email){
	            $this->Session->setFlash(__('Your ticket has expired, please request a new password', true));
	            $this->redirect(array('plugin' => 'management', 'controller' => 'users', 'action' => 'forgot_password'));
	        }

	        $this->data = $this->User->find(
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

			if(!(empty($this->data)) && $this->Auth->user()){
				$lastLogon = $this->User->getLastLogon($this->Auth->user('id'));
				$data = $this->_getUserData();

				if ($this->User->save($data)) {
					$currentUser = $this->Session->read('Auth.User');

					// there is something wrong
					if ($lastLogon === false) {
						$this->redirect('/logout');
					}

					$this->Session->write('Auth.User', array_merge($data['User'], $currentUser));
					$this->Session->setFlash(
						sprintf(
							__('Welcome back %s, your last login was from %s, %s on %s. (%s)', true),
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
			
			if(!empty($this->data) && !$this->Auth->user()){
				unset($this->data['User']['password']);
			}
		}

		public function admin_logout(){
			$this->Session->destroy();
			$this->redirect($this->Auth->logout());
		}

		public function admin_index(){
			$this->User->recursive = 0;
			$users = $this->paginate(null, $this->Filter->filter);

			$filterOptions = $this->Filter->filterOptions;
			$filterOptions['fields'] = array(
				'name',
				'email',
				'group_id' => $this->User->Group->find('list'),
				'active' => Configure::read('CORE.active_options')
			);

			$this->set(compact('users','filterOptions'));
		}

		public function admin_logged_in(){
			$Session = ClassRegistry::init('Session');
			$sessions = $Session->find('all');

			$counts['all'] = count($sessions);

			foreach($sessions as &$session){
				$session['User'] = explode('Auth|', $session['Session']['data']);
				$session['User'] = unserialize($session['User'][1]);
				if (isset($session['User']['User'])) {
					$session['User'] = $session['User']['User'];
				}
				else {
					$session['User'] = '';
				}
			}

			$users = Set::extract('/User/id', $sessions);

			$counts['loggedIn'] = count($users);
			$counts['guests']    = $counts['all'] - $counts['loggedIn'];

			$this->User->recursive = 0;
			$users = $this->paginate(array('User.id' => $users), $this->Filter->filter);

			$filterOptions = $this->Filter->filterOptions;
			$filterOptions['fields'] = array(
				'name',
				'email'
			);

			$this->set(compact('users','filterOptions', 'counts'));
			$this->render('admin_index');
		}

		public function admin_add(){
			if (!empty($this->data)) {
				$this->User->create();
				if ($this->User->saveAll($this->data)) {
					$this->Session->setFlash('The user has been saved.');
					$this->redirect(array('action' => 'index'));
				}
			}

			$groups = $this->User->Group->find('list');
			$this->set(compact('groups'));
		}

		public function admin_edit($id = null) {
			if (!$id) {
				$this->Session->setFlash(__('That user could not be found', true), true);
				$this->redirect($this->referer());
			}

			if (!empty($this->data)) {
				if ( $this->data['User']['password'] == Security::hash('', null, true)) {
					unset($this->data['User']['password']);
					unset($this->data['User']['confirm_password']);
				}

				if ($this->User->saveAll($this->data)) {
					$this->Session->setFlash(__('The user has been saved.', true));
					$this->redirect(array('action' => 'index'));
				}

				$this->Session->setFlash(__('The user could not be saved.', true));
			}

			if ($id && empty($this->data)) {
				$this->data = $this->User->read(null, $id);
			}

			$groups = $this->User->Group->find('list');
			$this->set(compact('groups'));
		}

		public function admin_initDB() {
			$group =& $this->User->Group;
			//Allow admins to everything
			$group->id = 1;
			$this->Acl->allow($group, 'controllers');

			//allow managers to posts and widgets
			$group->id = 2;
			//$this->Acl->deny($group, 'controllers');
		}

	}