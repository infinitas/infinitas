<?php
	/* 
	 * Short Description / title.
	 * 
	 * Overview of what the file does. About a paragraph or two
	 * 
	 * Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * 
	 * @filesource
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package Infinitas.routes
	 * @subpackage Infinitas.routes.events
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.8a
	 * 
	 * @author dogmatic69
	 * 
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */

	App::uses('InfinitasRoute', 'Routes.Lib');
	
	class InfinitasRouter extends Router {
		protected static $_reverseLookup = array();
		
		public static function connect($route, $defaults = array(), $options = array()) {
			if(empty($options['routeClass'])) {
				$options['routeClass'] = 'InfinitasRoute';
			}
			else {
				if($options['routeClass'] != 'PluginShortRoute') {
					var_dump($options);
					exit;
				}
			}
			
			parent::connect($route, $defaults, $options);
		}
		
		public function setup() {
			self::__registerExtensions();
			self::__buildRoutes();
		}

		public static function bestMatch($request, $params = array(), $bestMatch = true) {
			$hash = md5(serialize($request + $params + array($bestMatch)));
			if(!empty(self::$_reverseLookup[$hash])) {
				return self::$_reverseLookup[$hash];
			}
			
			foreach(parent::$routes as &$route) {
				ksort($route->defaults);
				ksort($request);
				
				if(array_filter($route->defaults) !== array_filter($request) || !strstr($route->template, ':')) {
					continue;
				}

				$templateParams = self::__getTemplateParams($route->template);
				
				$intersect = array_intersect($templateParams, array_keys($params));
				
				if($templateParams == $intersect) {
					foreach($templateParams as $v) {
						$request[$v] = $params[$v];
					}
					
					if(substr($route->template, -2) == '/*') {
						$add = $params;
						foreach($intersect as $k => $v) {
							if(isset($params[$v])) {
								unset($add[$v]);
							}
						}
						
						$request = array_merge(array_values($add), $request);
					}
					$bestMatch = false;
				}
			}

			if($bestMatch && !empty($params['id'])) {
				$request = array_merge($request, array('id' => $params['id']));
			}

			self::$_reverseLookup[$hash] = array_filter($request);

			return self::$_reverseLookup[$hash];
		}

		private static function __getTemplateParams($template) {
			$fragments = array_filter((array)explode('/', $template));
			foreach($fragments as $k => $fragment) {
				if(!strstr($fragment, ':')) {
					unset($fragments[$k]);
				}
			}

			$fragments = array_filter(explode(':', implode('', $fragments)));
			foreach($fragments as &$param) {
				$param = str_replace(array('-'), '', $param);
			}

			return $fragments;
		}

		public static function url($url = null, $full = true) {
			return parent::url($url, $full);
		}

		/**
		 * Build routes for the app.
		 *
		 * Allows other plugins to register routes to be used in the app and builds
		 * the routes from the database.
		 */
		private static function __buildRoutes() {
			App::uses('ClassRegistry', 'Utility');
			EventCore::trigger(new StdClass(), 'setupRoutes');

			if(InfinitasPlugin::infinitasInstalled()) {
				$routes = ClassRegistry::init('Routes.Route')->getRoutes();
				if (!empty($routes)) {
					foreach($routes as $route ) {
						if (false) {
							debugRoute($route);
							continue;
						}

						call_user_func_array(array('InfinitasRouter', 'connect'), $route['Route']);
					}
				}

				if(!defined('INFINITAS_ROUTE_HASH')) {
					define('INFINITAS_ROUTE_HASH', md5(serialize($routes)));
				}
			}
		}

		/**
		 * Register extensions that are used in the app
		 *
		 * Call all plugins and see what extensions are need, this is cached
		 */
		private static function __registerExtensions() {
			$extensions = Cache::read('extensions', 'routes');
			if($extensions === false) {
				$extensions = EventCore::trigger(new StdClass(), 'setupExtensions');

				$_extensions = array();
				foreach($extensions['setupExtensions'] as $plugin => $ext) {
					$_extensions = array_merge($_extensions, (array)$ext);
				}

				$extensions = array_flip(array_flip($_extensions));
				Cache::write('extentions', $extensions, 'routes');
				unset($_extensions);
			}
			
			call_user_func_array(array('Router', 'parseExtensions'), $extensions);
		}
	 }

	 class IRouter extends InfinitasRouter {
		 
	 }