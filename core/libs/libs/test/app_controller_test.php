<?php
	App::import('lib', 'libs.test/app_test.php');

	/**
	 * @brief AppControllerTestCase is the class that model tests should extend
	 *
	 * This class uses AppTest for autoloading fixtures, classes and all dependencies
	 *
	 * @copyright Copyright (c) 2011 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package Infinitas.Testing
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.9b
	 *
	 * @author dogmatic69
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */

	class AppControllerTestCase extends CakeTestCase {
		/**
		 * @brief if set to stop, testing will stop before the next testCase starts
		 *
		 * @var bool
		 */
		public $stop = false;

		/**
		 * @brief stores some data about the tests
		 *
		 * @var array
		 */
		public $data = array();

		/**
		 * @brief setup the test case and try catch any errors
		 *
		 * CakePHP just throws errors all over if there is a missing fixture.
		 * Here we try and catch any errors and display them so that its easier
		 * to fix.
		 *
		 * @access public
		 *
		 * @return void
		 */
		public function __construct(){
			parent::__construct();

			if(is_subclass_of($this, 'AppControllerTestCase')){
				try{
					$this->AppTest = new AppTest($this);
				}

				catch (AppTestException $e){
					pr($e->error());
					exit;
				}
			}
		}

		/**
		 * @brief call only when its not set
		 *
		 * overload the _initDb method and make sure its only called when the db
		 * object does not exist
		 *
		 * @access public
		 *
		 * @return true
		 */
		public function _initDb() {
			if(!isset($this->db) || !$this->db){
				parent::_initDb();
			}

			return true;
		}
		
		/**
		 * set up the session and fill it with any data passed
		 */
		public function session($data = array(), $engine = 'php') {
			$this->AppTest->session($data, $engine);
		}

		/**
		 * @brief allow running only some of the tests in the test case
		 *
		 * Overrides parent method to allow selecting tests to run in the current test case
		 * It is useful when working on one particular test
		 *
		 * @link https://github.com/CakeDC/templates
		 * @author Cake Development Corporation
		 * @copyright 2005-2011, Cake Development Corporation (http://cakedc.com)
		 *
		 * @access public
		 *
		 * @return array List of tests to run
		 */
		public function getTests() {
			if (!empty($this->tests)) {
				debug('Only the following tests will be executed: ' . join(', ', (array) $this->tests), false, false);
				return array_merge(array('start', 'startCase'), (array) $this->tests, array('endCase', 'end'));
			}

			return parent::getTests();
		}

		/**
		 * @brief before a model test starts
		 *
		 * Load up the fixtures and then set the model object for use. If it is
		 * the first test method that is run, display a list of fixtures that have
		 * been loaded so that people know what is going on under the hood
		 *
		 * @access public
		 *
		 * @return void
		 */
		public function startTest($method) {
			if(!is_subclass_of($this, 'AppControllerTestCase')) {
				return;
			}
			
			$this->AppTest->startTest($method);
			$this->AppTest->loadFixtures(null, true);

			echo '<div style="border: 2px solid #d6ab00; padding: 5px; margin-top:5px; margin-bottom:5px">';

			$this->mockEntireController($controller . 'Controller');

			list($plugin, $controller) = pluginSplit($this->setup[$this->setup['type']]);

			$testControllerClass = sprintf('TestController%sController', $controller);
			$this->{$controller} = new $testControllerClass();
			$this->{$controller}->constructClasses();
			$this->{$controller}->Component->initialize($this->{$controller});
			$this->{$controller}->Session->write('Message.flash', array());
		}

		public function mockEntireController($controller) {
			$this->__classVarCache = get_class_vars($controller);
			$this->__classVarCache = array_merge(array('helpers' => array(), 'components' => array(), 'uses' => array()), $this->__classVarCache);

			$this->setup['mock']['helpers'] = isset($this->setup['mock']['helpers']) ? $this->setup['mock']['helpers'] : $this->__classVarCache['helpers'];
			$this->setup['mock']['components'] = isset($this->setup['mock']['components']) ? $this->setup['mock']['components'] : $this->__classVarCache['components'];
			$this->setup['mock']['uses'] = isset($this->setup['mock']['uses']) ? $this->setup['mock']['uses'] : $this->__classVarCache['uses'];

			$this->mockHelpers($this->setup['mock']['helpers']);
			$this->mockComponents($this->setup['mock']['components']);
			$this->mockUses($this->setup['mock']['uses']);
		}

		public function mockHelpers($helpers) {
			foreach($helpers as $helper => $config) {
				if(is_int($helper)) {
					$helper = $config;
				}

				if(isset($this->__mockCache['helpers'][$helper]) || !App::import('Helper', $helper)){
					continue;
				}

				$methods = '*';
				if(isset($this->setup['mock']['helpers'][$helper]) && !empty($this->setup['mock']['helpers'][$helper])){
					$methods = $this->setup['mock']['helpers'][$helper];
				}

				$mockClass = 'Mock' . $helper . 'Helper';

				if(class_exists($mockClass)){
					continue;
				}
				
				$params = array($helper . 'Helper');
				if($methods !== '*'){
					$params[] = null;
					$params[] = $this->setup['mock']['helpers'][$helper];
				}
				
				if(count($params) == 1){
					forward_static_call_array(array('Mock', 'generate'), $params);
				}
				else{
					forward_static_call_array(array('Mock', 'generatePartial'), $params);
				}

				$this->__mockCache['helpers'][$helper] = new $mockClass();
			}
		}

		/**
		 * @brief after a test stops
		 *
		 * unset the model object and clear out the registry
		 *
		 * @access public
		 *
		 * @return void
		 */
		public function endTest($method) {
			if(!is_subclass_of($this, 'AppControllerTestCase')) {
				return;
			}
			
			list($plugin, $controller) = pluginSplit($this->setup[$this->setup['type']]);
			unset($this->{$controller});
			ClassRegistry::flush();

			$this->AppTest->endTest($method);
			echo sprintf(
				'<div style="padding: 8px; background-color: green; color: white;">%s <span style="color:#ffdd00;">[%ss]</span></div>',
				$this->AppTest->prettyTestMethod($method),
				$this->data[$method]['total']
			);

			echo '</div>';
			if($this->stop === true){
				debug('Skipping further tests', false, false);

				$this->AppTest->endTest();
				exit;
			}
		}

		public function startCase() {
			if(!is_subclass_of($this, 'AppControllerTestCase')) {
				return;
			}

			$this->AppTest->startCase();
		}

		/**
		 * @brief end of tests
		 *
		 * When everything is done, clean up the database logs so that debugging
		 * the queries is a bit easier.
		 *
		 * @access public
		 *
		 * @return void
		 */
		public function endCase(){
			if(!is_subclass_of($this, 'AppControllerTestCase')) {
				return;
			}

			$this->AppTest->endCase();
		}

		/**
		 * @brief clean out the data for the selected model
		 *
		 * @access public
		 *
		 * @return bool true if it happened, false if not
		 */
		public function truncate(){
			list($plugin, $model) = pluginSplit($this->setup[$this->setup['type']]);
			$this->assertTrue(isset($this->{$model}));
			$this->assertTrue($this->{$model}->deleteAll(array($this->{$model}->alias . '.' . $this->{$model}->primaryKey . ' != ' => 'will-never-be-this-that-is-for-sure')));
		}

		/**
		 * @brief asset that data is valid
		 *
		 * Asserts that data are valid given Model validation rules
		 * Calls the Model::validate() method and asserts the result
		 *
		 * @link https://github.com/CakeDC/templates
		 * @author Cake Development Corporation
		 * @copyright 2005-2011, Cake Development Corporation (http://cakedc.com)
		 *
		 * @access public
		 *
		 * @param Model $Model Model being tested
		 * @param array $data Data to validate
		 *
		 * @return void
		 */
		public function assertValid(Model $Model, $data) {
			$this->assertTrue($this->_validData($Model, $data));
		}

		/**
		 * @brief assert that data is not valid
		 *
		 * Asserts that data are invalid given Model validation rules
		 * Calls the Model::validate() method and asserts the result
		 *
		 * @link https://github.com/CakeDC/templates
		 * @author Cake Development Corporation
		 * @copyright 2005-2011, Cake Development Corporation (http://cakedc.com)
		 *
		 * @access public
		 *
		 * @param Model $Model Model being tested
		 * @param array $data Data to validate
		 *
		 * @return void
		 */
		public function assertInvalid(Model $Model, $data) {
			$this->assertFalse($this->_validData($Model, $data));
		}

		/**
		 * @brief check validation errors are correct
		 *
		 * Asserts that data are validation errors match an expected value when
		 * validation given data for the Model
		 * Calls the Model::validate() method and asserts validationErrors
		 *
		 * @link https://github.com/CakeDC/templates
		 * @author Cake Development Corporation
		 * @copyright 2005-2011, Cake Development Corporation (http://cakedc.com)
		 *
		 * @param Model $Model Model being tested
		 * @param array $data Data to validate
		 * @param array $expectedErrors Expected errors keys
		 *
		 * @return void
		 */
		public function assertValidationErrors($Model, $data, $expectedErrors) {
			$this->_validData($Model, $data, $validationErrors);
			sort($expectedErrors);
			$this->assertEqual(array_keys($validationErrors), $expectedErrors);
		}

		/**
		 * @brief Convenience method allowing to validate data and return the result
		 *
		 * @link https://github.com/CakeDC/templates
		 * @author Cake Development Corporation
		 * @copyright 2005-2011, Cake Development Corporation (http://cakedc.com)
		 *
		 * @access protected
		 *
		 * @param Model $Model Model being tested
		 * @param array $data Profile data
		 * @param array $validationErrors Validation errors: this variable will be updated with validationErrors (sorted by key) in case of validation fail
		 *
		 * @return boolean Return value of Model::validate()
		 */
		protected function _validData(Model $Model, $data, &$validationErrors = array()) {
			$valid = true;
			$Model->create($data);
			if (!$Model->validates()) {
				$validationErrors = $Model->validationErrors;
				ksort($validationErrors);
				$valid = false;
			}

			else {
				$validationErrors = array();
			}

			return $valid;
		}
	}