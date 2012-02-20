<?php
	App::uses('AppControllerTestCase', 'Libs.Lib/test');

	class TestGroupsController extends AppControllerTestCase {

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
			'controller' => 'users.Groups'
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
		 * @brief Tests admin_index
		 *
		 * @test Enter description here
		 */
		public function testAdminIndex() {

		}

		/**
		 * @brief Tests admin_view
		 *
		 * @test Enter description here
		 */
		public function testAdminView() {

		}

		/**
		 * @brief Tests admin_add
		 *
		 * @test Enter description here
		 */
		public function testAdminAdd() {

		}

		/**
		 * @brief Tests admin_edit
		 *
		 * @test Enter description here
		 */
		public function testAdminEdit() {

		}
	}