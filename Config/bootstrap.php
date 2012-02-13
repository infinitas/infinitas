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

	/**
	 * @brief the cached plugin paths
	 *
	 * Get the paths out of cache if there are any or get them with Folder::read
	 * They are used with App::build() to make any extra folders  in APP be plugin
	 * folders. This can help if you want to keep plugins outside of /plugins
	 */

	App::uses('Folder', 'Utility');
	App::uses('String', 'Utility');
	App::uses('Sanitize', 'Utility');
	
	$paths = false; //Cache::read('plugin_paths');
	if($paths === false){
		$Folder = new Folder(APP);
		$folders = $Folder->read();
		$folders = array_flip($folders[0]);
		unset($Folder, $folders['.git'], $folders['Config'], $folders['locale'],
			$folders['nbproject'], $folders['Console'], $folders['tmp'], $folders['View'],
			$folders['Controller'],  $folders['Lib'], $folders['webroot'], $folders['Test'],
			$folders['Model']);

		$paths = array();
		foreach(array_flip($folders) as $folder){
			$paths[] = APP . $folder . DS;
		}
		
		Cache::write('plugin_paths', $paths);
		unset($Folder, $folders);

		// @todo trigger event to get oter plugin paths
	}

	App::build(
		array(
			'Plugin' => $paths
		)
	);

	CakePlugin::loadAll();

	unset($paths);

	if (false === function_exists('lcfirst')) {
		function lcfirst($str) { 
			return (string)(strtolower(substr($str, 0, 1)) . substr($str, 1));
		} 
	}
	
	/**
	 * Load plugin events
	 */
	App::uses('EventCore', 'Events.Lib');

	EventCore::trigger(new StdClass(), 'setupConfig');
	EventCore::trigger(new StdClass(), 'requireLibs');
	configureCache(EventCore::trigger(new StdClass(), 'setupCache'));

	/**
	* Make sure the json defines are loaded.
	*/
	if(!defined('JSON_ERROR_NONE')){define('JSON_ERROR_NONE', 0);}
	if(!defined('JSON_ERROR_DEPTH')){define('JSON_ERROR_DEPTH', 1);}
	if(!defined('JSON_ERROR_CTRL_CHAR')){define('JSON_ERROR_CTRL_CHAR', 3);}
	if(!defined('JSON_ERROR_SYNTAX')){define('JSON_ERROR_SYNTAX', 4);}	
	
	function configureCache($cacheDetails) {
		foreach($cacheDetails['setupCache'] as $plugin => $cache) {
			$cache['config']['prefix'] = isset($cache['config']['prefix']) ? $cache['config']['prefix'] : '';			
			$folder = str_replace('.', DS, $cache['config']['prefix']);			
			if(!strstr($folder, 'core' . DS)){
				$folder = 'plugins' . DS . $folder;
			}
			
			if(Configure::read('Cache.engine') == 'Libs.NamespaceFile'){
				if(!is_dir(CACHE.$folder)){
					$Folder = new Folder(CACHE . $folder, true);
				}
			}
			
			else{
				$cache['config']['prefix'] = Inflector::slug(APP_DIR) . '_' . str_replace(DS, '_', $folder);
			}

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
	function regexEscape($str){
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
	function cacheName($prefix = 'PleaseNameMe', $data = null){
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
	function prettyName($class = null){
		if($class !== null){
			if(!class_exists('Inflector')){
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
	function debugRoute($route){
		echo 'Router::connect(\''.$route['Route']['url'].'\', array(';
		$parts = array();
		foreach($route['Route']['values'] as $k => $v){
			$parts[] = "'$k' => '$v'";
		}
		echo implode(', ', $parts);
		echo '), array(';
		$parts = array();
		foreach($route['Route']['regex'] as $k => $v){
			$parts[] = "'$k' => '$v'";
		}
		echo implode(', ', $parts);
		echo ')); </br>';
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
	function convert($size){
		if(!$size){
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
	function memoryUsage($print = true, $convert = true){
		$memory = array(
			'current' => memory_get_usage(),
			'current_t' => memory_get_usage(true),
			'max' => memory_get_peak_usage(),
			'max_' => memory_get_peak_usage(true),
			'limit' => ini_get('memory_limit')
		);

		if($convert){
			$memory['current']   = convert($memory['current']);
			$memory['current_t'] = convert($memory['current_t']);
			$memory['max']       = convert($memory['max']);
			$memory['max_']      = convert($memory['max_']);
		}

		if((bool)$print){
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
	function serverLoad($print = true){
		// try file method
		$load = @file_get_contents('/proc/loadavg');
		if($load){
			$load = explode(' ', $load, 4);
			unset($load[3]);
		}

		// try exec
		if(!$load){
			$load = @exec('uptime');

			// try shell_exec
			if(!$load){
				$load = @shell_exec('uptime');
			}

			if($load){
				$load = explode(' ', $load);
				$load[2] = trim(array_pop($load));
				$load[1] = str_replace(',', '', array_pop($load));
				$load[0] = str_replace(',', '', array_pop($load));
			}
			else{
				$load[0] = $load[1] = $load[2] = -1;
			}
		}

		if((bool)$print){
			pr($load);
			unset($load);
			return true;
		}

		return $load;
	}

	function systemInfo($extendedInfo = false){
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

		if(!$extendedInfo){
			return $return;
		}

		$extentions = get_loaded_extensions();
		foreach($extentions as $extention){
			$return['Php']['extentions'][] = array(
				'name' => $extention,
				'version' => phpversion($extention)
			);
		}

		return $return;
	}
