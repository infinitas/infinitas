<?php
/**
 * Route
 *
 * @package Infinitas.Routes.Model
 */

/**
 * Filemanager Events
 *
 * The events that can be triggered for the events class.
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Routes.Model
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.5a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

class Route extends RoutesAppModel {

/**
 * belongs to relations
 *
 * @var array
 */
	public $belongsTo = array(
		'Theme' => array(
			'className' => 'Themes.Theme',
			'fields' => array(
				'Theme.id',
				'Theme.name',
				'Theme.default_layout'
			)
		)
	);

/**
 * Constructor
 *
 * @param type $id
 * @param type $table
 * @param type $ds
 *
 * @return void
 */
	public function  __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);

		$this->order = array(
			$this->alias . '.ordering' => 'ASC'
		);

		$this->validate = array(
			'name' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => __d('routes', 'Please enter a name for this route'),
					'required' => true,
					'on' => 'create'
				),
				'isUnique' => array(
					'rule' => 'isUnique',
					'message' => __d('routes', 'There is already a route with this name')
				)
			),
			'url' => array(
				'validUrl' => array(
					'required' => true,
					'rule' => 'validateUrlOrAbsolute',
					'message' => __d('routes', 'Please use a valid url (absolute or full)')
				),
				'isUnique' => array(
					'rule' => 'isUnique',
					'message' => __d('routes', 'There is already a route for this url')
				)
			),
			'plugin' => array(
				'notEmpty' => array(
					'required' => true,
					'rule' => 'notEmpty',
					'message' => __d('routes', 'Please select where this route will go')
				),
				'validatePluginExists' => array(
					'rule' => array('validatePluginExists', array('pluginType' => 'installed')),
					'message' => __d('routes', 'Please select a valid controller')
				)
			),
			'controller' => array(
				'validateControllerExists' => array(
					'required' => true,
					'rule' => array('validateControllerExists', array('pluginField' => 'plugin')),
					'message' => __d('routes', 'Please select a valid controller')
				)
			),
			'action' => array(
				'validateActionExists' => array(
					'required' => true,
					'rule' => array('validateActionExists', array('pluginField' => 'plugin', 'controllerField' => 'controller')),
					'message' => __d('routes', 'Please select a valid action')
				)
			),
			'values' => array(
				'validateJson' => array(
					'allowEmpty' => true,
					'rule' => 'validateJson',
					'message' => __d('routes', 'Please enter valid configuration (json) for the route or leave blank')
				)
			),
			'rules' => array(
				'validateJson' => array(
					'allowEmpty' => true,
					'rule' => 'validateJson',
					'message' => __d('routes', 'Please enter valid rules (json) for the route or leave blank')
				)
			),
			'force_frontend' => array(
				'validateEitherOr' => array(
					'allowEmpty' => true,
					'rule' => array('validateEitherOr', array('force_backend', 'force_frontend')),
					'message' => __d('routes', 'Please select either frontend or backend')
				)
			),
			'force_backend' => array(
				'validateEitherOr' => array(
					'allowEmpty' => true,
					'rule' => array('validateEitherOr', array('force_backend', 'force_frontend')),
					'message' => __d('routes', 'Please select either frontend or backend')
				)
			),
			'pass' => array(
				'custom' => array(
					'rule' => '/^([a-z]+|\b,\b)+$/',
					'message' => __d('routes', 'Please enter a comma seperated list of variables to pass, no spaces'),
					'allowEmpty' => true
				)
			)
		);
	}

/**
 * beforeValidate callback
 *
 * Set force_backend/frontend to null if it is 0 so that allowEmpty will be used for non checked boxes.
 *
 * @param array $options the validation options
 *
 * @return array
 */
	public function beforeValidate($options = array()) {
		if (array_key_exists('force_backend', $this->data[$this->alias]) && !$this->data[$this->alias]['force_backend']) {
			unset($this->data[$this->alias]['force_backend']);
		}
		if (array_key_exists('force_frontend', $this->data[$this->alias]) && !$this->data[$this->alias]['force_frontend']) {
			unset($this->data[$this->alias]['force_frontend']);
		}

		return parent::beforeValidate($options);
	}

/**
 * BeforeSave callback
 *
 * Sort out the plugin name before saving
 *
 * @param array $options
 *
 * @return boolean
 */
	public function beforeSave($options = array()) {
		parent::beforeSave($options);

		if (!empty($this->data[$this->alias]['plugin'])) {
			$this->data[$this->alias]['plugin'] = Inflector::underscore($this->data[$this->alias]['plugin']);
		}

		if (!empty($this->data[$this->alias]['controller'])) {
			$this->data[$this->alias]['controller'] = str_replace('_controller', '', Inflector::underscore($this->data[$this->alias]['controller']));
		}

		return true;
	}

/**
 * AfterFind callback
 *
 * After finding routes fix up the plugin names
 *
 * @param array $results
 * @param boolean $primary
 *
 * @return string
 */
	public function afterFind($results, $primary = false) {
		$results = parent::afterFind($results, $primary);

		if ($this->findQueryType == 'first' && !empty($results[0][$this->alias][$this->primaryKey])) {
			$results[0][$this->alias]['plugin'] = Inflector::camelize($results[0][$this->alias]['plugin']);
			$results[0][$this->alias]['controller'] = Inflector::camelize($results[0][$this->alias]['controller']) . 'Controller';
		}

		return $results;
	}

/**
 * Get all routes required for the app
 *
 * Gets and formats the routes data and returns it ready for InfinitasRouter::connect()
 * only active routes are needed.
 *
 * A cache is also created of the params needed in to build a url so that
 * the onSlugUrl events are able to detect the type of url that is needed
 * and build them automatically.
 *
 * @return array
 */
	public function getRoutes() {
		$routes = false; //Cache::read('routes', 'routes');
		if ($routes !== false) {
			return $routes;
		}

		$config = array(
			'fields' => array(
				$this->alias . '.url',
				$this->alias . '.prefix',
				$this->alias . '.plugin',
				$this->alias . '.controller',
				$this->alias . '.action',
				$this->alias . '.values',
				$this->alias . '.pass',
				$this->alias . '.rules',
				$this->alias . '.force_backend',
				$this->alias . '.force_frontend',
				$this->alias . '.theme_id',
				$this->alias . '.layout',
				$this->Theme->alias . '.' . $this->Theme->primaryKey,
				$this->Theme->alias . '.' . $this->Theme->displayField,
				$this->Theme->alias . '.default_layout',
			),
			'conditions' => array(
				$this->alias . '.active' => 1
			),
			'order' => array(
				$this->alias . '.ordering' => 'ASC'
			),
			'joins' => array(
				array(
					'table' => $this->Theme->tablePrefix . $this->Theme->useTable,
					'alias' => $this->Theme->alias,
					'type' => 'LEFT',
					'conditions' => array(
						sprintf(
							'%s.%s = %s.%s',
							$this->alias, $this->belongsTo[$this->Theme->alias]['foreignKey'],
							$this->Theme->alias, $this->Theme->primaryKey
						)
					)
				)
			)
		);

		try {
			$routes = $this->find('all', $config);
		}

		catch(Exception $e) {
			CakeLog::write('infinitas', $e->getMessage());
			return array();
		}

		$config = array();
		$routingRules = array();
		foreach ($routes as $array) {
			$vaules = $regex = array();
			$routingRules[][$this->alias] = array(
				'url' => $array[$this->alias]['url'],
				'values' => $this->getValues($array[$this->alias]),
				'regex' => $this->getRegex($array[$this->alias]['rules'], $array[$this->alias]['pass']),
				'theme' => $array[$this->Theme->alias]['name'],
				'layout' => !empty($array[$this->alias]['layout']) ? $array[$this->alias]['layout'] : $array[$this->Theme->alias]['default_layout'],
			);

			if (!strstr($array[$this->alias]['url'], ':')) {
				continue;
			}

			$array = $array[$this->alias];

			$params = array();
			foreach (explode('/', $array['url']) as $param) {
				if (!strstr($param, ':')) {
					continue;
				}

				foreach (array_filter(explode(':', $param)) as $part) {
					$params[] = trim($part, ' -');
				}
			}

			$array['plugin'] = !empty($array['plugin']) ? $array['plugin'] : '__APP__';
			if ($array['prefix']) {
				$config[Inflector::camelize($array['plugin'])][$array['controller']][$array['prefix']][$array['action']][$array['url']] = $params;
			}
			else{
				$config[Inflector::camelize($array['plugin'])][$array['controller']][$array['action']][$array['url']] = $params;
			}
		}


		unset($routes);
		Cache::write('routes', $routingRules, 'routes');
		Configure::write('Routing.lookup', $config);

		return $routingRules;
	}

/**
 * get values for the route
 *
 * gets the values that are in the route and creates an array of what is
 * set for Router::connect
 *
 * @param array $route the route from the database
 *
 * @return array
 */
	public function getValues($route = array()) {
		if (!$route) {
			return false;
		}

		$values = array();

		if (!empty($route['prefix'])) {
			$values['prefix'] = $route['prefix'];
		}

		if (!empty($route['plugin'])) {
			$values['plugin'] = $route['plugin'];
		}

		if (!empty($route['controller'])) {
			$values['controller'] = $route['controller'];
		}

		if (!empty($route['action'])) {
			$values['action'] = $route['action'];
		}

		if (!empty($route['values'])) {
			$values = array_merge($values, $this->singleDimentionArray($this->getJson($route['values'])));
		}

		if ($route['force_backend']) {
			$values['admin'] = true;
		}
		else if ($route['force_frontend']) {
			$values['admin'] = null;
		}

		return $values;
	}

/**
 * get the regex rules
 *
 * gets the json regex rules from the data and creates an array or
 * returns an empty array.
 *
 * @param array $field the data from the rute
 * @param string $pass array of fields to pass back to the controller
 *
 * @return array
 */
	public function getRegex($field, $pass = null) {
		$values = array();
		if (!empty($field)) {
			$values = $this->singleDimentionArray($this->getJson($field));
		}

		if ($pass) {
			$values['pass'] = explode(',', $pass);
		}

		return $values;
	}

}