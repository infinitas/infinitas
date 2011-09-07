<?php
	/**
	 * @brief AppTest is a goint class to make testing easier
	 *
	 * This class handles thigns like fixture dependencies and auto loading of
	 * the class files you will be testing.
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

	class AppTest extends Object {
		/**
		 * @brief holder var for the test class being used.
		 * @var object
		 */
		private $__testObject;

		/**
		 * @brief structure of setup array
		 *
		 * type: automatically set according to the setup
		 * plugin: figured out according to the other data passed
		 * 
		 * depending on the type you would set one of the following
		 * model: Plugin.ModelName
		 * behavior: Plugin.BehaviorName
		 * datasource: Plugin.DatasourceName
		 * controller: Plugin.Controllername
		 * component: Plugin.ComponentName
		 * helper: Plugin.HelperName
		 * fixtures:
		 *	ignore: anything that should not be loaded
		 *	do: additional fixtures that should be loaded 
		 *
		 * @var array
		 */
		private $__setup = array(
			'type' => null,
			'plugin' => null,
			'model' => null,
			'behavior' => null,
			'models' => array(),
			'datasource' => null,
			'controller' => null,
			'component' => null,
			'helper' => null,
			'fixtures' => array(
				'ignore' => array(),
				'do' => array()
			),
			'mock' => array()
		);

		/**
		 * @brief log of inserts for the fixtures
		 *
		 * @var array
		 */
		private $__fixtureDbLog = array();

		/**
		 * @brief log of each time a fixture is loaded
		 *
		 * @var array
		 */
		private $__fixtureInsertsLog = array();

		/**
		 * @brief list of plugins that have used the events to load fixtures.
		 * 
		 * @var array
		 */
		private $__autoLoaded = array();

		/**
		 * @brief models that have been loaded
		 *
		 * This is a list of models that have been loaded up to figure out the
		 * dependency fixtures. Its used to stop recursion
		 *
		 * @var array
		 */
		private $__loadedModels = array();

		/**
		 * @brief list of fixtures that should be loaded.
		 * 
		 * @var array
		 */
		private $__fixturesToLoad = array();

		/**
		 * @brief figure out what sort of test is being called and set up
		 *
		 * Depending on what type of test is being run, a specific test method is
		 * called to set up anything that might need setting up for that tests.
		 *
		 * see the related methods ::model() ::behavior() ::datasource()
		 * ::controller() ::component() ::helper()
		 * @param <type> $testObject
		 * @return <type>
		 */
		public function __construct($testObject) {
			$this->__testObject = $testObject;

			$this->__testObject->_initDb();
			$this->__testObject->db->_queriesLogMax = 1000;

			$this->__testObject->setup = (isset($this->__testObject->setup)) ? array_merge_recursive($this->__setup, $this->__testObject->setup) : $this->__setup;
			$this->__testObject->setup['type'] = empty($this->__testObject->setup['type']) ? $this->__testType() : $this->__testObject->setup['type'];

			if(is_callable(array($this, $this->__testObject->setup['type']))){
				$this->{$this->__testObject->setup['type']}($this->__testObject->setup[$this->__testObject->setup['type']]);
			}

			return true;
		}

		/**
		 * @brief get the type of test being run, model, controller etc
		 * 
		 * @return string the type of test that is running.
		 */
		private function __testType(){
			$setup = $this->__testObject->setup;
			unset($setup['fixtures']);
			$setup = array_filter($setup);
			$type = current(array_keys($setup));

			if(isset($this->__testObject->setup[$type])){
				$this->__testObject->setup[$type] = current(array_filter($setup[$type]));
			}

			return $type;
		}

		/**
		 * @brief the setup method for model tests
		 *
		 * This will load up all the fixtures that are requrired
		 *
		 * @param string $model the model to test
		 *
		 * @return
		 */
		public function model($model = null){
			if(!App::import('Model', $model)){
				list($plugin, $model) = pluginSplit($model);

				if(!class_exists($model)){
					throw new AppTestException(sprintf('Unable to load model: %s', implode('.', array($plugin, $model))));
					return false;
				}
			}

			$this->__testObject->autoFixtures = false;
			$this->getFixtures($model, false);
		}

		/**
		 * @brief the setup method for model tests
		 *
		 * This will load up all the fixtures that are requrired
		 *
		 * @param string $model the model to test
		 *
		 * @return
		 */
		public function behavior($model = null){
			if(!App::import('Behavior', $model)){
				throw new AppTestException(sprintf('Unable to load behavior: %s', $model));
				return false;
			}

			foreach($this->__testObject->setup['models'] as $model){
				$this->model($model);
			}
		}

		/**
		 * @brief the setup method for model tests
		 *
		 * This will load up all the fixtures that are requrired
		 *
		 * @param string $model the model to test
		 *
		 * @return
		 */
		public function controller($controller = null){
			if(!App::import('Controller', $controller)){
				throw new AppTestException(sprintf('Unable to load model: %s', $controller));
				return false;
			}

			$this->__testObject->autoFixtures = false;
			$this->getFixtures(Inflector::classify($controller), false);
		}

		/**
		 * @brief get the fixtures
		 *
		 * This method will first get any required fixtures from events for plugins
		 * that need to load up fixtures regardless. Any fixtures you have specifically
		 * loaded will then be setup. After that any fixtures for the model that is being
		 * tested will be loaded and all dependencies that are found. Finally any
		 * replacements that you need doing are done.
		 *
		 * replacements:
		 *	you can replace the default fixture that is loaded automatically with
		 *	your own. too do this you just pass the array with (oldfixture => newfixture).
		 *	this is used when you need to load up your own data instead of some core
		 *	data for example.
		 *
		 * @access public
		 *
		 * @param string $model the model that is being loaded
		 * @param bool $load true will insert data, false will only load up fixtures
		 *
		 * @return void
		 */
		public function getFixtures($model = null, $load = false){
			$this->fixturesFromEvents();
			$this->getRequestsedFixtures();

			if($model){
				$this->fixturesForModel($model);
			}

			$this->getRequestsedFixtures('replace');


			$CakeTestFixture = new CakeTestFixture();
			$CakeTestFixture->drop($this->__testObject->db);

			$this->loadFixtures($this->__fixturesToLoad, $load);
		}

		/**
		 * @brief load up some fixtures from events
		 *
		 * There are some things that are always loaded up in infinitas like
		 * configs, routes and modules. This gives such plugins a chance to load
		 * fixtures without having to do it manually when writing tests.
		 *
		 * @access public
		 *
		 * @return array the array of fixtures
		 */
		public function fixturesFromEvents(){
			$autoFixtures = EventCore::trigger($this, 'getRequiredFixtures');

			foreach($autoFixtures['getRequiredFixtures'] as $plugin => $fixtureSet){
				$this->__autoLoaded[$plugin] = $fixtureSet;
				$this->__fixturesToLoad = array_merge($this->__fixturesToLoad, $fixtureSet);
			}

			return true;
		}

		/**
		 * @breif load up any fixtures for the main model and its dependencies
		 *
		 * @access public
		 *
		 * @param string $pluginModel Plugin.Model to load.
		 * 
		 * @return bool true on success false on fail.
		 */
		public function fixturesForModel($pluginModel = null, $as = null){
			if(!$pluginModel || (in_array($pluginModel, $this->__fixturesToLoad) && !$as)){
				return false;
			}

			list($plugin, $model) = pluginSplit($pluginModel);

			App::import('Model', $pluginModel);

			if(!class_exists($model)){
				throw new AppTestException(sprintf('Unable to load model: %s', $model));
				return false;
			}

			if($as){
				$this->__fixturesToLoad[] = $as;
			}
			else{
				$this->__fixturesToLoad[] = $pluginModel;
			}

			$relations = array('hasOne', 'hasMany', 'belongsTo', 'hasAndBelongsToMany');
			$vars = get_class_vars($model);

			if($vars['useTable'] === false){
				return false;
			}
			
			foreach($relations as $relationType){
				if(!isset($vars[$relationType]) || empty($vars[$relationType])){
					continue;
				}

				foreach($vars[$relationType] as $relation => $config){
					if(is_array($config) && isset($config['className'])){
						$this->fixturesForModel($config['className']);
					}
					else{
						$this->fixturesForModel($config);
					}

				}
			}			
		}

		/**
		 * @brief load up any explicitly set fixtures
		 *
		 * @access public
		 *
		 * @return true
		 */
		public function getRequestsedFixtures($type = 'do'){
			foreach($this->__testObject->setup['fixtures'][$type] as $k => $pluginModel){
				if($type == 'replace'){
					foreach($this->__fixturesToLoad as $i => $fixture){
						if($fixture == $k){
							unset($this->__fixturesToLoad[$i]);
						}
					}
					
					$this->fixturesForModel($k, $pluginModel);
				}
				
				$this->fixturesForModel($pluginModel);
			}

			return true;
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
			if(!is_array($behaviors)) {
				$behaviors = array($behaviors);
			}

			foreach($behaviors as $behavior) {
				list($plugin, $name) = pluginSplit($behavior);
				$class = $name . 'Behavior';
				$ModelBehavior = new ModelBehavior();
				if(ClassRegistry::isKeySet($class)) {
					ClassRegistry::removeObject($class);
					if(!empty($plugin)) {
						ClassRegistry::removeObject($plugin . '.' . $class);
					}

					foreach(ClassRegistry::keys() as $key) {
						$instance = ClassRegistry::getObject($key);

						if(property_exists($instance, 'Behaviors')) {
							$instance->Behaviors->{$name} = $ModelBehavior;
						}
					}
				}

				ClassRegistry::addObject($class, $ModelBehavior);
				if(!empty($plugin)) {
					ClassRegistry::addObject($plugin . '.' . $class, $ModelBehavior);
				}
			}
		}

		/**
		 * @brief load some fixtures up
		 *
		 * This wrapps up fixture inserts with a transaction so that it will be
		 * a bit quicker. Also saves a copy of the fixture insert log for later
		 * and stops logging all the fixtures so that the sql log is cleaner
		 *
		 * @access public
		 *
		 * @param array $fixtures list of fixtures to load
		 * @param bool $load if false the fixtures are just loaded, if true they are created
		 *
		 * @return void
		 */
		public function loadFixtures($fixtures = array(), $load = false){
			$start = microtime(true);
			$fixtureQueries = $this->__testObject->db->_queriesLog;

			if($load){
				$started = $this->__testObject->db->begin($this);
			}

			if(empty($fixtures)){
				$fixtures = $this->__fixturesToLoad;
			}

			foreach($fixtures as $fixture){
				$fixture = $this->loadFixture($fixture);
				if($load){
					$this->__runInserts($fixture);
				}
			}

			if(isset($started) && $started){
				$this->__testObject->db->commit($this);
			}

			if(!$this->__fixtureDbLog){
				$this->__fixtureDbLog = $this->__testObject->db->_queriesLog;
				$this->__testObject->db->_queriesLog = array();
			}

			$this->__fixtureInsertsLog[] = array(
				'query' => sprintf('RUNNING FIXTURE INSERTS #%d', count($this->__fixtureInsertsLog)),
				'error' => '',
				'affected' => count($this->__fixtureDbLog),
				'numRows' => '',
				'took' => round(microtime(true) - $start, 3)
			);
			$this->__testObject->db->_queriesLog = $fixtureQueries;
			unset($fixtures, $fixture);
		}

		/**
		 * @brief load up a specific fixture
		 *
		 * This will take a passed fixture and load it up
		 *
		 * @access public
		 *
		 * @param string $fixture the name of the fixture to load up
		 *
		 * @return mixed the loaded fixture
		 */
		public function loadFixture($fixture = null, $load = true){
			if(!$fixture){
				return false;
			}
			
			if(is_array($fixture)){
				$this->loadFixtures($fixture);
			}

			$file = App::pluginPath(current(pluginSplit($fixture))) . 'tests' . DS . 'fixtures' . DS . $this->__modelNameToFixtureFile($fixture);
			if(!is_readable($file)){
				throw new AppTestException(sprintf('Fixture "%s" was not found (%s)', $fixture, $file));
				return false;
			}

			require_once($file);
			list($plugin, $model) = pluginSplit($fixture);
			
			$fixtureClass = Inflector::camelize($model) . 'Fixture';
			$cakeFixtureName = $this->__modelNameToFixtureName($fixture);

			$this->__testObject->_fixtures[$cakeFixtureName] = new $fixtureClass($this->__testObject->db);
			$this->__testObject->_fixtureClassMap[Inflector::camelize($model)] = $cakeFixtureName;

			return $this->__testObject->_fixtures[$cakeFixtureName];
		}

		/**
		 * @brief run the fixture inserts for the passed fixture object.
		 *
		 * This will run the drop/create/insert for the passed fixture
		 *
		 * @acces private
		 *
		 * @param object $fixture the fixture object to run
		 */
		private function __runInserts($fixture){
			$started = $this->__testObject->db->begin($this);
				
			$table = $this->__testObject->db->config['prefix'] . $fixture->table;
			
			if(!in_array($table, $this->__testObject->db->listSources())) {
				$fixture->create($this->__testObject->db);
			}
			
			$fixture->truncate($this->__testObject->db);
			$fixture->insert($this->__testObject->db);

			if($started){
				$this->__testObject->db->commit($this);
			}
		}

		/**
		 * @brief convert a model name to a fixture file name
		 *
		 * Convert SomePlugin.SomeModel into some_model_fixture.php
		 *
		 * @access private
		 *
		 * @param string $fixture the name to convert
		 * @param string $prefix optional prefix plugin, app, core
		 *
		 * @return string the converted name
		 */
		private function __modelNameToFixtureFile($fixture = null){
			if(!$fixture){
				return $fixture;
			}

			list($plugin, $model) = pluginSplit($fixture);

			return Inflector::underscore($model) . '_fixture.php';
		}

		/**
		 * @brief convert Plugin.Model to a cake fixture name
		 *
		 * @access public
		 *
		 * @param sting $fixture the name of the fixture
		 * @param stirng $prefix one of the cake fixture prefixes (core, app, plugin)
		 *
		 * @return string the cake fixture name
		 */
		private function __modelNameToFixtureName($fixture = null, $prefix = 'plugin'){
			if(!$fixture){
				return $fixture;
			}

			return $prefix . '.' . Inflector::underscore($fixture);
		}

		/**
		 * @brief public method to access the private property
		 *
		 * @return array of items that were loaded using EventCore::tringger()
		 */
		public function getAutoloaded(){
			return $this->__autoLoaded;
		}

		/**
		 * @brief public method to access the private property
		 *
		 * @return array
		 */
		public function getFixtureInsertsLog(){
			return $this->__fixtureInsertsLog;
		}

		/**
		 * @brief public method to access the private property
		 *
		 * @return array
		 */
		public function getFixtureDbLog(){
			return $this->__fixtureDbLog;
		}

		public function startCase(){
			echo sprintf('<div style="border: 2px solid #003D4C; padding: 5px; margin-top:5px; margin-bottom:5px">', $this->prettyTestClass());

			$autoLoaded = $this->getAutoloaded();
			if($autoLoaded){
				ksort($autoLoaded);

				$allFixtures = array_flip($this->__testObject->_fixtureClassMap);
				
				$autoEvents = array();
				foreach($autoLoaded as $plugin => $fixtures){
					foreach($fixtures as $f){
						if(isset($allFixtures[$this->__modelNameToFixtureName($f)])){
							unset($allFixtures[$this->__modelNameToFixtureName($f)]);
						}
					}
					
					$autoEvents[] = sprintf('<li><span style="width:150px;">%s:</span> %s</li>', Inflector::humanize($plugin), implode(', ', $fixtures));
				}

				$lazy = array();
				foreach($allFixtures as $fixture => $model){
					list($plugin, $model) = pluginSplit(str_replace('plugin.', '', $fixture));
					$lazy[Inflector::classify($plugin)][] = Inflector::classify($model);
				}

				$autoLazy = array();
				foreach($lazy as $plugin => $fixtures){
					$autoLazy[] = sprintf('<li><span style="width:150px;">%s:</span> %s</li>', Inflector::humanize($plugin), implode(', ', $fixtures));
				}

				$autoEvents = sprintf('<h3>Event Loaded:</h3><ul>%s</ul>', implode('', $autoEvents));
				$autoLazy = sprintf('<h3>Lazy Loaded:</h3><ul>%s</ul>', implode('', $autoLazy));
				
				$dbConfig = new DATABASE_CONFIG();
				$connectionDiff = array_diff($this->__testObject->db->config, $dbConfig->default);
				$connection = sprintf('<div>%s</div>', $this->__testObject->db->configKeyName);
				
				$class = '';
				if(array_merge(array('login' => '', 'password' => '', 'database' => ''), $connectionDiff) !== $connectionDiff) {
					$connection = sprintf('<pre class="cake-debug">%s Seems to be using your production database</pre>', $this->__testObject->db->configKeyName);
				}
				
				echo sprintf(
					'<div style="background-color: green; color: #ffffff; clear:both; overflow:auto;">'.
						'<div style="width:49%%; float:left;">%s</div>'.
						'<div style="width:49%%; float:left;">%s</div></br>%s</div>',
					$autoEvents,
					$autoLazy,
					sprintf('<h3>DB Config</h3>%s', $connection)
				);
			}
		}

		/**
		 * @brief called at the end of a tests, manipulate some sql queries
		 *
		 * In the method that loads up tests data it stops queries showing in the
		 * log so that you can find the actual queries easier. This method appends
		 * one set of fixture inserts to the log at the end so that its still shows
		 *
		 * all cakes queries like 'SELECT CHARACTER_SET_NAME' are also filtered
		 * out so that you are left with a clean list of sql that is easier to
		 * debug
		 *
		 * @access public
		 *
		 * @return void
		 */
		public function endCase(){
			foreach($this->__testObject->db->_queriesLog as $i => $query){
				$unset = strstr($query['query'], 'SHOW ') ||
						strstr($query['query'], 'SELECT CHARACTER_SET_NAME');
				if($unset) {
					unset($this->__testObject->db->_queriesLog[$i]);
				}
			}

			$this->__testObject->db->_queriesLog = array_merge($this->getFixtureInsertsLog(), array_values($this->__testObject->db->_queriesLog));
			$this->__testObject->db->_queriesLog = array_merge($this->__testObject->db->_queriesLog, $this->getFixtureDbLog());

			$total = 0;
			foreach($this->__testObject->data as $data){
				$total += $data['total'];
			}

			echo sprintf(
				'<div style="padding: 8px; margin: 1em 0; background-color: green; color: white;">'.
					'Total time on tests: <span style="color:#ffdd00;">[%ss]</span></div></div>',
				$total
			);
		}

		public function startTest($method){
			$this->__testObject->data[$method] = array();
			$this->__testObject->data[$method]['started'] = microtime(true);
		}

		public function endTest($method){
			$this->__testObject->data[$method]['ended'] = microtime(true);
			$this->__testObject->data[$method]['total'] = round($this->__testObject->data[$method]['ended'] - $this->__testObject->data[$method]['started'], 3);
		}

		public function prettyTestClass(){
			return Inflector::humanize(Inflector::underscore(str_replace('TestCase', '', get_class($this->__testObject))));
		}

		public function prettyTestMethod($method){
			return Inflector::humanize(Inflector::underscore(substr($method, 4)));
		}

		public function outputArray($array, $level = 1){
			$out = "array(\n";
				foreach($array as $k => $v){
					$out .= str_repeat("\t", $level);
					if(is_array($v)){
						$value = $this->outputArray($v, $level + 1);
						$out .= "'$k' => $value)";
					}
					else if(is_int($v)) {
						$out .= "'$k' => $v";
					}
					else {
						$out .= "'$k' => '$v'";
					}
					$out .= ",\n";
				}
			if($level == 1){
				$out .= ");\n";
			}

			pr($out);
		}
	}

	/**
	 * @brief Exception class for AppTest
	 */
	class AppTestException extends Exception {
		public function error(){
			pr('Error:' . $this->message);
			pr(sprintf('[%d] %s', $this->line, $this->file));

			pr($this);
		}
	}
