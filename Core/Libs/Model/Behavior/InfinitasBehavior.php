<?php
/**
 * InfinitasBehavior
 *
 * @package Infinitas.Libs.Model.Behavior
 */

/**
 * InfinitasBehavior
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Libs.Model.Behavior
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.6a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

class InfinitasBehavior extends ModelBehavior {
/**
 * defaults for the model
 *
 * @var array
 */
	protected $_defaults = array();

/**
 * plugins that will be removed from the plugin list
 *
 * @var array
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
 */
	private $__jsonErrors = array();

/**
 * Behavior setup method
 *
 * @param Model $Model
 * @param array $config
 *
 * @return void
 */
	public function setup(Model $Model, $config = null) {
		$this->settings[$Model->alias] = $this->_defaults;
		if (is_array($config)) {
			$this->settings[$Model->alias] = array_merge($this->settings[$Model->alias], $config);
		}
	}

/**
 * Convinience method to get count of active records
 *
 * @var $Model object the current model
 * @var $active mixed int 1 / 0 or true false
 * @var $conditions normal find conditions
 *
 * @return integer
 */
	public function getActive(Model $Model, $active = true, $conditions = array()) {
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
 * @return array
 */
	public function getTables(Model $Model, $connection = 'default') {
		$this->db = ConnectionManager::getDataSource($connection);
		if (!$this->db) {
			return false;
		}
		$tables = Cache::read($connection . '_tables', 'core');
		if ($tables != false) {
			return $tables;
		}

		$tables	  = $this->db->query('SHOW TABLES;');
		$databseName = $this->db->config['database'];

		unset($this->db);

		$tables = Set::extract('/TABLE_NAMES/Tables_in_' . $databseName, $tables);
		Cache::write($connection.'_tables', $tables, 'core');

		return $tables;
	}

/**
 * Get detailed table information
 *
 * @param Model $Model the model being used
 * @param string $connection the connection to use
 *
 * @return boolean
 */
	public function getTablesAdvanced(Model $Model, $connection = 'default') {
		$this->db = ConnectionManager::getDataSource($connection);
		if (!$this->db) {
			return false;
		}

		$tables = Cache::read($connection.'_tables_advanced', 'core');
		if ($tables != false) {
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
 * @return array
 */
	public function getTablesByField(Model $Model, $connection = 'default', $field = null) {
		if (!$field) {
			return false;
		}

		$tableNames = $this->getTables($Model, $connection);
		$return = array();

		$this->db	= ConnectionManager::getDataSource($connection);

		foreach ($tableNames as $table ) {
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
 *
 * @var array
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

/**
 * Get the models date field
 *
 * @param Model $Model the model being used
 *
 * @return string
 */
	private function __getDateField(Model $Model) {
		if ($Model->hasField('created')) {
			return 'created';
		}

		else if ($Model->hasField('modified')) {
			return 'modified';
		}
	}

/**
 * figure out a rough guide to how often the fields change
 *
 * always, hourly, daily, weekly, monthly, yearly, never
 *
 * @param Model $Model the model being used
 *
 * @return string
 */
	public function getChangeFrequency(Model $Model) {
		$field = $this->__getDateField($Model);

		if (!$field) {
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

		if (!isset($data[0][0])) {
			return 'weekly';
		}

		$data = $data[0][0];
		if ($data['count'] == 0)  {
			$data['count'] = 1;
		}

		$seconds = strtotime($data['newest']) - strtotime($data['oldest']);
		$timeBetweenChanges = $seconds / $data['count'];
		$timeSinceLast = time() - strtotime($data['newest']);

		$average = ($timeSinceLast + $timeBetweenChanges) / 2;

		foreach ($this->__frequency as $time => $frequency) {
			if ($average > $time) {
				return $frequency;
			}
		}
	}

/**
 * get the newest row from the selected model
 *
 * Will check if the model has fields like created / modified and get the newest
 * records primary field
 *
 * @return string
 */
	public function getNewestRow(Model $Model) {
		$field = $this->__getDateField($Model);

		if (!$field) {
			return false;
		}

		$row = $Model->find(
			'first',
			array(
				'fields' => array(
					$Model->alias . '.' . $field
				),
				'order' => array(
					$Model->alias . '.' . $field => 'desc'
				),
				'contain' => false
			)
		);

		if (empty($row)) {
			return false;
		}

		return $row[$Model->alias][$field];
	}

/**
 * convert json data.
 *
 * takes a string and returns some data. can pass return false for validation.
 *
 * @param Model $Model the model being used
 * @param string $data a string of json data.
 * @param array $config the params to pass to json_decode (assoc && depth)
 * @param boolean $return will return the array/object by default but can be set to false to just check its valid.
 *
 * @return array|object|boolean
 */
	public function getJson(Model $Model, $data = null, $config = array(), $return = true) {
		if (!$data) {
			$this->_errors[] = 'No data for json';
			return false;
		}
		if (!is_string($data)) {
			$this->_errors[] = 'Data is not a string';
			return false;
		}

		$defaultConfig = array('assoc' => true);
		$config = array_merge($defaultConfig, (array)$config);

		$json = json_decode($data, $config['assoc']);

		if (!$json) {
			if (function_exists('json_last_error')) {
				$Model->__jsonErrors[] = $this->_jsonMessages[json_last_error()];
			} else {
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
 * @param array $config
 *
 * @return array
 */
	public function getJsonRecursive(Model $Model, $data = array(), $config = array()) {
		if (!is_array($data)) {
			$data = (array)$data;
		}

		foreach ($data as $k => $v) {
			if (is_array($v)) {
				$data[$k] = $this->getJsonRecursive($Model, $v, $config, true);
			}

			if (self::getJson($Model, $v, $config, false)) {
				$data[$k] = $this->getJson($Model, $v, $config, true);
			}
		}
		return $data;
	}

/**
 * Get the first dimention of an array
 *
 * get only the first dimention out of the array. used in router and configs
 * to stop multi dimention arrays being passed to methods that will not
 * handle them.
 *
 * @param Model $Model the model object
 * @param array $array the array to check
 *
 * @return array
 */
	public function singleDimentionArray(Model $Model, $array = array()) {
		if (empty($array) || !is_array($array)) {
			$this->errors[] = 'Array must be passed to me';
			return array();
		}

		$return = array();

		foreach ($array as $k => $v) {
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
 * @param boolean $skipBlocked
 *
 * @return array
 */
	public function getPlugins($skipBlocked = true) {
		$plugins = CakePlugin::loaded();

		if ($skipBlocked === false) {
			return $plugins;
		}

		foreach ($plugins as $plugin) {
			if (!in_array($plugin, $this->blockedPlugins)) {
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
 * @param Model $Model the currect model
 * @param string $plugin the plugin to search for controllers
 *
 * @return array
 */
	public function getControllers(Model $Model, $plugin) {
		if (empty($plugin)) {
			return false;
		}

		$plugin = Inflector::camelize($plugin);
		$list = App::objects(
			'Controller',
			array(CakePlugin::path($plugin) . 'Controller' . DS),
			false
		);
		$controllers = array();
		foreach ($list as $controller) {
			if ($controller == $plugin . 'AppController') {
				continue;
			}

			$name = str_replace(array($plugin, 'Controller'), '', $controller);
			if (empty($name)) {
				$name = sprintf('%s - %s', str_replace(array('Controller'), '', $controller), __d(Inflector::underscore($plugin), 'main'));

				$controllers = array_merge(array($controller => $name), $controllers);
				continue;
			}

			$controllers[$controller] = $name;
		}

		return $controllers;
	}

/**
 * Get a list of models.
 *
 * Checks the passed plugin and returns all the models for that
 * plugin, after formating the array to be used in a select box.
 *
 * @param Model $Model the currect model
 * @param string $plugin the plugin to search for models
 *
 * @return array
 */
	public function getModels(Model $Model, $plugin) {
		$plugin = Inflector::camelize($plugin);
		$list = App::objects(
			'Model',
			array(App::pluginPath($plugin) . 'Model' . DS),
			false
		);

		$models = array();
		foreach ($list as $model) {
			if ($model == $plugin . 'AppModel') {
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
 * @param Model $Model the currect model
 * @param string $plugin the plugin to search with
 * @param string $controller the controller to search with
 *
 * @return array
 */
	public function getActions(Model $Model, $plugin, $controller) {
		$package = Inflector::camelize($plugin) . '.Controller';
		$controller = Inflector::camelize($controller);
		App::uses($controller, $package);

		$list = get_class_methods($controller);
		$ignore = $this->_filterMethods();

		$actions = array();
		foreach ((array)$list as $action) {
			if (in_array($action, $ignore) || strpos($action, '_') === 0) {
				continue;
			}
			else{
				if (strstr($action, 'admin')) {
					$actions[__d('infinitas', 'Admin')][$action] = str_replace('admin_', '', $action);
					continue;
				}
				$actions[__d('infinitas', 'Frontend')][$action] = $action;
			}
		}

		return $actions;
	}

/**
 * Get records
 *
 * @param Model $Model
 * @param type $plugin
 * @param type $model
 *
 * @return array
 */
	public function getRecords(Model $Model, $plugin, $model) {
		try {
			$_Model = ClassRegistry::init($plugin . '.' . $model);

			if (!isset($_Model->contentable) || !$_Model->contentable) {
				return $_Model->find('list');
			}

			return $_Model->GlobalContent->find(
				'list',
				array(
					'fields' => array(
						$_Model->GlobalContent->alias . '.foreign_key',
						'GlobalContent.title'
					),
					'conditions' => array(
						$_Model->GlobalContent->alias . '.model' => $plugin . '.' . $model
					)
				)
			);
		}

		catch(Exception $e) {
			return array($e->getMessage());
		}
	}

/**
 * actions to filter out
 *
 * This filters out noise actions for the Infinitas::getActions method
 *
 * @return array
 */
	public function _filterMethods() {
		$ignores = get_class_methods('AppController');
		$dontIgnores = get_class_methods('Controller');

		foreach ($ignores as &$ignore) {
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
 *
 * @param Model $Model The model doing the find
 * @param string $plugin the plugin
 * @param string $model The model
 * @param string $method custom list method
 * @param array $conditions conditions for the find
 *
 * @return array
 */
	public function getList(Model $Model, $plugin = null, $model = null, $method = null, $conditions = array()) {
		$class = null;
		if (!$plugin && $model) {
			$class = Inflector::classify($model);
		}

		if (!$class && ($plugin && $model)) {
			$class = Inflector::camelize($plugin).'.'.Inflector::classify($model);
		}
		$this->Model = !empty($class) ? ClassRegistry::init($class) : $Model;
		if (get_class($this->Model) == 'AppModel' && !$this->Model->useTable) {
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
 * Get letter list
 *
 * Generate a list of possible first characters to filter by. letters,
 * numbers and special chars
 *
 * @param Model $Model the model object
 *
 * @return array
 */
	public function getLetterList(Model $Model) {
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

		foreach ($found as $value) {
			switch($value) {
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
 * get the root node of a model according to some condition
 *
 * This method is handy if you use a wrapper for the MPTT data that acts
 * as the sole root record.
 *
 * @param Model $Model the model doing the find
 * @param array $conditions any conditions for the find
 *
 * @return string
 */
	public function getRootNode(Model $Model, $conditions = array()) {
		if (!is_callable(array($Model, 'generateTreeList'))) {
			return false;
		}

		$data = $Model->generateTreeList($conditions, null, null, '@', -1);

		$roots = array();
		foreach ($data as $id => $name) {
			if (substr($name, 0, 1) != '@') {
				$roots[] = $id;
			}
		}

		unset($data);

		if (count($roots) != 1) {
			return false;
		}

		return $roots[0];
	}

/**
 * Returns the minimum of fields required to generate a link
 *
 * This method is handy to generate links for data in table rows
 *
 * @param Model $Model the model doing the find
 * @param array $conditions any conditions for the find
 *
 * @return array
 */
	public function getLinkData(Model $Model, $id = null) {
		if (is_null($id)) {
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
 * check if behaviors should / can be auto attached
 *
 * Adding a behavior to the actsAs will stop the behavior from auto loading
 * if it is setup to autoload in the events. There are some issues with
 * re attaching behaviors when they are already attached
 *
 * @code
 * // auto load FooBehavior to any model if its not attached or in actsAs
 *	if ($Model->shouldAutoAttachBehavior('Foo')) {
 *		$Model->Behaviors->attach('Foo');
 *	}
 *
 * // auto load FooBehavior to any model with the `bar` field if its not attached or in actsAs
 *	if ($Model->shouldAutoAttachBehavior('Foo', array('bar'))) {
 *		$Model->Behaviors->attach('Foo');
 *	}
 * @endcode
 *
 * @param Model $Model the model the check is being done on
 * @param string $behavior the name of the beahvior to check
 * @param array $onlyWithFields a list of fields that should be in the table for it to auto attach
 *
 * @return boolean
 */
	public function shouldAutoAttachBehavior(Model $Model, $behavior = null, $onlyWithFields = array()) {
		if (!is_subclass_of($Model, 'Model')) {
			return false;
		}

		if ($Model->useTable == false) {
			return false;
		}

		$schema = $Model->schema();
		if (empty($schema)) {
			return false;
		}

		if ($behavior === null) {
			return true;
		}


		if (!isset($Model->actsAs[$behavior]) && !in_array($behavior, $Model->actsAs) && !$Model->Behaviors->enabled($behavior)) {
			$should = true;
			if (!empty($onlyWithFields)) {
				foreach ((array)$onlyWithFields as $field) {
					$should = $should && $Model->hasField($field);
				}
			}

			return $should;
		}

		return false;
	}

}