<?php
/**
 * InfinitasRoute
 *
 * @package Infinitas.Routes.Routing
 */

/**
 * InfinitasRoute
 *
 * Infinitas route uses the Events system to allow plugins easy access to the current
 * route without having to define custom route classes.
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Routes.Routing
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.9a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

class InfinitasRoute extends CakeRoute {
/**
 * Parse url
 *
 * Trigger events to the routes plugin to see if it shoul be handled
 *
 * @param string $url the url being parsed
 *
 * @return array
 */
	public function parse($url) {
		$params = parent::parse($url);

		if (!empty($params['plugin'])) {
			$plugin = Inflector::camelize($params['plugin']);
			$data = current(EventCore::trigger($this, $plugin . '.routeParse', $params));

			if (isset($data[$plugin]) && $data[$plugin] !== null) {
				return $data[$plugin];
			}
		}

		return $params;
	}

}