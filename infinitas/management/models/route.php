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

	class Route extends ManagementAppModel {
		var $name = 'Route';

		var $actsAs = array(
			'Libs.Ordered'
		);

		var $order = array(
			'Route.ordering' => 'ASC'
		);

		var $belongsTo = array(
			'Management.Theme'
		);

		function getRoutes(){
			$routes = Cache::read('routes', 'core');


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
						'Theme' => array(
							'fields' => array(
								'Theme.id',
								'Theme.name'
							)
						)
					)
				)
			);


			foreach( $routes as $array ){
				$vaules = $regex = array();
				$routingRules[]['Route'] = array(
					'url' => $array['Route']['url'],
					'values' => $this->_getValues($array['Route']),
					'regex' => $this->_getRegex($array['Route']['rules'], $array['Route']['pass']),
					'theme' => $array['Theme']['name']
				);
			}

			unset($routes);

			Cache::write('routes', $routingRules, 'core');

			return $routingRules;
		}

		function _getValues($route = array()){
			if (!$route) {
				return false;
			}

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
				$_v = explode("\n", $route['values']);

				foreach((array)$_v as $line ){
					$parts = explode( ':', $line );
					if (count($parts) == 2) {
						$_values[$parts[0]] = $this->_getType($parts[1]);
					}
				}

				$values = array_merge((array)$values, (array)$_values);
			}

			if ($route['force_backend']) {
				$values['admin'] = true;
			}
			else if ($route['force_frontend']) {
				$values['admin'] = null;
			}

			return $values;
		}

		function _getRegex($field, $pass = null){
			$values = array();
			if (!empty($field)) {
				$_v = explode("\n", $field);

				foreach((array)$_v as $line ){
					$parts = explode( ':', $line );
					if (count($parts) == 2 ) {

						$values[$parts[0]] = $this->_getType($parts[1]);
					}
				}
			}

			if ($pass) {
				$_values = explode( ',', $pass);
				$values = array_merge((array)$values, (array)$_values);
			}

			return $values;
		}

		function _getType($field = false){
			$replace = array(
				"\r",
				"\n",
				"\n\r",
				"\r\n",
				' '
			);
			$field = str_replace( $replace, '', $field );
			switch($field){
				case 'null':
					return null;
					break;

				default:
					return $field;
					break;

			} // switch
		}

		function afterSave($created) {
			parent::afterSave($created);

			Cache::delete('routes', 'core');
			return true;
		}

		function afterDelete() {
			parent::afterDelete();

			Cache::delete('routes', 'core');
			return true;
		}
	}
?>