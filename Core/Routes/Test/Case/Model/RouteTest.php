<?php
/* Route Test cases generated on: 2010-03-13 11:03:13 : 1268471233*/
App::uses('Route', 'Routes.Model');

class RouteTest extends CakeTestCase {
	public $fixtures = array(
		'plugin.routes.route',
		'plugin.themes.theme',
		'plugin.installer.plugin'
	);

/**
 * @brief test startup
 */
	public function startTest() {
		$this->Route = new Route();
		$this->Route = ClassRegistry::init('Routes.Route');
		$this->Route->Behaviors->attach('Libs.Validation');
	}

/**
 * @brief test cleanup
 */
	public function endTest() {
		unset($this->Route);
		ClassRegistry::flush();
	}

/**
 * @brief test when missing connection
 */
	public function brokentestBrokenDatabaseOrPreInstall() {
		$expected = array();
		$result = $this->Route->getRoutes();

		$this->assertEquals($expected, $result);
	}

/**
 * @brief check validation rules
 *
 * @dataProvider validationFailData
 */
	public function testValidationRules($data, $expected) {
		$this->Route->create($data);
		$this->Route->validates();
		$this->assertEquals($expected, $this->Route->validationErrors);
	}

/**
 * @brief test saving routes
 */
	public function testSaveRoutes() {

	}

/**
 * @brief test building the data for the router
 *
 * @dataProvider routeData
 */
	public function testGetValues($data, $expected) {
		$this->assertEquals($expected, $this->Route->getValues($data));
	}

/**
 * @brief test building the regex and pass params
 *
 * @dataProvider regexData
 */
	public function testRegex($data, $expected) {
		if(!isset($data['pass'])) {
			return $this->assertEquals($expected, $this->Route->getRegex($data['rules']));
		}

		$this->assertEquals($expected, $this->Route->getRegex($data['rules'], $data['pass']));
	}

/**
 * @brief test getting the route out of the database formatted for InfinitasRouter::connect()
 */
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
				'regex' => array(
					'pass' => array(
						'year',
						'month',
						'day'
					)
				),
				'theme' => 'default',
				'layout' => 'front',
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
				'theme' => null,
				'layout' => null,
			)
		);
		$this->assertEqual($routes[2], $expected);
	}

/**
 * @brief routeData data provider
 */
	public function routeData() {
		return array(
			array(array(), false),
			array(null, false),
			array(
				array(
					'prefix' => 'admin',
					'plugin' => 'blog',
					'controller' => 'posts',
					'action' => '',
					'values' => '',
					'force_backend' => true,
					'force_frontend' => false
				),
				array('prefix' => 'admin', 'plugin' => 'blog', 'controller' => 'posts', 'admin' => true)
			),
			array( // no prefix
				array(
					'prefix' => '',
					'plugin' => 'blog',
					'controller' => 'posts',
					'action' => '',
					'values' => '',
					'force_backend' => true,
					'force_frontend' => false
				),
				array('plugin' => 'blog', 'controller' => 'posts', 'admin' => true)
			),
			array( // admin prefix without forcing anything
				array(
					'prefix' => 'admin',
					'plugin' => 'blog',
					'controller' => 'posts',
					'action' => '',
					'values' => '',
					'force_backend' => false,
					'force_frontend' => false
				),
				array('prefix' => 'admin', 'plugin' => 'blog', 'controller' => 'posts')
			),
			array( // force frontend (admin => false)
				array(
					'prefix' => '',
					'plugin' => 'blog',
					'controller' => 'posts',
					'action' => '',
					'values' => '',
					'force_backend' => false,
					'force_frontend' => true
				),
				array('plugin' => 'blog', 'controller' => 'posts', 'admin' => false)
			),
			array( // forcing both will default to admin
				array(
					'prefix' => '',
					'plugin' => 'blog',
					'controller' => 'posts',
					'action' => '',
					'values' => '',
					'force_backend' => true,
					'force_frontend' => true
				),
				array('plugin' => 'blog', 'controller' => 'posts', 'admin' => true)
			),
			array( // force frontend with a prefix
				array(
					'prefix' => 'something',
					'plugin' => 'blog',
					'controller' => 'posts',
					'action' => '',
					'values' => '',
					'force_backend' => false,
					'force_frontend' => true
				),
				array('prefix' => 'something', 'plugin' => 'blog', 'controller' => 'posts', 'admin' => false)
			),
			array( // with an action
				array(
					'prefix' => '',
					'plugin' => 'blog',
					'controller' => 'posts',
					'action' => 'index',
					'values' => '',
					'force_backend' => false,
					'force_frontend' => true
				),
				array('plugin' => 'blog', 'controller' => 'posts', 'action' => 'index', 'admin' => false)
			),
			array( // bad json data
				array(
					'prefix' => '',
					'plugin' => 'blog',
					'controller' => 'posts',
					'action' => '',
					'values' => '{}',
					'force_backend' => false,
					'force_frontend' => true
				),
				array('plugin' => 'blog', 'controller' => 'posts', 'admin' => false)
			),
			array( // simple values
				array(
					'prefix' => '',
					'plugin' => 'blog',
					'controller' => 'posts',
					'action' => '',
					'values' => '{"day":null}',
					'force_backend' => false,
					'force_frontend' => true
				),
				array('plugin' => 'blog', 'controller' => 'posts', 'admin' => false, 'day' => null)
			),
			array( // completely ignore nested arrays
				array( // @todo cake now can use nested get params
					'prefix' => '',
					'plugin' => 'blog',
					'controller' => 'posts',
					'action' => '',
					'values' => '{"day":null,"abc":{"test":"bad"}}',
					'force_backend' => false,
					'force_frontend' => true
				),
				array('plugin' => 'blog', 'controller' => 'posts', 'admin' => false, 'day' => null)
			)
		);
	}

/**
 * @brief regexData data provider
 */
	public function regexData() {
		return array(
			array(
				array('rules' => ''),
				array()
			),
			array(
				array('rules' => '', 'pass' => ''),
				array()
			),
			array(
				array('rules' => false, 'pass' => null),
				array()
			),
			array(
				array(
					'rules' => '{"year":"[12][0-9]{3}","month":"0[1-9]|1[012]","day":"0[1-9]|[12][0-9]|3[01]"}'
				),
				array('year' => '[12][0-9]{3}', 'month' => '0[1-9]|1[012]', 'day' => '0[1-9]|[12][0-9]|3[01]')
			),
			array( //ignore nested arrays
				array(
					'rules' => '{"year":"[12][0-9]{3}","month":"0[1-9]|1[012]","day":"0[1-9]|[12][0-9]|3[01]","array":{"abc":false}}'
				),
				array('year' => '[12][0-9]{3}', 'month' => '0[1-9]|1[012]', 'day' => '0[1-9]|[12][0-9]|3[01]')
			),
			array( // basic rules with pass
				array(
					'rules' => '{"year":"[12][0-9]{3}"}',
					'pass' => 'id,year'
				),
				array('year' => '[12][0-9]{3}', 'pass' => array('id', 'year'))
			),
			array( // pass only one field
				array(
					'rules' => '{"year":"[12][0-9]{3}"}',
					'pass' => 'id'
				),
				array('year' => '[12][0-9]{3}', 'pass' => array('id'))
			),
			array( //pass many fields
				array(
					'rules' => '{"year":"[12][0-9]{3}"}',
					'pass' => 'id,name,slug,date'
				),
				array('year' => '[12][0-9]{3}', 'pass' => array('id', 'name', 'slug', 'date'))
			),
			array( //pass many fields
				array(
					'rules' => '',
					'pass' => 'id'
				),
				array('pass' => array('id'))
			)
		);
	}

/**
 * validationFailData data provider
 *
 * @return void
 */
	public function validationFailData() {
		return array(
			array(
				array(),
				array(
					'name' => array('Please enter a name for this route'),
					'url' => array('Please use a valid url (absolute or full)'),
					'plugin' => array('Please select where this route will go'),
					'controller' => array('Please select a valid controller'),
					'action' => array('Please select a valid action'))
			),
			array(
				array(
					'name' => 'Home Page',
					'url' => '/pages/*',
					'plugin' => 'missing-plugin'
				),
				array(
					'name' => array('There is already a route with this name'),
					'url' => array('There is already a route for this url'),
					'plugin' => array('Please select a valid controller'),
					'controller' => array('Please select a valid controller'),
					'action' => array('Please select a valid action'))
			),
			array(
				array(
					'name' => 'Test Route',
					'url' => '/custom/route/*',
					'plugin' => 'Users',
					'controller' => 'missing-controller'
				),
				array(
					'controller' => array('Please select a valid controller'),
					'action' => array('Please select a valid action'))
			),
			array(
				array(
					'name' => 'Test Route',
					'url' => '/custom/route/*',
					'plugin' => 'Users',
					'controller' => 'UsersController',
					'action' => 'missing-action'
				),
				array(
					'action' => array('Please select a valid action'))
			),
			array(
				array(
					'name' => 'Test Route',
					'url' => '/custom/route/*',
					'plugin' => 'Users',
					'controller' => 'UsersController',
					'action' => 'register'
				),
				array()
			),
			array(
				array(
					'name' => 'Test Route',
					'url' => '/custom/route/*',
					'plugin' => 'Users',
					'controller' => 'UsersController',
					'action' => 'register',
					'values' => '/asd/',
					'rules' => '/asd/',
					'pass' => '/asd/'
				),
				array(
					'values' => array('Please enter valid configuration (json) for the route or leave blank'),
					'rules' => array('Please enter valid rules (json) for the route or leave blank'),
					'pass' => array('Please enter a comma seperated list of variables to pass, no spaces'))
			),
			array(
				array(
					'name' => 'Test Route',
					'url' => '/custom/route/*',
					'plugin' => 'Users',
					'controller' => 'UsersController',
					'action' => 'register',
					'values' => '{"asd":"123"}',
					'rules' => '{"asd":"123"}',
					'pass' => 'foo'
				),
				array()
			),
			array(
				array(
					'name' => 'Test Route',
					'url' => '/custom/route/*',
					'plugin' => 'Users',
					'controller' => 'UsersController',
					'action' => 'register',
					'values' => '{"asd":"123"}',
					'rules' => '{"asd":"123"}',
					'pass' => 'foo,bar',
					'force_frontend' => 1,
					'force_backend' => 1
				),
				array(
					'force_frontend' => array('Please select either frontend or backend'),
					'force_backend' => array('Please select either frontend or backend'))
			),
			array(
				array(
					'name' => 'Test Route',
					'url' => '/custom/route/*',
					'plugin' => 'Users',
					'controller' => 'UsersController',
					'action' => 'register',
					'values' => '{"asd":"123"}',
					'rules' => '{"asd":"123"}',
					'pass' => 'foo,bar',
					'force_frontend' => 0,
					'force_backend' => 1
				),
				array()
			),
			array(
				array(
					'name' => 'Test Route',
					'url' => '/custom/route/*',
					'plugin' => 'Users',
					'controller' => 'UsersController',
					'action' => 'register',
					'values' => '{"asd":"123"}',
					'rules' => '{"asd":"123"}',
					'pass' => 'foo,bar',
					'force_frontend' => 1,
					'force_backend' => 0
				),
				array()
			)
		);
	}
}