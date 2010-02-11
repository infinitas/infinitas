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
?>