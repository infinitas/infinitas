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
	
	class AppBehaviorTestCase extends CakeTestCase {
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

			if(is_subclass_of($this, 'AppBehaviorTestCase')){
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
			$this->AppTest->startTest($method);
			$this->AppTest->loadFixtures(null, true);

			echo '<div style="border: 2px solid #d6ab00; padding: 5px; margin-top:5px; margin-bottom:5px">';
			
			list($plugin, $behavior) = pluginSplit($this->setup[$this->setup['type']]);
			$behaviorClass = $behavior . 'Behavior';
			$this->{$behaviorClass} = new $behaviorClass();

			foreach($this->setup['models'] as $modelToLoad){
				list($plugin, $model) = pluginSplit($modelToLoad);
				$this->{$model} = ClassRegistry::init($modelToLoad);
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
			list($plugin, $behavior) = pluginSplit($this->setup[$this->setup['type']]);
			$behaviorClass = $behavior . 'Behavior';
			unset($this->{$behaviorClass});

			foreach($this->setup['models'] as $modelToLoad){
				list($plugin, $model) = pluginSplit($modelToLoad);
				unset($this->{$model});
			}

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

		public function startCase() {
			if(is_subclass_of($this, 'AppBehaviorTestCase')) {
				$this->AppTest->startCase();
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
			if(is_subclass_of($this, 'AppBehaviorTestCase')) {
				$this->AppTest->endCase();
			}
		}

		public function outputArray($array, $level = 1){
			$this->AppTest->outputArray($array, $level);
		}
	}