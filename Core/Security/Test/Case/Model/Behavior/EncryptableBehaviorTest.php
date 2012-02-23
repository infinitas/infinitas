<?php

	/**
	 * NumberTree class
	 *
	 * @package       infinitas.test.encrypted_category
	 */
	class CategoryEncrypted extends CakeTestModel {
		public $actsAs = array(
			'Security.Encryptable'
		);

		public $useTable = 'global_categories';

		public function initialize() {}
	}

	/**
	 * EncryptableBehaviorTest class
	 *
	 * @package       cake
	 * @subpackage    cake.tests.cases.libs.model.behaviors
	 */
	class EncryptableBehaviorTest extends CakeTestCase {
		public $settings = array(
			'modelClass' => 'CategoryEncrypted'
		);

		public $fixtures = array(
			'plugin.configs.config',
			'plugin.view_counter.view_count',

			'plugin.contents.global_category',
			'plugin.users.group',
		);

		public function setUp() {
			$this->Category =& new $this->settings['modelClass']();
		}

		public function tearDown() {
			unset($this->Category);
			ClassRegistry::flush();
		}

		public function testInitialize() {
			$this->Category =& new $this->settings['modelClass']();
			$result = $this->Category->find('count');
			$this->assertEquals(4, $result);
		}

		/**
		 * empty the table so its like a new model etc.
		 */
		public function truncate(){
			$this->Category->deleteAll(array($this->Category->alias . '.id >' => 0));
		}

		/**
		 * test encrypting data
		 */
		public function testEncrypt(){
			Configure::write('Security.encryption_salt', '');
			Configure::write('Security.encryption_secret', '­sç„¢˜4m™¤Àsç');

			$result = $this->Category->encrypt('hello');
			$expected = 'Gcav7FmvCHLJZj41u6pQXgAmvm2GKG6O0QPxaqYfRoQ=';
			$this->assertEquals($expected, $result);

			$result = $this->Category->encrypt('tests');
			$expected = '/hIRJvaeVIZo49hudjj04W+KnO+H5R7eNBdXTQ1Fdk4=';
			$this->assertEquals($expected, $result);

			$result = $this->Category->encrypt('12345');
			$expected = '5oeh2eKAeEOYjdCHCJgXPlkBHzam7kKyQTqcZuDnYag=';
			$this->assertEquals($expected, $result);

			Configure::write('Security.encryption_secret', '­sç„¢˜4m™¤ÀšžË');
			$result = $this->Category->encrypt('hello');
			$expected = 'Wn0RBbCXOvfC6awXazk4MMtukIR9MdFjMhw748izFHc=';
			$this->assertEquals($expected, $result);

			$result = $this->Category->encrypt('tests');
			$expected = '2EnJ7XZ24dDITrer+7w1vDsZLu2KOqPmuyLDpUFJ9ug=';
			$this->assertEquals($expected, $result);

			$result = $this->Category->encrypt('12345');
			$expected = 'x/OAVJGN6dXyC0jSeaKuxZrH8ZQahArm4CHxRh22H2g=';
			$this->assertEquals($expected, $result);
		}

		/**
		 * test decrypting data
		 */
		public function testDecrypt(){
			Configure::write('Security.encryption_secret', '­sç„¢˜4m™¤Àsç');
			$result = $this->Category->decrypt('Gcav7FmvCHLJZj41u6pQXgAmvm2GKG6O0QPxaqYfRoQ=');
			$expected = 'hello';
			$this->assertEquals($expected, $results);

			$result = $this->Category->decrypt('/hIRJvaeVIZo49hudjj04W+KnO+H5R7eNBdXTQ1Fdk4=');
			$expected = 'tests';
			$this->assertEquals($expected, $results);

			$result = $this->Category->decrypt('5oeh2eKAeEOYjdCHCJgXPlkBHzam7kKyQTqcZuDnYag=');
			$expected = '12345';
			$this->assertEquals($expected, $results);

			Configure::write('Security.encryption_secret', '­sç„¢˜4m™¤ÀšžË');
			$result = $this->Category->decrypt('Wn0RBbCXOvfC6awXazk4MMtukIR9MdFjMhw748izFHc=');
			$expected = 'hello';
			$this->assertEquals($expected, $result);

			$result = $this->Category->decrypt('2EnJ7XZ24dDITrer+7w1vDsZLu2KOqPmuyLDpUFJ9ug=');
			$expected = 'tests';
			$this->assertEquals($expected, $result);

			$result = $this->Category->decrypt('x/OAVJGN6dXyC0jSeaKuxZrH8ZQahArm4CHxRh22H2g=');
			$expected = '12345';
			$this->assertEquals($expected, $result);
		}

		/**
		 * test finding and saving encrypted data
		 */
		public function testSaveAndFind(){
			$this->truncate();

			/**
			 * testing save()
			 */
			$category[$this->Category->alias] = array(
				'title' => 'encrypted',
				'description' => 'this should be encrypted and not stored as plain text'
			);
			$result = $this->Category->create() && $this->Category->save($category);
			$this->assertTrue($result);

			$category = $this->Category->read(array('description'), $this->Category->id);
			$result = $category[$this->Category->alias]['description'];
			$expected = 'this should be encrypted and not stored as plain text';
			$this->assertEquals($expected, $result);
			unset($category);

			/**
			 * testing save() with empty encrypted field
			 */
			$category[$this->Category->alias] = array(
				'title' => 'encrypted',
				'description' => ''
			);
			$result = $this->Category->create() && $this->Category->save($category);
			$this->assertTrue($result);

			$category = $this->Category->read(array('id', 'description'), $this->Category->id);
			$result = $category[$this->Category->alias]['description'];
			$expected = '';
			$this->assertEquals($expected, $result);

			$result = $this->Category->delete($category[$this->Category->alias]['id']);
			$this->assertTrue($result);

			/**
			 * testing saveField()
			 */
			$this->Category->id = 5;
			$this->Category->saveField('description', 'this is the description');
			$this->Category->Behaviors->detach('Encryptable');
			$result = $this->Category->read(array('id', 'description'), 5);
			$expected[$this->Category->alias] = array(
				'id' => 5,
				'description' => 'O4DZmLyOgZBwaX3W4Doem7g+oX6iuNrSDbCctH6ZyeQ='
			);
			$this->assertEquals($expected, $result);

			/**
			 * testing saveAll()
			 */
			$this->Category->Behaviors->attach('Encryptable');
			$categories = array(
				array('title' => 'encrypted1', 'description' => 'encrypted 1 encrypted 1 encrypted 1 encrypted 1 encrypted 1encrypted 1encrypted 1encrypted 1encrypted 1encrypted 1encrypted 1encrypted 1encrypted 1'),
				array('title' => 'encrypted2', 'description' => 'encrypted 2'),
				array('title' => 'encrypted3', 'description' => 'encrypted 3')
			);

			$result = $this->Category->create() && $this->Category->saveAll($categories);
			$this->assertTrue($result);
			$this->Category->Behaviors->detach('Encryptable');

			$categories = $this->Category->find('all');
			$this->assertCount(4, $categories);

			$expected = array(
				'O4DZmLyOgZBwaX3W4Doem7g+oX6iuNrSDbCctH6ZyeQ=',
				'UbRP6kBHwfLBBVJgy4Acq2MDY0qwcpJlYx1ZdgcGFBEHGteyrcYj1ePjJYtxT3Ed48O1Yiw1AEHIGTPQNr9tANCSRn5EQuNOVRj5MFPLbxa/Lt9pZhdQZX5oSvB3xTIkf3n3LD7cJT/iDLFxWff5+Hm2UXlwVSpqvTyXbF65vHEEosIEq57BM4ba/mnrt4DYqvQy47GTRdPaBw+nkRI5WQ==',
				'rL2Wd3fP5J0Vawwxp1/MZ1sf6IbjDWNE63Dm9qtTxdY=',
				'FIQVzMDwlMaW8T/AeRUOxUYO21VW4dW2anjq2NAv85E='
			);
			$result = Set::extract('/' . $this->Category->alias . '/description', $categories);
			$this->assertEquals($expected, $result);

			/**
			 * testing updateAll is not possible at this time. do not use
			 *
			$this->Category->Behaviors->attach('Encryptable');
			$this->Category->updateAll(array($this->Category->alias . '.description' => '\'this is the description\''), array($this->Category->alias . '.id >=' => 0));
			$this->Category->Behaviors->detach('Encryptable');

			$expected = array(
				'O4DZmLyOgZBwaX3W4Doem7g+oX6iuNrSDbCctH6ZyeQ=',
				'O4DZmLyOgZBwaX3W4Doem7g+oX6iuNrSDbCctH6ZyeQ=',
				'O4DZmLyOgZBwaX3W4Doem7g+oX6iuNrSDbCctH6ZyeQ=',
				'O4DZmLyOgZBwaX3W4Doem7g+oX6iuNrSDbCctH6ZyeQ='
			);
			$categories = $this->Category->find('all');
			$this->assertEqual(Set::extract('/' . $this->Category->alias . '/description', $categories), $expected);
			 */

		}
	}