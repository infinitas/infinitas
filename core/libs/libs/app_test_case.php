<?php
/**
 * Copyright 2005-2010, Cake Development Corporation (http://cakedc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2005-2010, Cake Development Corporation (http://cakedc.com)
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * Ignore test classes
 *
 */
SimpleTest::ignore('AppTestCase');

/**
 * App Test case. Contains base set of fixtures.
 *
 * @package templates
 * @subpackage templates.libs
 */
class AppTestCase extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $dependedFixtures = array();

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array();

/**
 * Autoload entrypoint for fixtures dependecy solver
 *
 * @var string
 */
	public $plugin = null;

/**
 * Test to run for the test case (e.g array('testFind', 'testView'))
 * If this attribute is not empty only the tests from the list will be executed
 *
 * @var array
 */
	protected $_testsToRun = array();

/**
 * Constructor
 *
 * If a class is extending AppTestCase it will merge these with the extending classes
 * so that you don't have to put the plugin fixtures into the AppTestCase
 *
 * @return void
 */
	public function __construct() {
		parent::__construct();
		if (is_subclass_of($this, 'AppTestCase')) {
			$parentClass = get_parent_class($this);
			$parentVars = get_class_vars($parentClass);
			if (isset($parentVars['fixtures'])) {
				$this->fixtures = array_unique(array_merge($parentVars['fixtures'], $this->fixtures));
			}
			if (!empty($this->plugin)) {
				$this->dependedFixtures = $this->solveDependancies($this->plugin);
			}
			if (!empty($this->dependedFixtures)) {
				foreach ($this->dependedFixtures as $plugin) {
					$fixtures = $this->loadConfig('fixtures', $plugin);
					if (!empty($fixtures)) {
						$this->fixtures = array_unique(array_merge($this->fixtures, $fixtures));
					}
				}
			}
		}
	}

/**
 * Loads a file from app/tests/config/configure_file.php or app/plugins/PLUGIN/tests/config/configure_file.php
 *
 * Config file variables should be formated like:
 *  $config['name'] = 'value';
 * These will be used to create dynamic Configure vars.
 *
 *
 * @param string $fileName name of file to load, extension must be .php and only the name
 *     should be used, not the extenstion.
 * @param string $type Type of config file being loaded. If equal to 'app' core config files will be use.
 *    if $type == 'pluginName' that plugins tests/config files will be loaded.
 * @return mixed false if file not found, void if load successful
 */
	public function loadConfig($fileName, $type = 'app') {
		$found = false;
		if ($type == 'app') {
			$folder = APP . 'tests' . DS . 'config' . DS;
		} else {
			$folder = App::pluginPath($type);
				if (!empty($folder)) {
				$folder .= 'tests' . DS . 'config' . DS;
			} else {
				return false;
			}
		}
		if (file_exists($folder . $fileName . '.php')) {
			include($folder . $fileName . '.php');
			$found = true;
		}

		if (!$found) {
			return false;
		}

		if (!isset($config)) {
			$error = __("AppTestCase::load() - no variable \$config found in %s.php", true);
			trigger_error(sprintf($error, $fileName), E_USER_WARNING);
			return false;
		}
		return $config;
	}

/**
 * Solves Plugin Fixture dependancies.  Called in AppTestCase::__construct to solve
 * fixture dependancies.  Uses a Plugins tests/config/dependent and tests/config/fixtures
 * to load plugin fixtures. To use this feature set $plugin = 'pluginName' in your test case.
 *
 * @param string $plugin Name of the plugin to load
 * @return array Array of plugins that this plugin's test cases depend on.
 */
	public function solveDependancies($plugin) {
		$found = false;
		$result = array($plugin);
		$add = $result;
		do {
			$changed = false;
			$copy = $add;
			$add = array();
			foreach ($copy as $pluginName) {
				$dependent = $this->loadConfig('dependent', $pluginName);
				if (!empty($dependent)) {
					foreach ($dependent as $parentPlugin) {
						if (!in_array($parentPlugin, $result)) {
							$add[] = $parentPlugin;
							$result[] = $parentPlugin;
							$changed = true;
						}
					}
				}
			}
		} while ($changed);
		return $result;
	}

/**
 * Overrides parent method to allow selecting tests to run in the current test case
 * It is useful when working on one particular test
 *
 * @return array List of tests to run
 */
	public function getTests() {
		if (!empty($this->_testsToRun)) {
			debug('Only the following tests will be executed: ' . join(', ', (array) $this->_testsToRun), false, false);
			$result = array_merge(array('start', 'startCase'), (array) $this->_testsToRun, array('endCase', 'end'));
			return $result;
		} else {
			return parent::getTests();
		}
	}

/**
 * Asserts that data are valid given Model validation rules
 * Calls the Model::validate() method and asserts the result
 *
 * @param Model $Model Model being tested
 * @param array $data Data to validate
 * @return void
 */
	public function assertValid(Model $Model, $data) {
		$this->assertTrue($this->_validData($Model, $data));
	}

/**
 * Asserts that data are invalid given Model validation rules
 * Calls the Model::validate() method and asserts the result
 *
 * @param Model $Model Model being tested
 * @param array $data Data to validate
 * @return void
 */
	public function assertInvalid(Model $Model, $data) {
		$this->assertFalse($this->_validData($Model, $data));
	}

/**
 * Asserts that data are validation errors match an expected value when
 * validation given data for the Model
 * Calls the Model::validate() method and asserts validationErrors
 *
 * @param Model $Model Model being tested
 * @param array $data Data to validate
 * @param array $expectedErrors Expected errors keys
 * @return void
 */
	public function assertValidationErrors($Model, $data, $expectedErrors) {
		$this->_validData($Model, $data, $validationErrors);
		sort($expectedErrors);
		$this->assertEqual(array_keys($validationErrors), $expectedErrors);
	}

/**
 * Convenience method allowing to validate data and return the result
 *
 * @param Model $Model Model being tested
 * @param array $data Profile data
 * @param array $validationErrors Validation errors: this variable will be updated with validationErrors (sorted by key) in case of validation fail
 * @return boolean Return value of Model::validate()
 */
	protected function _validData(Model $Model, $data, &$validationErrors = array()) {
		$valid = true;
		$Model->create($data);
		if (!$Model->validates()) {
			$validationErrors = $Model->validationErrors;
			ksort($validationErrors);
			$valid = false;
		} else {
			$validationErrors = array();
		}
		return $valid;
	}

}

/**
 * AppMock Class used for generating CakePHP mock objects.
 *
 * @package templates
 * @subpackage templates.libs
 */
class AppMock extends Mock {

/**
 * Generate a Controller instance.  Returns a partial
 * mock object with render() and redirect() mocked.
 * In addtion the methods found in ControllerMockBase have been mixed in.
 *
 * @param string $className Name of the class to mock
 * @param string $mockName Name of the generated mock class, optional [default: 'Test' . $className]
 * @param array $methods Methods to mock, optional. These 4 methods are mocked by default: 'redirect', 'referer', 'render', 'setAction'
 * @return object Instance of new mock class.
 */
	public function getTestController($className, $mockName = '', $methods = array()) {
		if ($mockName === '') {
			$mockName = 'Test' . $className;
		}

		if (!class_exists($mockName)) {
			$_back = SimpleTest::getMockBaseClass();
			SimpleTest::setMockBaseClass('ControllerMockBase');
			$generator = new AppMockGenerator($className, $mockName);
			$methods = array_merge(array('redirect', 'referer', 'render', 'setAction'), $methods);
			$generator->generatePartial($methods);
			SimpleTest::setMockBaseClass($_back);
		}
		return new $mockName();
	}

/**
 * Generate a Model instance. Returns a partial mock object with the methods passed
 *
 * @param string $class Name of the class to mock
 * @param string $mockName Name of the generated mock class, optional [default: 'Test' . $className]
 * @param array $methods Methods to mock, optional
 * @return object Instance of new mock class.
 */
	public function getTestModel($class, $mockName = '', $methods = array()) {
		list(, $className) = pluginSplit($class);
		if (!class_exists($className)) {
			App::import('Model', $class);
		}

		if ($mockName === '') {
			$mockName = 'Test' . $className;
		}

		if (!class_exists($mockName)) {
			$generator = new AppMockGenerator($className, $mockName);
			$generator->generatePartial($methods);
		}
		return new $mockName(false, null, 'test_suite');
	}

}

/**
 * Reflection Mock Can be used to subclass and create partial mocks of classes
 * declared final.  Also used as a base class for
 *
 * @package templates
 * @subpackage templates.libs
 */
class AppMockGenerator extends MockGenerator {

/**
 * Allow mock classes to inject additional code for the methods
 * they add
 *
 * @return string
 */
	public function _chainMockExpectations() {
		$code = parent::_chainMockExpectations();
		$mockClass = SimpleTest::getMockBaseClass();
		if (method_exists($mockClass, 'chainExpectations')) {
			$code .= call_user_func(array($mockClass, 'chainExpectations'));
		}
		return $code;
	}

/**
 * The extension class code as a string
 *
 * The class composites a mock object and chains mocked methods to it.
 *
 * @param array  $methods Mocked methods
 * @return string Code for a new class
 */
	public function _extendClassCode($methods) {
		$code  = "class " . $this->_mock_class . " extends " . $this->_class . " {\n";
		$code .= "    var \$_mock;\n";
		$code .= $this->_addMethodList($methods);
		$code .= "\n";
		$code .= "    function __construct() {\n";
		$code .= "        \$this->_mock = &new " . $this->_mock_base . "();\n";
		$code .= "        \$this->_mock->disableExpectationNameChecks();\n";
		$code .= "        \$args = func_get_args();\n";
		$code .= "        call_user_func_array(array('parent', '__construct'), \$args);\n";
		$code .= "    }\n";
		$code .= $this->_chainMockReturns();
		$code .= $this->_chainMockExpectations();
		$code .= $this->_chainThrowMethods();
		$code .= $this->_overrideMethods($methods);
		$code .= "}\n";
		return $code;
	}

/**
 * Creates source code to override a list of methods with mock versions
 *
 * @param array $methods Methods to be overridden with mock versions
 * @return string Code for overridden chains
 */
	public function _overrideMethods($methods) {
		$code = "";
		foreach ($methods as $method) {
			if ($this->_isConstructor($method)) {
				continue;
			}
			$code .= "    " . $this->_reflection->getSignature($method) . " {\n";
			$code .= "        \$args = func_get_args();\n";
			$code .= "        \$result = &\$this->_mock->_invoke(\"$method\", \$args);\n";
			$code .= "        return \$result;\n";
			$code .= "    }\n";
		}
		return $code;
	}
}

/**
 * Controller Mock Base
 * Base Class for controller specific Mocks
 *
 * @package templates
 * @subpackage templates.libs
 */
class ControllerMockBase extends SimpleMock {

/**
 * redirectCounter - keeps track of the next expectRedirect count
 *
 * @var int
 */
	protected $_redirectCounter = 0;

/**
 * redirectCounter - keeps track of the next expectRedirect count
 *
 * @var int
 */
	protected $_renderCounter = 0;

/**
 * setActionCounter - keeps track of the next setAction count
 *
 * @var int
 */
	protected $_setActionCounter = 0;

/**
 * Wrapper for expectAt() makes expecting redirects easier
 *
 * @return void
 */
	public function expectRedirect($args) {
		$this->expectAt($this->_redirectCounter, 'redirect', $args);
		$this->_redirectCounter++;
	}

/**
 * Wrapper for expectCallCount() makes expecting redirects easier
 *
 * @return void
 */
	public function expectExactRedirectCount() {
		$this->expectCallCount('redirect', $this->_redirectCounter);
	}

/**
 * Expect Render
 *
 * @return void
 */
	public function expectRender($args) {
		$this->expectAt($this->_renderCounter, 'render', $args);
		$this->_renderCounter++;
	}

/**
 * Wrapper for expectCallCount() makes expecting render easier
 *
 * @return void
 */
	public function expectExactRenderCount($args) {
		$this->expectCallCount('render', $this->_renderCounter);
	}

/**
 * Expect setAction
 *
 * @return void
 */
	public function expectSetAction($args) {
		$this->expectAt($this->_setActionCounter, 'setAction', $args);
		$this->_setActionCounter++;
	}

/**
 * Wrapper for expectCallCount() makes expecting setAction easier
 *
 * @return void
 */
	public function expectExactSetActionCount() {
		$this->expectCallCount('setAction', $this->_setActionCounter);
	}

/**
 * Add Expectation chain to parent class.
 *
 * @return void
 */
	public function chainExpectations() {
		$code = '';
		$code .= "    function expectRedirect() {\n";
		$code .= "        \$this->_mock->expectRedirect(func_get_args());\n";
		$code .= "    }\n";
		$code .= "    function expectExactRedirectCount() {\n";
		$code .= "        \$this->_mock->expectExactRedirectCount();\n";
		$code .= "    }\n";
		$code .= "    function expectRender() {\n";
		$code .= "        \$this->_mock->expectRender(func_get_args());\n";
		$code .= "    }\n";
		$code .= "    function expectExactRender() {\n";
		$code .= "        \$this->_mock->expectExactRender();\n";
		$code .= "    }\n";
		$code .= "    function expectSetAction() {\n;";
		$code .= "        \$this->_mock->expectSetAction(func_get_args());\n";
		$code .= "    }\n";
		$code .= "    function expectExactSetActionCount() {\n";
		$code .= "        \$this->_mock->expectExactSetActionCount();\n";
		$code .= "    }\n";
		return $code;
	}

}

/**
 * A simple class name registry for mocks
 * so you do not need to constantly make unique names for mock clases.
 * Allows reuse of mock names between tests.
 *
 * @package templates
 * @package templates.libs
 */
class MockRegistry {

/**
 * Registry of mock names.
 *
 * @var array
 */
	protected static $_registry = array();

/**
 * Get a Mock object from the registry or make a new one if there
 * are no mocks for this class.
 *
 * @param string $className Name of the class to generate a mock for
 * @param string $mockName Final mock name defaults to Mock . $className
 * @param string $methods Additional methods to mock define in the mock
 * @return Mock constructed mock object.
 * @throws LogicException When you try to generate mocks for classes that don't exist
 */
	public static function getMock($className, $mockName = '', $methods = array()) {
		if (!class_exists($className, false)) {
			throw new LogicException(sprintf('Cannot generate mock %s it has not been loaded.', $className));
		}
		if ($mockName === '') {
			$mockName = 'Mock' . $className;
		}
		if (!isset(self::$_registry[$className][$mockName])) {
			Mock::generate($className, $mockName, $methods);
			self::$_registry[$className][$mockName] = $mockName;
		}
		return new self::$_registry[$className][$mockName]();
	}

/**
 * Get a partial Mock from the registry. Or create a new partial
 *
 * @param string $className Name of class to partially mock
 * @param string $mockName name of final partial mock.  Defaults to 'PartialMock' . $className
 * @param string $methods Methods to mock in the partial
 * @return Mock constructed partial mock
 * @throws LogicException When you try to generate mocks for classes that don't exist
 */
	public static function getPartial($className, $mockName = '', $methods = array()) {
		if (!class_exists($className, false)) {
			throw new LogicException(sprintf('Cannot generate partial mock %s it has not been loaded.', $className));
		}
		if ($mockName === '') {
			$mockName = 'PartialMock' . $className;
		}
		if (!isset(self::$_registry[$className][$mockName])) {
			Mock::generatePartial($className, $mockName, $methods);
			self::$_registry[$className][$mockName] = $mockName;
		}
		$context = &SimpleTest::getContext();
		return new self::$_registry[$className][$mockName]($context->getTest());
	}

/**
 * Get a list of the available mocks for a classname
 *
 * @param string $className Name of the class you want mocks for.
 * @return array
 */
	public static function showAvailableMocks($className) {
		if (!isset(self::$_registry[$className])) {
			return array();
		}
		return self::$_registry[$className];
	}
}