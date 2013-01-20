<?php
App::uses('User', 'Users.Model');

class UserTestCase extends CakeTestCase {

/**
 * fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.users.user',
		'plugin.users.group',
		'plugin.management.ticket',
		'plugin.trash.trash'
	);

/**
 * @brief set up at the start
 */
	public function setUp() {
		parent::setUp();
		CakeSession::destroy();
		$this->Model = ClassRegistry::init('Users.User');
	}

/**
 * @brief break down at the end
 */
	public function tearDown() {
		parent::tearDown();
		CakeSession::destroy();
		unset($this->Model);
	}

/**
 * Test the validation methods
 */
	public function testValidationRules() {
		// fake the submision
		$this->data['User']['password'] = 'cd4f70413dececd8b813e1d5c56c6421e1a35018';
		$this->data['User']['email'] = 'test@example.com';
		$this->Model->set($this->data['User']);

		// pw regex simple
		Configure::write('Website.password_regex', '[a-z]');
		$field['confirm_password'] = 'simplepw';
		$result = $this->Model->validatePassword($field);
		$this->assertTrue($result);

		$field['confirm_password'] = '�^%&^%*^&�$%�';
		$result = $this->Model->validatePassword($field);
		$this->assertFalse($result);

		// pw regex advanced
		Configure::write('Website.password_regex', '^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).{4,8}$');
		$field['confirm_password'] = 'aBcD123';
		$result = $this->Model->validatePassword($field);
		$this->assertTrue($result);

		$field['confirm_password'] = 'something';
		$result = $this->Model->validatePassword($field);
		$this->assertFalse($result);
	}

/**
 * Test the other methods
 */
	public function testFindLastLogin() {
		$result = $this->Model->find('lastLogin');
		$this->assertEmpty($result);

		$result = $this->Model->find('lastLogin', 1);
		$expected = array(
			'User' => array(
				'ip_address' => '127.0.0.1',
				'last_login' => '2010-08-16 10:49:19',
				'country' => 'Unknown',
				'city' => '',
			)
		);
		$this->assertEquals($expected, $result);

		CakeSession::write('Auth.User.id', 1);
		$result = $this->Model->find('lastLogin');
		$this->assertEquals($expected, $result);
	}

/**
 * test find current logged in users
 */
	public function testFindLoggedIn() {
		$result = $this->Model->find('loggedIn');
		$this->assertEmpty($result);

		$this->Model->save(array(
			'id' => 1,
			'last_login' => date('Y-m-d H:i:s')
		));

		$expected = array(
			1
		);
		$result = Hash::extract($this->Model->find('loggedIn'), '{n}.User.id');
		$this->assertEquals($expected, $result);
	}

/**
 * test find profile
 */
	public function testFindProfile() {
		$result = $this->Model->find('profile');
		$this->assertEmpty($result);

		$expected = array(
			'User' => array(
				'id' => 1,
				'username' => 'admin',
				'email' => 'admin@admin.com',
				'password' => 'b45e7cddbeafb9f619d93fd996c6a78c784e7fb5',
				'birthday' => '2010-02-04',
				'active' => 1,
				'group_id' => 1,
				'ip_address' => '127.0.0.1',
				'browser' => 'Mozilla 5.0',
				'operating_system' => 'Windows NT',
				'last_login' => '2010-08-16 10:49:19',
				'last_click' => '0000-00-00 00:00:00',
				'country' => 'Unknown',
				'city' => '',
				'is_mobile' => 0,
				'created' => '2010-02-04 16:54:48',
				'modified' => '2010-02-04 16:54:48',
				'facebook_id' => 0,
				'twitter_id' => 0,
				'time_zone' => 'UTC',
				'full_name' => 'Admin Guy',
				'prefered_name' => null
			)
		);
		$result = $this->Model->find('profile', 1);
		$this->assertEquals($expected, $result);

		CakeSession::write('Auth.User.id', 1);
		$result = $this->Model->find('profile');
		$this->assertEquals($expected, $result);
	}

/**
 * test one user saving another users profile
 *
 * @expectedException InvalidArgumentException
 */
	public function testSaveProfileWrongUser() {
		CakeSession::write('Auth.User.id', 2);
		$this->Model->saveProfile(array(
			'User' => array(
				'id' => 1
			)
		));
	}

/**
 * test save profile
 */
	public function testSaveProfile() {
		CakeSession::write('Auth.User.id', 1);

		$expected = array(
			'User' => array(
				'id' => 1,
				'username' => 'bob',
			)
		);
		$result = $this->Model->saveProfile($expected);
		unset($result['User']['modified']);
		$this->assertEquals($expected, $result);

		$expected = array(
			'User' => array(
				'id' => 1,
				'username' => 'bob',
				'prefered_name' => null
			)
		);
		$result = $this->Model->saveProfile($expected);
		unset($result['User']['modified']);
		$expected['User']['prefered_name'] = 'bob';
		$this->assertEquals($expected, $result);

		$expected = array(
			'User' => array(
				'id' => 1,
				'username' => 'bob',
				'prefered_name' => 'bob smith'
			)
		);
		$result = $this->Model->saveProfile($expected);
		unset($result['User']['modified']);
		$this->assertEquals($expected, $result);

		$expected = array(
			'User' => array(
				'id' => 1,
				'username' => 'bob',
				'confirm_password' => 'foobar'
			)
		);
		$result = $this->Model->saveProfile($expected);
		unset($result['User']['modified'], $expected['User']['confirm_password']);
		$this->assertEquals($expected, $result);

		$expected = array(
			'User' => array(
				'id' => 1,
				'username' => 'bob',
				'password' => 'Asd123',
				'confirm_password' => 'Asd123'
			)
		);
		$result = $this->Model->saveProfile($expected);
		unset($result['User']['modified'],
			$expected['User']['password'], $expected['User']['confirm_password'],
			$result['User']['password'], $result['User']['confirm_password']);
		$this->assertEquals($expected, $result);

		$result = $this->Model->field('password', array(
			'User.id' => 1
		));
		$this->assertEquals(Security::hash('Asd123', null, true), $result);
	}

/**
 * test update password
 */
	public function testUpdatePassword() {
		$result = $this->Model->updatePassword(array(
			'User' => array(
				'id' => 1,
				'new_password' => 'asdf'
			)
		));
		$this->assertTrue($result);

		$result = $this->Model->updatePassword(array(
			'id' => 1,
			'new_password' => 'asdf'
		));
		$this->assertTrue($result);

		$this->Model->id = 1;
		$result = $this->Model->updatePassword(array(
			'new_password' => 'asdf'
		));
		$this->assertTrue($result);

		$this->Model->id = null;
		$result = $this->Model->updatePassword(array(
			'new_password' => 'asdf'
		));
		$this->assertFalse($result);

		$result = $this->Model->field('password', array(
			'User.id' => 1
		));
		$this->assertEquals(Security::hash('asdf', null, true), $result);
	}

/**
 * test save registration
 */
	public function testSaveRegistration() {
		$data = array(
			'username' => 'new-user',
			'email' => 'new-user@example.com',
			'password' => 'asdf'
		);
		$result = $this->Model->saveRegistration($data);
		$this->assertTrue($result);

		$result = $this->Model->field('password', array(
			'User.id' => $this->Model->id
		));
		$this->assertEquals(Security::hash('asdf', null, true), $result);

		$this->assertTrue($this->Model->delete($this->Model->id));

		$data = array(
			'User' => array(
				'username' => 'new-user',
				'email' => 'new-user@example.com',
				'password' => 'asdf'
			)
		);
		$result = $this->Model->saveRegistration($data);
		$this->assertTrue($result);

		$result = $this->Model->field('password', array(
			'User.id' => $this->Model->id
		));
		$this->assertEquals(Security::hash('asdf', null, true), $result);
	}

/**
 * test save activation
 */
	public function testSaveActivation() {
		$data = array(
			'User' => array(
				'username' => 'new-user',
				'email' => 'new-user@example.com',
				'password' => 'asdf'
			)
		);
		$result = $this->Model->saveRegistration($data);
		$this->assertTrue($result);

		$result = $this->Model->field('active', array(
			'User.id' => $this->Model->id
		));
		$this->assertFalse($result);

		$result = $this->Model->saveActivation('asd');
		$this->assertFalse($result);

		$result = $this->Model->saveActivation($this->Model->createTicket($data['User']['email']));
		$this->assertTrue((bool)$result);

		$result = $this->Model->field('active', array(
			'User.id' => $this->Model->id
		));
		$this->assertTrue($result);
	}

}