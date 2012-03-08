<?php
	/**
	 * This is core configuration file.
	 *
	 * Used to configure some base settings and load configs from all the plugins in the app.
	 */
	if(substr(env('SERVER_ADDR'), 0, 3) == 127){
		Configure::write('debug', 2);
	}
	else{
		Configure::write('debug', 0);
	}
	
	Configure::write('log', true);

	/**
	 * @brief Error logging level
	 *
	 * Same as the debug level, but will send errors to a file. Good to see
	 * what is going on when the site's debug is off.
	 */
	define('LOG_ERROR', 2);

	if(phpversion() >= 5.3){
		date_default_timezone_set(date_default_timezone_get());
	}

	/**
	 * if debug is off check for view file cache
	 */
	if(Configure::read('debug') == 0){
		Configure::write('Cache.check', true);
	}
