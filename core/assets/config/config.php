<?php
	/**
	 * Configuration file for the Script Combiner helper. This file is used to determine
	 * the behaviour of the helper.
	 * 
	 * @author Geoffrey Garbers
	 * @version 0.1
	 */

	/**
	 * Indicates whether CSS files should be combined. Set to false to disable
	 * CSS combination.
	 */
	$config['ScriptCombiner']['combineCss'] = true;

	/**
	 * Indicates whether Javascript files should be combined. Set to false to disable
	 * Javascript combination.
	 */
	$config['ScriptCombiner']['combineJs'] = true;

	/**
	 * Indicates whether CSS files should be compressed. Set to false to disable CSS
	 * compression.
	 */
	$config['ScriptCombiner']['compressCss'] = true;

	/**
	 * Indicates whether Javascript files should be compressed. Set to false to disable
	 * Javascript compression.
	 *
	 * jsmin.php is very slow, adding 6 seconds to a page load (xhprof) and breaks debugkit
	 */
	$config['ScriptCombiner']['compressJs'] = false;

	/**
	 * Indicates how long the combined cache files should exist for. If an integer is
	 * supplied] = then it is supplied as the number of seconds the file should be cached
	 * for. Otherwise] = it is assumed a valid strtotime() value is supplied. Set to
	 * -1 to disable caching.
	 */
	$config['ScriptCombiner']['cacheLength'] = '1 year';

	/**
	 * Sets the path to the cached CSS combined file. Must be a directory] = and must
	 * be a valid directory on the local machine.
	 */
	$config['ScriptCombiner']['cssCachePath'] = CSS . 'combined' . DS;

	/**
	 * Sets the path to the cached Javascript combined file. Must be a directory,
	 * and must be a valid directory on the local machine.
	 */
	$config['ScriptCombiner']['jsCachePath'] = JS . 'combined' . DS;

	/**
	 * The string to use when combining multiple to separator file contents. This is
	 * mainly used when files are not compressed] = and you're looking to see where
	 * the file joins are.
	 */
	$config['ScriptCombiner']['fileSeparator'] = "\n\n/** FILE SEPARATOR **/\n\n";
