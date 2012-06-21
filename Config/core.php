<?php
	/**
	 * This is core configuration file.
	 *
	 * Used to configure some base settings and load configs from all the plugins in the app.
	 */

	if(substr(env('SERVER_ADDR'), 0, 3) == 127 || substr(env('HTTP_HOST'), -3) == 'dev') {
		Configure::write('debug', 2);
	}
	else{
		Configure::write('debug', 2);
	}

	Configure::write('log', true);

	/**
	 * @brief Error logging level
	 *
	 * Same as the debug level, but will send errors to a file. Good to see
	 * what is going on when the site's debug is off.
	 */
	define('LOG_ERROR', 2);

	if(phpversion() >= 5.3) {
		date_default_timezone_set(date_default_timezone_get());
	}

	/**
	 * if debug is off check for view file cache
	 */
	if(Configure::read('debug') == 0) {
		Configure::write('Cache.check', true);
	}

	App::uses('AppError', 'Lib');
	App::uses('InfinitasException', 'Lib/Error');
//	Configure::write(
//		'Error',
//		array(
//			'handler' => 'AppError::handleError',
//			'level' => E_ALL,
//			'renderer' => 'InfinitasErrorRenderer',
//			'trace' => true,
//		)
//	);
	Configure::write('Error', array(
		'handler' => 'ErrorHandler::handleError',
		'level' => E_ALL & ~E_DEPRECATED,
		'trace' => true
	));

//	Configure::write(
//		'Exception',
//		array(
//			'handler' => 'AppError::handleException',
//			'renderer' => 'InfinitasExceptionRenderer',
//			'log' => true,
//		)
//	);
	Configure::write('Exception', array(
		'handler' => 'ErrorHandler::handleException',
		'renderer' => 'ExceptionRenderer',
		'log' => true
	));

	Configure::write('Dispatcher.filters', array(
		'AssetDispatcher',
		'CacheDispatcher',
	));

