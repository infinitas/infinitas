<?php
	App::import('lib', 'libs.test/AppBehaviorTest');
	
	class TestCopyableBehavior extends AppBehaviorTestCase {

		/**
		 * @brief Configuration for the test case
		 *
		 * Loading fixtures:
		 * 
		 * List all the needed fixtures in the do part of the fixture array.
		 * In replace you can overwrite fixtures of other plugins by your own.
		 *
		 * 'fixtures' => array(
		 *		'do' => array(
		 *			'SomePlugin.SomeModel
		 *		),
		 *		'replace' => array(
		 *			'Core.User' => 'SomePlugin.User
		 *		)
		 * )
		 * @var array 
		 */
		public $setup = array(
			'behavior' => 'libs.Copyable',
			'models' => array(
				'Users.User'
			)
		);
		
		/**
		 * @brief Contains a list of test methods to run
		 *
		 * If it is set to false all the methods will run. Otherwise pass in an array
		 * with a list of tests to run.
		 *
		 * @var mixed 
		 */
		public $tests = false;

		/**
		 * @brief Tests setup
		 *
		 * @test check that you can configure the behavior how you like
		 */
		public function testSetup() {
			if($this->User->Behaviors->attached('Copyable')){
				$this->User->Behaviors->detach('Copyable');
			}

			$this->assertTrue(!isset($this->User->Behaviors->Copyable));

			$this->User->Behaviors->attach('Libs.Copyable');
			$expected = array('id', 'created', 'modified', 'lft', 'rght');
			$this->assertEqual(true, $this->User->Behaviors->Copyable->settings['User']['recursive']);
			$this->assertEqual(true, $this->User->Behaviors->Copyable->settings['User']['habtm']);
			$this->assertEqual($expected, $this->User->Behaviors->Copyable->settings['User']['stripFields']);
			$this->assertEqual(array(), $this->User->Behaviors->Copyable->settings['User']['ignore']);
			$this->User->Behaviors->detach('Copyable');

			$this->User->Behaviors->attach('Libs.Copyable', array('ignore' => array('Something.Foo')));
			$expected = array('id', 'created', 'modified', 'lft', 'rght');
			$this->assertEqual(true, $this->User->Behaviors->Copyable->settings['User']['recursive']);
			$this->assertEqual(true, $this->User->Behaviors->Copyable->settings['User']['habtm']);
			$this->assertEqual($expected, $this->User->Behaviors->Copyable->settings['User']['stripFields']);
			$this->assertEqual(array('Something.Foo'), $this->User->Behaviors->Copyable->settings['User']['ignore']);
			$this->User->Behaviors->detach('Copyable');

			$this->User->Behaviors->attach('Libs.Copyable', array('ignore' => 'Something.Foo'));
			$expected = array('id', 'created', 'modified', 'lft', 'rght');
			$this->assertEqual(true, $this->User->Behaviors->Copyable->settings['User']['recursive']);
			$this->assertEqual(true, $this->User->Behaviors->Copyable->settings['User']['habtm']);
			$this->assertEqual($expected, $this->User->Behaviors->Copyable->settings['User']['stripFields']);
			$this->assertEqual(array('Something.Foo'), $this->User->Behaviors->Copyable->settings['User']['ignore']);
			$this->User->Behaviors->detach('Copyable');

			$this->User->Behaviors->attach('Libs.Copyable', array('stripFields' => array('lft', 'rght')));
			$expected = array('lft', 'rght');
			$this->assertEqual(true, $this->User->Behaviors->Copyable->settings['User']['recursive']);
			$this->assertEqual(true, $this->User->Behaviors->Copyable->settings['User']['habtm']);
			$this->assertEqual($expected, $this->User->Behaviors->Copyable->settings['User']['stripFields']);
			$this->assertEqual(array(), $this->User->Behaviors->Copyable->settings['User']['ignore']);
			$this->User->Behaviors->detach('Copyable');

			$this->User->Behaviors->attach('Libs.Copyable', array('stripFields' => 'rght'));
			$expected = array('rght');
			$this->assertEqual(true, $this->User->Behaviors->Copyable->settings['User']['recursive']);
			$this->assertEqual(true, $this->User->Behaviors->Copyable->settings['User']['habtm']);
			$this->assertEqual($expected, $this->User->Behaviors->Copyable->settings['User']['stripFields']);
			$this->assertEqual(array(), $this->User->Behaviors->Copyable->settings['User']['ignore']);
			$this->User->Behaviors->detach('Copyable');

			$this->User->Behaviors->attach('Libs.Copyable', array('recursive' => false, 'habtm' => false));
			$expected = array('rght');
			$this->assertEqual(false, $this->User->Behaviors->Copyable->settings['User']['recursive']);
			$this->assertEqual(false, $this->User->Behaviors->Copyable->settings['User']['habtm']);
			$this->User->Behaviors->detach('Copyable');

			$this->User->Behaviors->attach('Libs.Copyable', array('recursive' => 1, 'habtm' => 0));
			$expected = array('rght');
			$this->assertEqual(true, $this->User->Behaviors->Copyable->settings['User']['recursive']);
			$this->assertEqual(false, $this->User->Behaviors->Copyable->settings['User']['habtm']);
			$this->User->Behaviors->detach('Copyable');

			$this->User->Behaviors->attach('Libs.Copyable', array('recursive' => 2, 'habtm' => 'sdf'));
			$expected = array('rght');
			$this->assertEqual(true, $this->User->Behaviors->Copyable->settings['User']['recursive']);
			$this->assertEqual(true, $this->User->Behaviors->Copyable->settings['User']['habtm']);
		}

		/**
		 * @brief Tests copy
		 *
		 * @test Enter description here
		 */
		public function testCopy() {
			
		}

		/**
		 * @brief Tests generateContain
		 *
		 * @test Enter description here
		 */
		public function testGenerateContain() {
			
		}
	}