<?php
	class InfinitasBehavior extends ModelBehavior {
		var $_defaults = array();

		var $blockedPlugins = array(
			'DebugKit',
			'Filter',
			'Libs'
		);

		/**
		 * JSON error messages.
		 *
		 * Set up some errors for json.
		 * @access public
		 */
		var $_json_messages = array(
		    JSON_ERROR_NONE      => 'No error',
		    JSON_ERROR_DEPTH     => 'The maximum stack depth has been exceeded',
		    JSON_ERROR_CTRL_CHAR => 'Control character error, possibly incorrectly encoded',
		    JSON_ERROR_SYNTAX    => 'Syntax error',
		);

		/**
		 * error messages from checking json
		 */
		var $__jsonErrors = array();

		function setup(&$Model, $config = null) {
			if (is_array($config)) {
				$this->settings[$Model->alias] = array_merge($this->_defaults, $config);
			} else {
				$this->settings[$Model->alias] = $this->_defaults;
			}
		}

		/**
		 * Get all the tables
		 *
		 * @param array $Model
		 * @param string $connection the connection to use for getting tables.
		 *
		 * @return array list of tables.
		 */
		function getTables(&$Model, $connection = 'default'){
			$this->db    = ConnectionManager::getDataSource($connection);
			$tables      = $this->db->query('SHOW TABLES;');
			$databseName = $this->db->config['database'];

			unset($this->db);

			return Set::extract('/TABLE_NAMES/Tables_in_'.$databseName, $tables);
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
		function getTablesByField(&$Model, $connection = 'default', $field = null){
			if (!$field) {
				return false;
			}

			$tableNames = $this->getTables($Model, $connection);
			$return = array();

			$this->db    = ConnectionManager::getDataSource($connection);

			foreach($tableNames as $table ){
				$fields = $this->db->query('DESCRIBE '.$table);
				$fields = Set::extract('/COLUMNS/Field', $fields);

				if (in_array($field, $fields)) {
					$_table = explode('_', $table, 2);

					$plugin = ucfirst(count($_table) == 2 ? $_table[0] : '');
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
		 * convert json data.
		 *
		 * takes a string and returns some data. can pass return false for validation.
		 *
		 * @params string $data a string of json data.
		 * @params array $config the params to pass to json_decode (assoc && depth)
		 * @params bool $return will return the array/object by default but can be set to false to just check its valid.
		 */
		function getJson(&$Model, $data = null, $config = array(), $return = true){
			if (!$data) {
				$this->_errors[] = 'No data for json';
				return false;
			}

			$defaultConfig = array('assoc' => true);
			$config = array_merge($defaultConfig, (array)$config);
			$json = json_decode((string)$data, $config['assoc']);

			if (!$json) {
				if (function_exists('json_last_error')) {
					$Model->__jsonErrors[] = $this->_json_messages[json_last_error()];
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
		 * get only the first dimention out of the array. used in router and configs
		 * to stop multi dimention arrays being passed to methods that will not
		 * handle them.
		 *
		 * @param object $Model the model object
		 * @param array $array the array to check
		 *
		 * @return empty array on error or single dimention array.
		 */
		function singleDimentionArray(&$Model, $array = array()){
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
		function getPlugins($skipBlocked = true){
			$plugins = Configure::listObjects('plugin');

			if ($skipBlocked === false) {
				return $plugins;
			}

			foreach($plugins as $plugin){
				if (!in_array($plugin, $this->blockedPlugins)){
					$return[Inflector::underscore($plugin)] = $plugin;
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
		function getControllers(&$Model, $plugin){
			$list = App::objects(
				'controller',
				array(App::pluginPath($plugin).'controllers'.DS),
				false
			);

			foreach($list as $controller){
				$controllers[$controller] = $controller;
			}

			return $controllers;
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
		function getActions(&$Model, $plugin, $controller){
			App::import('Controller', $plugin.'.'.$controller);

			$list = get_class_methods($controller.'Controller');
			$ignore = $this->_filterMethods();

			$actions = array();
			foreach((array)$list as $action){
				if (in_array($action, $ignore) || substr($action, 0, 1) == '_'){
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
		function getList(&$Model, $plugin = null, $model = 'AppModel', $method = null, $conditions = array()){
			if (!$model) {
				return 'Config error!';
			}

			$class = null;

			if (!$plugin) {
				$class = Infilector::classify($model);
			}

			if (!$class) {
				$class = Infilector::classify($plugin).'.'.Infilector::classify($model);
			}

			if ($model == 'AppModel') {
				App::import('Appmodel', array('table' => false));
				$this->Model = new AppModel();
			}

			if (!isset($this->AppModel) && $model != 'AppModel') {
				$this->Model = ClassRegistry::init($class);
			}

			if ($method && method_exists($this->Model, $method)) {
				return $this->Model->{$method}($conditions);
			}

			if (method_exists($this->Model, '_getList')) {
				return $this->Model->_getList($conditions);
			}

			return $this->find('list');
		}

		function validateJson(&$Model, $data = null, $field = null){
			if (!$data) {
				return false;
			}

			return $this->getJson(&$Model, current($data), array(), false);
		}
	}
?>