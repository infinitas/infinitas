<?php
	App::import('Model', array('AppModel', 'Model'));

	/**
	 * NumberTree class
	 *
	 * @package       infinitas.test.encrypted_category
	 */
	class CategoryEncrypted extends CakeTestModel {
		public $name = 'CategoryEncrypted';

		public $actsAs = array(
			'Security.Encryptable'
		);

		public $useTable = 'global_categories';

		public function initialize() {}
	}

	/**
	 * NumberTreeTest class
	 *
	 * @package       cake
	 * @subpackage    cake.tests.cases.libs.model.behaviors
	 */
	class NumberTreeTest extends CakeTestCase {
		public $settings = array(
			'modelClass' => 'CategoryEncrypted'
		);
		
		public $fixtures = array(
			'plugin.configs.config',
			'plugin.view_counter.view_count',

			'plugin.categories.category',
			'plugin.users.group',
		);

		public function testInitialize() {
			$this->Category =& new $this->settings['modelClass']();
			$this->assertEqual(4, $this->Category->find('count'));
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
			$this->assertEqual($this->Category->encrypt('hello'), 'Gcav7FmvCHLJZj41u6pQXgAmvm2GKG6O0QPxaqYfRoQ=');
			$this->assertEqual($this->Category->encrypt('tests'), '/hIRJvaeVIZo49hudjj04W+KnO+H5R7eNBdXTQ1Fdk4=');
			$this->assertEqual($this->Category->encrypt('12345'), '5oeh2eKAeEOYjdCHCJgXPlkBHzam7kKyQTqcZuDnYag=');
			
			Configure::write('Security.encryption_secret', '­sç„¢˜4m™¤ÀšžË');
			$this->assertEqual($this->Category->encrypt('hello'), 'Wn0RBbCXOvfC6awXazk4MMtukIR9MdFjMhw748izFHc=');
			$this->assertEqual($this->Category->encrypt('tests'), '2EnJ7XZ24dDITrer+7w1vDsZLu2KOqPmuyLDpUFJ9ug=');
			$this->assertEqual($this->Category->encrypt('12345'), 'x/OAVJGN6dXyC0jSeaKuxZrH8ZQahArm4CHxRh22H2g=');
		}

		/**
		 * test decrypting data
		 */
		public function testDecrypt(){
			Configure::write('Security.encryption_secret', '­sç„¢˜4m™¤Àsç');
			$this->assertEqual($this->Category->decrypt('Gcav7FmvCHLJZj41u6pQXgAmvm2GKG6O0QPxaqYfRoQ='), 'hello');
			$this->assertEqual($this->Category->decrypt('/hIRJvaeVIZo49hudjj04W+KnO+H5R7eNBdXTQ1Fdk4='), 'tests');
			$this->assertEqual($this->Category->decrypt('5oeh2eKAeEOYjdCHCJgXPlkBHzam7kKyQTqcZuDnYag='), '12345');

			Configure::write('Security.encryption_secret', '­sç„¢˜4m™¤ÀšžË');
			$this->assertEqual($this->Category->decrypt('Wn0RBbCXOvfC6awXazk4MMtukIR9MdFjMhw748izFHc='), 'hello');
			$this->assertEqual($this->Category->decrypt('2EnJ7XZ24dDITrer+7w1vDsZLu2KOqPmuyLDpUFJ9ug='), 'tests');
			$this->assertEqual($this->Category->decrypt('x/OAVJGN6dXyC0jSeaKuxZrH8ZQahArm4CHxRh22H2g='), '12345');
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
			$this->assertEqual($this->Category->create() && $this->Category->save($category), true);

			$category = $this->Category->read(array('description'), $this->Category->id);
			$this->assertEqual(
				$category[$this->Category->alias]['description'],
				'this should be encrypted and not stored as plain text'
			);
			unset($category);

			/**
			 * testing save() with empty encrypted field
			 */
			$category[$this->Category->alias] = array(
				'title' => 'encrypted',
				'description' => ''
			);
			$this->assertEqual($this->Category->create() && $this->Category->save($category), true);

			$category = $this->Category->read(array('id', 'description'), $this->Category->id);
			$this->assertEqual(
				$category[$this->Category->alias]['description'],
				''
			);
			$this->assertTrue($this->Category->delete($category[$this->Category->alias]['id']));

			/**
			 * testing saveField()
			 */
			$this->Category->id = 5;
			$this->Category->saveField('description', 'this is the description');
			$expected[$this->Category->alias] = array(
				'id' => 5,
				'description' => 'O4DZmLyOgZBwaX3W4Doem7g+oX6iuNrSDbCctH6ZyeQ='
			);
			$this->Category->Behaviors->detach('Encryptable');
			$this->assertEqual($expected, $this->Category->read(array('id', 'description'), 5));

			/**
			 * testing saveAll()
			 */
			$this->Category->Behaviors->attach('Encryptable');
			$categories = array(
				array('title' => 'encrypted1', 'description' => 'encrypted 1 encrypted 1 encrypted 1 encrypted 1 encrypted 1encrypted 1encrypted 1encrypted 1encrypted 1encrypted 1encrypted 1encrypted 1encrypted 1'),
				array('title' => 'encrypted2', 'description' => 'encrypted 2'),
				array('title' => 'encrypted3', 'description' => 'encrypted 3')
			);
			
			$this->assertTrue($this->Category->create() && $this->Category->saveAll($categories));
			$this->Category->Behaviors->detach('Encryptable');

			$categories = $this->Category->find('all');
			$this->assertEqual(count($categories), 4);

			$expected = array(
				'O4DZmLyOgZBwaX3W4Doem7g+oX6iuNrSDbCctH6ZyeQ=',
				'UbRP6kBHwfLBBVJgy4Acq2MDY0qwcpJlYx1ZdgcGFBEHGteyrcYj1ePjJYtxT3Ed48O1Yiw1AEHIGTPQNr9tANCSRn5EQuNOVRj5MFPLbxa/Lt9pZhdQZX5oSvB3xTIkf3n3LD7cJT/iDLFxWff5+Hm2UXlwVSpqvTyXbF65vHEEosIEq57BM4ba/mnrt4DYqvQy47GTRdPaBw+nkRI5WQ==',
				'rL2Wd3fP5J0Vawwxp1/MZ1sf6IbjDWNE63Dm9qtTxdY=',
				'FIQVzMDwlMaW8T/AeRUOxUYO21VW4dW2anjq2NAv85E='
			);
			$this->assertEqual(Set::extract('/' . $this->Category->alias . '/description', $categories), $expected);
			
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