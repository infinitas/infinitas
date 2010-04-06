<?php
	/**
	* Load all the plugin dirs
	*/
	App::build(
		array(
			'plugins' => array(
				APP . 'infinitas' . DS,
				APP . 'extensions' . DS
			)
		)
	);

	/**
	 * Load plugin events
	 */
	App::import('Libs', 'Events.Events');
	EventCore::getInstance();


	/**
	* Make sure the json defines are loaded.
	*/
	if(!defined('JSON_ERROR_NONE')){define('JSON_ERROR_NONE', 0);}
	if(!defined('JSON_ERROR_DEPTH')){define('JSON_ERROR_DEPTH', 1);}
	if(!defined('JSON_ERROR_CTRL_CHAR')){define('JSON_ERROR_CTRL_CHAR', 3);}
	if(!defined('JSON_ERROR_SYNTAX')){define('JSON_ERROR_SYNTAX', 4);}

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
			$hash = sha1(serialize($data));
		}

		return Inflector::underscore($prefix).'_'.$hash;
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
		if(!class_exists('Inflector')){
			App::import('Inflector');
		}
		
		if($class !== null){
			return Inflector::humanize(Inflector::underscore((string)$class));
		}
		
		return false;		
	}
?>