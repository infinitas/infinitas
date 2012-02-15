<?php
	class InfinitasBehavior extends ModelBehavior {
		/**
		 * defaults for the model
		 *
		 * @var array
		 * @access protected
		 */
		protected $_defaults = array();

		/**
		 * plugins that will be removed from the plugin list
		 *
		 * @var array
		 * @access public
		 */
		public $blockedPlugins = array(
			'DebugKit',
			'Filter',
			'Libs'
		);

		/**
		 * JSON error messages.
		 *
		 * Set up some errors for json.
		 * @access protected
		 */
		protected  $_jsonMessages = array(
			JSON_ERROR_NONE	  => 'No error',
			JSON_ERROR_DEPTH	 => 'The maximum stack depth has been exceeded',
			JSON_ERROR_CTRL_CHAR => 'Control character error, possibly incorrectly encoded',
			JSON_ERROR_SYNTAX	=> 'Syntax error',
		);

		/**
		 * error messages from checking json
		 *
		 * @var array
		 * @access private
		 */
		private $__jsonErrors = array();

		public function setup($Model, $config = null) {
			if (is_array($config)) {
				$this->settings[$Model->alias] = array_merge($this->_defaults, $config);
			}

			else {
				$this->settings[$Model->alias] = $this->_defaults;
			}
		}

		/**
		 * Convinience method to get active records
		 *
		 * @var $Model object the current model
		 * @var $active mixed int 1 / 0 or true false
		 * @var $conditions normal find conditions
		 *
		 * @return the count of records
		 */
		public function getActive($Model, $active = true, $conditions = array()){
			$conditions = array_merge(
				array($Model->alias.'.active' => (int)(bool)$active),
				(array)$conditions
			);
			return $Model->find(
				'count',
				array(
					'conditions' => $conditions
				)
			);
		}

		/**
		 * Get all the tables
		 *
		 * @param array $Model
		 * @param string $connection the connection to use for getting tables.
		 *
		 * @return array list of tables.
		 */
		function getTables($Model, $connection = 'default'){
			$this->db = ConnectionManager::getDataSource($connection);
			if(!$this->db){
				return false;
			}
			$tables = Cache::read($connection.'_tables', 'core');
			if($tables != false){
				return $tables;
			}

			$tables	  = $this->db->query('SHOW TABLES;');
			$databseName = $this->db->config['database'];

			unset($this->db);

			$tables = Set::extract('/TABLE_NAMES/Tables_in_'.$databseName, $tables);
			Cache::write($connection.'_tables', $tables, 'core');

			return $tables;
		}

		public function getTablesAdvanced($Model, $connection = 'default'){
			$this->db = ConnectionManager::getDataSource($connection);
			if(!$this->db){
				return false;
			}

			$tables = Cache::read($connection.'_tables_advanced', 'core');
			if($tables != false){
				return $tables;
			}

			$tables = $this->db->listDetailedSources();
			sort($tables);

			return $tables;
		}

		/**
		 * Get tables with a certain field.
		 *
		 * Gets a list of tables with the selected field in the selected connection.
		 *
		 * @param mixed $Model
		 * @param string $connection the connection to use when finding tables
		 * @param mixed $field the tables with this field are returned
		 *
		 * @return false when no field set, else array of tables with model/plugin.
		 */
		function getTablesByField($Model, $connection = 'default', $field = null){
			if (!$field) {
				return false;
			}

			$tableNames = $this->getTables($Model, $connection);
			$return = array();

			$this->db	= ConnectionManager::getDataSource($connection);

			foreach($tableNames as $table ){
				$fields = $this->db->query('DESCRIBE '.$table);
				$fields = Set::extract('/COLUMNS/Field', $fields);

				if (in_array($field, $fields)) {
					$_table = explode('_', $table, 2);

					$plugin = ucfirst((count($_table) == 2) ? $_table[0] : '');
					$plugin = ($plugin == 'Core') ? 'Management' : $plugin;

					$return[] = array(
						'plugin' => $plugin,
						'model'  => Inflector::classify(isset($_table[1]) ? $_table[1] : $_table[0]),
						'table'  => $table
					);
				}
			}
			return $return;
		}

		/**
		 * seconds -> text
		 * @var array
		 * @access private
		 */
		private $__frequency = array(
			1113144960 => 'yearly3',
			1131449600 => 'yearly2',
			31449600 => 'yearly',
			2620800 => 'monthly',
			604800 => 'weekly',
			86400 => 'daily',
			3600 => 'hourly',
			600 => 'always',
		);

		private function __getDateField($Model){
			if($Model->hasField('created')){
				return 'created';
			}

			else if($Model->hasField('modified')){
				return 'modified';
			}

			return null;
		}

		/**
		 * figure out a rough guide to how often the fields change
		 *
		 * always, hourly, daily, weekly, monthly, yearly, never
		 */
		public function getChangeFrequency($Model){
			$field = $this->__getDateField($Model);

			if(!$field){
				return 'weekly';
			}

			$data = $Model->find(
				'all',
				array(
					'fields' => array(
						'MAX(`'.$Model->alias.'`.`'.$field.'`) as newest',
						'MIN(`'.$Model->alias.'`.`'.$field.'`) as oldest',
						'COUNT(*) as count'
					),
					'order' => array(
						$Model->alias.'.'.$field => 'asc'
					),
					'contain' => false,
					'limit' => 1
				)
			);

			if(!isset($data[0][0])){
				return 'weekly';
			}

			$data = $data[0][0];
			if($data['count'] == 0)  {
				$data['count'] = 1;
			}

			$seconds = strtotime($data['newest']) - strtotime($data['oldest']);
			$timeBetweenChanges = $seconds / $data['count'];
			$timeSinceLast = time() - strtotime($data['newest']);

			$average = ($timeSinceLast + $timeBetweenChanges) / 2;

			foreach($this->__frequency as $time => $frequency){
				//pr($time);
				if($average > $time){
					return $frequency;
				}
			}
		}

		/**
		 * get the newest row from the selected model
		 */
		public function getNewestRow($Model){
			$field = $this->__getDateField($Model);

			if(!$field){
				return false;
			}

			$row = $Model->find(
				'first',
					array(
						'fields' => array(
							$Model->alias.'.'.$field
						),
						'order' => array(
							$Model->alias.'.'.$field => 'desc'
						),
						'limit' => 1,
						'contain' => false
					)
			);

			if(empty($row)){
				return false;
			}

			return $row[$Model->alias][$field];

		}

		/**
		 * convert json data.
		 *
		 * takes a string and returns some data. can pass return false for validation.
		 *
		 * @params string $data a string of json data.
		 * @params array $config the params to pass to json_decode (assoc && depth)
		 * @params bool $return will return the array/object by default but can be set to false to just check its valid.
		 */
		function getJson($Model, $data = null, $config = array(), $return = true){
			if (!$data) {
				$this->_errors[] = 'No data for json';
				return false;
			}

			$defaultConfig = array('assoc' => true);
			$config = array_merge($defaultConfig, (array)$config);
			$json = json_decode((string)$data, $config['assoc']);

			if (!$json) {
				if (function_exists('json_last_error')) {
					$Model->__jsonErrors[] = $this->_jsonMessages[json_last_error()];
				}
				else{
					$Model->__jsonErrors[] = 'Json seems invalid';
				}
				return false;
			}

			if ($return) {
				return $json;
			}

			unset($json);
			return true;
		}

		/**
		 * Recursive json conversion.
		 *
		 * Takes an array with k => v format and gets json into array format.
		 *
		 * @param object $Model
		 * @param array $data
		 * @param unknown_type $config
		 * @param unknown_type $return
		 */
		function getJsonRecursive($Model, $data = array(), $config = array()){
			if(!is_array($data)){
				$data = (array)$data;
			}

			foreach($data as $k => $v){
				if(is_array($v)){
					$data[$k] = $this->getJsonRecursive($Model, $v, $config, true);
				}

				if(self::getJson($Model, $v, $config, false)){
					$data[$k] = $this->getJson($Model, $v, $config, true);
				}
			}
			return $data;
		}

		/**
		 * get only the first dimention out of the array. used in router and configs
		 * to stop multi dimention arrays being passed to methods that will not
		 * handle them.
		 *
		 * @param object $Model the model object
		 * @param array $array the array to check
		 *
		 * @return empty array on error or single dimention array.
		 */
		function singleDimentionArray($Model, $array = array()){
			if (empty($array) || !is_array($array)) {
				$this->errors[] = 'Array must be passed to me';
				return array();
			}

			$return = array();

			foreach($array as $k => $v){
				if (is_array($v)) {
					continue;
				}
				$return[$k] = $v;
			}

			return $return;
		}

		/**
		 * Get a list of plugins.
		 *
		 * Just gets a list of plugins and returns them after rempving the plugins
		 * that should not be displayed to the user.
		 *
		 * @return array all the plugins in infinitas.
		 */
		public function getPlugins($skipBlocked = true){
			$plugins = CakePlugin::loaded();

			if ($skipBlocked === false) {
				return $plugins;
			}

			foreach($plugins as $plugin){
				if (!in_array($plugin, $this->blockedPlugins)){
					$return[$plugin] = $plugin;
				}
			}

			return array('' => 'None') + (array)$return;
		}

		/**
		* Get a list of controllers.
		*
		* Checks the passed plugin and returns all the controllers for that
		* plugin, after formating the array to be used in a select box.
		*
		* @param object $Model the currect model
		* @param string $plugin the plugin to search for controllers
		*
		* @return array a list of controllers that were found
		*/
		public function getControllers($Model, $plugin){
			$plugin = Inflector::camelize($plugin);
			$list = App::objects(
				'Controller',
				array(CakePlugin::path($plugin) . 'Controller' . DS),
				false
			);
			$controllers = array();
			foreach($list as $controller){
				if($controller == $plugin . 'AppController') {
					continue;
				}

				$controllers[$controller] = $controller;
			}
			return $controllers;
		}

		/**
		* Get a list of models.
		*
		* Checks the passed plugin and returns all the models for that
		* plugin, after formating the array to be used in a select box.
		*
		* @param object $Model the currect model
		* @param string $plugin the plugin to search for models
		*
		* @return array a list of models that were found
		*/
		public function getModels($Model, $plugin) {
			$plugin = Inflector::camelize($plugin);
			$list = App::objects(
				'Model',
				array(App::pluginPath($plugin) . 'Model' . DS),
				false
			);

			$models = array();
			foreach($list as $model){
				if($model == $plugin . 'AppModel') {
					continue;
				}

				$models[$model] = $model;
			}

			return $models;
		}

		/**
		 * Get a list of actions.
		 *
		 * Checks the passed plugin and controller and returns all the
		 * actions that fall under the match, will filter out some methods. that
		 * should not be called. returns the data after formating the array to
		 * be used in a select box.
		 *
		 * @param object $Model the currect model
		 * @param string $plugin the plugin to search with
		 * @param string $controller the controller to search with
		 */
		public function getActions($Model, $plugin, $controller){
			$package = Inflector::camelize($plugin) . '.Controller';
			$controller = Inflector::camelize($controller) . 'Controller';
			App::uses($controller, $package);

			$list = get_class_methods($controller);
			$ignore = $this->_filterMethods();

			$actions = array();
			foreach((array)$list as $action){
				if (in_array($action, $ignore) || strpos($action, '_') === 0) {
					continue;
				}
				else{
					$actions[$action] = $action;
				}
			}

			return $actions;
		}

		/**
		* actions to filter out
		*
		* This filters out noise actions for the Infinitas::getActions method
		*
		* @return array the stuff that can be ignored
		*/
		function _filterMethods(){
			$ignores = get_class_methods('AppController');
			$dontIgnores = get_class_methods('GlobalActions');

			foreach($ignores as &$ignore){
				if (in_array($ignore, $dontIgnores)) {
					unset($ignore);
				}
			}
			return $ignores;
		}

		/**
		* get a list from the db.
		*
		* seems like a method to call a function in a model via ajax mostly.
		*/
		function getList($Model, $plugin = null, $model = null, $method = null, $conditions = array()){
			$class = null;
			if (!$plugin && $model) {
				$class = Inflector::classify($model);
			}

			if (!$class && ($plugin && $model)) {
				$class = Inflector::camelize($plugin).'.'.Inflector::classify($model);
			}
			$this->Model = !empty($class) ? ClassRegistry::init($class) : $Model;
			if(get_class($this->Model) == 'AppModel' && !$this->Model->useTable){
				return false;
			}

			if ($method && method_exists($this->Model, $method)) {
				return $this->Model->{$method}($conditions);
			}

			if (method_exists($this->Model, '_getList')) {
				return $this->Model->_getList($conditions);
			}

			return $this->Model->find('list', array('conditions' => $conditions));
		}

		/**
		 * @deprecated
		 *
		 * @param <type> $Model
		 * @param <type> $data
		 * @param <type> $field
		 * @return <type>
		 */
		function validateJsonDepriciated($Model, $data = null, $field = null){
			if (!$data) {
				return false;
			}
			if(!is_array($data)){
				$data = array($data);
			}

			return $this->getJson($Model, current($data), array(), false);
		}

		/**
		 * Generate a list of possible first characters to filter by. letters,
		 * numbers and special chars
		 * @param object $Model the model object
		 * @return array key -> value array were a value of true means there is a row that matches
		 */
		public function getLetterList($Model){
			$Model->virtualFields['letters'] = sprintf('LOWER(LEFT(%s.%s, 1))', $Model->alias, $Model->displayField);

			$found = $Model->find(
				'list',
				array(
					'fields' => array(
						'letters',
						'letters'
					),
					'group' => array(
						'letters'
					)
				)
			);

			$return['#'] = false;
			$return['?'] = false;
			$return = array_merge(
				$return,
				array_combine(range('a', 'z'), array_fill(0, 26, false))
			);

			foreach($found as $value){
				switch($value){
					case is_int($value):
						$return['#'] = 1;
						break;

					case isset($return[$value]):
						$return[$value] = 1;
						break;

					default:
						$return['?'] = 1;
						break;
				}
			}

			unset($found);

			return $return;
		}

		/**
		 * @brief get the root node of a model according to some condition
		 *
		 * This method is handy if you use a wrapper for the MPTT data that acts
		 * as the sole root record.
		 *
		 * @access public
		 *
		 * @param object $Model the model doing the find
		 * @param array $conditions any conditions for the find
		 *
		 * @return mixed the string/int id of the root record or false on error
		 */
		public function getRootNode($Model, $conditions = array()){
			if(!is_callable(array($Model, 'generateTreeList'))){
				return false;
			}

			$data = $Model->generateTreeList($conditions, null, null, '@', -1);

			$roots = array();
			foreach($data as $id => $name){
				if(substr($name, 0, 1) != '@'){
					$roots[] = $id;
				}
			}

			unset($data);

			if(count($roots) != 1){
				return false;
			}

			return $roots[0];
		}

		/**
		 * @brief Returns the minimum of fields required to generate a link
		 *
		 * This method is handy to generate links for data in table rows
		 *
		 * @access public
		 *
		 * @param object $Model the model doing the find
		 * @param array $conditions any conditions for the find
		 *
		 * @return mixed the string/int id of the root record or false on error
		 */
		public function getLinkData($Model, $id = null) {
			if(is_null($id)) {
				$id = $Model->id;
			}

			$options = array(
				'conditions' => array(
					$Model->alias . '.' . $Model->primaryKey => $id
				),
				'fields' =>	array($Model->primaryKey, $Model->displayField)
			);

			return $Model->find('first', $options);
		}

		/**
		 * @brief check if behaviors should / can be auto attached
		 *
		 * Adding a behavior to the actsAs will stop the behavior from auto loading
		 * if it is setup to autoload in the events. There are some issues with
		 * re attaching behaviors when they are already attached
		 *
		 * @code
		 * // auto load FooBehavior to any model if its not attached or in actsAs
		 *	if($Model->shouldAutoAttachBehavior('Foo')) {
		 *		$Model->Behaviors->attach('Foo');
		 *	}
		 *
		 * // auto load FooBehavior to any model with the `bar` field if its not attached or in actsAs
		 *	if($Model->shouldAutoAttachBehavior('Foo', array('bar'))) {
		 *		$Model->Behaviors->attach('Foo');
		 *	}
		 * @endcode
		 *
		 * @access public
		 *
		 * @param Model $Model the model the check is being done on
		 * @param string $behavior the name of the beahvior to check
		 * @param array $onlyWithFields a list of fields that should be in the table for it to auto attach
		 *
		 * @return type
		 */
		public function shouldAutoAttachBehavior($Model, $behavior = null, $onlyWithFields = array()) {
			if(!is_subclass_of($Model, 'Model')) {
				return false;
			}

			$schema = $Model->schema();
			if(empty($schema)) {
				return false;
			}

			if($behavior === null) {
				return true;
			}


			if(!isset($Model->actsAs[$behavior]) && !in_array($behavior, $Model->actsAs) && !$Model->Behaviors->enabled($behavior)) {
				$should = true;
				if(!empty($onlyWithFields)) {
					foreach((array)$onlyWithFields as $field) {
						$should = $should && $Model->hasField($field);
					}
				}

				return $should;
			}

			return false;
		}
	}