<?php
	App::import('lib', 'libs.test/app_test.php');

	/**
	 * @brief AppModelTestCase is the class that model tests should extend
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
	
	class AppModelTestCase extends CakeTestCase {
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

			if(is_subclass_of($this, 'AppModelTestCase')){
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
				return array_merge(array('start', 'startCase'), (array)$this->tests, array('endCase', 'end'));
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
			$this->AppTest->startTest($method);
			$this->AppTest->loadFixtures(null, true);

			echo '<div style="border: 2px solid #d6ab00; padding: 5px; margin-top:5px; margin-bottom:5px">';
			
			list($plugin, $model) = pluginSplit($this->setup[$this->setup['type']]);
			$this->{$model} = ClassRegistry::init($this->setup[$this->setup['type']]);
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
			list($plugin, $model) = pluginSplit($this->setup[$this->setup['type']]);
			unset($this->{$model});
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
			if(is_subclass_of($this, 'AppModelTestCase')) {
				$this->AppTest->startCase();

				if(empty($this->tests)){
					$this->startTest('testFixtureIntegrityCheck');
					$this->doFixtureIntegrityCheck();
					$this->endTest('testFixtureIntegrityCheck');
				}
			}
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
			if(is_subclass_of($this, 'AppModelTestCase')) {
				$this->AppTest->endCase();
			}
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
		 * @brief Disables a behavior in tests
		 *
		 * This function will overwrite the given behavior to a clean
		 * instance of the ModelBehavior class so the behavior will act like it never
		 * existed.
		 *
		 * @param mixed $behaviors Single behavior name or an array of names
		 *
		 * @return void
		 */
		public function disableBehavior($behaviors) {
			$this->AppTest->disableBehavior($behaviors);
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

		public function outputArray($array, $level = 1){
			$this->AppTest->outputArray($array, $level);
		}

		public function doFixtureIntegrityCheck() {
			list($plugin, $className) = pluginSplit($this->setup['model']);

			$this->{$className}->Behaviors->disable($this->{$className}->Behaviors->enabled());
			$records = $this->{$className}->find('all');

			$schema = $this->{$className}->schema();
			foreach($records as $record) {
				foreach($this->{$className}->belongsTo as $alias => $relation) {
					if($schema[$relation['foreignKey']]['null'] == true && $record[$className][$relation['foreignKey']] === null) {
						continue;
					}
					$conditions = array($alias . '.' . $this->{$className}->{$alias}->primaryKey => $record[$className][$relation['foreignKey']]);
					$this->{$className}->{$alias}->Behaviors->disable($this->{$className}->{$alias}->Behaviors->enabled());
					$exists = $this->{$className}->{$alias}->find('count', array('conditions' => $conditions, 'callbacks' => false));
					$message = sprintf('Invalid value in field %s (value: %s) for %s (id: %s), the associated %s does not exist.', $relation['foreignKey'], $record[$className][$relation['foreignKey']], $className, $record[$className][$this->{$className}->primaryKey], $relation['className']);
					$this->assertTrue($exists, $message);
				}
			}
		}
	}