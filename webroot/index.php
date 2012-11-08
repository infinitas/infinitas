<?php
/**
 * Index
 *
 * The Front Controller for handling every request
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2011, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2011, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.webroot
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
/**
 * Use the DS to separate the directories in other defines
 */
	if (!defined('DS')) {
		define('DS', DIRECTORY_SEPARATOR);
	}
/**
 * These defines should only be edited if you have cake installed in
 * a directory layout other than the way it is distributed.
 * When using custom settings be sure to use the DS and do not add a trailing DS.
 */

/**
 * The full path to the directory which holds "app", WITHOUT a trailing DS.
 *
 */
	if (!defined('ROOT')) {
		define('ROOT', dirname(dirname(dirname(__FILE__))));
	}
/**
 * The actual directory name for the "app".
 *
 */
	if (!defined('APP_DIR')) {
		define('APP_DIR', basename(dirname(dirname(__FILE__))));
	}

/**
 * The absolute path to the "cake" directory, WITHOUT a trailing DS.
 *
 * Un-comment this line to specify a fixed path to CakePHP.
 * This should point at the directory containing `Cake`.
 *
 * For ease of development CakePHP uses PHP's include_path.  If you
 * cannot modify your include_path set this value.
 *
 * Leaving this constant undefined will result in it being defined in Cake/bootstrap.php
 */
	//define('CAKE_CORE_INCLUDE_PATH', ROOT . DS . 'lib');

/**
 * Editing below this line should NOT be necessary.
 * Change at your own risk.
 *
 */
	if (!defined('WEBROOT_DIR')) {
		define('WEBROOT_DIR', basename(dirname(__FILE__)));
	}
	if (!defined('WWW_ROOT')) {
		define('WWW_ROOT', dirname(__FILE__) . DS);
	}

	$failed = false;
	if (!defined('CAKE_CORE_INCLUDE_PATH')) {
		$root = getcwd();
		if (function_exists('ini_set')) {
			$root = ROOT . DS . APP_DIR . DS . 'CakePHP';
			if(is_dir($root . DS . 'lib')) {
				define('INFINITAS_CAKEPHP_SUBMODULE', true);
				ini_set('include_path', $root . DS . 'lib' . PATH_SEPARATOR . ini_get('include_path'));
			}
		}
		if (!include('Cake' . DS . 'bootstrap.php')) {
			$failed = true;
		}
	} else {
		if (!include(CAKE_CORE_INCLUDE_PATH . DS . 'Cake' . DS . 'bootstrap.php')) {
			$failed = true;
		}
	}
	if ($failed) {
		$message = sprintf('<h1>%s</h1>', 'CakePHP core could not be found.');
		$message .= sprintf('<p>%s</p>', 'Infinitas has tried the following paths: <br/></br>%s<br/><br/><b>See:</b> %s');
		echo sprintf($message, implode('<br/>', explode(PATH_SEPARATOR, ini_get('include_path'))), 'http://infinitas-cms.org/infinitas_docs/Installer');
		exit;
	}

	if (isset($_SERVER['PATH_INFO']) && $_SERVER['PATH_INFO'] == '/favicon.ico') {
		return;
	}

	App::uses('Dispatcher', 'Routing');

	$Dispatcher = new Dispatcher();
	$Dispatcher->dispatch(new CakeRequest(), new CakeResponse(array('charset' => Configure::read('App.encoding'))));

	EventCore::trigger($Dispatcher, 'requestDone');