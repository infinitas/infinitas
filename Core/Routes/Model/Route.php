<?php
	/**
	 * Comment Template.
	 *
	 * @todo Implement .this needs to be sorted out.
	 *
	 * Copyright (c) 2009 Carl Sutton ( dogmatic69 )
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 * @filesource
	 * @copyright Copyright (c) 2009 Carl Sutton ( dogmatic69 )
	 * @link http://infinitas-cms.org
	 * @package sort
	 * @subpackage sort.comments
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.5a
	 */

	class Route extends RoutesAppModel {
		public $useTable = 'routes';

		public $order = array();

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

		public function  __construct($id = false, $table = null, $ds = null) {
			parent::__construct($id, $table, $ds);

			$this->order = array(
				$this->alias . '.ordering' => 'ASC'
			);

			$this->validate = array(
				'name' => array(
					'notEmpty' => array(
						'rule' => 'notEmpty',
						'message' => __('Please enter a name for this route')
					),
					'isUnique' => array(
						'rule' => 'isUnique',
						'message' => __('There is already a route with this name')
					)
				),
				'url' => array(
					'validUrl' => array(
						'rule' => 'validateUrlOrAbsolute',
						'message' => __('please use a valid url (absolute or full)')
					)
				),
				'plugin' => array(
					'notEmpty' => array(
						'rule' => 'notEmpty',
						'message' => __('Please select where this route will go')
					)
				),
				'values' => array(
					'validateEmptyOrJson' => array(
						'rule' => 'validateJson',
						'allowEmpty' => true,
						'message' => __('Please enter valid json for the values or leave blank')
					)
				),
				'rules' => array(
					'validateEmptyOrJson' => array(
						'rule' => 'validateJson',
						'allowEmpty' => true,
						'message' => __('Please enter valid json for the rules or leave blank')
					)
				),
				'force_frontend' => array(
					'validateEitherOr' => array(
						'allowEmpty' => true,
						'rule' => array('validateEitherOr', array('force_backend', 'force_frontend')),
						'message' => __('Please chose only one, or none')
					)
				),
				'force_backend' => array(
					'validateEitherOr' => array(
						'allowEmpty' => true,
						'rule' => array('validateEitherOr', array('force_backend', 'force_frontend')),
						'message' => __('Please chose only one, or none')
					)
				),
				'pass' => array(
					'custom' => array(
						'rule' => '/^([a-z]+|\b,\b)+$/',
						'message' => __('Please enter a comma seperated list of variables to pass, no spaces'),
						'allowEmpty' => true
					)
				)
			);
		}

		public function beforeSave($options = array()) {
			parent::beforeSave($options);

			if(!empty($this->data[$this->alias]['plugin'])) {
				$this->data[$this->alias]['plugin'] = Inflector::underscore($this->data[$this->alias]['plugin']);
			}

			if(!empty($this->data[$this->alias]['controller'])) {
				$this->data[$this->alias]['controller'] = str_replace('_controller', '', Inflector::underscore($this->data[$this->alias]['controller']));
			}

			return true;
		}

		public function afterFind($results, $primary = false) {
			$results = parent::afterFind($results, $primary);

			if($this->findQueryType == 'first' && !empty($results[0][$this->alias][$this->primaryKey])) {
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
		 * @return array the formatted routes
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
				CakeLog::write('core', $e->getMessage());
				$routes = array();
			}

			$config = array();
			$routingRules = array();
			foreach($routes as $array) {
				$vaules = $regex = array();
				$routingRules[][$this->alias] = array(
					'url' => $array[$this->alias]['url'],
					'values' => $this->getValues($array[$this->alias]),
					'regex' => $this->getRegex($array[$this->alias]['rules'], $array[$this->alias]['pass']),
					'theme' => $array[$this->Theme->alias]['name'],
					'layout' => !empty($array[$this->alias]['layout']) ? $array[$this->alias]['layout'] : $array[$this->Theme->alias]['default_layout'],
				);

				if(!strstr($array[$this->alias]['url'], ':')) {
					continue;
				}

				$array = $array[$this->alias];

				$params = array();
				foreach(explode('/', $array['url']) as $param) {
					if(!strstr($param, ':')) {
						continue;
					}

					foreach(array_filter(explode(':', $param)) as $part) {
						$params[] = trim($part, ' -');
					}
				}

				$array['plugin'] = !empty($array['plugin']) ? $array['plugin'] : '__APP__';
				if($array['prefix']) {
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
		 * @return formatted array for Router::connect
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
		 * @param mixed $pass array of fields to pass back to the controller
		 *
		 * @return array the data for Router::connect
		 */
		public function getRegex($field, $pass = null) {
			$values = array();
			if (!empty($field)) {
				$values = $this->singleDimentionArray($this->getJson($field));
			}

			if ($pass) {
				$values['pass'] = explode( ',', $pass);
			}

			return $values;
		}
	}