<?php
/**
	* UsersController
	*
	* @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	* @link http://www.infinitas-cms.org
	* @package Infinitas.Users.Controller
	* @license http://www.opensource.org/licenses/mit-license.php The MIT License
	* @since 0.7alpha
	*
	* @author Carl Sutton <dogmatic69@infinitas-cms.org>
	*/
	App::uses('UsersAppController', 'Users.Controller');

class UsersController extends UsersAppController {

/**
 * BeforeFilter callback
 *
 * @return void
 */
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow(
			'login', 'logout', 'register',
			'forgot_password', 'reset_password'
		);

		$this->notice['require_auth'] = array(
			'message' => __d('users', 'Please login first'),
			'level' => 'warning',
			'redirect' => array(
				'action' => 'login'
			)
		);
		$this->notice['login_disabled'] = array(
			'message' => __d('users', 'Login is disabled'),
			'redirect' => true,
			'level' => 'warning'
		);
		$this->notice['bad_login'] = array(
			'message' => __d('users', 'Your login details have not been recognised'),
			'level' => 'warning',
			'redirect' => false
		);
		$this->notice['registration_disabled'] = array(
			'message' => __d('users', 'Registration is disabled'),
			'redirect' => true,
			'level' => 'warning'
		);
		$this->notice['registration_failed'] = array(
			'message' => __d('users', 'There was a problem saving your details'),
			'redirect' => false,
			'level' => 'error'
		);
		$this->notice['account_activated'] = array(
			'message' => __d('users', 'Your account is now active, you may log in'),
			'redirect' => array('action' => 'login')
		);
		$this->notice['account_not_activated'] = array(
			'message' => __d('users', 'There was a problem activating your account, please try again'),
			'level' => 'error',
			'redirect' => '/'
		);
		$this->notice['password_reset'] = array(
			'message' => __d('users', 'Your new password was saved. You may now login'),
			'redirect' => array(
				'action' => 'login'
			)
		);
		$this->notice['password_reset_failed'] = array(
			'message' => __d('users', 'User could not be saved'),
			'level' => 'warning',
			'redirect' => array(
				'action' => 'forgot_password'
			)
		);
		$this->notice['reset_timeout'] = array(
			'message' => __d('users', 'Reset request timed out, please try again'),
			'level' => 'warning',
			'redirect' => '/'
		);
		$this->notice['forgot_password'] = array(
			'message' => __d('users', 'An email has been sent to your address with instructions to reset your password'),
			'redirect' => '',
		);
		$this->notice['forgot_password_failed'] = array(
			'message' => __d('users', 'That does not seem to be a valid user'),
			'level' => 'warning',
			'redirect' => ''
		);
	}

/**
 * BeforeRender callback
 *
 * @return void
 */
	public function beforeRender() {
		parent::beforeRender();

		if (!strstr($this->request->params['action'], 'login') && !strstr($this->request->params['action'], 'register')) {
			return;
		}

		if ($this->request->params['admin']) {
			$this->layout = 'admin_login';
		} else if ($this->theme && file_exists(APP . 'View' . DS . 'Themed' . DS . $this->theme . DS . 'Layouts' . DS . 'front_login.ctp')) {
			$this->layout = 'front_login';
		}
	}

/**
 * Profile view
 *
 * @todo remove, same as UserController::profile() ?
 *
 * @return void
 */
	public function view() {
		if (!$this->Auth->user('id')) {
			$this->notice('require_auth');
		}

		$user = $this->{$this->modelClass}->find('profile');

		if (empty($user)) {
			$this->notice('require_auth');
		}

		$this->set(compact('user'));
	}

/**
 * Profile view
 *
 * @return void
 */
	public function profile() {
		if (!empty($this->request->data)) {
			try{
				if ($this->{$this->modelClass}->saveProfile($this->request->data)) {
					$this->notice('saved');
				}
			} catch(Exception $e) {
				$this->notice($e);
			}

			$this->notice('not_saved');
		}

		if ($this->Auth->user('id') && !$this->request->data) {
			$this->request->data = $this->{$this->modelClass}->find('profile');
		}

		if (empty($this->request->data)) {
			$this->notice('require_auth');
		}

		$this->set(compact('user'));
		$this->saveRedirectMarker();
	}

/**
 * Login method.
 *
 * @return void
 */
	public function login() {
		if (!Configure::read('Website.allow_login')) {
			$this->notice('login_disabled');
		}

		$this->_createCookie();

		if (!empty($this->request->data)) {
			if ($this->Auth->login()) {
				$data = $this->_getUserData();

				if ($this->{$this->modelClass}->save($data)) {
					$currentUser = $this->Auth->user();
					$lastLogon = $this->{$this->modelClass}->find('lastLogin');

					$this->Session->write('Auth.User', array_merge($data[$this->modelClass], $currentUser));
					$this->notice(__d('users',
						'Welcome back %s, your last login was from %s, %s on %s. (%s)',
						$currentUser['username'],
						$lastLogon[$this->modelClass]['country'],
						$lastLogon[$this->modelClass]['city'],
						$lastLogon[$this->modelClass]['last_login'],
						$lastLogon[$this->modelClass]['ip_address']
					));
				}

				$this->Event->trigger('userLogin', $currentUser);
				$this->redirect($this->Auth->redirect());
			}

			$this->InfinitasSecurity->badLoginAttempt($this->request->data[$this->modelClass]);
			$this->notice('bad_login');
		}
	}

/**
 * Logout method.
 *
 * @return void
 */
	public function logout() {
		$this->Event->trigger('beforeUserLogout');

		$this->Session->delete('Auth');
		$this->Event->trigger('afterUserLogout');
		return $this->redirect($this->Auth->logout());
	}

/**
 * register a new user
 *
 * Only works when you have allowed registrations. When the email validation
 * is on the user will be sent an email to confirm the registration
 *
 * @return void
 */
	public function register() {
		if (!Configure::read('Website.allow_registration')) {
			$this->notice('registration_disabled');
		}

		if (!empty($this->request->data)) {
			$data = $this->{$this->modelClass}->saveRegistration($this->request->data);

			$flashMessage = 'registration_failed';
			if ($data) {
				$data = $this->{$this->modelClass}->find('first', array(
					'conditions' => array(
						$this->modelClass . '.' . $this->{$this->modelClass}->primaryKey => $this->{$this->modelClass}->id
					)
				));
				$data[$this->modelClass]['activation_url'] = current($this->Event->trigger('getShortUrl', array(
					'url' => InfinitasRouter::url(array(
						'action' => 'activate',
						$this->{$this->modelClass}->createTicket($data[$this->modelClass]['email'])
					))
				)));
				$data[$this->modelClass]['activation_url'] = InfinitasRouter::url($data[$this->modelClass]['activation_url']['ShortUrls']);

				$email = array(
					'email' => $data[$this->modelClass]['email'],
					'name' => $data[$this->modelClass]['prefered_name'] ?: $data[$this->modelClass]['username'],
				);
				if (!$data[$this->modelClass]['active']) {
					$flashMessage = __d('users', 'Thank you, please check your email to complete your registration');
					$email['newsletter'] = 'users-confirm-registration';
				} else {
					$flashMessage = __d('users', 'Thank you, your registration was completed');
					$email['newsletter'] = 'users-account-created';
				}

				$this->Event->trigger('systemEmail', array(
					'email' => $email,
					'var' => $data
				));
				$this->Event->trigger('userRegistration', $data[$this->modelClass]);
			}

			$config = array(
				'redirect' => false,
				'level' => 'warning'
			);
			if ($data) {
				$config = array(
					'redirect' => ''
				);
			}
			$this->notice($flashMessage, $config);
		}
		$this->saveRedirectMarker();
	}

/**
 * Activate a registration
 *
 * When this was set in registration  the user needs to click the link
 * from an email. the code is then checked and if found it will activate
 * that user. aims to stop spam
 *
 * @param string $hash
 *
 * @return void
 */
	public function activate($hash = null) {
		$user = $this->{$this->modelClass}->saveActivation($hash);
		if ($user) {
			$email = array(
				'email' => $user[$this->modelClass]['email'],
				'name' => $user[$this->modelClass]['prefered_name'] ?: $user[$this->modelClass]['username'],
				'newsletter' => 'users-account-created'
			);
			$this->Event->trigger('systemEmail', array(
				'email' => $email,
				'var' => $user
			));
			$this->Event->trigger('userActivation', $user[$this->modelClass]);
			return $this->notice('account_activated');
		}

		$this->notice('account_not_activated');
	}

/**
 * get password reset token
 *
 * If the user has forgotten the pw they can reset it using this form.
 * An email will be sent if they supply the correct details which they
 * will need to click the link to be taken to the reset page.
 *
 * @return void
 */
	public function forgot_password() {
		if (!empty($this->request->data)) {
			$theUser = $this->{$this->modelClass}->find('first', array(
				'conditions' => array(
					$this->{$this->modelClass}->alias . '.email' => $this->request->data[$this->modelClass]['email']
				)
			));
			if (empty($theUser)) {
				return $this->notice('forgot_password_failed');
			}

			$ticket = $this->{$this->modelClass}->createTicket($theUser[$this->modelClass]['email']);
			if (empty($ticket)) {
				return $this->notice('forgot_password_failed');
			}

			$link = current($this->Event->trigger('getShortUrl', array('url' => InfinitasRouter::url(array('action' => 'reset_password', $ticket)))));

			$theUser[$this->modelClass]['reset_link'] = InfinitasRouter::url($link['ShortUrls']);
			$email = array(
				'email' => $theUser[$this->modelClass]['email'],
				'name' => $theUser[$this->modelClass]['prefered_name'] ?: $theUser[$this->modelClass]['username'],
				'newsletter' => 'users-forgot-password'
			);
			try {
				$this->Event->trigger('systemEmail', array(
					'email' => $email,
					'var' => $theUser
				));
			} catch (Exception $e) {
				return $this->notice($e);
			}
			$this->notice('forgot_password');
		}

		$this->saveRedirectMarker();
	}

/**
 * reset the password
 *
 * After the forgot pw page and they have entered the correct details
 * they will recive an email with a link to this page. they can then
 * enter a new pw and it will be reset.
 *
 * @param string $hash the hash of the reset request.
 */
	public function reset_password($hash = null) {
		if (!$hash) {
			$this->notice('reset_timeout');
		}

		if (!empty($this->request->data)) {
			if ($this->{$this->modelClass}->updatePassword($this->request->data)) {
				$this->notice('password_reset');
			}
			$this->notice('password_reset_failed');
		}

		$email = $this->{$this->modelClass}->getTicket($hash);

		if (!$email) {
			$this->notice(__d('users', 'Your ticket has expired, please request a new password'), array(
				'level' => 'error',
				'redirect' => array(
					'action' => 'forgot_password'
				)
			));
		}

		$this->request->data = $this->{$this->modelClass}->find('first', array(
			'conditions' => array(
				$this->{$this->modelClass}->alias . '.email' => $email
			)
		));
	}

/**
 * Admin login
 *
 * @return void
 */
	public function admin_login() {
		$this->layout = 'admin_login';

		$this->_createCookie();

		if ($this->request->data) {
			if ($this->Auth->login()) {
				$lastLogon = $this->{$this->modelClass}->find('lastLogin');
				$data = $this->_getUserData();

				if ($this->{$this->modelClass}->save($data)) {
					$currentUser = $this->Auth->user();

					// there is something wrong
					if ($lastLogon === false) {
						$this->redirect('/logout');
					}

					$this->Session->write('Auth.User', array_merge($data[$this->modelClass], $currentUser));
					$this->notice(__d('users',
						'Welcome back %s, your last login was from %s, %s on %s. (%s)',
						$currentUser['username'],
						$lastLogon[$this->modelClass]['country'],
						$lastLogon[$this->modelClass]['city'],
						$lastLogon[$this->modelClass]['last_login'],
						$lastLogon[$this->modelClass]['ip_address']
					));
				}
				$this->redirect($this->Auth->redirect());
			}

			// bad login
			$this->InfinitasSecurity->badLoginAttempt($this->request->data[$this->modelClass]);
		}

		if ($this->Auth->user()) {
			$this->redirect('/admin');
		}
	}

/**
 * Admin out
 *
 * @return void
 */
	public function admin_logout() {
		$this->Session->delete('Auth');
		$this->redirect(array(
			'action' => 'login'
		));
	}

/**
 * User plugin dashboard for admin backend
 *
 * @return void
 */
	public function admin_dashboard() {

	}

/**
 * display a list of users in the backend for CRUD
 *
 * @return void
 */
	public function admin_index() {
		$users = $this->Paginator->paginate(null, $this->Filter->filter);

		$filterOptions = $this->Filter->filterOptions;
		$filterOptions['fields'] = array(
			'full_name',
			'username',
			'email',
			'group_id' => $this->{$this->modelClass}->Group->find('list'),
			'active' => Configure::read('CORE.active_options')
		);

		$this->set(compact('users', 'filterOptions'));
	}

/**
 * View users that are currently using the app
 *
 * @param string $id the users id
 *
 * @return void
 */
	public function admin_logged_in() {
		$this->Paginator->settings = array('loggedIn');
		$users = $this->Paginator->paginate(null, $this->Filter->filter);

		$filterOptions = $this->Filter->filterOptions;
		$filterOptions['fields'] = array(
			'name',
			'email'
		);

		$this->set(compact('users', 'filterOptions'));
		$this->render('admin_index');
	}

/**
 * Created a new user
 *
 * @param string $id the users id
 *
 * @return void
 */
	public function admin_add() {
		parent::admin_add();

		$groups = $this->{$this->modelClass}->Group->find('list');
		$this->set(compact('groups'));
	}

/**
 * Edit user details in the backend
 *
 * @param string $id the users id
 *
 * @return void
 */
	public function admin_edit($id = null) {
		if (!empty($this->request->data)) {
			if (empty($this->request->data[$this->modelClass]['password'])) {
				unset($this->request->data[$this->modelClass]['password']);
				unset($this->request->data[$this->modelClass]['confirm_password']);
			}
		}

		parent::admin_edit($id);

		$groups = $this->{$this->modelClass}->Group->find('list');
		$this->set(compact('groups'));
	}

/**
 * get some info about the user when they log in
 *
 * @return array
 */
	public function _getUserData() {
		$location = $this->Event->trigger('getLocation');
		return array_merge(array($this->modelClass => array(
			'id' => $this->Auth->user('id'),
			'last_login' => date('Y-m-d H:i:s'),
			'modified' => false,
			'browser' => $this->Infinitas->getBrowser(),
			'operating_system' => $this->Infinitas->getOperatingSystem(),
			'is_mobile' => $this->RequestHandler->isMobile()
		)), current($location['getLocation']));
	}

/**
 * Check if there is a cookie to log the user in with
 *
 * @return void
 */
	protected function _checkCookie() {
		if (!empty($this->request->data)) {
			$cookie = $this->Cookie->read('Auth.User');
			if (!is_null($cookie) && $this->Auth->login()) {
				$this->Session->del('Message.auth');
			}
		}
	}

/**
 * Create a remember me cookie
 *
 * @return void
 */
	public function _createCookie() {
		if (!$this->Auth->user() || empty($this->request->data[$this->modelClass]['remember_me'])) {
			return;
		}

		$cookie = array();
		$cookie['username'] = $this->request->data[$this->modelClass]['username'];
		$cookie['password'] = $this->request->data[$this->modelClass]['password'];
		return $this->Cookie->write('Auth.User', $cookie, true, '+2 weeks');
	}
}