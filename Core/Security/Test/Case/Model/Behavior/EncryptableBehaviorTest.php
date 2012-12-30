<?php

	/**
	 * NumberTree class
	 *
	 * @package       infinitas.test.encrypted_content
	 */
	class ContentEncrypted extends CakeTestModel {
		public $actsAs = array(
			'Security.Encryptable'
		);

		public $useTable = 'global_contents';

		public function initialize() {}
	}

	/**
	 * EncryptableBehaviorTest class
	 */
	class EncryptableBehaviorTest extends CakeTestCase {
		public $settings = array(
			'modelClass' => 'ContentEncrypted'
		);

		public $fixtures = array(
			'plugin.configs.config',
			'plugin.view_counter.view_counter_view',

			'plugin.contents.global_content',
			'plugin.users.group',
		);

		public function setUp() {
			$this->Content = new $this->settings['modelClass']();
		}

		public function tearDown() {
			unset($this->Content);
			ClassRegistry::flush();
		}

		public function testInitialize() {
			$this->Content = new $this->settings['modelClass']();
			$result = $this->Content->find('count');
			$this->assertEquals(7, $result);
		}

		/**
		 * empty the table so its like a new model etc.
		 */
		public function truncate() {
			$this->Content->deleteAll(array($this->Content->alias . '.id >' => 0));
		}

		/**
		 * test encrypting data
		 */
		public function testEncrypt() {
			$this->skipif (!function_exists('mcrypt_encrypt'), 'mcrypt does not seem to be installed');

			Configure::write('Security.encryption_salt', '');
			Configure::write('Security.encryption_secret', '­sç„¢˜4m™¤Àsç');

			$result = $this->Content->encrypt('hello');
			$expected = 'Gcav7FmvCHLJZj41u6pQXgAmvm2GKG6O0QPxaqYfRoQ=';
			$this->assertEquals($expected, $result);

			$result = $this->Content->encrypt('tests');
			$expected = '/hIRJvaeVIZo49hudjj04W+KnO+H5R7eNBdXTQ1Fdk4=';
			$this->assertEquals($expected, $result);

			$result = $this->Content->encrypt('12345');
			$expected = '5oeh2eKAeEOYjdCHCJgXPlkBHzam7kKyQTqcZuDnYag=';
			$this->assertEquals($expected, $result);

			Configure::write('Security.encryption_secret', '­sç„¢˜4m™¤ÀšžË');
			$result = $this->Content->encrypt('hello');
			$expected = 'Wn0RBbCXOvfC6awXazk4MMtukIR9MdFjMhw748izFHc=';
			$this->assertEquals($expected, $result);

			$result = $this->Content->encrypt('tests');
			$expected = '2EnJ7XZ24dDITrer+7w1vDsZLu2KOqPmuyLDpUFJ9ug=';
			$this->assertEquals($expected, $result);

			$result = $this->Content->encrypt('12345');
			$expected = 'x/OAVJGN6dXyC0jSeaKuxZrH8ZQahArm4CHxRh22H2g=';
			$this->assertEquals($expected, $result);
		}

		/**
		 * test decrypting data
		 */
		public function testDecrypt() {
			$this->skipif (!function_exists('mcrypt_encrypt'), 'mcrypt does not seem to be installed');

			Configure::write('Security.encryption_secret', '­sç„¢˜4m™¤Àsç');
			$result = $this->Content->decrypt('Gcav7FmvCHLJZj41u6pQXgAmvm2GKG6O0QPxaqYfRoQ=');
			$expected = 'hello';
			$this->assertEquals($expected, $result);

			$result = $this->Content->decrypt('/hIRJvaeVIZo49hudjj04W+KnO+H5R7eNBdXTQ1Fdk4=');
			$expected = 'tests';
			$this->assertEquals($expected, $result);

			$result = $this->Content->decrypt('5oeh2eKAeEOYjdCHCJgXPlkBHzam7kKyQTqcZuDnYag=');
			$expected = '12345';
			$this->assertEquals($expected, $result);

			Configure::write('Security.encryption_secret', '­sç„¢˜4m™¤ÀšžË');
			$result = $this->Content->decrypt('Wn0RBbCXOvfC6awXazk4MMtukIR9MdFjMhw748izFHc=');
			$expected = 'hello';
			$this->assertEquals($expected, $result);

			$result = $this->Content->decrypt('2EnJ7XZ24dDITrer+7w1vDsZLu2KOqPmuyLDpUFJ9ug=');
			$expected = 'tests';
			$this->assertEquals($expected, $result);

			$result = $this->Content->decrypt('x/OAVJGN6dXyC0jSeaKuxZrH8ZQahArm4CHxRh22H2g=');
			$expected = '12345';
			$this->assertEquals($expected, $result);
		}

		/**
		 * test finding and saving encrypted data
		 */
		public function testSaveAndFind() {
			$this->skipif (!function_exists('mcrypt_encrypt'), 'mcrypt does not seem to be installed');

			$this->truncate();

			/**
			 * testing save()
			 */
			$content[$this->Content->alias] = array(
				'title' => 'encrypted',
				'body' => 'this should be encrypted and not stored as plain text'
			);
			$result = $this->Content->create() && $this->Content->save($content);
			$this->assertTrue($result);

			$content = $this->Content->read(array('body'), $this->Content->id);
			$result = $content[$this->Content->alias]['body'];
			$expected = 'this should be encrypted and not stored as plain text';
			$this->assertEquals($expected, $result);
			unset($content);

			/**
			 * testing save() with empty encrypted field
			 */
			$content[$this->Content->alias] = array(
				'title' => 'encrypted',
				'body' => ''
			);
			$result = $this->Content->create() && $this->Content->save($content);
			$this->assertTrue($result);

			$content = $this->Content->read(array('id', 'body'), $this->Content->id);
			$result = $content[$this->Content->alias]['body'];
			$expected = '';
			$this->assertEquals($expected, $result);

			$result = $this->Content->delete($content[$this->Content->alias]['id']);
			$this->assertTrue($result);

			/**
			 * testing saveField()
			 */

			$this->truncate();
			$this->Content->create();
			$this->Content->saveField('body', 'this is the description');
			$this->Content->Behaviors->detach('Encryptable');
			$result = $this->Content->read(array('id', 'body'), $this->Content->id);
			$expected[$this->Content->alias] = array(
				'id' => $this->Content->id,
				'body' => 'O4DZmLyOgZBwaX3W4Doem7g+oX6iuNrSDbCctH6ZyeQ='
			);
			$this->assertEquals($expected, $result);

			/**
			 * testing saveAll()
			 */
			$this->Content->Behaviors->attach('Encryptable');
			$contents = array(
				array('title' => 'encrypted1', 'body' => 'encrypted 1 encrypted 1 encrypted 1 encrypted 1 encrypted 1encrypted 1encrypted 1encrypted 1encrypted 1encrypted 1encrypted 1encrypted 1encrypted 1'),
				array('title' => 'encrypted2', 'body' => 'encrypted 2'),
				array('title' => 'encrypted3', 'body' => 'encrypted 3')
			);

			$result = $this->Content->create() && $this->Content->saveAll($contents);
			$this->assertTrue($result);
			$this->Content->Behaviors->detach('Encryptable');

			$contents = $this->Content->find('all', array('order' => array('title' => 'ASC')));
			$this->assertCount(4, $contents);

			$expected = array(
				'O4DZmLyOgZBwaX3W4Doem7g+oX6iuNrSDbCctH6ZyeQ=',
				'UbRP6kBHwfLBBVJgy4Acq2MDY0qwcpJlYx1ZdgcGFBEHGteyrcYj1ePjJYtxT3Ed48O1Yiw1AEHIGTPQNr9tANCSRn5EQuNOVRj5MFPLbxa/Lt9pZhdQZX5oSvB3xTIkf3n3LD7cJT/iDLFxWff5+Hm2UXlwVSpqvTyXbF65vHEEosIEq57BM4ba/mnrt4DYqvQy47GTRdPaBw+nkRI5WQ==',
				'rL2Wd3fP5J0Vawwxp1/MZ1sf6IbjDWNE63Dm9qtTxdY=',
				'FIQVzMDwlMaW8T/AeRUOxUYO21VW4dW2anjq2NAv85E='
			);
			$result = Set::extract('/' . $this->Content->alias . '/body', $contents);
			$this->assertEquals($expected, $result);

			/**
			 * testing updateAll is not possible at this time. do not use
			 *
			$this->Content->Behaviors->attach('Encryptable');
			$this->Content->updateAll(array($this->Content->alias . '.description' => '\'this is the description\''), array($this->Content->alias . '.id >=' => 0));
			$this->Content->Behaviors->detach('Encryptable');

			$expected = array(
				'O4DZmLyOgZBwaX3W4Doem7g+oX6iuNrSDbCctH6ZyeQ=',
				'O4DZmLyOgZBwaX3W4Doem7g+oX6iuNrSDbCctH6ZyeQ=',
				'O4DZmLyOgZBwaX3W4Doem7g+oX6iuNrSDbCctH6ZyeQ=',
				'O4DZmLyOgZBwaX3W4Doem7g+oX6iuNrSDbCctH6ZyeQ='
			);
			$contents = $this->Content->find('all');
			$this->assertEqual(Set::extract('/' . $this->Content->alias . '/description', $contents), $expected);
			 */

		}
	}