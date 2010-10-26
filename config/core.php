<?php
	/**
	 * This is core configuration file.
	 *
	 * Use it to configure core behavior of Cake.
	 *
	 * PHP versions 4 and 5
	 *
	 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
	 * Copyright 2005-2009, Cake Software Foundation, Inc. (http://cakefoundation.org)
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 *
	 * @copyright Copyright 2005-2009, Cake Software Foundation, Inc. (http://cakefoundation.org)
	 * @link http://cakephp.org CakePHP(tm) Project
	 * @package cake
	 * @subpackage cake.app.config
	 * @since CakePHP(tm) v 0.2.9
	 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
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