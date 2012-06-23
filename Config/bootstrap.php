<?php
	/**
	 * @brief bootstrap defines a few global methods and triggers plugin integration
	 *
	 * This is the place where infinitas invokes plugins to start acting on the
	 * core, alowing them to set cache configurations, import required lib files.
	 *
	 * Things like the Xhprof profiler needs to be attached as early as possible,
	 * so using AppController::beforeFilter() is not good enough. For this reason
	 * you can attach libs from here using the Events
	 *
	 * @li AppEvents::onRequireLibs()
	 * @li AppEvents::onSetupCache()
	 *
	 * @throws E_USER_ERROR when Events is not available
	 *
	 * @copyright Copyright (c) 2009 Carl Sutton ( dogmatic69 )
	 * @link http://infinitas-cms.org
	 * @package Infinitas
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.5a
	 *
	 * @author dogmatic69
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */

	App::uses('Folder', 'Utility');
	App::uses('String', 'Utility');
	App::uses('Sanitize', 'Utility');
	App::uses('ClassRegistry', 'Utility');
	App::uses('InfinitasPlugin', 'Lib');
	App::uses('CakeLog', 'Log');

	App::uses('AppModel', 'Model');
	App::uses('AppController', 'Controller');
	App::uses('AppHelper', 'View/Helper');

	App::uses('ClearCache', 'Data.Lib');
	App::uses('EventCore', 'Events.Lib');
	App::uses('InfinitasRouter', 'Routes.Routing');

	// Add logging configuration.
	CakeLog::config('debug', array(
		'engine' => 'FileLog',
		'types' => array('notice', 'info', 'debug'),
		'file' => 'debug',
	));
	CakeLog::config('error', array(
		'engine' => 'FileLog',
		'types' => array('warning', 'error', 'critical', 'alert', 'emergency'),
		'file' => 'error',
	));


	/**
	 * Cache configuration.
	 *
	 * Try apc or memcache, default to the namespaceFile cache.
	 */
	$cacheEngine = 'File';
	switch(true) {
		case function_exists('apc_cache_info') && ini_get('apc.enabled'):
			$cacheEngine = 'Apc';
			break;

		case function_exists('xcache_info'):
			$cacheEngine = 'Xcache';
			break;

		case class_exists('Memcache'):
			$cacheEngine = 'Memcache';
			break;

		default:
			//$cacheEngine = 'Libs.NamespaceFileCache';
			break;
	}
	Configure::write('Cache.engine', $cacheEngine);

	Cache::config('_cake_core_', array('engine' => $cacheEngine, 'prefix' => cachePrefix(), 'mask' => 0664));
	Cache::config('_cake_model_', array('engine' => $cacheEngine, 'prefix' => cachePrefix(), 'mask' => 0664));
	Cache::config('default', array('engine' => $cacheEngine, 'prefix' => cachePrefix(), 'mask' => 0644));

	//no home
	Configure::write('Rating.require_auth', true);
	Configure::write('Rating.time_limit', '4 weeks');
	Configure::write('Reviews.auto_moderate', true);

	/**
	 * @brief get the configuration values from cache
	 *
	 * If they are available, set them to the Configure object else run the
	 * Event to get the values from all the plugins and cache them
	 */
	$cachedConfigs = Cache::read('global_configs');
	if(!empty($cachedConfigs)) {
		foreach($cachedConfigs as $k => $v) {
			Configure::write($k, $v);
		}
	}

	unset($cacheEngine, $cachedConfigs);

	InfinitasPlugin::loadCore();
	EventCore::trigger(new StdClass(), 'requireLibs');

	/**
	 * @todo cake2.0
	 * Cache::write('global_configs', Configure::getInstance());
	 */

	configureCache(EventCore::trigger(new StdClass(), 'setupCache'));

	if(in_array('test', env('argv'))) {
		InfinitasPlugin::load(InfinitasPlugin::listPlugins('nonCore'));
	} else {
		InfinitasPlugin::loadInstalled();
	}

	/**
	* Make sure the json defines are loaded.
	*/
	if(!defined('JSON_ERROR_NONE')) {define('JSON_ERROR_NONE', 0);}
	if(!defined('JSON_ERROR_DEPTH')) {define('JSON_ERROR_DEPTH', 1);}
	if(!defined('JSON_ERROR_CTRL_CHAR')) {define('JSON_ERROR_CTRL_CHAR', 3);}
	if(!defined('JSON_ERROR_SYNTAX')) {define('JSON_ERROR_SYNTAX', 4);}

	if (!function_exists('lcfirst')) {
		function lcfirst($str) {
			define('INFINITAS_FUNCTION_LCFIRST', true);
			return (string)(strtolower(substr($str, 0, 1)) . substr($str, 1));
		}
	}

	function configureCache($cacheDetails) {
		foreach($cacheDetails['setupCache'] as $plugin => $cache) {
			$cache['config']['prefix'] = isset($cache['config']['prefix']) ? $cache['config']['prefix'] : '';
			$folder = str_replace('.', DS, $cache['config']['prefix']);
			if(!strstr($folder, 'core' . DS)) {
				$folder = 'plugins' . DS . $folder;
			}

			if(Configure::read('Cache.engine') == 'Libs.NamespaceFile') {
				if(!is_dir(CACHE . $folder)) {
					$Folder = new Folder(CACHE . $folder, true, 755);
				}
			}

			else{
				$cache['config']['prefix'] = Inflector::slug(APP_DIR) . '_' . str_replace(DS, '_', $folder);
			}

			$cache['config']['prefix'] = cachePrefix() . $cache['config']['prefix'];

			$cache['config'] = array_merge(array('engine' => Configure::read('Cache.engine')), (array)$cache['config']);

			// set a default and turn off if debug is on.
			$cache['config']['duration'] = isset($cache['config']['duration']) ? $cache['config']['duration'] : '+ 999 days';
			$cache['config']['duration'] = (Configure::read('debug') > 0) ? '+ 10 seconds' : $cache['config']['duration'];

			if(!empty($cache['name']) && !empty($cache['config'])) {
				Cache::config($cache['name'], $cache['config']);
			}
		}
		unset($cacheDetails, $cache, $folder, $plugin);
	}

	function cachePrefix() {
		if(!defined('INFINITAS_CACHE_PREFIX')) {
			define('INFINITAS_CACHE_PREFIX', substr(sha1(env('DOCUMENT_ROOT') . env('HTTP_HOST')), 0, 10));
		}

		return INFINITAS_CACHE_PREFIX;
	}

	/**
	 * Escape things for preg_match
	 *
	 * will escape a string for goind preg match.
	 * http://www.php.net/manual/en/function.preg-replace.php#92456
	 *
	 * - Escapes the following
	 *   - \ ^ . $ | ( ) [ ] * + ? { } ,
	 *
	 * - Example
	 *   - regexEscape('http://www.example.com/s?q=php.net+docs')
	 *   - http:\/\/www\.example\.com\/s\?q=php\.net\+docs
	 *
	 * @author alammar at gmail dot com
	 *
	 * @param string $str the stuff you want escaped
	 * @return string the escaped string
	 */
	function regexEscape($str) {
		$patterns = array(
			'/\//', '/\^/', '/\./', '/\$/', '/\|/',
			'/\(/', '/\)/', '/\[/', '/\]/', '/\*/',
			'/\+/', '/\?/', '/\{/', '/\}/', '/\,/'
		);

		$replace = array('\/', '\^', '\.', '\$', '\|', '\(', '\)',
		'\[', '\]', '\*', '\+', '\?', '\{', '\}', '\,');

		return preg_replace($patterns, $replace, $str);
	}

	/**
	 * @brief generate a unique cache name for a file.
	 *
	 * uses an array of data or a string to generate a hash for the end of the cache
	 * name so that you can cache finds etc
	 *
	 * @param string $prefix the normal name for cache
	 * @param mixed $data your conditions in the find
	 *
	 * @return a nice unique name
	 */
	function cacheName($prefix = 'PleaseNameMe', $data = null) {
		$hash = '';

		if ($data) {
			$hash = '_' . sha1(serialize($data));
		}

		$data = Inflector::underscore($prefix) . $hash;
		unset($hash);

		return $data;
	}

	/**
	 * @brief return a nice user friendly name.
	 *
	 * Takes a cake class like SomeModel and converts it to Some model
	 *
	 * @code
	 *	prettyName(SomeModel);
	 *	// Some models
	 *
	 *	prettyName(CateogryStuff);
	 *	// Category stuffs
	 * @endcode
	 *
	 * @param string $class the name to convert
	 *
	 * @return a nice name
	 */
	function prettyName($class = null) {
		if($class !== null) {
			if(!class_exists('Inflector')) {
				App::import('Inflector');
			}

			$class = preg_replace('/global|core/i', '', $class);

			return ucfirst(strtolower(Inflector::humanize(Inflector::underscore(Inflector::pluralize((string)$class)))));
		}

		return false;
	}

	/**
	 * @brief having issues with routes
	 *
	 * @todo this should be moved to the routing plugin and a page added to admin
	 * to view what is being generated.
	 *
	 * @param mixed $route
	 *
	 * @return null echo's out the route.
	 */
	function debugRoute($route, $return = true) {
		$out = '';
		$out .= 'InfinitasRouter::connect(\''.$route['Route']['url'].'\', array(';
		$parts = array();
		foreach($route['Route']['values'] as $k => $v) {
			$parts[] = "'$k' => '$v'";
		}
		$out .= implode(', ', $parts);
		$out .= ')';
		$parts = array();
		foreach($route['Route']['regex'] as $k => $v) {
			$parts[] = "'$k' => '$v'";
		}

		if(!empty($parts)) {
			$out .= ', array(';
			$out .= implode(', ', $parts);
			$out .= ')';
		}
		$out .= ');';

		if($return) {
			return $out;
		}

		echo $out;
	}

	function debugBacktrace($backtrace) {
		$count = 0;
		foreach($backtrace as $k => $v) {
			$default = array(
				'line' => null,
				'file' => null,
				'function' => null,
				//'args' => null,
			);
			$line = $v;

			$pass = array();
			foreach($line['args'] as $k => &$v) {
				if(empty($v)) {
					continue;
				}

				if(is_object($v)) {
					$pass[] = $v = sprintf('$%s', get_class($v));
					continue;
				}

				if(is_array($v)) {
					$pass[] = 'array()';
					continue;
				}

				if(is_int($v)) {
					$pass[] = $v;
					continue;
				}

				if($v === null) {
					$pass[] = '<i>null</i>';
					continue;
				}

				$pass[] = sprintf("'%s'", $v);
			}

			$line['function'] = sprintf(
				'%s(%s)',
				implode($line['type'], array($line['class'], $line['function'])),
				implode(', ', $pass)
			);
			array_walk($line['args'], function(&$line) {
				$line = print_r($line, true);
			});
			$line['function'] = sprintf(
				'<span onclick="debugToggle(%s)">%s</span><pre id="backtrace-%s" style="display:none;">%s</pre>',
				$count,
				$line['function'],
				$count++,
				sprintf('<pre>%s</pre>', implode('</pre><pre>', $line['args']))
			);
			unset($line['object'], $line['class'], $line['type'], $line['args']);
			$line = array_merge($default, $line);

			$line['file'] = str_replace(
				array(APP . 'Core' . DS, APP, CAKE),
				array('INFINTIAS/', 'APP/', 'CAKE/'),
				$line['file']
			);

			$lines[] = sprintf(
				'<td>%s<td>',
				implode('</td><td>', $line)
			);
		}

		$lines = sprintf(
			'<script type="text/javascript">function debugToggle(id) {var e = document.getElementById(\'backtrace-\' + id);if(e.style.display == \'block\') {e.style.display = \'none\';}else {e.style.display = \'block\';}}</script>'.
			'<table style="width:80%%; margin:auto;"><tr><th>%s</th></tr><tr>%s</tr></table>',
			implode('</th><th>', array('Line', 'File', 'Function')),
			implode('</tr><tr>', array_reverse($lines))
		);

		echo $lines;
	}

	/**
	 * @brief Quick method to conver byte -> anything.
	 *
	 * @code
	 *	// output 1 kb
	 *	convert(1024);
	 *
	 *	// output 5.24 mb
	 *	convert(5494237);
	 * @endcode
	 *
	 * @param $size size in bytes
	 *
	 * @return string size in human readable
	 */
	function convert($size) {
		if(!$size) {
			return '0 b';
		}
		$unit = array('b', 'kb', 'mb', 'gb', 'tb', 'pb');
		return @round($size / pow(1024, ($i = floor(log($size, 1024)))), 2) . ' ' . $unit[$i];
	}

	/**
	 * @brief get the current memory stats
	 *
	 * @param $print true to pr() false to return
	 *
	 * @code
	 *	// both output pr() data;
	 *	memoryUsage();
	 *	memoryUsage(true);
	 *
	 *	// set var with mem details
	 *	$var = memoryUsage(false);
	 * @endcode
	 *
	 * @return mixed true if $print, array if !$print
	 */
	function memoryUsage($print = true, $convert = true) {
		$memory = array(
			'current' => memory_get_usage(),
			'current_t' => memory_get_usage(true),
			'max' => memory_get_peak_usage(),
			'max_' => memory_get_peak_usage(true),
			'limit' => ini_get('memory_limit')
		);

		if($convert) {
			$memory['current']   = convert($memory['current']);
			$memory['current_t'] = convert($memory['current_t']);
			$memory['max']       = convert($memory['max']);
			$memory['max_']      = convert($memory['max_']);
		}

		if((bool)$print) {
			pr($memory);
			unset($memory);
			return true;
		}

		return $memory;
	}

	/**
	 * @brief attempt to get the current server load
	 *
	 * This will attempt a few methods to determin the server load, can be used
	 * for reporting or keeping an eye on how things are running.
	 *
	 * @param <type> $print
	 * @return <type>
	 */
	function serverLoad($print = true) {
		// try file method
		$load = @file_get_contents('/proc/loadavg');
		if($load) {
			$load = explode(' ', $load, 4);
			unset($load[3]);
		}

		// try exec
		if(!$load) {
			$load = @exec('uptime');

			// try shell_exec
			if(!$load) {
				$load = @shell_exec('uptime');
			}

			if($load) {
				$load = explode(' ', $load);
				$load[2] = trim(array_pop($load));
				$load[1] = str_replace(',', '', array_pop($load));
				$load[0] = str_replace(',', '', array_pop($load));
			}
			else{
				$load[0] = $load[1] = $load[2] = -1;
			}
		}

		if((bool)$print) {
			pr($load);
			unset($load);
			return true;
		}

		return $load;
	}

	function systemInfo($extendedInfo = false) {
		$return = array();
		$return['Server'] = array(
			'name' => php_uname('n'),
			'os' => php_uname('s'),
			'type' => php_uname('s'),
			'version' => php_uname('v'),
			'release' => php_uname('r'),
		);
		$return['Php'] = array(
			'version' => phpversion(),
			'memory_limit' => ini_get('memory_limit'),
			'sapi' => php_sapi_name()
		);

		if(!$extendedInfo) {
			return $return;
		}

		$extentions = get_loaded_extensions();
		foreach($extentions as $extention) {
			$return['Php']['extentions'][] = array(
				'name' => $extention,
				'version' => phpversion($extention)
			);
		}

		return $return;
	}


