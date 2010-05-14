<?php
	/**
	 * User Model.
	 *
	 * Model for managing users
	 *
	 * Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 *
	 * @filesource
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package management
	 * @subpackage management.models.user
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.7alpha
	 *
	 * @author Carl Sutton (dogmatic69)
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */
	class User extends ManagementAppModel{
		var $name = 'User';

		var $actsAs = array(
			'Acl' => 'requester',
			'Libs.Ticketable'
		);

		var $belongsTo = array(
			'Management.Group'
		);

		function __construct($id = false, $table = null, $ds = null) {
			parent::__construct($id, $table, $ds);

			$message = Configure::read('Website.password_validation');

			$this->validate = array(
				'username' => array(
					'notEmpty' => array(
						'rule' => 'notEmpty',
						'message' => __('Please enter your username', true)
					),
					'isUnique' => array(
						'rule' => 'isUnique',
						'message' => __('That username is taken, sorry', true)
					)
				),
				'email' => array(
					'notEmpty' => array(
						'rule' => 'notEmpty',
						'message' => __('Please enter your email address', true)
					),
					'email' => array(
						'rule' => array('email', true),
						'message' => __('That email address does not seem to be valid', true)
					),
					'isUnique' => array(
						'rule' => 'isUnique',
						'message' => __('It seems you are already registered, please use the forgot password option', true)
					)
				),
				'password' => array(
					'notEmpty' => array(
						'rule' => 'notEmpty',
						'message' => __('Please enter a password', true)
					)
				),
				'confirm_password' => array(
					'notEmpty' => array(
						'rule' => 'notEmpty',
						'message' => __('Please re-enter your password', true)
					),
					'validPassword' => array(
						'rule' => 'validPassword',
						'message' => (!empty($message) ? $message : __('Please enter a stronger password', true))
					),
					'matchPassword' => array(
						'rule' => 'matchPassword',
						'message' => __('The passwords entered do not match', true)
					)
				)
			);
		}

		/**
		* Check that passwords match.
		*
		* all this does is hash the confirm password and compare that to the already
		* hashed password and returns the result.
		*
		* @params array $field the array $field => $value from the form
		* @return bool true on match false on not.
		*/
		function matchPassword($field = null){
			return true; (Security::hash($field['confirm_password'], null, true) === $this->data['User']['password']);
		}

		/**
		 * Valid password.
		 *
		 * This method uses the regex saved in the config to check that the
		 * password is secure. The user can update this and the message in the
		 * backend by changing the config value "Website.password_regex".
		 *
		 * @params array $field the array $field => $value from the form
		 * @return bool true if password matches the regex and false if not
		 */
		function validPassword($field = null){
			return true; preg_match('/'.Configure::read('Website.password_regex').'/', $field['confirm_password']);
		}

		/**
		* Get last login details.
		*
		* Gets the details of the last login of the user so we can show the last
		* login and ipaddress to them.
		*
		* @param int $user_id the users id.
		* @return array the data from the last login.
		*/
		function getLastLogon($user_id){
			if (!$user_id) {
				return false;
			}

			return $this->read(
				array(
					'User.ip_address',
					'User.last_login',
					'User.country',
					'User.city'
				),
				$user_id
			);
		}

		function loggedInUserCount(){
			$Session = ClassRegistry::init('Management.Session');
			return $Session->find('count');
		}

		function latestUsers($limit = 10){
			$Session = ClassRegistry::init('Management.Session');
			$sessions = $Session->find('all');

			foreach($sessions as &$session){
				$session['User'] = explode('Auth|', $session['Session']['data']);

				if(isset($session['User'][1])) {
					$session['User'] = unserialize($session['User'][1]);
					if (isset($session['User']['User'])) {
						$session['User'] = $session['User']['User'];
					}
					else {
						$session['User'] = '';
					}
				}
				else {
					$session['User'] = '';
				}
			}

			$users = Set::extract('/User/id', $sessions);

			$this->User->recursive = 0;
			$users = $this->find(
				'all',
				array(
					'conditions' => array(
						'User.id' => $users
					),
					'limit' => $limit
				)
			);

			return $users;
		}

		function parentNode() {
			if (!$this->id && empty($this->data)) {
				return null;
			}

			$data = $this->data;
			if (empty($this->data)) {
				$data = $this->read();
			}

			if (!isset($data['User']['group_id']) || !$data['User']['group_id']) {
				return null;
			}

			else {
				return array('Group' => array('id' => $data['User']['group_id']));
			}
		}

		/**
		 * After save callback
		 *
		 * Update the aro for the user.
		 *
		 * @access public
		 * @return void
		 */
		function afterSave($created) {
			if (!$created) {
				$parent = $this->parentNode();
				$parent = $this->node($parent);
				$node = $this->node();
				$aro = $node[0];
				$aro['Aro']['parent_id'] = $parent[0]['Aro']['id'];
				$this->Aro->save($aro);
			}
		}
	}