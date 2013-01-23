<?php
/**
 * InfinitasRouter
 *
 * @package Infinitas.Routes.Routing
 */

App::uses('InfinitasRoute', 'Routes.Routing/Route');
App::uses('Router', 'Routing');

/**
 * InfinitasRouter
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Routes.Routing
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.8a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

class InfinitasRouter extends Router {
/**
 * Cache for reverse lookups
 *
 * @var array
 */
	protected static $_reverseLookup = array();

/**
 * Router connect method
 *
 * Overloaded to set the default routeClass
 *
 * @param string $route A string describing the template of the route
 * @param array $defaults An array describing the default route parameters. These parameters will be used by default
 *   and can supply routing parameters that are not dynamic. See above.
 * @param array $options An array matching the named elements in the route to regular expressions which that
 *   element should match.  Also contains additional parameters such as which routed parameters should be
 *   shifted into the passed arguments, supplying patterns for routing parameters and supplying the name of a
 *   custom routing class.
 *
 * @return void
 */
	public static function connect($route, $defaults = array(), $options = array()) {
		if (empty($options['routeClass'])) {
			$options['routeClass'] = 'InfinitasRoute';
		} else {
			if ($options['routeClass'] != 'PluginShortRoute') {
				var_dump($options);
				exit;
			}
		}

		parent::connect($route, $defaults, $options);
	}

/**
 * Setup the routing
 *
 * @return void
 */
	public static function setup() {
		self::_registerExtensions();
		self::_buildRoutes();
	}

/**
 * Find the best match for a route
 *
 * @param array $request the requested url
 * @param array $params request params
 * @param boolean $bestMatch
 *
 * @return array
 */
	public static function bestMatch($request, $params = array(), $bestMatch = true) {
		$hash = md5(serialize($request + $params + array($bestMatch)));
		if (!empty(self::$_reverseLookup[$hash])) {
			return self::$_reverseLookup[$hash];
		}

		foreach (parent::$routes as &$route) {
			ksort($route->defaults);
			ksort($request);

			if (array_filter($route->defaults) !== array_filter($request) || !strstr($route->template, ':')) {
				continue;
			}

			$templateParams = self::_getTemplateParams($route->template);

			$intersect = array_intersect($templateParams, array_keys($params));

			if ($templateParams == $intersect) {
				foreach ($templateParams as $v) {
					$request[$v] = $params[$v];
				}

				if (substr($route->template, -2) == '/*') {
					$add = $params;
					foreach ($intersect as $k => $v) {
						if (isset($params[$v])) {
							unset($add[$v]);
						}
					}

					$request = array_merge(array_values($add), $request);
				}
				$bestMatch = false;
			}
		}

		if ($bestMatch && !empty($params['id'])) {
			$request = array_merge($request, array('id' => $params['id']));
		}

		self::$_reverseLookup[$hash] = array_filter($request);

		return self::$_reverseLookup[$hash];
	}

/**
 * Figure out the params in the route
 *
 * @param string $template the routing template
 *
 * @return array
 */
	protected static function _getTemplateParams($template) {
		$fragments = array_filter((array)explode('/', $template));
		foreach ($fragments as $k => $fragment) {
			if (!strstr($fragment, ':')) {
				unset($fragments[$k]);
			}
		}

		$fragments = array_filter(explode(':', implode('', $fragments)));
		foreach ($fragments as &$param) {
			$param = str_replace(array('-'), '', $param);
		}

		return $fragments;
	}

/**
 * Url generation method
 *
 * Overloaded to generate full urls by default
 *
 * @param string|array $url the url to be generated
 * @param boolean $full true for full url with hostname
 *
 * @return string
 */
	public static function url($url = null, $full = true) {
		return parent::url($url, $full);
	}

/**
 * Get request params for slug routing
 *
 * @param array $requestData
 *
 * @return array
 */
	public static function requestParams(array $requestData) {
		$_keys = array_diff(
			array_keys($requestData),
			array('plugin', 'controller', 'action', 'named')
		);
		extract($requestData);
		return compact($_keys);
	}

/**
 * Build routes for the app.
 *
 * Allows other plugins to register routes to be used in the app and builds
 * the routes from the database.
 *
 * If the route has been specified to force a particular prefix but the requested url matches the
 * prefix already it will be ignored.
 *
 * eg: forcing admin prefix on /admin/* url will remove admin => true from the router connect.
 *
 * @return void
 */
	protected static function _buildRoutes() {
		App::uses('ClassRegistry', 'Utility');
		EventCore::trigger(new StdClass(), 'setupRoutes');

		$CakeRequest = new CakeRequest();
		$admin = false;
		if (strstr($CakeRequest->here, '/admin') === 0) {
			$admin = true;
		}

		if (InfinitasPlugin::infinitasInstalled()) {
			$routes = ClassRegistry::init('Routes.Route')->getRoutes();
			if (!empty($routes)) {
				foreach ($routes as $route) {
					if (false) {
						debugRoute($route);
						continue;
					}
					if (array_key_exists('admin', $route['Route']['values'])) {
						if (!$admin && !$route['Route']['values']['admin']) {
							unset($route['Route']['values']['admin']);
						}

						if ($admin && $route['Route']['values']['admin']) {
							unset($route['Route']['values']['admin']);
						}
					}

					call_user_func_array(array('InfinitasRouter', 'connect'), $route['Route']);
				}
			}

			if (!defined('INFINITAS_ROUTE_HASH')) {
				define('INFINITAS_ROUTE_HASH', md5(serialize($routes)));
			}
		}
	}

/**
 * Register extensions that are used in the app
 *
 * Call all plugins and see what extensions are need, this is cached
 *
 * @return void
 */
	protected static function _registerExtensions() {
		$extensions = Cache::read('extensions', 'routes');
		if ($extensions === false) {
			$extensions = EventCore::trigger(new StdClass(), 'setupExtensions');

			$_extensions = array();
			foreach ($extensions['setupExtensions'] as $plugin => $ext) {
				$_extensions = array_merge($_extensions, (array)$ext);
			}

			$extensions = array_flip(array_flip($_extensions));
			Cache::write('extentions', $extensions, 'routes');
			unset($_extensions);
		}

		call_user_func_array(array('Router', 'parseExtensions'), $extensions);
	}

}