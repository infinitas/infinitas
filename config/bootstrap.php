<?php
	App::build(
		array(
			'plugins' => array(
				APP . 'infinitas' . DS,
				APP . 'extensions' . DS,
				APP . 'infinitas' . DS . 'shop'. DS .'plugins' . DS
			)
		)
	);

	/**
	 * Load plugin events
	 */
	App::import('Libs', 'Events.Events');
	EventCore::getInstance();

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
			if(!is_dir(CACHE.$cache['name'])){
				$Folder = new Folder(CACHE.$cache['name'], true);
			}
			if(!empty($cache['name']) && !empty($cache['config'])) {
				Cache::config($cache['name'], array_merge(array('engine' => Configure::read('Cache.engine')), (array)$cache['config']));
			}
		}
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
	function regexEscape($str)
	{
		$patterns = array(
			'/\//', '/\^/', '/\./', '/\$/', '/\|/',
			'/\(/', '/\)/', '/\[/', '/\]/', '/\*/',
			'/\+/', '/\?/', '/\{/', '/\}/', '/\,/'
		);

		$replace = array('\/', '\^', '\.', '\$', '\|', '\(', '\)',
		'\[', '\]', '\*', '\+', '\?', '\{', '\}', '\,');

		return preg_replace($patterns,$replace, $str);
	}

	/**
	 * generate a unique cache name for a file.
	 *
	 * uses an array of data or a string to generate a hash for the end of the cache
	 * name so that you can cache finds etc
	 *
	 * @param string $prefix the normal name for cache
	 * @param mixed $data your conditions in the find
	 * @return a nice unique name
	 */
	function cacheName($prefix = 'PleaseNameMe', $data = null){
		$hash = '';

		if ($data) {
			$hash = '_'.sha1(serialize($data));
		}

		return Inflector::underscore($prefix).$hash;
	}

	/**
	 * return a nice user friendly name.
	 *
	 * Takes a cake class like SomeModel and converts it to Some model
	 *
	 * @param string $class the name to convert
	 * @return a nice name
	 */
	function prettyName($class = null){
		if($class !== null){
			if(!class_exists('Inflector')){
				App::import('Inflector');
			}
			return Inflector::humanize(Inflector::underscore((string)$class));
		}

		return false;
	}

	/**
	 * having issues with routes ?
	 *
	 * @todo this can be removed after stable.
	 *
	 * @param mixed $route
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
	 * Quick method to conver byte -> anything.
	 * @param $size
	 */
	function convert($size){
		$unit=array('b','kb','mb','gb','tb','pb');
		return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
	}

	/**
	 * get the current memory stats
	 */
	function memoryUsage(){
		pr(
			array(
				'current' => convert(memory_get_usage()),
				'current_t' => convert(memory_get_usage(true)),
				'max' => convert(memory_get_peak_usage()),
				'max_' => convert(memory_get_peak_usage(true)),
				'limit' => ini_get('memory_limit')
			)
		);
	}