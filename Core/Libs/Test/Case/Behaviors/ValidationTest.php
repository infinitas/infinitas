	<?php
	/* Infinitas Test cases generated on: 2010-03-13 14:03:31 : 1268484451*/
	App::import('Behavior', 'libs.Validation');

	class ValidationBehaviorTestCase extends CakeTestCase {
		public $fixtures = array(
			'plugin.users.user',
		);

		public function startTest() {
			$this->User = ClassRegistry::init('Users.User');
		}

		public function endTest() {
			unset($this->Infinitas);
			ClassRegistry::flush();
		}

		/**
		 * check validation is attached
		 */
		public function testValidationAttached(){
			$this->assertTrue($this->User->Behaviors->attached('Validation'));
		}

		
		public function testValidateJson(){
			$this->User->validate = array(
				'field_1' => array(
					'validateJson' => array(
						'rule' => 'validateJson',
						'message' => 'thats not json'
					)
				)
			);
			
			$expected = array('field_1' => 'thats not json');

			$data['User']['field_1'] = 'foo';
			$this->User->set($data); $this->User->validates();
			$this->assertEqual($this->User->validationErrors, $expected);

			$data['User']['field_1'] = '';
			$this->User->set($data); $this->User->validates();
			$this->assertEqual($this->User->validationErrors, $expected);

			$data['User']['field_1'] = json_encode(array('asd' => 'meh'));
			$this->User->set($data); $this->User->validates();
			$this->assertEqual($this->User->validationErrors, array());
		}

		/**
		 * test either or
		 */
		public function testValidateEitherOr(){
			$this->User->validate = array(
				'field_1' => array(
					'validateEitherOr' => array(
						'rule' => array('validateEitherOr', array('field_1', 'field_2')),
						'message' => 'pick one, only one'
					)
				)
			);
			$expected = array('field_1' => 'pick one, only one');

			/**
			 * invalid
			 */
			$data['User']['field_1'] = 'foo';
			$data['User']['field_2'] = 'bar';
			$this->User->set($data); $this->User->validates();
			$this->assertEqual($this->User->validationErrors, $expected);
			/**
			 * valid
			 */

			$data['User'] = array(); $this->User->create();
			$data['User']['field_1'] = 'foo';
			$this->User->set($data); $this->User->validates();
			$this->assertEqual($this->User->validationErrors, array());

			$data['User'] = array(); $this->User->create();
			$data['User']['field_2'] = 'bar';
			$this->User->set($data); $this->User->validates();
			$this->assertEqual($this->User->validationErrors, array());

			$data['User'] = array(); $this->User->create();
			$this->User->set($data); $this->User->validates();
			$this->assertEqual($this->User->validationErrors, array());

			/**
			 * force one to be selected
			 */
			$this->User->validate['field_1']['validateEitherOr']['required'] = true;

			$data['User'] = array(); $this->User->create();
			$data['User']['field_1'] = '';
			$data['User']['field_2'] = '';
			$this->User->set($data); $this->User->validates();
			$this->assertEqual($this->User->validationErrors, $expected);

			$data['User'] = array(); $this->User->create();
			$data['User']['field_1'] = '';
			$data['User']['field_2'] = 'bar';
			$this->User->set($data); $this->User->validates();
			$this->assertEqual($this->User->validationErrors, array());
		}

		/**
		 * tests is url or absolute path
		 */
		public function testValidateUrlOrAbsolute(){
			$this->User->validate = array(
				'url' => array(
					'validateUrlOrAbsolute' => array(
						'rule' => 'validateUrlOrAbsolute',
						'message' => 'not url or absolute path'
					)
				)
			);
			
			$expected = array('url' => 'not url or absolute path');

			/**
			 * invalid
			 */
			$data['User']['url'] = 'this/is/not/an/absolute.path';
			$this->User->set($data); $this->User->validates();
			$this->assertEqual($this->User->validationErrors, $expected);

			$data['User']['url'] = 'http:/this.is/an/url.ext';
			$this->User->set($data); $this->User->validates();
			$this->assertEqual($this->User->validationErrors, $expected);

			/**
			 * valid
			 */
			$data['User']['url'] = '/this/is/an/absolute.path';
			$this->User->set($data); $this->User->validates();
			$this->assertEqual($this->User->validationErrors, array());

			$data['User']['url'] = 'http://this.is/an/url.ext';
			$this->User->set($data); $this->User->validates();
			$this->assertEqual($this->User->validationErrors, array());

			$data['User']['url'] = 'ftp://this.is/an/url.ext';
			$this->User->set($data); $this->User->validates();
			$this->assertEqual($this->User->validationErrors, array());
		}

		/**
		 * tests comparing passwords
		 */
		public function testValidateComparePasswords(){
			$this->User->validate = array(
				'password' => array(
					'validateCompareFields' => array(
						'rule' => array('validateCompareFields', array('password', 'password_match')),
						'message' => 'fields dont match'
					)
				)
			);
			
			$data['User']['password'] = 'abc';
			$data['User']['password_match'] = 'xyz';

			$this->User->set($data); $this->User->validates();
			$expected = array('password' => 'fields dont match');
			$this->assertEqual($this->User->validationErrors, $expected);

			$data['User']['password'] = Security::hash('abc', null, true);
			$this->User->set($data); $this->User->validates();
			$this->assertEqual($this->User->validationErrors, $expected);

			$data['User']['password_match'] = 'abc';
			$this->User->set($data); $this->User->validates();
			$this->assertEqual($this->User->validationErrors, array());
		}

		/**
		 * test comparing normal fields
		 */
		public function testCompareFields(){
			$this->User->validate = array(
				'field_1' => array(
					'validateCompareFields' => array(
						'rule' => array('validateCompareFields', array('field_1', 'field_2')),
						'message' => 'fields dont match'
					)
				)
			);


			$data['User']['field_1'] = 'abc';
			$data['User']['field_2'] = 'xyz';
			$this->User->set($data); $this->User->validates();
			$expected = array('field_1' => 'fields dont match');
			$this->assertEqual($this->User->validationErrors, $expected);

			$data['User']['field_2'] = 'abc';
			$this->User->set($data); $this->User->validates();
			$this->assertEqual($this->User->validationErrors, array());
		}

		public function testValidateRecordExists() {
			//Add validation rule for the user record
			$this->User->validate['group_id'] = array(
				'rule' => 'validateRecordExists',
				'message' => 'Invalid group',
				'required' => true
			);

			// Test for an non existing group
			$data = array(
				'User' => array(
					'username' => 'test-user',
					'group_id' => 'non-existing-group-id'
				)
			);

			$this->User->set($data);
			$this->assertFalse($this->User->validates());
			$expected = array('group_id' => 'Invalid group');
			$this->assertEqual($this->User->validationErrors, $expected);

			//Test for an existing group
			$data['User']['group_id'] = 1;
			$this->User->set($data);
			$this->assertTrue($this->User->validates());
			$expected = array();
			$this->assertEqual($this->User->validationErrors, $expected);
		}
	}