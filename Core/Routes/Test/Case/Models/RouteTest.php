<?php
	/* Route Test cases generated on: 2010-03-13 11:03:13 : 1268471233*/
	App::uses('Route', 'Routes.Model');

	class RouteTest extends CakeTestCase {
		var $fixtures = array(
			'plugin.routes.route',
			'plugin.themes.theme'
		);

		function startTest() {
			$this->Route = ClassRegistry::init('Routes.Route');
		}

		function testGetValues(){
			//$this->assertEquals('_getValues', 'getValues');
			//cant test private Route::_getValues() like this

			$this->assertFalse($this->Route->getValues(array()));
			$this->assertFalse($this->Route->getValues(null));

			$route = array(
				'prefix' => 'admin',
				'plugin' => 'blog',
				'controller' => 'posts',
				'action' => '',
				'values' => '',
				'force_backend' => true,
				'force_frontend' => false
			);
			//basic test
			$this->assertEqual($this->Route->getValues($route), array('prefix' => 'admin', 'plugin' => 'blog', 'controller' => 'posts', 'admin' => true));

			// no prefix
			$route['prefix'] = '';
			$this->assertEqual($this->Route->getValues($route), array('plugin' => 'blog', 'controller' => 'posts', 'admin' => true));

			// admin prefix without forcing anything
			$route['prefix'] = 'admin';
			$route['force_backend'] = false;
			$route['force_frontend'] = false;
			$this->assertEqual($this->Route->getValues($route), array('prefix' => 'admin', 'plugin' => 'blog', 'controller' => 'posts'));

			// force frontend (admin => false)
			$route['prefix'] = '';
			$route['force_backend'] = false;
			$route['force_frontend'] = true;
			$this->assertEqual($this->Route->getValues($route), array('plugin' => 'blog', 'controller' => 'posts', 'admin' => false));

			// forcing both will default to admin
			$route['prefix'] = '';
			$route['force_backend'] = true;
			$route['force_frontend'] = true;
			$this->assertEqual($this->Route->getValues($route), array('plugin' => 'blog', 'controller' => 'posts', 'admin' => true));

			// force frontend with a prefix
			$route['prefix'] = 'something';
			$route['force_backend'] = false;
			$route['force_frontend'] = true;
			$this->assertEqual($this->Route->getValues($route), array('prefix' => 'something', 'plugin' => 'blog', 'controller' => 'posts', 'admin' => false));

			// with an action
			$route['prefix'] = '';
			$route['force_backend'] = false;
			$route['force_frontend'] = true;
			$route['action'] = 'index';
			$this->assertEqual($this->Route->getValues($route), array('plugin' => 'blog', 'controller' => 'posts', 'action' => 'index', 'admin' => false));

			// bad json data
			$route['values'] = '{}';
			$route['action'] = '';
			$this->assertEqual($this->Route->getValues($route), array('plugin' => 'blog', 'controller' => 'posts', 'admin' => false));

			// simple
			$route['values'] = '{"day":null}';
			$route['action'] = '';
			$this->assertEqual($this->Route->getValues($route), array('plugin' => 'blog', 'controller' => 'posts', 'admin' => false, 'day' => null));

			// completely ignore nested arrays
			$route['values'] = '{"day":null,"abc":{"test":"bad"}}';
			$route['action'] = '';
			$this->assertEqual($this->Route->getValues($route), array('plugin' => 'blog', 'controller' => 'posts', 'admin' => false, 'day' => null));


			//reset route and test regex rules and values passing
			$route = '';
			$route['rules'] = '';
			$route['pass']  = '';

			// wrong usage
			$this->expectError();
			$this->Route->getRegex();

			// test empty
			$this->assertEqual($this->Route->getRegex($route['rules']), array());
			$this->assertEqual($this->Route->getRegex($route['rules'], $route['pass']), array());

			//basic test
			$route['rules'] = '{"year":"[12][0-9]{3}","month":"0[1-9]|1[012]","day":"0[1-9]|[12][0-9]|3[01]"}';
			$this->assertEqual($this->Route->getRegex($route['rules']), array('year' => '[12][0-9]{3}', 'month' => '0[1-9]|1[012]', 'day' => '0[1-9]|[12][0-9]|3[01]'));

			//ignore nested arrays
			$route['rules'] = '{"year":"[12][0-9]{3}","month":"0[1-9]|1[012]","day":"0[1-9]|[12][0-9]|3[01]","array":{"abc":false}}';
			$this->assertEqual($this->Route->getRegex($route['rules']), array('year' => '[12][0-9]{3}', 'month' => '0[1-9]|1[012]', 'day' => '0[1-9]|[12][0-9]|3[01]'));

			// basic rules with pass
			$route['rules'] = '{"year":"[12][0-9]{3}"}';
			$route['pass'] = 'id,year';
			$this->assertEqual($this->Route->getRegex($route['rules'], $route['pass']), array('year' => '[12][0-9]{3}', 0 => 'id', 1 => 'year'));

			// pass only one field
			$route['pass'] = 'id';
			$this->assertEqual($this->Route->getRegex($route['rules'], $route['pass']), array('year' => '[12][0-9]{3}', 0 => 'id'));

			//pass many fields
			$route['pass'] = 'id,name,slug,date';
			$this->assertEqual($this->Route->getRegex($route['rules'], $route['pass']), array('year' => '[12][0-9]{3}', 0 => 'id', 1 => 'name', 2 => 'slug', 3 => 'date'));

			// pass wtih no rules
			$route['rules'] = '';
			$route['pass'] = 'id';
			$this->assertEqual($this->Route->getRegex($route['rules'], $route['pass']), array(0 => 'id'));
		}

		public function testGetRoutes() {
			//basic find
			$routes = $this->Route->getRoutes();

			//random checks
			$this->assertTrue(isset($routes[7]));
			$this->assertNotEmpty($routes[5]);

			// advanced route
			$expected = array(
				'Route' => array(
					'url' => '/p/:year/:month/:day',
					'values' => array(
						'plugin' => 'blog',
						'controller' => 'posts',
						'admin' => false
					),
					'regex' => array(),
					'theme' => 'default'
				)
			);
			$this->assertEqual($routes[9], $expected);

			// admin route
			$expected = array(
				'Route' => array(
					'url' => '/admin',
					'values' => array(
						'prefix' => 'admin',
						'plugin' => 'management',
						'controller' => 'management',
						'action' => 'dashboard',
						'admin' => true
					),
					'regex' => array(),
					'theme' => null
				)
			);
			$this->assertEqual($routes[2], $expected);
		}

		function endTest() {
			unset($this->Route);
			ClassRegistry::flush();
		}

	}