<?php
	App::import('lib', 'libs.test/AppBehaviorTest');

	class TestCopyableBehavior extends CakeTestCase {

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
				'Users.User',
				'Modules.Module',
				'Modules.ModulePosition',
				'Routes.Route'
			),
			'fixtures' => array(
				'do' => array(
					'Contents.GlobalTag',
					'Contents.GlobalTagged'
				)
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
			if($this->User->Behaviors->attached('Copyable')) {
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
		 * @brief Tests generateContain
		 *
		 * @test test that getting contains fetches the correct stuff.
		 */
		public function testGenerateContain() {
			if($this->User->Behaviors->attached('Copyable')) {
				$this->User->Behaviors->detach('Copyable');
			}

			$this->User->Behaviors->detach('Containable');
			$this->User->Behaviors->attach('Libs.Copyable');

			$this->assertFalse($this->User->Behaviors->attached('Containable'));
			$this->assertEqual(array(), $this->User->Behaviors->Copyable->contain);
			$this->assertEqual(array(), $this->User->generateContain());
			$this->assertTrue($this->User->Behaviors->attached('Containable'));

			$this->Module->Behaviors->attach('Libs.Copyable');
			$expected = array(0 => 'Route');
			$this->assertEqual($expected, $this->Module->generateContain());
			$this->assertEqual($expected, $this->Module->Behaviors->Copyable->contain);

			$this->ModulePosition->Behaviors->attach('Libs.Copyable');
			$expected = array('Module' => array());
			$this->assertEqual($expected, $this->ModulePosition->generateContain());
			$this->assertEqual($expected, $this->ModulePosition->Behaviors->Copyable->contain);

			$this->expectError(); // its gonna use AppModel for the join here.
			$this->assertTrue($this->Module->bindModel(array('hasAndBelongsToMany' => array('GlobalTag' => array('className' => 'Contents.GlobalTag', 'with' => 'global_tags'))), false));
			$expected = array(0 => 'Route', 1 => 'GlobalTag');
			$this->assertEqual($expected, $this->Module->generateContain());
		}

		/**
		 * @brief Tests copy
		 *
		 * @test Enter description here
		 */
		public function testCopy() {
			foreach($this->ModulePosition->Behaviors->attached() as $attached) {
				if(!in_array($attached, array('Containable', 'Infinitas'))) {
					$this->ModulePosition->Behaviors->detach($attached);
				}
			}
			$this->ModulePosition->Behaviors->attach('Libs.Copyable');

			$positionCount = $this->ModulePosition->find('count');
			$moduleCount = $this->ModulePosition->Module->find('count');

			$this->assertIdentical(11, $positionCount);
			$this->assertIdentical(10, $moduleCount);

			/**
			 * test some fails
			 */
			$this->assertFalse($this->ModulePosition->copy());
			$this->assertFalse($this->ModulePosition->copy('fake-record'));
			$this->assertFalse($this->ModulePosition->copy(array()));
			$this->assertFalse($this->ModulePosition->copy(array('fake-record')));

			/**
			 * copy the bottom module position that has no modules
			 */
			$this->assertTrue($id = $this->ModulePosition->copy('module-position-bottom'));
			$this->assertIdentical(12, $this->ModulePosition->find('count'));
			$this->assertIdentical(10, $this->ModulePosition->Module->find('count'));

			$actual = $this->ModulePosition->find('first', array('conditions' => array('ModulePosition.id' => 'module-position-bottom'), 'contain' => array('Module')));
			$copy = $this->ModulePosition->find('first', array('conditions' => array('ModulePosition.id' => $id), 'contain' => array('Module')));
			$this->assertIdentical($actual['Module'], $copy['Module']);
			$this->assertTrue(strstr($copy['ModulePosition']['name'], $actual['ModulePosition']['name'] . ' - copied ' . date('Ymd')));

			/**
			 * copy the bottom module position that has no modules
			 */
			$this->assertTrue($id = $this->ModulePosition->copy('module-position-custom1'));
			$this->assertIdentical(13, $this->ModulePosition->find('count'));
			$this->assertIdentical(12, $this->ModulePosition->Module->find('count'));

			$actual = $this->ModulePosition->find('first', array('conditions' => array('ModulePosition.id' => 'module-position-custom1'), 'contain' => array('Module')));
			$copy = $this->ModulePosition->find('first', array('conditions' => array('ModulePosition.id' => $id), 'contain' => array('Module')));
			$this->assertIdentical(count($actual['Module']), count($copy['Module']));
			$this->assertTrue(strstr($copy['ModulePosition']['name'], $actual['ModulePosition']['name'] . ' - copied ' . date('Ymd')));
			foreach($copy['Module'] as $k => $module) {
				$this->assertTrue(strstr($module['name'], $actual['Module'][$k]['name'] . ' - copied ' . date('Ymd')));
			}

			/**
			 * copy multi rows at a time
			 */
			$idsToCopy = array('module-position-right', 'module-position-top');
			$result = $this->ModulePosition->copy($idsToCopy);
			$this->assertIdentical($idsToCopy, array_keys($result));
			$this->assertIdentical(15, $this->ModulePosition->find('count'));
			$this->assertIdentical(18, $this->ModulePosition->Module->find('count'));

			/**
			 * test transactions externally
			 */
			$this->assertTrue($this->ModulePosition->transaction());
			$this->assertTrue($this->ModulePosition->copy('module-position-custom4'));
			$this->assertTrue($this->ModulePosition->transaction(true));
			$this->assertIdentical(16, $this->ModulePosition->find('count'));

			/**
			 * test saving another copy of the same thing
			 */
			$this->assertTrue($id = $this->ModulePosition->copy('module-position-custom4'));
			$this->assertIdentical(17, $this->ModulePosition->find('count'));

			$this->assertTrue($id = $this->ModulePosition->copy('module-position-custom4'));
			$this->assertIdentical(18, $this->ModulePosition->find('count'));
		}
	}