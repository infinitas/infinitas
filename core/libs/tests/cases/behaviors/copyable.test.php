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
				'Users.User',
				'Modules.Module',
				'Modules.ModulePosition',
				'Routes.Route'
			),
			'fixtures' => array(
				'do' => array(
					'Tags.Tag',
					'Tags.Tagged'
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
		 * @brief Tests generateContain
		 *
		 * @test test that getting contains fetches the correct stuff.
		 */
		public function testGenerateContain() {
			if($this->User->Behaviors->attached('Copyable')){
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
			$this->assertTrue($this->Module->bindModel(array('hasAndBelongsToMany' => array('Tag' => array('className' => 'Tags.Tag', 'with' => 'global_tags'))), false));
			$expected = array(0 => 'Route', 1 => 'Tag');
			$this->assertEqual($expected, $this->Module->generateContain());
		}

		/**
		 * @brief Tests copy
		 *
		 * @test Enter description here
		 */
		public function testCopy() {
			foreach($this->ModulePosition->Behaviors->attached() as $attached){
				if(!in_array($attached, array('Containable', 'Infinitas'))){
					$this->ModulePosition->Behaviors->detach($attached);
				}
			}
			$this->ModulePosition->Behaviors->attach('Libs.Copyable');

			$positions = $this->ModulePosition->find(
				'all',
				array(
					'fields' => array(
						'ModulePosition.id',
						'ModulePosition.name'
					),
					'contain' => array(
						'Module' => array(
							'fields' => array(
								'Module.id',
								'Module.name'
							)
						)
					)
				)
			);

			$positionCount = $this->ModulePosition->find('count');
			$moduleCount = $this->ModulePosition->Module->find('count');

			$this->assertEqual(11, $positionCount);
			$this->assertEqual(10, $moduleCount);


			// copy the bottom module position that has no modules
			$this->assertTrue($this->ModulePosition->copy(2));
			$this->assertEqual(12, $this->ModulePosition->find('count'));
			$this->assertEqual(10, $this->ModulePosition->Module->find('count'));

			// copy the bottom module position that has no modules
			$this->assertTrue($this->ModulePosition->copy(5));
			$this->assertEqual(13, $this->ModulePosition->find('count'));
			$this->assertEqual(12, $this->ModulePosition->Module->find('count'));
		}
	}