<?php
	/* User Test cases generated on: 2010-03-11 18:03:16 : 1268325136*/
	App::import('Model', 'Management.User');
	App::import('Security');

	/**
	 *
	 *
	 */
	class UserTest extends User{
		public $useDbConfig = 'test';
		public $belongsTo = array();
		public $actsAs = array();
		public $data;
	}

	class UserTestCase extends CakeTestCase {
		public $fixtures = array(
			'plugin.management.core_user',
			'plugin.management.core_ticket'
		);

		public function startTest() {
			$this->User =& new UserTest();
		}

		/**
		* Test the validation methods
		*/
		public function testValidationRules(){
			// fake the submision
			$this->data['User']['password'] = 'cd4f70413dececd8b813e1d5c56c6421e1a35018';
			$this->data['User']['email'] = 'test@example.com';
			$this->User->set($this->data['User']);

			// pw should match
			$field['confirm_password'] = 'my cool password';
			$this->assertTrue($this->User->matchPassword($field));

			// pw does not match
			$field['confirm_password'] = 'this should not match';
			$this->assertFalse($this->User->matchPassword($field));

			// pw regex simple
			Configure::write('Website.password_regex', '[a-z]');
			$field['confirm_password'] = 'simplepw';
			$this->assertTrue($this->User->validPassword($field));
			$field['confirm_password'] = '£^%&^%*^&£$%£';
			$this->assertFalse($this->User->validPassword($field));

			// pw regex advanced
			Configure::write('Website.password_regex', '^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).{4,8}$');
			$field['confirm_password'] = 'aBcD123';
			$this->assertTrue($this->User->validPassword($field));
			$field['confirm_password'] = 'something';
			$this->assertFalse($this->User->validPassword($field));

			// email should match
			$field['confirm_email'] = $this->data['User']['email'];
			$this->assertTrue($this->User->matchEmail($field));

			// email should fail
			$field['confirm_email'] = 'wrong@exaple.com';
			$this->assertFalse($this->User->matchEmail($field));
		}

		/**
		* Test the other methods
		*/
		public function testMethods(){
			$expected['User'] = array(
				'ip_address' => '127.0.0.1',
				'last_login' => '2010-08-16 10:49:19',
				'country' => 'Unknown',
				'city' => '',
			);
			$this->assertEqual($expected, $this->User->getLastLogon(1));
		}

		public function endTest() {
			unset($this->User);
			ClassRegistry::flush();
		}
	}