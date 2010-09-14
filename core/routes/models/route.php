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
		public $name = 'Route';

		public $order = array(
			'Route.ordering' => 'ASC'
		);

		public $belongsTo = array(
			'Theme' => array(
				'className' => 'Themes.Theme',
				'fields' => array(
					'Theme.id',
					'Theme.name'
				)
			)
		);

		/**
		 * Get all routes required for the app
		 *
		 * Gets and formats the routes data and returns it ready for Router::connect()
		 * only active routes are needed.
		 *
		 * @return array the formatted routes
		 */
		public function getRoutes(){
			$routes = Cache::read('routes', 'routes');

			if ($routes !== false) {
				return $routes;
			}

			$routes = $this->find(
				'all',
				array(
					'fields' => array(
						'Route.url',
						'Route.prefix',
						'Route.plugin',
						'Route.controller',
						'Route.action',
						'Route.values',
						'Route.pass',
						'Route.rules',
						'Route.force_backend',
						'Route.force_frontend',
						'Route.theme_id'
					),
					'conditions' => array(
						'Route.active' => 1
					),
					'order' => array(
						'Route.ordering' => 'ASC'
					),
					'contain' => array(
						'Theme'
					)
				)
			);


			foreach( $routes as $array ){
				$vaules = $regex = array();
				$routingRules[]['Route'] = array(
					'url' => $array['Route']['url'],
					'values' => $this->__getValues($array['Route']),
					'regex' => $this->__getRegex($array['Route']['rules'], $array['Route']['pass']),
					'theme' => $array['Theme']['name']
				);
			}

			unset($routes);

			Cache::write('routes', $routingRules, 'routes');

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
		private function __getValues($route = array()){
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
		private function __getRegex($field, $pass = null){
			$values = array();
			if (!empty($field)) {
				$values = $this->singleDimentionArray($this->getJson($field));
			}

			if ($pass) {
				$_values = explode( ',', $pass);
				$values = array_merge((array)$values, (array)$_values);
			}

			return $values;
		}
	}