<?php
	/* User Test cases generated on: 2010-03-11 18:03:16 : 1268325136*/
	App::import('Model', 'Users.User');
	App::import('Security');

	/**
	 *
	 *
	 */
	class UserTest extends User{
		public $alias = 'User';
		public $useDbConfig = 'test';
		public $belongsTo = array();
		public $actsAs = false;
		public $data;
	}

	class UserTestCase extends CakeTestCase {
		public $fixtures = array(
			'plugin.users.user'
		);

		public function startTest() {
			$this->User = new UserTest();
		}

		/**
		* Test the validation methods
		*/
		public function testValidationRules() {
			// fake the submision
			$this->data['User']['password'] = 'cd4f70413dececd8b813e1d5c56c6421e1a35018';
			$this->data['User']['email'] = 'test@example.com';
			$this->User->set($this->data['User']);

			// pw should match
			$field['confirm_password'] = 'my cool password';
			//$this->assertTrue($this->User->matchPassword($field));

			// pw does not match
			$field['confirm_password'] = 'this should not match';
			//$this->assertFalse($this->User->matchPassword($field));

			// pw regex simple
			Configure::write('Website.password_regex', '[a-z]');
			$field['confirm_password'] = 'simplepw';
			$result = $this->User->validPassword($field);
			$expected = 1;
			$this->assertSame($expected, $result);
			$field['confirm_password'] = '�^%&^%*^&�$%�';
			$result = $this->User->validPassword($field);
			$expected = 0;
			$this->assertSame($expected);

			// pw regex advanced
			Configure::write('Website.password_regex', '^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).{4,8}$');
			$field['confirm_password'] = 'aBcD123';
			$result = $this->User->validPassword($field);
			$expected = 1;
			$this->assertSame($expected, $result);
			$field['confirm_password'] = 'something';
			$result = $this->User->validPassword($field);
			$expected = 0;
			$this->assertSame($expected, $result);

			// email should match
			$field['confirm_email'] = $this->data['User']['email'];
			//$this->assertTrue($this->User->matchEmail($field));

			// email should fail
			$field['confirm_email'] = 'wrong@exaple.com';
			//$this->assertFalse($this->User->matchEmail($field));
		}

		/**
		* Test the other methods
		*/
		public function testMethods() {
			$result = $this->User->getLastLogon(1);
			$expected['User'] = array(
				'ip_address' => '127.0.0.1',
				'last_login' => '2010-08-16 10:49:19',
				'country' => 'Unknown',
				'city' => '',
			);
			$this->assertEquals($expected, $result);
		}

		public function endTest() {
			unset($this->User);
			ClassRegistry::flush();
		}
	}