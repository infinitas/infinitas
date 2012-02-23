<?php
	/* Branches Test cases generated on: 2010-12-14 13:12:47 : 1292334947*/
	App::uses('BranchesController', 'Contact.Controller');

	class TestBranchesController extends BranchesController {
		var $autoRender = false;

		function redirect($url, $status = null, $exit = true) {
			$this->redirectUrl = $url;
		}
	}

	class BranchesControllerTest extends CakeTestCase {
		var $fixtures = array(
			'plugin.configs.config',
			'plugin.themes.theme',
			'plugin.routes.route',
			'plugin.modules.module',
			'plugin.modules.module_position',
			'plugin.modules.modules_route',
			'plugin.view_counter.view_count',
			'plugin.blog.post',

			'plugin.contact.branche',
			'plugin.contact.contact',
			'plugin.contact.address',
			'plugin.users.user',
			'plugin.users.group',
			'plugin.management.ticket',
			//'plugin.users.group',
		);

		function startTest() {
			$this->Branches = new TestBranchesController();
			$this->Branches->constructClasses();
		}

		function endTest() {
			unset($this->Branches);
			ClassRegistry::flush();
		}

		function testStuff(){
			$this->assertInstanceOf('BranchesController', $this->Branches);
		}
	}