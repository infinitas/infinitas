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
?>