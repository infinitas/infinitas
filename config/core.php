<?php
	/**
	 * This is core configuration file.
	 *
	 * Used to configure some base settings and load configs from all the plugins in the app.
	 */

	Configure::write('debug', 2);
	Configure::write('log', true);
	define('LOG_ERROR', 2);

	if(phpversion() >= 5.3){
		date_default_timezone_set(date_default_timezone_get());
	}


	/**
	 * Cache configuration
	 */
	$__cache = function_exists('apc_cache_info') ? 'Apc' : 'Libs.NamespaceFile';	
	Configure::write('Cache.engine', $__cache);

	if(Configure::read('debug') == 0){
		Configure::write('Cache.check', true);
	}
	
	Cache::config('default', array('engine' => 'File', 'prefix' => 'infinitas_'));

	//no home
	Configure::write('Rating.require_auth', true);
	Configure::write('Rating.time_limit', '4 weeks');

	Configure::write('Reviews.auto_moderate', true);

	App::build(array('plugins' => array(APP . 'core' . DS)));

	$configs = Cache::read('global_configs');
	if($configs !== false){
		foreach($configs as $k => $v){
			Configure::write($k, $v);
		}
		return true;
	}

	/**
	 * Load plugin events
	 */
	if(!App::import('Libs', 'Events.Events')){
		trigger_error('Could not load the Events Class', E_USER_ERROR);
	}
	EventCore::getInstance();
	EventCore::trigger(new StdClass(), 'setupConfig');

	Cache::write('global_configs', Configure::getInstance());